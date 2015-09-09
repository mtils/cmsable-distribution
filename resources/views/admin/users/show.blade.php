@extends('users.detail-layout')

@section('sub-nav')
@include('partials.detail-sub-nav')
@stop

@section('inner-content')
<? print_r($model->toArray()) ?>
@stop