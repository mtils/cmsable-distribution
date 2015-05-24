@extends('index')
@section('head')
    @parent
    {{ HTML::style('themes/admin/css/sitetree.css') }}
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
                @toJsTree($tree, 'name', $editedId)
            </div>
        </div>
@stop
@section('bottomjs')
    @parent
    {{ HTML::script('themes/admin/js/jstree/dist/jstree.min.js') }}
    {{ HTML::script('themes/admin/js/ckeditor/ckeditor.js') }}
    {{ HTML::script('themes/admin/js/ckeditor/adapters/jquery.js') }}
    {{ HTML::script('themes/admin/js/tree.js') }}
@stop