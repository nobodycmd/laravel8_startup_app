@extends('layouts.layout')

@section('layout')

    <style>
        .table th, .table td{
            white-space: pre-line;
            /*width: 400px !important;*/
            max-width: 400px;
        }
        .table th:first-child, .table td:first-child{
            position: inherit;
        }
        .table th:last-child, .table td:last-child{
            position: inherit;
        }
    </style>
    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-cog"></i> 服务器参数</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table">
                            <tr>
                                <td>服务器域名地址</td>
                                <td><?= $info['environment']['domainIP'] ?></td>
                                <td>服务器标识</td>
                                <td><?= $info['environment']['flag'] ?></td>
                            </tr>
                            <tr>
                                <td>操作系统</td>
                                <td><?= $info['environment']['os'] ?></td>
                                <td>服务器解析引擎</td>
                                <td><?= $info['environment']['webEngine'] ?></td>
                            </tr>
                            <tr>
                                <td>服务器语言</td>
                                <td><?= $info['environment']['language'] ?></td>
                                <td>服务器端口</td>
                                <td><?= $info['environment']['webPort'] ?></td>
                            </tr>
                            <tr>
                                <td>服务器主机名</td>
                                <td><?= $info['environment']['name'] ?></td>
                                <td>站点绝对路径</td>
                                <td><?= $info['environment']['webPath'] ?></td>
                            </tr>
                            <tr>
                                <td>服务器当前时间</td>
                                <td><span id="divTime"></span></td>
                                <td>服务器已运行时间</td>
                                <td><?= $info['uptime'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-cog"></i> 服务器硬件数据</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="col-sm-12 text-center" id="memData">
                            <div class="col-sm-3 "><?= $info['hardDisk']['used']?>/<?= $info['hardDisk']['total']?> (G)</div>
                            <div class="col-sm-3"><?= $info['memory']['real']['used'] ?>/<?= $info['memory']['memory']['total']; ?> (M)</div>
                            <div class="col-sm-3"><?= $info['memory']['memory']['used'] ?>/<?= $info['memory']['memory']['total']; ?> (M)</div>
                            <div class="col-sm-3"><?= $info['memory']['cache']['real']?>/<?= $info['memory']['cache']['total']?> (M)<br>Buffers缓冲为 <?= $info['memory']['memory']['buffers']?> M</div>
                        </div>
                        <div id="sys-hardware">
                            <table class="table">
                                <tr>
                                    <td>CPU</td>
                                    <td><?= $info['cpu']['num'] ?></td>
                                    <td>CPU型号</td>
                                    <td><?= $info['cpu']['model'] ?></td>
                                </tr>
                                <tr>
                                    <td>系统平均负载(1分钟、5分钟、以及15分钟的负载均值)</td>
                                    <td colspan="3"><?= $info['loadavg']['explain'] ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-cog"></i> 服务器实时网络数据</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table">
                            <tr>
                                <td>总发送</td>
                                <td id="netWork_allOutSpeed"><?= $info['netWork']['allOutSpeed'] ?></td>
                                <td>总接收</td>
                                <td id="netWork_allInputSpeed"><?= $info['netWork']['allInputSpeed'] ?></td>
                            </tr>
                            <tr>
                                <td>发送速度</td>
                                <td id="netWork_currentOutSpeed"><?= $info['netWork']['currentOutSpeed'] ?></td>
                                <td>接收速度</td>
                                <td id="netWork_currentInputSpeed"><?= $info['netWork']['currentInputSpeed'] ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@section('script')

@endsection
