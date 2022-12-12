<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
       $this->data=$data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@goverify.co.za')
        ->subject('Login credentials')
        ->markdown('emails.sendMailCredentials')
        ->with([
            'full_name' => $this->data['full_name'],
            'email' => $this->data['email'],
            'password' => $this->data['password'],
        ]);
    }
}
