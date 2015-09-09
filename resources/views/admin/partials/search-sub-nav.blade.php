    <? $modelClass = Resource::modelClass() ?>
    <? $mainActions = Actions::forType($modelClass)->filtered('main') ?>
    <? $besideActions = Actions::forType($modelClass)->filtered('!main') ?>

    @if(count($mainActions) || count($besideActions))
        @section('sub-nav')
        @include('partials.sub-nav', ['mainActions'=>$mainActions, 'besideActions'=>$besideActions])
        @stop
    @endif