# Database Schema Documentation

Berikut adalah penjelasan mengenai struktur database untuk tabel-tabel utama yang menjadi fokus sistem: `users` dan `surats`.

## 1. Tabel `users` (Otentikasi & Sistem)
Tabel ini digunakan untuk **hak akses masuk (login)** ke dalam aplikasi.

*   **Fungsi Utama:** Menyimpan kredensial (`username`, `password`, `email`) untuk login.
*   **Karakteristik:**
    *   Menggunakan kolom `id` sebagai Primary Key.
    *   Menyimpan data otentikasi standar Laravel.

## 3. Tabel `surats` (Sentralisasi Surat)
Tabel ini adalah tabel transaksi utama yang menyimpan **semua jenis surat** (Surat Cuti dan Surat Tugas) dalam satu tempat.

*   **Fungsi Utama:** Mencatat detail surat yang dibuat.
*   **Desain Struktur:** Menggunakan pendekatan *Single Table Inheritance* (STI) sederhana.
    *   **Kolom Umum:** `nomor_surat`, `jenis_surat` (enum: cuti/tugas), `tanggal_surat`, `perihal`, `tembusan`.
    *   **Kolom Cuti:** `jenis_cuti` (enum: tahunan, sakit, melahirkan, alasan_penting, besar), `tanggal_mulai_cuti`, dsb (hanya diisi jika `jenis_surat` = 'cuti').
    *   **Kolom Tugas:** `dasar_hukum`, `tujuan_tugas`, `lokasi_tugas`, dsb (hanya diisi jika `jenis_surat` = 'tugas').
*   **Aktor:**
    *   `pegawai_id`: **Pegawai Utama** yang dituju surat (Pemohon Cuti atau Petugas yang ditugaskan).
    *   `penandatangan_id`: Merujuk ke `pegawais` (Pejabat yang ttd).
    *   `created_by_user_id`: Merujuk ke `users` (Operator/Admin yang menginput data).

---

## Ringkasan Relasi

## Ringkasan Relasi

1.  **Surat -> User (Created By):** "Surat ini diketik/dibuat oleh Admin D".

---

## Detail Kolom Baru

### Surats
*   **tembusan**: Text (Nullable). Menyimpan daftar tembusan surat (jika ada).
*   **jenis_cuti**: Enum ditambahkan opsi `alasan_penting` dan `besar`.
