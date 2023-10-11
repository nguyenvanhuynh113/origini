<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->markdown('emails.invoice')
            ->subject('Hóa đơn đặt hàng số #' . $this->order->number)
            ->with('order', $this->order);
    }


}
