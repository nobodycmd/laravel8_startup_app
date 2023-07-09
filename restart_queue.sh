
echo '所有队列 worker 在完成当前任务后优雅地 “死亡”，这样就不会丢失现有的任务'
php artisan queue:restart
echo '由于在执行 queue:restart 命令时，队列 worker 将被杀掉，因此你应该运行一个进程管理器 (如 Supervisor) 来自动重新启动队列 worker'