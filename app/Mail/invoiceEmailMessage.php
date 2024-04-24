<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class invoiceEmailMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $paid_cash; 
    public $tripprice; 
    public $discount; 
    public $requiredcash;

    public function __construct($paid_cash = '',$tripprice = '',$discount = '' ,$requiredcash = ''){
        $this->paid_cash    = $paid_cash;
        $this->tripprice    = $tripprice;
        $this->discount     = $discount;
        $this->requiredcash = $requiredcash;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        // $test = $this->message;
        return $this->view('emails.invoiceEmail')->with('paid_cash',$this->paid_cash,'tripprice',$this->tripprice,'discount',$this->discount,'requiredcash',$this->requiredcash);
    }
}
