<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationPeserta extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $pelatihan;
    public function __construct($pelatihan)
    {
        $this->pelatihan = $pelatihan;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data['judul'] = $this->pelatihan->katalog->judul;
        return $this->subject('Pemberitahuan peserta untuk melakukan konfirmasi pendaftaran')->markdown('emails.peserta.confirmation', $data);
    }
}
