<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\NominationDocument;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
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
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->nomination->licence->trading_name.' Appointment Of Managers ',
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
        if(! $get_doc){
            return back()->with('error','Could not locate document.');
        }
        
        return [
            Attachment::fromStorageDisk(env('FILESYSTEM_DISK'), env('BLOB_FILE_PATH').$get_doc->document)
                ->withMime('application/pdf'),
        ];
    }
}
