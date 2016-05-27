@extends('index')
@section('head')
    @parent
    <link href="/cmsable/css/sitetree.css" rel="stylesheet" type="text/css" />
@stop
@section('content')
        @include('partials.bootstrap-modal')
        <div class="col-lg-3 col-md-4">
            <div class="btn btn-primary btn-file new-page">
                <i class="fa fa-edit"></i>
                Neue Seite
            </div>
            <div id="sitetree-container" data-edit-url="{{ URL::currentPage() }}">
                @toJsTree($sitetreeModel->tree(),'menu_title', $editedId)
            </div>
        </div>
        <div class="col-lg-9 col-md-8 left-splitted">
            {!! $form !!}
        </div>
@stop
@section('js')
    @parent
    <script src="/cmsable/js/jstree/dist/jstree.min.js"></script>
    <script src="/cmsable/js/ckeditor/ckeditor.js"></script>
    <script src="/cmsable/js/ckeditor/adapters/jquery.js"></script>
    <script src="{{ URL::route('sitetree.jsconfig') }}"></script>
    <script src="/cmsable/js/sitetree.js"></script>
@stop