{!! Form::open(['action' => 'OrderController@checkout', 'method' => 'post']) !!}
<div class="row align-items-center">
    <div class="col-md-6">
        <div class="form-row mb-2">
            {!! Form::label('client_name','Full Name') !!}
            {!! Form::text('client_name',old('client_name') ?? '',['class'=>'form-control','required' => 'required']) !!}
        </div>
        <div class="form-row mb-2">
            {!! Form::label('client_address','Full Address') !!}
            {!! Form::text('client_address',old('client_address') ?? '',['class'=>'form-control','required' => 'required','placeholder' => 'St. Anton am Arlberg, Austria']) !!}
        </div>


    </div>
    <div class="col-md-6">

        <div class="checkout-order-wrapper">
            <h3 class="text-center mb-3">Your Order Details</h3>
            <div class="order-review d-flex flex-column">
                <div id="product-table-wrapper" class="table-responsive ">
                    @include('widgets.checkout-product-table')

                </div>
                <div class="checkout-payment mt-2 p-2">
                    <div class="form-row mb-2">
                        <label>Card Details</label>
                        <div class="w-100" id="card-holder"></div>
                        <small class="form-text text-muted">We'll never save or share your credit card info with anyone
                            else.</small>

                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary w-100">PAY</button>
                </div>
            </div>

        </div>


    </div>
</div>

{!! Form::close() !!}
