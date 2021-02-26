<?php


namespace App\Http\Handlers;

use App\Exceptions\BadRequestException;
use App\Helpers\AppHelper;
use App\Models\Product;
use Illuminate\Http\Response;

/**
 * Class OrderHandler
 * @package App\Http\Handlers
 */
class OrderHandler
{
    // Normally this should be stored in the db
    const SHIPPING_COST_FREE_STANDARD = 'free_standard';
    const SHIPPING_COST_EXPRESS = 'express';
    const AVAILABLE_SHIPPING_OPTIONS = [
        self::SHIPPING_COST_FREE_STANDARD,
        self::SHIPPING_COST_EXPRESS
    ];
    const DEFAULT_SHIPPING_METHOD = self::SHIPPING_COST_FREE_STANDARD;
    const SHIPPING_OPTION_PRICE = [
        self::SHIPPING_COST_FREE_STANDARD => 0,
        self::SHIPPING_COST_EXPRESS => 10
    ];

    /**
     * Updates the product session data
     * @param int $product_id
     */
    public function updateCartProductSession(int $product_id): void
    {
        $cartSession = session('cart') ?? [];
        // Check if there are any other product into session else init it
        if (empty($cartSession) || empty($cartSession['products'])) {
            $cartSession["products"] = [];
        }
        // Get a list of all products IDS
        $ids = array_column($cartSession["products"], 'id') ?? [];
        // Check if product already exist
        if (in_array($product_id, $ids)) {
            // if it exists just increase the quantity by 1
            $foundKey = array_search($product_id, $ids);
            $cartSession["products"][$foundKey]["quantity"] = intval($cartSession["products"][$foundKey]["quantity"]) + 1;
        } else {
            // else push it into array
            array_push($cartSession['products'], ["id" => $product_id, "quantity" => 1]);
        }
        // Add product into Session and return a success response
        session(['cart' => $cartSession]);
    }

    /**
     * Updates the shipping method in session
     * @param string $shippingOption
     */
    public function updateCartShippingSession(string $shippingOption): void
    {
        // Update the Session
        $cartSession = session('cart');
        $cartSession["shipping_method"] = $shippingOption;
        session(['cart' => $cartSession]);
    }

    /**
     * Returns all the cart data required for checkout page
     * @return array
     * @throws BadRequestException
     */
    public function buildCartData(): array
    {
        $data = $this->buildCartProductData();
        $data["shippingCost"] = $this->calculateShippingCost();

        return $data;
    }

    /**
     * Calculates the shipping_value and product_value in order to store them into database
     * SOS: ONLY CALL THIS AFTER CheckoutRequest Validation PASSES !!!
     * @param array $data
     * @return array
     * @throws BadRequestException
     */
    public function buildOrderStoreData(array $data): array
    {
        data_set($data, 'total_shipping_value', $this->shippingMethodCost($data['shipping_option']));
        data_set($data, 'total_product_value', $this->buildCartProductData()['productCost']);
        return $data;
    }

    /**
     * Returns the cart data for products
     * @return array
     * @throws BadRequestException
     */
    private function buildCartProductData(): array
    {
        $cartSession = session('cart');

        if (empty($cartSession["products"])) {
            throw new BadRequestException('Cart is empty', Response::HTTP_BAD_REQUEST);
        }
        // extracts only the product ids from session
        $ids = array_column($cartSession["products"], "id");
        // get them and return them with id as assoc key
        $products = Product::whereIn('id', $ids)->get()->keyBy('id');
        $overallCost = 0;
        // Use the session array to loop through each product and calculate the price
        foreach ($cartSession["products"] as $productKey) {
            if (empty($products[$productKey["id"]])) { // this can only happen if someone throws random ids into session
                session()->flush(); // flush the session
                throw new BadRequestException("Session Data is polluted", Response::HTTP_BAD_REQUEST);
            }
            $overallCost += AppHelper::denormalizePriceData($products[$productKey["id"]]->price) * intval($productKey["quantity"]);
        }

        return [
            "products" => $products,
            "cartProducts" => $cartSession["products"],
            "productCost" => AppHelper::normalizePriceData($overallCost)
        ];
    }

    /**
     * Returns the cost of the current selected shipping method
     * @return int
     */
    private function calculateShippingCost(): int
    {
        $cartSession = session('cart');
        $shippingMethod = (!empty($cartSession["shipping_method"]) && in_array($cartSession["shipping_method"], self::AVAILABLE_SHIPPING_OPTIONS)) ? $cartSession["shipping_method"] : self::DEFAULT_SHIPPING_METHOD;
        return $this->shippingMethodCost($shippingMethod);
    }

    /**
     * Calculates price for shipping method
     * @param string $shippingMethod
     * @return int
     */
    private function shippingMethodCost(string $shippingMethod): int
    {
        return self::SHIPPING_OPTION_PRICE[$shippingMethod];
    }
}
