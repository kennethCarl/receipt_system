<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container mt-3 mb-3">
        @if(count($errors) > 0)
            <div class="alert alert-danger" role="alert">
            @foreach($errors as $error)
                {{$error}}<br>
            @endforeach
            </div>
        @else
        <div class="d-flex justify-content-center mb-2"><h3>Thank for your purchase!</h3></div>
        <div class="d-flex align-items-center justify-content-center">
            <div class="mt-3 w-50">
                <div class="row">
                    <div class="col-md-2">
                        <strong>Receipt:</strong>
                    </div>
                </div>

                <div class="row" style="padding-left: 50px;">
                    <div class="col-md-1">
                        <strong>Items:</strong>
                    </div>
                </div>

                {{--Dispplay Items--}}
                @foreach($receipt_items as $receipt_item)
                    <div class="row" style="padding-left: 50px;">
                        <div class="col-md-12">
                            <strong>{{$receipt_item->itemName}}</strong>
                        </div>
                    </div>
                    <div class="row" style="padding-left: 100px;">
                        <div class="col-md-2 d-flex justify-content-end">
                            <strong>{{$receipt_item->qty}}</strong>
                        </div>
                        <div class="col-md-5 d-flex justify-content-end">
                            <strong>{{$receipt_item->unitPrice > 0 ? number_format($receipt_item->unitPrice, 2) : 'FREE'}}</strong>
                        </div>
                        <div class="col-md-5 d-flex justify-content-end">
                            <strong>{{$receipt_item->totalAmount > 0 ? number_format($receipt_item->totalAmount, 2) : 'FREE'}}</strong>
                        </div>
                    </div>
                @endforeach

                <div class="row">
                    <div class="col-md-1">
                        <strong>Total:</strong>
                    </div>
                    <div class="col-md-1 d-flex justify-content-end">
                        {{$grand_total}}
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-1">
                        <strong>GST:</strong>
                    </div>
                    <div class="col-md-1 d-flex justify-content-end">
                        {{$gst}}
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</body>
</html>
