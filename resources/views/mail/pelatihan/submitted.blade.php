@component('mail::message')
Yth. Sahabat Metrologi
<br><br>
Telah dibuka pendaftaran pelatihan kemetrologian dengan judul {{$pelatihan->katalog->judul}}. Pendaftaran dibuka pada
tanggal @date($pelatihan->mulai_pendaftaran) sampai dengan @date($pelatihan->selesai_pendaftaran).
<br><br>
Anda dapat melakukan pendaftaran menggunakan menu pelatihan melalui aplikasi SISDMK dengan alamat website
<a href="https://metrologi.kemendag.go.id/sdmk">https://metrologi.kemendag.go.id/sdmk.</a>
<br><br>
Salam Hormat,<br>
Panitia Pelatihan Sumber Daya Kemetrologian.
@endcomponent