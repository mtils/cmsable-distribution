@extends('layouts.popup')
@section('head')
    @parent
    {{ HTML::style('themes/admin/css/filemanager.css') }}
@stop
@section('body')
    @include('partials.filemanager')
@stop