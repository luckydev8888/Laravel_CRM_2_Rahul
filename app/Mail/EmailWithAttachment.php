<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailWithAttachment extends Mailable
{
    use Queueable, SerializesModels;

    public $details; // Add this to hold email details
    public $filePath;
    public $fileName;
    public $mime;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $filePath = null, $fileName = null, $mime = null)
    {
        $this->details = $details;
        $this->filePath = $filePath;
        $this->fileName = $fileName;
        $this->mime = $mime;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('emails.attachment') // Specify the email view
                     ->subject($this->details['subject']) // Use the subject from details
                     ->with('details', $this->details); // Pass details to the view

        // Attach the file only if it exists
        if ($this->filePath) {
            $email->attach($this->filePath, [
                'as' => $this->fileName, // Rename the file
                'mime' => $this->mime,  // Specify MIME type
            ]);
        }

        return $email;
    }
}
