Pegawai: form pengajuan cuti
Buatkan form pengajuan surat cuti untuk pegawai dengan ketentuan berikut:

- Field: jenis_cuti (dropdown: tahunan/sakit/melahirkan/alasan_penting/besar), tanggal_mulai, tanggal_selesai, keterangan_cuti, tembusan (textarea)
- Hitung otomatis jumlah hari dari tanggal yang dipilih
- Tampilkan sisa kuota cuti dari tabel cuti_kuota sesuai jenis_cuti yang dipilih
- Validasi: tanggal_selesai tidak boleh sebelum tanggal_mulai
- Validasi: sisa kuota harus cukup (kecuali jenis sakit & melahirkan, kuota tidak dibatasi)
- Setelah submit, status surat otomatis jadi "diajukan"
- Nomor surat digenerate otomatis dengan format: [kode]/CUTI/[bulan-romawi]/[tahun]

Gunakan PHP + MySQL + HTML form biasa. Tampilkan notifikasi sukses/gagal di atas form.
Admin: verifikasi & lapor pimpinan
Buatkan halaman admin untuk memverifikasi surat cuti pegawai:

- Tampilkan daftar surat dengan status "diajukan" dalam tabel
- Kolom: nama pegawai, NIP, jenis cuti, tanggal, jumlah hari, tanggal pengajuan
- Tombol "Proses & Lapor ke Pimpinan" > ubah status menjadi "diproses"
- Tambahkan field keterangan opsional saat memproses
- Filter tabel: berdasarkan jenis_cuti, status_kepegawaian (PNS/PPPK), bulan

Gunakan PHP + MySQL. Validasi perubahan status hanya boleh dari "diajukan" ke "diproses".
Pimpinan: setujui/tolak cuti
Buatkan halaman pimpinan untuk menyetujui atau menolak surat cuti:

- Tampilkan surat dengan status "diproses" saja
- Tombol "Setujui" > status jadi "disetujui", kuota cuti pegawai dikurangi
- Tombol "Tolak" > status jadi "ditolak", wajib isi alasan penolakan
- Setelah keputusan, pegawai bisa melihat update status di dashboard-nya
- Validasi: pimpinan tidak bisa mengubah surat yang sudah "disetujui" atau "ditolak"

Gunakan PHP + MySQL. Pengurangan kuota cuti harus dalam satu transaksi DB.
Admin: cetak surat cuti (PDF/print)
Buatkan fitur cetak surat cuti untuk admin dengan ketentuan:

- Hanya surat dengan status "disetujui" yang bisa dicetak
- Format surat resmi instansi pemerintah: kop surat, nomor surat, perihal, isi, tanda tangan
- Data yang ditampilkan: nama, NIP, jabatan, unit_kerja, jenis_cuti, tanggal_mulai, tanggal_selesai, jumlah_hari, keterangan, tembusan
- Gunakan window.print() atau library dompdf untuk generate PDF
- Tampilkan preview sebelum cetak

Stack: PHP + HTML/CSS print-friendly (gunakan @media print). Jika pakai dompdf, sertakan cara instalasinya.
