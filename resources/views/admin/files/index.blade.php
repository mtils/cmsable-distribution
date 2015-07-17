@extends('index')
@section('head')
    @parent
    <style>
        @include('file-db::partials.style')
        div.filemanager {
            background-color: #fff;
        }
    </style>
@stop
@section('content')
    @include('file-db::partials.filemanager')
@stop
@section('js')
    @parent
    <script>
        @include('file-db::partials.javascript')
    </script>
@stop