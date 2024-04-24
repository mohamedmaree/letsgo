<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PublicMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $message;

    public function __construct($messagee)
    {
        $this->message = $messagee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $test = $this->message;
        return $this->view('emails.WelcomeMail')->with('Message',$this->message);
    }
}
