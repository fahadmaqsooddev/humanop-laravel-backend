<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoicePaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $user, public array $invoice) {}

    public function build()
    {
        return $this->subject('Your invoice '.$this->invoice['number'].' is paid')
            ->view('emails.invoice-paid');
    }
}
