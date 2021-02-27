@extends('layout')

@section('title','Dummy Homepage')

@section('content')
    <div class="mt-3">
        @if(!empty($products))
            {{-- This will render a React component /resources/js/components/ProductList ! --}}
            <div id="product-list" data-products='{{ json_encode($products) }}'></div>
        @else
            <div><h4>No Products ! Add some !</h4></div>
        @endif
    </div>
@endsection
