

#link https://www.digitalocean.com/community/tutorials/how-to-install-php-7-4-and-set-up-a-local-development-environment-on-ubuntu-20-04

sudo apt-get update
sudo apt -y install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt-get update

sudo apt -y install php7.4
sudo apt-get install -y php7.4-cli php7.4-json php7.4-common php7.4-mysql php7.4-zip php7.4-gd php7.4-mbstring php7.4-curl php7.4-xml php7.4-bcmath
sudo apt-get install -y php7.4-redis

sudo apt-get install -y php7.4-fpm
#The FPM service will start automatically, once the installation is over. You can verify that using the following systemd command:
#systemctl status php7.4-fpm
systemctl enable php7.4-fpm # enable it to start at system reboot:


apt-get install nginx -y
systemctl start nginx
systemctl enable nginx

nginx -v

