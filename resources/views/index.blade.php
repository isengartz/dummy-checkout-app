@extends('layout')

@section('title','Dummy Homepage')

@section('content')
    @if(!empty($products))
        <div id="product-list" data-products='{{ json_encode($products) }}'></div>
    @else
        <div><h4>No Products ! Add some !</h4></div>
    @endif
@endsection
