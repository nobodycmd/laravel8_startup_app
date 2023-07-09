<?php

namespace App\Console\Commands;

use App\Models\Merchant;
use App\Models\PayinChannel;
use App\Models\PayinOrder;
use App\Services\PayinService;
use App\Services\RedisService;
use App\Services\TgNameService;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Str;

class CheckRunningThirdChannelAvailable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CheckRunningThirdChannelAvailable {driver}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '主动检查运行中的三方（代收）通道是否正常';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // artisan::call 是直接获取到值
        $driver = $this->argument('driver');
        //Schedule 传递过来的是  driver=value
        if (Str::contains($driver, '=')) {
            $driver = explode('=', $driver)[1];
        }

        if (RedisService::lock('CheckRunningThirdChannelAvailable' . $driver, 1200) == false) {
            return 0;
        }

        try {

        } finally {
            RedisService::del('CheckThirdChannelAvailable' . $driver);
        }
    }
}
