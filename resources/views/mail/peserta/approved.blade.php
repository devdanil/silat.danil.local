@component('mail::message')
Yth. Sahabat Metrologi
<br><br>
Saudara telah terdaftar sebagai peserta pada pelatihan {{$pelatihan->katalog->judul}}. Waktu pelatihan akan dilaksanakan
pada tanggal @date($pelatihan->mulai_pelatihan) sampai dengan @date($pelatihan->selesai_pelatihan).
<br><br>
Terima kasih atas perhatian dan kerja sama saudara demi mewujudkan SDM Kemetrologian yang yang kompeten dan
professional.
<br><br>
Salam Hormat,<br>
Panitia Pelatihan Sumber Daya Kemetrologian.
@endcomponent