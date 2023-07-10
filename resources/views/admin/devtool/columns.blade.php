@extends('layouts.layout')

@section('layout')

    <form method="post">
        @csrf
        <input type="hidden" name="table" value="{{$table}}" />
        <table class="table">
            <tbody>
                <tr>
                    <td>Table</td>
                    <td>{{$table}}</td>
                </tr>
            <?php
            foreach($aryColumn as $one){
                $fieldName = $one->Field;
                $comment = $one->Comment ? $one->Comment : $one->Field;
                ?>
                <tr>
                    <td>{{$comment}}</td>
                    <td>
                        <select class="form-control" name="{{$fieldName}}">
                            <option value="text">文本</option>
                            <option value="date">日期Y-m-d</option>
                            <option value="datetime">日期Y-m-d H:i:s</option>
                            <option value="file">文件上传</option>
                            <option value="dropdown">下拉框</option>
                        </select>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
        <input type="submit" class="btn btn-success" style="width: 100%;">
    </form>

@endsection

@section('script')

@endsection
