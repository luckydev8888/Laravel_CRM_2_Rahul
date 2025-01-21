<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Storage;

class WhatsAppService {
    public function sendMessage($from, $to, $message, $subject = null, $attachment = null) {
        try {
            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
            $to = 'whatsapp:' . $to;
            $from = 'whatsapp:' . $from;
            // print_r($from);exit;
            $options = ['body' => $message];
            if ($attachment) {
                // Store the file in the 'public' directory
                $path = $attachment->store('attachments', 'public');
                $options['mediaUrl'] = [Storage::url($path)]; // Generate a public URL
            }

            $twilio->messages->create($to, array_merge(['from' => $from], $options));

            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
