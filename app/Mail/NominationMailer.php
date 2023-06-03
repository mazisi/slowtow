<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\NominationDocument;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NominationMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $nomination,$template;
    public function __construct($licence,$template){
       $this->nomination = $licence;
       $this->template = $template;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        try {
             // 1= > Client Quoted
  //
            switch ($this->nomination->status) {      
                case '1':                
                    $get_doc = NominationDocument::where('nomination_id',$this->nomination->id)->where('doc_type','Client Quoted')->first();
                   break;
                case '2':
                    $get_doc = NominationDocument::where('nomination_id',$this->nomination->id)->where('doc_type','Client Invoiced')->first();
                    break;
                case '3':
                    $get_doc = NominationDocument::where('nomination_id',$this->nomination->id)->where('doc_type','Client Paid')->first();
                    break;
                case '4':
                    $get_doc = NominationDocument::where('nomination_id',$this->nomination->id)->where('doc_type','Payment To The Liquor Board')->first();
                    break;
                case '8':
                    $get_doc = NominationDocument::where('nomination_id',$this->nomination->id)->where('doc_type','Nomination Logded')->first();
                    break;
                case '9':
                    $get_doc = NominationDocument::where('nomination_id',$this->nomination->id)->where('doc_type','Nomination Issued')->first();
                    break;
                default:
                return back()->with('error','Could not locate document.');
                 break;
            }
            
                 return $this->from(env("MAIL_FROM_ADDRESS"))
                 ->view('emails.mail-template')
                 ->cc('info@slotow.co.za')
                 ->cc('sales@slotow.co.za')
                ->subject($this->nomination->licence->trading_name.' Appointment Of Managers ')
                ->attach(env('BLOB_FILE_PATH').$get_doc->document);

            
        } catch (\Throwable $th) {
            return to_route('get_licence_transfers')->with('error','Error sending mail.');
        }
        
    }
}
