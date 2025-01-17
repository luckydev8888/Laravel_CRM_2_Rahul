<?php

namespace App\Services;

use Twilio\Rest\Client;

class WhatsAppService {
    public function sendMessage($from, $to, $message, $subject = null, $attachment = null) {
        try {
            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
            $to = 'whatsapp:' . $to;
            $from = 'whatsapp:' . $from;

            $options = ['body' => $message];
            if ($attachment) {
                $options['mediaUrl'] = [url($attachment->store('attachments'))];
            }

            $twilio->messages->create($to, array_merge(['from' => $from], $options));

            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
