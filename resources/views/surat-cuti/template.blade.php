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
            font-family: 'Times New Roman', Times, serif;
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

        .header-peraturan {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .kop-surat {
            text-align: right;
            font-size: 9pt;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .kop-surat.right {
            text-align: right;
            margin-left: auto;
            max-width: 50%;
        }

        .content {
            margin-top: 20px;
        }

        .nomor-surat {
            text-align: center;
            margin: 15px 0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .body-text {
            text-align: justify;
            margin: 5px 0;
        }

        .data-pegawai {
            margin-left: 0;
            margin-bottom: 15px;
        }

        .data-row {
            display: flex;
            margin-bottom: 3px;
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
            margin: 15px 0;
            text-align: justify;
        }

        .ketentuan-intro {
            margin-bottom: 5px;
        }

        .ketentuan-list {
            margin-left: 20px;
            text-align: justify;
        }

        .ketentuan-item {
            margin-bottom: 3px;
            display: flex;
        }

        .ketentuan-item .bullet {
            min-width: 20px;
            margin-right: 5px;
        }

        .ttd {
            margin-top: 30px;
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
                    $statusPegawai = $surat->status_pegawai ?? 'PNS';
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
            Bener Meriah, {{ $surat->tanggal_surat->translatedFormat('d F Y') ?? '...' }}
        </div>

        <div style="text-align: center; font-weight: bold; margin-bottom: 5px;">
            <u>SURAT IZIN CUTI {{ strtoupper(str_replace('_', ' ', $surat->jenis_cuti)) }}</u>
        </div>
        <div style="text-align: center; margin-bottom: 20px;"> <!-- Reduced from 30px -->
            NOMOR : {{ $surat->nomor_surat ?? '...' }}
        </div>

        <div style="text-align: justify; margin-bottom: 10px;"> <!-- Reduced from 20px -->
            1. Diberikan cuti {{ str_replace('_', ' ', $surat->jenis_cuti) }} kepada Pegawai {{ $statusPegawai === 'PNS' ? 'Negeri Sipil' : 'Pemerintah dengan Perjanjian Kerja' }} :
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;"> <!-- Reduced from 20px -->
            <tr>
                <td style="width: 200px;">Nama</td>
                <td style="width: 20px;">:</td>
                <td>{{ $surat->nama_lengkap_pegawai ?? '' }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $surat->nip_pegawai ?? '' }}</td>
            </tr>
            <tr>
                <td>Pangkat / Golongan Ruang</td>
                <td>:</td>
                <td>{{ $surat->pangkat_golongan_pegawai ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $surat->jabatan_pegawai ?? '' }}</td>
            </tr>
            <tr>
                <td>Unit Kerja</td>
                <td>:</td>
                <td>{{ $surat->bidang_seksi_pegawai ?? '' }}</td>
            </tr>
        </table>

        <div style="text-align: justify; margin-bottom: 10px;"> <!-- Reduced from 20px -->
            Selama {{ $lama_cuti ?? '...' }}, terhitung mulai tanggal {{ $surat->tanggal_mulai_cuti->translatedFormat('d F Y') ?? '...' }} sampai dengan tanggal {{ $surat->tanggal_selesai_cuti->translatedFormat('d F Y') ?? '...' }}, dengan ketentuan sebagai berikut :
        </div>

        <div class="ketentuan-list">
            @if($surat->jenis_cuti === 'sakit')
                {{-- Opsi 1: Cuti Sakit --}}
                <div class="ketentuan-item">
                    <div class="bullet">a.</div>
                    <div style="text-align: justify;">Sebelum menjalankan cuti sakit, wajib menyerahkan pekerjaannya kepada pejabat lain yang telah ditunjuk.</div>
                </div>
                <div class="ketentuan-item">
                    <div class="bullet">b.</div>
                    <div style="text-align: justify;">Setelah selesai menjalankan cuti sakit, wajib melaporkan diri kepada atasan langsungnya dan bekerja kembali sebagaimana biasa.</div>
                </div>

            @elseif($surat->jenis_cuti === 'melahirkan')
                {{-- Opsi 2: Cuti Melahirkan --}}
                <div class="ketentuan-item">
                    <div class="bullet">a.</div>
                    <div style="text-align: justify;">Selama menjalankan cuti melahirkan, wajib memberitahukan tugas yang belum terselesaikan kepada atasan.</div>
                </div>
                <div class="ketentuan-item">
                    <div class="bullet">b.</div>
                    <div style="text-align: justify;">Sebelum menjalankan cuti melahirkan, wajib menyerahkan pekerjaannya kepada pejabat lain yang telah ditunjuk.</div>
                </div>
                <div class="ketentuan-item">
                    <div class="bullet">c.</div>
                    <div style="text-align: justify;">Setelah selesai menjalankan cuti melahirkan, wajib melaporkan diri kepada atasan langsungnya dan bekerja kembali sebagaimana biasa.</div>
                </div>

            @else
                {{-- Opsi 3: Cuti Tahunan, Alasan Penting, Besar / Default --}}
                <div class="ketentuan-item">
                    <div class="bullet">a.</div>
                    <div style="text-align: justify;">Selama menjalankan cuti {{ str_replace('_', ' ', $surat->jenis_cuti) }}, bersedia menjalankan tugas yang sewaktu-waktu diberikan oleh atasan.</div>
                </div>
                <div class="ketentuan-item">
                    <div class="bullet">b.</div>
                    <div style="text-align: justify;">Sebelum menjalankan cuti {{ str_replace('_', ' ', $surat->jenis_cuti) }}, wajib menyerahkan pekerjaannya kepada pejabat lain yang telah ditunjuk.</div>
                </div>
                <div class="ketentuan-item">
                    <div class="bullet">c.</div>
                    <div style="text-align: justify;">Setelah selesai menjalankan cuti {{ str_replace('_', ' ', $surat->jenis_cuti) }}, wajib melaporkan diri kepada atasan langsungnya dan bekerja kembali sebagaimana biasa.</div>
                </div>
            @endif
        </div>

        <div style="text-align: justify; margin-top: 10px; margin-bottom: 30px;"> <!-- Reduced from 50px -->
            2. Demikian surat izin cuti {{ str_replace('_', ' ', $surat->jenis_cuti) }} ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
        </div>

        <div class="ttd">
            <div class="ttd-content">
                <div style="text-align: left; margin-bottom: 60px;">{{ $surat->jabatan_kepala_pegawai ?? 'Kepala' }},</div> <!-- Reduced from 80px -->
                <div style="text-align: left; font-weight: bold; text-decoration: underline;">{{ $surat->nama_lengkap_kepala_pegawai ?? '...' }}</div>
                <div style="text-align: left;">NIP. {{ $surat->nip_kepala_pegawai ?? '...' }}</div>
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
            window.focus();
            window.print();
        }
    </script>
    @endif
</body>
</html>
