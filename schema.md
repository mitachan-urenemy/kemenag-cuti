# Database Schema Documentation

Berikut adalah penjelasan mengenai struktur database untuk tabel-tabel utama yang menjadi fokus sistem: `users`, `pegawais`, dan `surats`.

## 1. Tabel `pegawais` (Master Data Pegawai)
Tabel ini berfungsi sebagai **sumber kebenaran tunggal (Single Source of Truth)** untuk data kepegawaian.

*   **Fungsi Utama:** Menyimpan biodata lengkap pegawai seperti NIP, Nama, Pangkat/Golongan, dan Jabatan.
*   **Karakteristik:**
    *   `is_kepala`: Flag boolean untuk menandai apakah pegawai tersebut adalah pejabat (Kepala) yang berwenang menandatangani surat.
    *   Tidak menyimpan data login; data login dipisahkan ke tabel `users`.

## 2. Tabel `users` (Otentikasi & Sistem)
Tabel ini digunakan untuk **hak akses masuk (login)** ke dalam aplikasi.

*   **Fungsi Utama:** Menyimpan kredensial (`username`, `password`, `email`) untuk login.
*   **Relasi:**
    *   Memiliki `pegawai_id` (Foreign Key) yang menghubungkan akun user dengan data pegawai aslinya.
    *   Jika `pegawai_id` null, user tersebut mungkin adalah admin sistem murni yang tidak terikat data kepegawaian.

## 3. Tabel `surats` (Sentralisasi Surat)
Tabel ini adalah tabel transaksi utama yang menyimpan **semua jenis surat** (Surat Cuti dan Surat Tugas) dalam satu tempat.

*   **Fungsi Utama:** Mencatat detail surat yang dibuat.
*   **Desain Struktur:** Menggunakan pendekatan *Single Table Inheritance* (STI) sederhana.
    *   **Kolom Umum:** `nomor_surat`, `jenis_surat` (enum: cuti/tugas), `tanggal_surat`.
    *   **Kolom Cuti:** `jenis_cuti`, `tanggal_mulai_cuti`, dsb (hanya diisi jika `jenis_surat` = 'cuti').
    *   **Kolom Tugas:** `dasar_hukum`, `tujuan_tugas`, `lokasi_tugas`, dsb (hanya diisi jika `jenis_surat` = 'tugas').
*   **Aktor:**
    *   `pegawai_id`: **Pegawai Utama** yang dituju surat (Pemohon Cuti atau Petugas yang ditugaskan).
    *   `penandatangan_id`: Merujuk ke `pegawais` (Pejabat yang ttd).
    *   `created_by_user_id`: Merujuk ke `users` (Operator/Admin yang menginput data).

---

## Ringkasan Relasi

1.  **User -> Pegawai:** "Akun ini milik Pegawai A".
2.  **Surat -> Pegawai (Target):** "Surat Cuti ini milik Pegawai B" atau "Surat Tugas ini menugaskan Pegawai B".
3.  **Surat -> Pegawai (Penandatangan):** "Surat ini ditandatangani oleh Kepala Dinas C".
4.  **Surat -> User (Created By):** "Surat ini diketik/dibuat oleh Admin D".
