<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\RenewalDocument;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

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
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'RENEWAL '. $this->renewal->date.'–'.strtoupper($this->renewal->licence->trading_name).' – 
            '.strtoupper($this->renewal->licence->licence_number).'',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.mail-template',
            with: ['message_body' => $this->template],
        );
    }


    
    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {

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
      
        // if(! $get_doc){
        //     return back()->with('error','Could not locate document.');
        // }
        
        return [
            Attachment::fromStorageDisk(env('FILESYSTEM_DISK'), env('BLOB_FILE_PATH').$get_doc->document)
                ->withMime('application/pdf'),
        ];
    }
}
