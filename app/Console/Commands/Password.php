<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Password extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
        $m           = \App\Models\Admin::query()->first();
        $m->password = bcrypt('123456'.config('config.secret_key'));
        $m->save();

        return 0;
    }
}
