<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CurhatanReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data; // Data curhatan
    public $replyMessage; // Pesan balasan admin

    public function __construct($data, $replyMessage)
    {
        $this->data = $data;
        $this->replyMessage = $replyMessage;
    }

    public function build()
    {
        return $this->subject('Balasan Curhat Warga - JTV')
                    ->view('emails.curhatan_reply'); // Kita akan buat view ini nanti
    }
}