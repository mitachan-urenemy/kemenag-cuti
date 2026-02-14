<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Izin {{ ucwords(str_replace('_', ' ', $surat->jenis_cuti)) }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
        }

        .container {
            width: 210mm;
            min-height: 297mm;
            padding: 2cm;
            margin: 1cm auto;
            background: white;
            box-sizing: border-box;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        @media print {
            body {
                margin: 0;
                background-color: white;
            }
            .container {
                width: 100%;
                min-height: auto;
                margin: 0;
                padding: 2cm;
                box-shadow: none;
                page-break-after: always;
            }
        }

        /* Styling specifically to match the provided screenshots */
        .header-peraturan {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px; /* Reduced from 20px */
        }

        .kop-surat {
            text-align: right;
            font-size: 9pt;
            margin-bottom: 10px; /* Reduced from 30px */
            line-height: 1.2;
        }

        .kop-surat.right {
            text-align: right;
            margin-left: auto;
            max-width: 50%;
        }

        .content {
            margin-top: 20px; /* Reduced from 40px */
        }

        .nomor-surat {
            text-align: center;
            margin: 15px 0; /* Reduced from 20px */
            font-weight: bold;
            text-transform: uppercase;
        }

        .body-text {
            text-align: justify;
            margin: 5px 0; /* Reduced from 10px */
        }

        .data-pegawai {
            margin-left: 0;
            margin-bottom: 15px; /* Reduced from 20px */
        }

        .data-row {
            display: flex;
            margin-bottom: 3px; /* Reduced from 5px */
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
            margin: 15px 0; /* Reduced from 20px */
            text-align: justify;
        }

        .ketentuan-intro {
            margin-bottom: 5px; /* Reduced from 10px */
        }

        .ketentuan-list {
            margin-left: 20px;
        }

        .ketentuan-item {
            margin-bottom: 3px; /* Reduced from 5px */
            display: flex;
        }

        .ketentuan-item .bullet {
            min-width: 20px;
            margin-right: 5px;
        }

        .ttd {
            margin-top: 30px; /* Reduced from 50px */
            text-align: right;
        }

        .ttd-content {
            display: inline-block;
            text-align: center;
            min-width: 250px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;"> <!-- Reduced from 40px -->
            <div style="text-align: left; font-size: 10pt; width: 300px;">
                @php
                    $statusPegawai = $surat->pegawai->status_pegawai ?? 'PNS';
                @endphp

                @if($statusPegawai === 'PNS')
                    <strong>PERATURAN BADAN KEPEGAWAIAN NEGARA</strong><br>
                    <strong>REPUBLIK INDONESIA</strong><br>
                    <strong>NOMOR 7 TAHUN 2021</strong><br>
                    <strong>TENTANG</strong><br>
                    <strong>TATA CARA PEMBERIAN CUTI PEGAWAI NEGERI SIPIL</strong>
                @else
                    <strong>PERATURAN BADAN KEPEGAWAIAN NEGARA</strong><br>
                    <strong>REPUBLIK INDONESIA</strong><br>
                    <strong>NOMOR 7 TAHUN 2022</strong><br>
                    <strong>TENTANG</strong><br>
                    <strong>TATA CARA PEMBERIAN CUTI PEGAWAI PEMERINTAH DENGAN PERJANJIAN KERJA</strong>
                @endif
            </div>
        </div>

        <div style="text-align: right; margin-bottom: 15px;"> <!-- Reduced from 20px -->
            Bener Meriah, {{ $tanggal_surat ?? '...' }}
        </div>

        <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">
            <u>SURAT IZIN CUTI {{ strtoupper(str_replace('_', ' ', $surat->jenis_cuti)) }}</u>
        </div>
        <div style="text-align: center; margin-bottom: 20px;"> <!-- Reduced from 30px -->
            NOMOR : {{ $nomor_surat ?? '...' }}
        </div>

        <div style="text-align: justify; margin-bottom: 10px;"> <!-- Reduced from 20px -->
            1. Diberikan cuti {{ str_replace('_', ' ', $surat->jenis_cuti) }} kepada Pegawai {{ $statusPegawai === 'PNS' ? 'Negeri Sipil' : 'Pemerintah dengan Perjanjian Kerja' }} :
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;"> <!-- Reduced from 20px -->
            <tr>
                <td style="width: 200px;">Nama</td>
                <td style="width: 20px;">:</td>
                <td>{{ $nama ?? '' }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $nip ?? '' }}</td>
            </tr>
            <tr>
                <td>Pangkat / Golongan Ruang</td>
                <td>:</td>
                <td>{{ $pangkat ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $jabatan ?? '' }}</td>
            </tr>
            <tr>
                <td>Unit Kerja</td>
                <td>:</td>
                <td>{{ $unit_kerja ?? '' }}</td>
            </tr>
        </table>

        <div style="text-align: justify; margin-bottom: 10px;"> <!-- Reduced from 20px -->
            Selama {{ $lama_cuti ?? '...' }}, terhitung mulai tanggal {{ $tanggal_mulai ?? '...' }} sampai dengan tanggal {{ $tanggal_selesai ?? '...' }}, dengan ketentuan sebagai berikut :
        </div>

        <div class="ketentuan-list">
            <div class="ketentuan-item">
                <div class="bullet">a.</div>
                <div>Sebelum menjalankan cuti {{ str_replace('_', ' ', $surat->jenis_cuti) }}, wajib menyerahkan pekerjaannya kepada atasan langsungnya atau pejabat lain yang ditunjuk.</div>
            </div>
            <div class="ketentuan-item">
                <div class="bullet">b.</div>
                <div>Setelah selesai menjalankan cuti {{ str_replace('_', ' ', $surat->jenis_cuti) }}, wajib melaporkan diri kepada atasan langsungnya dan bekerja kembali sebagaimana biasa.</div>
            </div>
        </div>

        <div style="text-align: justify; margin-top: 10px; margin-bottom: 30px;"> <!-- Reduced from 50px -->
            2. Demikian surat izin cuti {{ str_replace('_', ' ', $surat->jenis_cuti) }} ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </div>

        <div class="ttd">
            <div class="ttd-content">
                <div style="text-align: left; margin-bottom: 60px;">{{ $jabatan_kepala ?? 'Kepala' }},</div> <!-- Reduced from 80px -->
                <div style="text-align: left; font-weight: bold; text-decoration: underline;">{{ $nama_kepala ?? '...' }}</div>
                <div style="text-align: left;">NIP. {{ $nip_kepala ?? '...' }}</div>
            </div>
        </div>
        @if(!empty($surat->tembusan))
        <div style="margin-top: 20px;">
            <div style="font-weight: bold; text-decoration: underline;">Tembusan:</div>
            <div style="white-space: pre-line;">{{ $surat->tembusan }}</div>
        </div>
        @endif
    </div>

    @if(request()->has('print'))
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
    @endif
</body>
</html>
