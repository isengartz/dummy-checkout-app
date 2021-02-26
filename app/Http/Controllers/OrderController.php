<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Http\Handlers\OrderHandler;
use App\Http\Requests\CheckoutRequest;
use App\Mail\OrderCreatedEmail;
use App\Models\Order;
use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    use ApiResponser;


    /**
     * @var OrderHandler
     */
    private OrderHandler $orderHandler;

    /**
     * OrderController constructor.
     * @param OrderHandler $orderHandler
     */
    public function __construct(OrderHandler $orderHandler)
    {
        $this->orderHandler = $orderHandler;
    }

    /**
     * Adds a product into cart.
     * Normally should get added in a Cart Class
     * @param Request $request
     * @return JsonResponse
     */
    public function addProductToCart(Request $request): JsonResponse
    {

        // Check if request includes the product
        $product_id = $request->get('product');
        if (!$product_id) {
            return $this->errorResponse('You need to provide a product!', Response::HTTP_BAD_REQUEST);
        }

        // Check if product exists in the db
        $product = Product::find($product_id);
        if (!$product) {
            return $this->errorResponse('Couldnt find product', Response::HTTP_NOT_FOUND);
        }

        $this->orderHandler->updateCartProductSession($product_id);
        return $this->successResponse([], Response::HTTP_OK);
    }

    /**
     * Returns the checkout page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * @throws BadRequestException
     */
    public function checkoutPage()
    {
        $cartSession = session('cart');
        // Check if cart has a product
        if (empty($cartSession) || empty($cartSession["products"])) {
            return redirect()->back()->withErrors(['You need to add a product in your cart!']);
        }

        // buildCardData() returns an array with
        // "products" => product data from DB
        // "cartProducts" => products ids and quantities
        // "productCost" => sum of products
        // "shippingCost" => cost of shipping methods
        return view('checkout', $this->orderHandler->buildCartData());
    }

    /**
     * Create and store a new order
     * @param CheckoutRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws BadRequestException
     */
    public function checkout(CheckoutRequest $request)
    {
        $validated = $request->all();
        $order = Order::create($this->orderHandler->buildOrderStoreData($validated));
        $request->session()->forget(['cart']); // reset the cart session
        Mail::to(config('mail.from.address'))->send(new OrderCreatedEmail($order)); // Send the email
        return redirect('/')->with('status', 'Your order placed successfully!');
    }

    /**
     * Updates the shipping method
     * @param Request $request
     * @return JsonResponse
     */
    public function updateShippingCost(Request $request): JsonResponse
    {
        $shippingOption = $request->get('shipping_option');
        // Check if the request contain the shipping option OR
        // Check if the shipping option exist in the available options
        if (empty($shippingOption) || !in_array($shippingOption, $this->orderHandler::AVAILABLE_SHIPPING_OPTIONS)) {
            return $this->errorResponse('Unknown Shipping Option', Response::HTTP_BAD_REQUEST);
        }

        $this->orderHandler->updateCartShippingSession($shippingOption);

        return $this->successResponse([], Response::HTTP_OK);
    }

    /**
     * Renders the checkout table.
     * Used to rerender table on shipping option change
     * @return JsonResponse
     */
    public function renderCheckoutTable(): JsonResponse
    {
        // This is an ajax request so we need to try and catch any error in order to return our own response
        // We could throw a CustomException and handle it in the global error handler too
        try {
            $data = $this->orderHandler->buildCartData();
            $view = view('widgets.checkout-product-table', $data)->render();
        } catch (BadRequestException $exception) {
            return $this->errorResponse($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return $this->successResponse(["view" => $view], Response::HTTP_OK);
    }
}
