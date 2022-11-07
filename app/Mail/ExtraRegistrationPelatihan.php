<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExtraRegistrationPelatihan extends Mailable
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
        $data['judul']                  = $this->pelatihan->katalog->judul;
        $data['selesai_pendaftaran']    = Carbon::parse($this->pelatihan->selesai_pendaftaran)->translatedFormat('d F Y');
        return $this->subject('Pemberitahuan Batas waktu pendaftaran diperpanjang')->markdown('emails.pelatihan.extra-registration', $data);
    }
}
