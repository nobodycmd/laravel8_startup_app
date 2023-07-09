@extends('layouts.layout')

@section('layout')


    <div class="container">
        <div class="panel panel-info">
            <div class="panel-heading">部署配置</div>
            <div class="panel-body">

                <div>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">supervisor配置</a></li>
                        <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">commands</a></li>
                        {{--                            <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">PHP</a></li>--}}
                        {{--                            <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">GOLANG</a></li>--}}
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="home">
                                <pre>
            <?php
                                    $aryName = [
                                        'default',
                                        \App\Services\QueueNameService::all_send_tg,
                                        \App\Services\QueueNameService::payin_webhook,
                                        \App\Services\QueueNameService::payout_webhook,
                                        \App\Services\QueueNameService::PayinUtrDeviceMatchJob,
                                        \App\Services\QueueNameService::BankOrderFileHandleJob,
                                        \App\Services\QueueNameService::payin_utr_from_telegram,
                                        \App\Services\QueueNameService::UtrUserMatchJob,
                                        \App\Services\QueueNameService::TelegramImageHandleJob,
                                    ];

                                    foreach($aryName as $name){
                                        $command = base_path('artisan').' queue:work  database --daemon  --queue='.$name;
                                        $log = base_path('storage/logs/supervisor_'.$name.'.log');
                                        $str =<<<EOF
<br>
        [program:laravel_worker_$name]<br>
        process_name=%(program_name)s_%(process_num)02d<br>
        command=php $command<br>
        autostart=true<br>
        autorestart=true<br>
        stopasgroup=true<br>
        killasgroup=true<br>
        user=root<br>
        numprocs=1<br>
        redirect_stderr=true<br>
        stdout_logfile=$log<br>

EOF;
                                        echo $str;
                                    }
                                    ?>
    </pre>
                        </div>
                        <div role="tabpanel" class="tab-pane " id="profile">
                            * * * * * php {{base_path('artisan')}} schedule:run >> /dev/null 2>&1 <br>
                            #php artisan queue:retry all #重试所有失败的queue，已放入schedule <br>
                            #php artisan queue:restart #完成当前任务后重启queue，优雅退出所有queue；配合supervisor使用完美<br>
                        </div>
                    </div>

@endsection

@section('script')

@endsection
