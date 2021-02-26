<table class="checkout-order-table">
    <thead>
    <tr>
        <th class="product-name">Product</th>
        <th class="product-total">Sub Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($cartProducts as $productIndex)
        <tr class="cart_item">
            <td class="product-name">{{$products[$productIndex["id"]]->name}} <strong
                    class="product-quantity">×{{ $productIndex["quantity"]}}</strong></td>
            <td class="product-total">
                <span class="price-amount"><bdi>{{App\Helpers\AppHelper::formatCurrency($products[$productIndex["id"]]->price * $productIndex["quantity"],env('APP_CURRENCY_NAME'))}}</bdi></span>
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr class="table-shipping-totals">
        <th>Shipping Cost</th>
        <td>

            <ul class="table-shipping-methods">
                <li>
                    <input class="form-check-input shipping_on_change_handler" type="radio"
                           name="shipping_option"
                           value="free_standard" {{ empty(session('cart')["shipping_method"]) || session('cart')["shipping_method"] === "free_standard" ? 'checked' : '' }}>
                    <label class="form-check-label">
                        Standard
                    </label>
                <li>
                    <input class="form-check-input shipping_on_change_handler" type="radio"
                           name="shipping_option"
                           value="express" {{  !empty(session('cart')["shipping_method"]) && session('cart')["shipping_method"] === "express" ? 'checked' : '' }}>
                    <label class="form-check-label">
                        Express: {{App\Helpers\AppHelper::formatCurrency(10,env('APP_CURRENCY_NAME'))}}
                    </label>
                </li>
            </ul>
        </td>
    </tr>
    <tr class="order-total">
        <th>Total</th>
        <td><strong><span class="price-amount"><bdi>{{App\Helpers\AppHelper::formatCurrency($productCost + $shippingCost ,env('APP_CURRENCY_NAME'))}}</bdi></span></strong>
        </td>
    </tr>
    </tfoot>
</table>
