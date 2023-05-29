<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\TransferDocument;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
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
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Transfer Mailer',
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
        
        if($this->transfer->status == 'Client Quoted'){
           
            return [
                Attachment::fromStorageDisk(env('FILESYSTEM_DISK'), env('BLOB_FILE_PATH').$get_doc->document)
                            ->withMime('application/pdf'),
                // Attachment::fromStorageDisk(env('FILESYSTEM_DISK'), env('BLOB_FILE_PATH').'GoVerify_Attached_FIle.pdf')
                // ->withMime('application/pdf')
            ];

        }else{
            return [
                Attachment::fromStorageDisk(env('FILESYSTEM_DISK'), env('BLOB_FILE_PATH').$get_doc->document)
                            ->withMime('application/pdf'),
            ];

        }
        
    }
}
