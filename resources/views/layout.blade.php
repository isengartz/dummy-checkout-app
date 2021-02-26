<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title','Dummy Laravel Project')</title>
    <link rel="stylesheet" href="{{asset('/css/app.css')}}">
</head>
<body>
@include('widgets.navbar')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger m-2" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('status'))
        <div class="alert alert-success m-2" role="alert">
            {{ session('status') }}
        </div>
    @endif


    @yield('content')
</div>
<script src="{{asset('/js/app.js')}}"></script>
@yield('custom-scripts')
</body>
</html>
