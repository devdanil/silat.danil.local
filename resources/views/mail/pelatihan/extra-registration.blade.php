@component('mail::message')
Yth. Sahabat Metrologi
<br><br>
Batas Waktu pendaftaran pelatihan {{$pelatihan->katalog->judul}} diperpanjang sampai dengan
@date($pelatihan->selesai_pendaftaran).
<br><br>
Anda dapat melakukan pendaftaran menggunakan menu pelatihan melalui aplikasi SISDMK dengan alamat website
https://metrologi.kemendag.go.id/sdmk.
<br><br>
Terima kasih atas perhatian dan kerja sama saudara demi mewujudkan SDM Kemetrologian yang yang kompeten dan
professional.
<br><br>
Salam Hormat,<br>
Panitia Pelatihan Sumber Daya Kemetrologian.
@endcomponent