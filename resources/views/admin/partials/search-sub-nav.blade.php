    <? $modelClass = Resource::modelClass() ?>
    <? $mainActions = Actions::forType($modelClass)->filtered('main') ?>
    <? $besideActions = Actions::forType($modelClass)->filtered('!main') ?>
    <? $collectionActions = Actions::forCollection($collection)->filtered('!main') ?>
    @if(count($collectionActions))
    @foreach($collectionActions as $action)
    <? $besideActions->push($action) ?>
    @endforeach
    @endif
    @if(count($mainActions) || count($besideActions))
        @include('partials.sub-nav', ['mainActions'=>$mainActions, 'besideActions'=>$besideActions, 'collectionActions'=>$collectionActions])
    @endif