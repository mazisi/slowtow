<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $company;
    public $userName;
    public $company_name;
    public $type;

    public function __construct($company, $type){
       $this->type = $type;
       $this->company = $company;
       $this->userName = auth()->user()->name;
       $this->company_name = $company->name;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        try {  

                return $this->from(env("MAIL_FROM_ADDRESS"), auth()->user()->name)
                ->markdown('emails.notify-slotow')
                ->subject($this->type == 'company' ? 'Company Details Changed' : 'Profile Details Changed');          
            
        } catch (\Throwable $th) {
            //  return back()->with('error','Error!! Please contact support.');
        }
        
    }

  
}
