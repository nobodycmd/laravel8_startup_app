@extends('layouts.layout')

@section('layout')
<style>
    .table-responsive>table.table th, .table-responsive>table.table td{
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

    <?php
    ob_start();
    phpinfo();
    $pinfo = ob_get_contents();
    ob_end_clean();
    $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo);
    $phpinfo = str_replace('<table', '<div class="table-responsive"><table class="table table-condensed table-bordered table-striped table-hover config-php-info-table" ', $phpinfo);
    $phpinfo = str_replace('</table>', '</table></div>', $phpinfo);
    echo $phpinfo;
    ?>

    </div>
@endsection

@section('script')

@endsection
