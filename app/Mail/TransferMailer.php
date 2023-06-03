<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\TransferDocument;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TransferMailer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $transfer,$template;
    public function __construct($licence,$template){
       $this->transfer = $licence;
       $this->template = $template;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        try {
            
            switch ($this->transfer->status) {      
                case '1':                
                    $get_doc = TransferDocument::where('licence_transfer_id',$this->transfer->id)->where('doc_type','Client Quoted')->first();
                    break;
                case '2':
                    $get_doc = TransferDocument::where('licence_transfer_id',$this->transfer->id)->where('doc_type','Client Invoiced')->first();
                    break;
                case '3':
                    $get_doc = TransferDocument::where('licence_transfer_id',$this->transfer->id)->where('doc_type','Client Paid')->first();
                    break;
                case '5':
                    $get_doc = TransferDocument::where('licence_transfer_id',$this->transfer->id)->where('doc_type','Payment To The Liquor Board')->first();
                    break;
                case '6':
                    $get_doc = TransferDocument::where('licence_transfer_id',$this->transfer->id)->where('doc_type','Transfer Logded')->first();
                    break;
                case '7':
                    $get_doc = TransferDocument::where('licence_transfer_id',$this->transfer->id)->where('doc_type','Activation Fee Paid')->first();
                    break;
                case '8':
                    $get_doc = TransferDocument::where('licence_transfer_id',$this->transfer->id)->first();
                    break;
                default:
                return back()->with('error','Could not send email.');
                    break;
            }
            if(! $get_doc){
                return back()->with('error','Could not locate document.');
            }
            
            if($this->transfer->status == 'Client Quoted'){
                return $this->from(env("MAIL_FROM_ADDRESS"))
                ->view('emails.mail-template')
                ->cc('info@slotow.co.za')
                ->cc('sales@slotow.co.za')
                ->subject($this->transfer->licence->trading_name.' Transfer')
                ->attach(env('BLOB_FILE_PATH').$get_doc->document)
                ->attach(storage_path('app/public/GoVerify.pdf'))
                ->with([
                    'message_body' => $this->template
                ]);

            }else{
                return $this->from(env("MAIL_FROM_ADDRESS"))
                ->view('emails.mail-template')
                ->cc(env("MAIL_FROM_ADDRESS"))
                ->subject($this->transfer->licence->trading_name.' Transfer ')
                ->attach(env('BLOB_FILE_PATH').$get_doc->document);

            }
            
        } catch (\Throwable $th) {
            return to_route('get_licence_transfers')->with('error','Error!! Please contact support.');
        }
        
    }

  
}
