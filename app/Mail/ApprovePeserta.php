<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovePeserta extends Mailable
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
        $data['judul']              = $this->pelatihan->katalog->judul;
        $data['mulai_pelatihan']    = Carbon::parse($this->pelatihan->mulai_pelatihan)->translatedFormat('d F Y');
        $data['selesai_pelatihan']  = Carbon::parse($this->pelatihan->selesai_pelatihan)->translatedFormat('d F Y');
        return $this->subject('Pemberitahuan peserta yang telah di approved')->markdown('emails.peserta.approve', $data);
    }
}
