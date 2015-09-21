@extends('layouts.col-1')

<? $context = isset($context) ? $context : 'default' ?>

<? if (isset($search)) {
    $collection = Listing::paginate($search);
} ?>

@section('sub-nav')
@include('partials.bootstrap-modal')

@include('partials.search-sub-nav')
@stop

@section('inner-content')

    @searchForm()
    @include('partials.table-of-resources')

@stop