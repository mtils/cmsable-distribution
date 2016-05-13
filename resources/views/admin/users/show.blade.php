@extends('users.detail-layout')

@section('sub-nav')
@include('partials.detail-sub-nav')
@stop

@section('inner-content')
@include('partials.boxes.tabbed_detail_header')
<pre>
<? print_r($model->toArray()) ?>
</pre>
@include('partials.boxes.tabbed_detail_footer')
@stop
