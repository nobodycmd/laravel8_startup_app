@extends('layouts.adminpop')

@section('layout')
<div class="container">
    <div class="panel panel-info">
        <div class="panel-heading">开发包</div>
        <div class="panel-body">
            <div class="alert alert-success" role="alert">
                <b>api基地址: https://api.lightpays.com/lightpays/</b><br>
                代收下单[get|post]：/payment/createorder<br>
                代收查询[get|post]：/payment/query<br>
                <br><br>
                代付下单[get|post]：/transfer/createorder<br>
                代付查询[get|post]：/transfer/query<br>
                <br><br>
                余额查询[get|post]：/transfer/balance<br>
            </div>

            <div>

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">JAVA</a></li>
                    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">C#</a></li>
                    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">PHP</a></li>
                    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">GOLANG</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">
                        <pre>
                            <?= file_get_contents(public_path('/demo/java.java')) ?>
                        </pre>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="profile">
                        <pre>
                            <?= file_get_contents(public_path('/demo/c.cs')) ?>
                        </pre>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="messages">
                        <pre>
                            <?= file_get_contents(public_path('/demo/php.php')) ?>
                        </pre>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="settings">
                        <pre>
                            <?= file_get_contents(public_path('/demo/go.go')) ?>
                        </pre>
                    </div>
                </div>

            </div>

        </div>
    </div>

</div>
@endsection

@section('script')

@endsection
