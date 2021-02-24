<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <link rel="stylesheet" href="{{asset('/css/app.css')}}">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        @foreach($products as $product)
            <div class="col-6 col-sm-4 col-md-3 ">
                <div class="border rounded text-center p-2 m-2">
                    <h3>{{$product->name}}</h3>
                    <p>{{$product->brand->name}}</p>
                    <p>{{\App\Helpers\AppHelper::formatCurrency($product->price,"EUR")}}</p>
                    <button data-ref="{{$product->id}}" class="btn btn-primary buy-btn">BUY</button>
                </div>

            </div>

        @endforeach
    </div>
</div>

</body>
</html>
