<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Izin Cuti Tahunan</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
        }

        .container {
            width: 21.59cm;
            height: 27.94cm;
            margin: 0 auto;
            background: white;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h3 {
            margin: 5px 0;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-surat {
            text-align: right;
            font-size: 9pt;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .kop-surat.right {
            text-align: left;
            margin-left: auto;
            width: fit-content;
        }

        .content {
            margin-top: 40px;
        }

        .nomor-surat {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
        }

        .body-text {
            text-align: justify;
            margin: 10px 0;
        }

        .data-pegawai {
            margin-left: 30px;
            margin-bottom: 10px;
        }

        .data-row {
            display: flex;
            margin-bottom: 5px;
        }

        .data-label {
            width: 200px;
            display: inline-block;
        }

        .data-separator {
            width: 20px;
            display: inline-block;
            text-align: center;
        }

        .data-value {
            flex: 1;
        }

        .ketentuan {
            margin: 10px 0;
            text-align: justify;
        }

        .ketentuan-intro {
            margin-bottom: 10px;
        }

        .ketentuan-list {
            margin-left: 10px;
        }

        .ketentuan-item {
            margin-bottom: 10px;
            display: flex;
        }

        .ketentuan-item .bullet {
            width: 30px;
            flex-shrink: 0;
        }

        .ketentuan-item .text {
            flex: 1;
            text-align: justify;
        }

        .penutup {
            text-align: justify;
            margin: 10px 0;
        }

        .ttd {
            margin-top: 20px;
            text-align: right;
        }

        .ttd-content {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }

        .ttd-jabatan {
            margin-bottom: 80px;
        }

        .ttd-nama {
            font-weight: bold;
            border-bottom: 1px solid #000;
        }

        .ttd-nip {
            margin-top: 0px;
        }

        @media print {
            body {
                padding: 0;
            }
            .container {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="kop-surat right">
            <strong>PERATURAN BADAN KEPEGAWAIAN NEGARA</strong><br>
            <strong>REPUBLIK INDONESIA</strong><br>
            <strong>NOMOR 7 TAHUN 2022</strong><br>
            <strong>TENTANG</strong><br>
            <strong>TATA CARA PEMBERIAN CUTI PEGAWAI PEMERINTAH</strong><br>
            <strong>DENGAN PERJANJIAN KERJA</strong>
        </div>

        <div class="kop-surat right">
            Redelong, {{ $tanggal_surat ?? '()' }}
        </div>

        <div class="content">
            <div class="nomor-surat" style="margin-top: 40px;">
                <strong>IZIN PELAKSANAAN CUTI TAHUNAN</strong><br>
                <strong>Nomor : {{ $nomor_surat ?? '-' }}</strong>
            </div>

            <div class="body-text">
                1. Diberikan izin untuk melaksanakan cuti tahunan kepada Pegawai Negeri Sipil :
            </div>

            <div class="data-pegawai">
                <div class="data-row">
                    <span class="data-label">Nama</span>
                    <span class="data-separator">:</span>
                    <span class="data-value">{{ $nama ?? '' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">NIP</span>
                    <span class="data-separator">:</span>
                    <span class="data-value">{{ $nip ?? '' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Pangkat/Golongan Ruang</span>
                    <span class="data-separator">:</span>
                    <span class="data-value">{{ $pangkat ?? '' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Jabatan</span>
                    <span class="data-separator">:</span>
                    <span class="data-value">{{ $jabatan ?? '' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Unit Kerja</span>
                    <span class="data-separator">:</span>
                    <span class="data-value">{{ $unit_kerja ?? '' }}</span>
                </div>
            </div>

            <div class="ketentuan">
                <div class="ketentuan-intro">
                    Selama {{ $lama_cuti ?? '()' }}, terhitung mulai tanggal {{ $tanggal_mulai ?? '()' }} sampai dengan tanggal {{ $tanggal_selesai ?? '()' }}, dengan ketentuan sebagai berikut :
                </div>

                <div class="ketentuan-list">
                    <div class="ketentuan-item">
                        <span class="bullet">a.</span>
                        <span class="text">Selama menjalankan cuti tahunan, bersedia menjalankan tugas yang sewaktu waktu diberikan oleh atasan.</span>
                    </div>
                    <div class="ketentuan-item">
                        <span class="bullet">b.</span>
                        <span class="text">Sebelum menjalankan cuti, wajib menyerahkan pekerjaannya kepada pejabat lain yang telah ditunjuk.</span>
                    </div>
                    <div class="ketentuan-item">
                        <span class="bullet">c.</span>
                        <span class="text">Setelah selesai menjalankan cuti, wajib melaporkan diri kepada atasan langsungnya dan bekerja kembali sebagaimana biasa.</span>
                    </div>
                </div>
            </div>

            <div class="penutup">
                2. Demikian izin pelaksanaan cuti tahunan ini dibuat, untuk dapat digunakan sebagaimana mestinya.
            </div>

            <div class="ttd">
                <div class="ttd-content">
                    <div class="ttd-jabatan">{{ $jabatan_kepala ?? 'Kepala' }}</div>
                    <div class="ttd-nama">{{ $nama_kepala ?? '()' }}</div>
                    <div class="ttd-nip">NIP. {{ $nip_kepala ?? '()' }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
