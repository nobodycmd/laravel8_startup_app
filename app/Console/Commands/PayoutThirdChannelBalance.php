<?php

namespace App\Console\Commands;

use App\Models\PayoutChannel;
use App\Services\PayoutWithMajiaService;
use App\Services\TgNameService;
use Illuminate\Console\Command;

class PayoutThirdChannelBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PayoutThirdChannelBalance {channel}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '三方代付通道查询余额';

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
    public function handle(PayoutWithMajiaService $orderService)
    {
        $this->line($this->description);
        $channel_id = explode('=', $this->argument('channel'))[1];

        $payoutChannel = PayoutChannel::query()->find($channel_id);

        $balance = $orderService->getBalance($channel_id);
        if (is_numeric($balance) && $balance <= $payoutChannel->balance_warning) {
            //sendMessage("通道{$payoutChannel->name},余额{$balance}触及了警戒余额{$payoutChannel->balance_warning}", TgNameService::chat_id_monitor, '', TgNameService::monitor);
        }
    }
}
