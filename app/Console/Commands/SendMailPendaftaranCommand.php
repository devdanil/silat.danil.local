<?php

namespace App\Console\Commands;

use App\Models\Pendaftaran;
use Illuminate\Console\Command;

class SendMailPendaftaranCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:pendaftaran';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email pemberitahuan pendaftaran pelatihan dikirim...';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $peserta = Pendaftaran::select('id')->where('sendmail_at', null)->with('peserta.email')->limit(10)->get();
        foreach ($peserta as $key) {
            Pendaftaran::where('id', $key->id)->update(['sendmail_at' => date('Y-m-d H:i:s')]);
        }
    }
}
