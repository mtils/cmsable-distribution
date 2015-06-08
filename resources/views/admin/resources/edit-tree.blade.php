@extends('index')
@section('head')
    @parent
    <link href="/cmsable/css/sitetree.css" rel="stylesheet" type="text/css" />
@stop
@section('body')
@if(isset($title) && $title)
    <h2 class="page-header text-right">{{ $title }}</h2>
    @endif
        <div class="col-lg-12 col-md-12">
            @if(isset($allowCreate) && $allowCreate)
            <div class="btn btn-success btn-file" style="width: 150px;" onclick="window.location='{{ URL::action('create') }}'">
                <i class="fa fa-edit"></i>
                Neue {{ Lang::choice($modelLangKey,1) }}
            </div>
            <br/><br/>
            @endif
            <div id="tree-container" data-edit-url="{{ URL::to(Menu::current()) }}">
                @toJsTree($model, 'name', $model->id)
            </div>
        </div>
@stop
@section('bottomjs')
    @parent
    <script src="/cmsable/js/jstree/dist/jstree.min.js"></script>
    <script src="/cmsable/js/ckeditor/ckeditor.js"></script>
    <script src="/cmsable/js/ckeditor/adapters/jquery.js"></script>
    <script src="{{ URL::route('sitetree.jsconfig') }}"></script>
    <script src="/cmsable/js/tree.js"></script>
@stop