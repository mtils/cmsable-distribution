@extends('index')
@section('head')
    @parent
    <link href="/cmsable/css/sitetree.css" rel="stylesheet" type="text/css" />
@stop
@section('content')
        <div class="col-lg-12 col-md-12">
            <form class="new-page" name="new-page" id="new-page" role="form" action="{{ URL::action('create') }}" method="get">
                <ul class="timeline">
                    <li class="time-label">
                        <span class="bg-red">
                            1. Position der Seite
                        </span>
                    </li>
                    <!-- Übergeordnete Seite auswählen-->
                    <li>
                        <i class="fa fa-hand-o-right bg-blue"></i>
                        <div class="timeline-item">
                            <span class="time"><i class="fa fa-file-text-o"></i> Position</span>

                            <h3 class="timeline-header"><a href="#">Wählen Sie die übergeordnete Seite aus</a></h3>

                            <div class="timeline-body">
                            <select name="parent_id" class="form-control selectpicker" data-live-search="true">
                                @foreach(BeeTree\Helper::flatify($sitetreeModel->tree()) as $page)
                                    @if($page->isRootNode())
                                    <option value="{{ $page->id }}">Seite auf oberster Ebene anlegen</option>
                                    @else
                                        <option class="depth-<?= $page->getDepth() ?>" value="{{ $page->id }}" @if($parentId == $page->id)selected="selected"@endif>{{ $page->menu_title }}</option>
                                    @endif
                                @endforeach
                            </select>
                            </div>
                        </div>
                    </li>
                    <li class="time-label">
                        <span class="bg-red">
                            2. Seitentyp festlegen
                        </span>
                    </li>
                    <!-- Seitentyp (Controller) auswählen-->
                    <?php $first=TRUE; ?>
                    @foreach(PageType::byCategory($routeScope) as $category=>$pageTypes)
                    <li>
                        @if($first)<i class="fa fa-cogs bg-purple"></i>@endif
                        <div class="timeline-item">
                            <span class="time"><i class="fa {{ PageType::getCategory($category)->icon }}"></i> {{ PageType::getCategory($category)->title }}</span>

                            <h3 class="timeline-header"><a href="#">{{ PageType::getCategory($category)->title }}</a></h3>

                            <div class="timeline-body">
                                <div class="form-group">
                                @foreach($pageTypes as $pageType)
                                    <div class="radio">
                                        <label>
                                        <input type="radio" name="page_type" @if($first)checked="checked" @endif id="controllerClass-{{ $pageType->getId() }}" value="{{ $pageType->getId() }}">
                                        <strong>{{ $pageType->singularName() }}</strong> <small class="text-muted">{{ $pageType->description() }}</small>
                                        </label>
                                    </div>
                                    <?php $first=FALSE; ?>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                    <li><div class="timeline-item">
                    <button type="submit" class="btn btn-primary">Seite anlegen</button>
                    </div></li>
                </ul>
                
            </form>
        </div>
@stop
@section('bottomjs')
    @parent
    <script src="/cmsable/js/jstree/dist/jstree.min.js"></script>
    <script src="/cmsable/js/ckeditor/ckeditor.js"></script>
    <script src="/cmsable/js/ckeditor/adapters/jquery.js"></script>
    <script src="{{ URL::route('sitetree.jsconfig') }}"></script>
    <script src="/cmsable/js/sitetree.js"></script>
    
@stop