    @if(isset($model))
    <? $mainActions = Actions::forItem($model)->filtered('main') ?>
    <? $besideActions = Actions::forItem($model)->filtered('!main') ?>
    @else
    <? $mainActions = [] ?>
    @endif

    @if(count($mainActions) > 1)
        <div class="nav-tabs-custom">
        @include('partials.resource-detail-box-header')
    @else
        <div class="box">
    @endif
        @section('inner-content')
        @show()
        </div><!-- div.nav-tabs-custom|div.box --> 
