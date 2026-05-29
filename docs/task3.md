Admin: buat surat tugas
Buatkan form pembuatan surat tugas oleh admin dengan ketentuan:

- Field: pilih pegawai (dropdown dari tabel pegawais), dasar_hukum (textarea), tujuan_tugas (textarea), lokasi_tugas, tanggal_mulai_tugas, tanggal_selesai_tugas, perihal
- Bisa assign ke satu atau lebih pegawai sekaligus (simpan sebagai baris terpisah di tabel surats dengan nomor surat yang sama atau seri)
- Nomor surat digenerate otomatis: [kode]/TUGAS/[bulan-romawi]/[tahun]
- Status surat tugas langsung "disetujui" karena dibuat oleh admin
- Pegawai yang ditugaskan langsung bisa melihat di dashboard mereka

Gunakan PHP + MySQL + HTML form biasa.
Admin: monitor & cetak surat tugas
Buatkan halaman monitoring surat tugas untuk admin:

- Tabel daftar surat tugas: nomor surat, pegawai, lokasi, tanggal mulai, tanggal selesai, status (sedang berjalan / selesai)
- Status "sedang berjalan" jika tanggal hari ini antara tanggal_mulai dan tanggal_selesai
- Filter: bulan, nama pegawai, status
- Tombol cetak per surat menggunakan window.print() atau dompdf
- Format cetak: surat tugas resmi dengan kop, dasar hukum, isi penugasan, tanda tangan

Gunakan PHP + MySQL. Status dihitung dari perbandingan tanggal, tidak disimpan sebagai kolom terpisah.
