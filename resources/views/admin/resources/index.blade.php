@extends('layouts.col-1')

@section('sub-nav')
@include('partials.search-sub-nav')
@stop

@section('inner-content')

<? $context = isset($context) ? $context : 'default' ?>

@include('partials.bootstrap-modal')

<? if(isset($search)){
    $collection = Listing::paginate($search);
} ?>

    @searchForm()
    @include('partials.table-of-resources')

</div>
@stop