@extends('index')

@section('content')

<? $context = isset($context) ? $context : 'default' ?>

@include('partials.bootstrap-modal')


<? $modelClass = Resource::modelClass() ?>
<? $searchActions = Actions::forType($modelClass)->filtered('search') ?>
<? $typeActions = Actions::forType($modelClass)->filtered('!search') ?>


<? if(isset($search)){
    $collection = Listing::paginate($search);
} ?>

<div class="col-lg-12 col-md-12">
    @if(isset($title) && $title)
    <h2 class="page-header text-right">{{ $title }}</h2>
    @endif
    @if(Resource::hasSearchForm())
    @include('partials.resource-search-header')
    @elseif($typeActions)
    @include('partials.resource-no-search-header')
    @endif
    @include('partials.table-of-resources')

</div>
@stop