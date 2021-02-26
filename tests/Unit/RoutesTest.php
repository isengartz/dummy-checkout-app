<?php

namespace Tests\Feature;

use App\Helpers\AppHelper;
use App\Http\Handlers\OrderHandler;
use App\Mail\OrderCreatedEmail;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tests the index page
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * It should redirect when no session is set
     */
    public function testCheckoutPageWithoutProductsInSession()
    {
        $response = $this->get('/checkout');
        $response->assertStatus(302);
        $response->assertSessionHasErrors();
    }

    /**
     * It should return 200 when session is set and id exist in db
     */
    public function testCheckoutPageWithProductsInSession()
    {

        // Without Products set in session
        $session = ['cart'];
        $response = $this->withSession($session)->get('/checkout');
        $response->assertStatus(302);
        $response->assertSessionHasErrors();


        // With Products set in session but empty db

        $session['cart'] = ['products' => [["id" => 1, "quantity" => 1]]];
        $response = $this->withSession($session)->get('/checkout');
        $response->assertStatus(302);
        $response->assertSessionHasErrors();


        // With Products set in session and products in db
        $this->seed();
        $session['cart'] = ['products' => [["id" => 1, "quantity" => 1]]];
        $response = $this->withSession($session)->get('/checkout');
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
    }

    public function testCheckoutProcedureErrorCases()
    {

        // Create Checkout Post Data
        $checkoutData = [
            "client_name" => "Test Test",
            "client_address" => "St. Anton am Arlberg, Austria",
            "shipping_option" => "express",
            "cardNumber" => "4111111111111111",
            "expiryDate" => "11/22",
            "cvc" => "123"
        ];
        // Create Session Data using random product id
        $session['cart'] = ['products' => [["id" => 1, "quantity" => 1]]];


        // If no session set it should redirect to homepage with Errors
        $this->post('/checkout')->assertRedirect('/')->assertSessionHasErrors();

        // Send post checkout data without setting session
        // Should redirect to homepage with errors
        $this->post('/checkout', $checkoutData)->assertRedirect('/')->assertSessionHasErrors();


        // Send post data & set session data too without seeding the db ( should throw pollution error )
        $this->withSession($session)->post('/checkout', $checkoutData)->assertRedirect('/')->assertSessionHasErrors();
    }

    public function testCheckoutProcedureSuccessCases()
    {

        // Create a Product
        $product = Product::factory()->create();
        // Create Checkout Post Data
        $checkoutData = [
            "client_name" => "Test Test",
            "client_address" => "St. Anton am Arlberg, Austria",
            "shipping_option" => "express",
            "cardNumber" => "4111111111111111",
            "expiryDate" => "11/22",
            "cvc" => "123"
        ];
        // Create session using product id
        $session['cart'] = ['products' => [["id" => $product->id, "quantity" => 1]]];

        // Prevent Mail from been sent
        Mail::fake();

        // Send post data & set session data & seed db
        $response = $this->withSession($session)->post('/checkout', $checkoutData);
        $response->assertStatus(302)->assertRedirect('/')->assertSessionHasNoErrors();

        // Also a new order should get created
        $order = Order::find(1);
        $this->assertNotEmpty($order);

        // The order's total_product_value price should equal the price of products ( as we added only 1 )
        $this->assertEquals(AppHelper::denormalizePriceData($product->price), AppHelper::denormalizePriceData($order->total_product_value));

        // The Price of shipping option should match the one of orders
        $this->assertEquals(
            AppHelper::denormalizePriceData(OrderHandler::SHIPPING_OPTION_PRICE[$checkoutData["shipping_option"]]),
            AppHelper::denormalizePriceData($order->total_shipping_value)
        );

        // Check that an email was sent
        Mail::assertSent(function (OrderCreatedEmail $mail) use ($order) {
            return $mail->getOrder()->id === $order->id;
        });
    }
}
