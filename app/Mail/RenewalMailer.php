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
                return back('error', 'An error occured.Please try again.');
                    break;
            }
          
            if(! $get_doc){
                return back()->with('error','Could not locate document.');
            }
        
        return $this->from(env("MAIL_FROM_ADDRESS"))
                    ->cc(env("MAIL_FROM_ADDRESS"))
                    ->subject('RENEWAL '. $this->renewal->date.'. – TRADING NAME – LICENCE NUMBER')
                    ->subject('Renewal for '.$this->renewal->licence->trading_name.' AND '.$this->renewal->date)
                    ->view('emails.mail-template')
                   ->attach(env('BLOB_FILE_PATH').$get_doc->document)
                    ->with([
                        'message_body' => $this->template
                    ]);

                } catch (\Throwable $th) {
                    return to_route('get_licence_transfers')->with('error','Error sending mail.');
                }
    }
}
