<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailWithAttachment extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $filePath, $fileName, $mime)
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
        return $this->attach($this->filePath, [
                        'as' => $this->fileName, // Optional: Rename the file
                        'mime' => $this->mime,  // Specify MIME type
                    ]);
    }
}
