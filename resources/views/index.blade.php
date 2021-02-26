@extends('layout')

@section('title','Dummy Homepage')

@section('content')
    <div id="product-list" data-products='{{ json_encode($products) }}'></div>
@endsection
