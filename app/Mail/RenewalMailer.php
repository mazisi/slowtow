<?php

namespace App\Mail;

use App\Models\RenewalDocument;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RenewalMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $renewal,$template;
    public function __construct($licence,$template){
       $this->renewal = $licence;
       $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        try {
            switch ($this->renewal->status) {
            
                case '1':                
                    $get_doc = RenewalDocument::where('licence_renewal_id',$this->renewal->id)->first();
                    break;
                case '2':
                    $get_doc = RenewalDocument::where('licence_renewal_id',$this->renewal->id)->where('doc_type','Client Invoiced')->first();
                    break;
                case '3':
                    $get_doc = RenewalDocument::where('licence_renewal_id',$this->renewal->id)->where('doc_type','Client Paid')->first();
                    break;
                case '5':
                    $get_doc = RenewalDocument::where('licence_renewal_id',$this->renewal->id)->where('doc_type','Renewal Issued')->first();
                    break;
                default:
                return 'An error occured.Please try again.';
                    break;
            }
          
        } catch (\Throwable $th) {
            return to_route('get_licence_transfers')->with('error','Error sending mail.');
        }
        
        return $this->from('no-reply@slowtow.co.za')
                    ->cc('mazisimsebele18@gmail.com')
                    ->subject($this->renewal->licence->trading_name.' AND '.$this->renewal->date)
                    ->markdown('emails.ecomms.renewalMailer')
                    ->attach(public_path('storage/renewalDocuments/'.$get_doc->document))
                    ->with([
                        'message_body' => $this->template
                    ]);
    }
}
