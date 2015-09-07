@extends('index')
@section('head')
    @parent
    <link href="/cmsable/css/sitetree.css" rel="stylesheet" type="text/css" />
@stop
@section('content')
    @if(isset($title) && $title)
        <h2 class="page-header text-right">{{ $title }}</h2>
    @endif
    <? $layoutRatio = isset($layoutRatio) ? $layoutRatio : '3:9' ?>
    <? list($leftCols, $rightCols) = explode(':',$layoutRatio) ?>
    <div class="col-lg-{{ $leftCols }}">
        @if(Auth::allowed($model, 'alter'))
        <div class="btn btn-success btn-file new-node" style="width: 160px;">
            <i class="fa fa-edit"></i>
            {{ Lang::get('ems::base.create-object', ['class'=>Render::classTitle($model)]) }}
        </div>
        <br/><br/>
        @endif
        <? $iconClass = isset($iconClass) ? $iconClass : 'normal' ?>
        <div id="tree-container" data-edit-url="{{ URL::route(Resource::getCurrentResource().'.index') }}">
            <ul>{!! Render::tree($model)->iconClass($iconClass) !!}</ul>
        </div>
    </div>
    <div class="col-lg-{{ $rightCols }} left-splitted">
        @form()
    </div>
@stop
@section('js')
    @parent
    <script src="/cmsable/js/jstree/dist/jstree.min.js"></script>
    <script src="/cmsable/js/ckeditor/ckeditor.js"></script>
    <script src="/cmsable/js/ckeditor/adapters/jquery.js"></script>
    <script src="{{ URL::route('sitetree.jsconfig') }}"></script>
    <script src="/cmsable/js/tree.js"></script>
@stop