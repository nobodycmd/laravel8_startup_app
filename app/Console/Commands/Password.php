<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\AdminPermission;
use App\Models\AdminRole;
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
        $r = AdminRole::query()->first();
        if($r == false){
            $r = new AdminRole();
            $r->name = $r->identity = 'boss';
            $r->status = 1;
            $r->create_time = $r->update_time = time();
            $r->permission = implode(',',array_values(array_column( AdminPermission::query()->select('id')->get(),'id')));
            $r->save();
        }

        $m           = \App\Models\Admin::query()->first();
        if($m == false){
            $m = new Admin();
            $m->admin_role_id = $r->save();
            $m->name = 'boss';
            $m->mobile = '110';
            $m->username = 'boss';
            $m->status = 1;
            $m->remember_token = '';
            $m->google_authenticator = '';
        }
        $m->password = bcrypt('123456'.config('config.secret_key'));
        $m->save();

        return 0;
    }
}
