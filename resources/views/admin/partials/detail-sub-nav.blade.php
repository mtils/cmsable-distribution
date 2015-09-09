    @if(isset($model))
    <? $mainActions = Actions::forItem($model)->filtered('main') ?>
    <? $besideActions = Actions::forItem($model)->filtered('!main') ?>
    @else
    <? $mainActions = [] ?>
    @endif

    @if(count($mainActions) > 1)
        @section('sub-nav')
        @include('partials.sub-nav', ['mainActions'=>$mainActions, 'besideActions'=>$besideActions])
        @stop
    @endif