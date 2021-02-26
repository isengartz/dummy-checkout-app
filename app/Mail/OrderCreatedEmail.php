<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCreatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected Order $order;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('You received a new order!')
            ->view('emails.order-created')
            ->with([
                "totalProductValue" => $this->order->total_product_value,
                "totalShippingValue" => $this->order->total_shipping_value,
                "clientName" => $this->order->client_name,
                "clientAddress" => $this->order->client_address
            ]);
    }
}
