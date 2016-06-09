@extends('users.detail-layout')

@section('sub-nav')
@include('partials.detail-sub-nav')
@stop

@section('inner-content')
@include('partials.boxes.tabbed_detail_header')
@include('partials.generated-detail-view')
@include('partials.boxes.tabbed_detail_footer')
@stop
