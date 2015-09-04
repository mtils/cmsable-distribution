@extends('index')

@section('content')

@include('partials.breadcrumb-in-subpath')

<div class="col-md-3">
@section('side-boxes')
@show
</div>

<div class="col-md-9">
@include('partials.detail-box')
</div>
@stop
@section('js')
    @parent
    <script src="/cmsable/js/jstree/dist/jstree.min.js"></script>
    <script src="/cmsable/js/ckeditor/ckeditor.js"></script>
    <script src="/cmsable/js/ckeditor/adapters/jquery.js"></script>
    <script src="{{ URL::route('sitetree.jsconfig') }}"></script>
@stop