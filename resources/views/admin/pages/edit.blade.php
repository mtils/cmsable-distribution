@extends('index')
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
<?

Assets::import('css/sitetree.css', 'cmsable.css')->after('css/admin.css');

Assets::import([
    'js/jstree/dist/jstree.min.js',
    'js/ckeditor/ckeditor.js',
    'js/ckeditor/adapters/jquery.js',
    URL::route('sitetree.jsconfig'),
    'js/sitetree.js'
],'cmsable.js')->after('js/admin.js');

?>

@stop
