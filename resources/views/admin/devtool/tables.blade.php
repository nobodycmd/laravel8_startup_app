@extends('layouts.layout')

@section('layout')

    <div class="container-fluid" style="padding-top:15px;">

        <table class="table">
            <tbody>
            <tr>
                <th>Name</th>
                <th>Engine</th>
                <th>Rows</th>
{{--                <th>Data_length</th>--}}
{{--                <th>Index_length</th>--}}
{{--                <th>Auto_increment</th>--}}
                <th>Create_time</th>
                <th>Update_time</th>
                <th>Collation</th>
                <th>Comment</th>
                <th>Actions</th>
            </tr>
            @foreach($aryList as $one)
                <tr>
                    <th>{{$one->Name}}</th>
                    <th>{{$one->Engine}}</th>
                    <th>{{$one->Rows}}</th>
{{--                    <th>{{$one->Data_length}}</th>--}}
{{--                    <th>{{$one->Index_length}}</th>--}}
{{--                    <th>{{$one->Auto_increment}}</th>--}}
                    <th>{{$one->Create_time}}</th>
                    <th>{{$one->Update_time}}</th>
                    <th>{{$one->Collation}}</th>
                    <th>{{$one->Comment}}</th>
                    <th>
                        <a target="_blank" href="{{route('admin.devtool.rows',['table'=>$one->Name])}}">Rows</a>
                        <a target="_blank" href="{{route('admin.devtool.codes',['table'=>$one->Name])}}">Code</a>
                    </th>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')

@endsection
