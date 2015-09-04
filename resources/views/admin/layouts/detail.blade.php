@extends('index')

@section('content')

@include('partials.breadcrumb-in-subpath')

<? $mainActions = Actions::forItem($model)->filtered('main') ?>
<? $besideActions = Actions::forItem($model)->filtered('!main') ?>

<div class="col-lg-12 col-md-12">
    @if(count($mainActions) > 1)
        <div class="nav-tabs-custom">
        @include('partials.resource-detail-box-header')
    @else
        <div class="box">
    @endif
    @section('inner-content')
    @show()
    </div><!-- div.nav-tabs-custom|div.box -->
</div>
@stop
@section('js')
    @parent
    <script src="/cmsable/js/jstree/dist/jstree.min.js"></script>
    <script src="/cmsable/js/ckeditor/ckeditor.js"></script>
    <script src="/cmsable/js/ckeditor/adapters/jquery.js"></script>
    <script src="{{ URL::route('sitetree.jsconfig') }}"></script>
@stop