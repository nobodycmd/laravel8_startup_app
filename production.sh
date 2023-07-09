#!/bin/bash

echo 'UBUNTU 20 LTS 下自动部署 laravel 项目开始....'

if [ -e .env ]
then
  echo '发现.env'
else
  echo '未发现.env'
  exit
fi


root="/var/www/pay"

if [ ! -d $root ]
then
  mkdir -p $root
fi

cr=`pwd`
if [ $root != $cr ]
then
  echo "不是在/var/www/pay目录"
  exit
fi


if ! which nginx >/dev/null 2>&1; then
  echo '安装php7.4&php-fpm  nginx'
  sh do_app_deploy_config/soft.sh
fi


echo ' 配置nginx conf  '
cp do_app_deploy_config/nginx.conf /etc/nginx/nginx.conf

echo ' 网站nginx文件 '
cp do_app_deploy_config/api.conf /etc/nginx/sites-enabled/api.conf

echo ' 配置php-fpm conf '
cp do_app_deploy_config/www.conf /etc/php/7.4/fpm/pool.d/www.conf

git config --global credential.helper store
git config --global --add safe.directory /var/www/pay
git pull

mkdir bootstrap/cache
mkdir -p storage/framework/{sessions,views,cache}
sudo -u  www-data php composer.phar install --optimize-autoloader --no-dev #Autoloader Optimization
chown -R www-data:www-data ./
chmod 777 -R bootstrap/cache

php artisan clear-compiled
#php artisan clear
php artisan view:clear
php artisan route:clear
php artisan config:clear

#cp .env.example .env

echo "完毕： 如果还没有证书可手动执行 sh do_app_deploy_config/ssl.sh 进行SSL证书生成"