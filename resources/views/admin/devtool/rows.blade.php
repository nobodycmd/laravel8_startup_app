@extends('layouts.layout')

@section('layout')

    <div class="container-fluid" style="padding-top:15px;">

    <form method="get" class="form-inline">
            <input type="hidden" name="table" value="{{$_GET['table']}}" />
            <input type="text" name="where" class="form-control" placeholder="sql where" />
            <input type="submit" class="btn btn-success"/>
        </form>

        <div class="table-responsive">

        <table class="table">
            <tbody>
            <tr>
                @foreach($aryColumn as $one)
                <th>[{{$one->Field}}]{{$one->Comment}}</th>
                @endforeach
            </tr>

            @foreach($aryList as $one)
                <tr>
                    <?php
                    foreach($aryColumn as $c){
                        $f = $c->Field; ?>
                    <td>{{$one->$f}}</td>
                    <?php } ?>
                    <td>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        </div>
        {{$paginator->links()}}
    </div>
@endsection

@section('script')

@endsection
