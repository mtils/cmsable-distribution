@extends('index')

@section('content')

@include('partials.breadcrumb-in-subpath')
<div class="col-lg-12 col-md-12">
{!! $form !!}
</div>
@stop
@section('bottomjs')
    @parent
    <script src="/cmsable/js/jstree/dist/jstree.min.js"></script>
    <script src="/cmsable/js/ckeditor/ckeditor.js"></script>
    <script src="/cmsable/js/ckeditor/adapters/jquery.js"></script>
    <script src="{{ URL::route('sitetree.jsconfig') }}"></script>
@stop