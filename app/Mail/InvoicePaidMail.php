<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoicePaidMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public $user, public array $invoice) {}

    public function build()
    {
        return $this->subject('Your invoice '.$this->invoice['number'].' is paid')
            ->view('emails.invoice-paid');
    }
}
