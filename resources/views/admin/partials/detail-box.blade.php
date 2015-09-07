    @if(isset($model))
    <? $mainActions = Actions::forItem($model)->filtered('main') ?>
    <? $besideActions = Actions::forItem($model)->filtered('!main') ?>
    @else
    <? $mainActions = [] ?>
    @endif

    @if(count($mainActions) > 1)
        @section('sub-nav')
        @include('partials.resource-detail-nav')
        @stop
    @endif
        @section('inner-content')
        @show()
        </div><!-- div.nav-tabs-custom|div.box --> 
