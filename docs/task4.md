Tambah tabel cuti_kuota
Dibutuhkan untuk fitur "pantau sisa cuti" oleh pegawai dan pengurangan kuota saat disetujui.
Table cuti_kuota {
id integer [primary key, increment]
pegawai_id integer [ref: > pegawais.id, delete: cascade]
tahun integer
jenis_cuti enum('tahunan', 'sakit', 'melahirkan', 'alasan_penting', 'besar')
kuota_hari integer [default: 12]
terpakai integer [default: 0]

indexes {
(pegawai_id, tahun, jenis_cuti) [unique]
}
}
Klarifikasi kolom atasan_id di tabel surats
Kolom ini ambigu - apakah referensi ke pimpinan yang approve, atau ke atasan langsung pegawai? Rekomendasi: rename jadi approved_by dan jadikan nullable FK ke pegawais.id. Diisi saat pimpinan menyetujui/menolak.
Tambah kolom ditolak_alasan di surats
Saat pimpinan menolak, butuh tempat menyimpan alasan. Kolom keterangan yang ada bisa dipakai untuk ini, tapi lebih eksplisit kalau dipisah atau diberi konvensi penggunaan yang jelas di kode.
Rekap tahunan - tidak perlu tabel baru
Cukup pakai query agregasi seperti ini. Tidak perlu tabel rekap terpisah.
SELECT
p.status_kepegawaian,
s.jenis_cuti,
COUNT(\*) AS jumlah_surat,
SUM(DATEDIFF(s.tanggal_selesai_cuti, s.tanggal_mulai_cuti) + 1) AS total_hari
FROM surats s
JOIN pegawais p ON s.pegawai_id = p.id
WHERE
s.jenis_surat = 'cuti'
AND s.status = 'disetujui'
AND YEAR(s.tanggal_surat) = :tahun
GROUP BY p.status_kepegawaian, s.jenis_cuti
ORDER BY p.status_kepegawaian, s.jenis_cuti;
Validasi transisi status surat
Tambahkan konstanta atau helper function di kode PHP untuk validasi transisi yang sah. Jangan handle ini hanya di frontend.
// Transisi status yang diizinkan
$allowed_transitions = [
'draft' => ['diajukan'], // pegawai submit
'diajukan' => ['diproses'], // admin proses
'diproses' => ['disetujui', 'ditolak'], // pimpinan acc/tolak
];

function can_transition($current, $next, $allowed) {
  return isset($allowed[$current])
&& in_array($next, $allowed[$current]);
}
