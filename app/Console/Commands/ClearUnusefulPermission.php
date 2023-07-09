<?php

namespace App\Console\Commands;

use App\Models\AdminPermission;
use Illuminate\Console\Command;

class ClearUnusefulPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ClearUnusefulPermission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清空未申明的路由权限';

    protected $log;

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
        foreach (AdminPermission::where('uri', '<>', '')->get() as $one) {
            try {
                route($one->uri);
            } catch (\Exception $e) {
                AdminPermission::firstWhere([
                    'uri' => $one->uri,
                ])->delete();
            }
        }

        return 0;
    }
}
