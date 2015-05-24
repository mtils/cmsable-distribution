@extends('index')

@section('content')

<? $context = isset($context) ? $context : 'default' ?>

@include('partials.bootstrap-modal')

<? if(isset($search)){
    $collection = Listing::paginate($search);
} ?>

<div class="col-lg-12 col-md-12">
    @if(isset($title) && $title)
    <h2 class="page-header text-right">{{ $title }}</h2>
    @endif
    @include('partials.new-model-button')
    @include('partials.resource-index-header')
    @include('partials.table-of-resources')

</div>
@stop