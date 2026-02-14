<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas - {{ $nomor_surat }}</title>
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
            background-color: #FAFAFA; /* Light gray background for screen view */
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

        /* Kop Surat Tugas Style */
        .kop-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
        }

        .kop-logo {
            width: 80px;
            height: auto;
            margin-right: 20px;
        }

        .kop-text {
            text-align: center;
        }

        .kop-text h2, .kop-text h3, .kop-text p {
            margin: 0;
            line-height: 1.2;
        }

        .kop-text h2 {
            font-size: 14pt;
            font-weight: bold;
        }

        .kop-text h3 {
            font-size: 12pt;
            font-weight: bold;
        }

        .kop-text p {
            font-size: 10pt;
            font-style: italic;
        }

        .judul-surat {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
            text-decoration: underline;
        }

        .nomor-surat {
            text-align: center;
            margin-top: -15px;
            margin-bottom: 20px;
        }

        .content {
            margin-top: 20px;
        }

        .data-row {
            display: flex;
            margin-bottom: 5px;
        }

        .data-label {
            width: 150px;
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

        .pegawai-list {
            margin-left: 20px;
        }

        .pegawai-item {
            margin-bottom: 15px;
        }

        .ttd {
            margin-top: 30px;
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
            text-decoration: underline;
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
        <!-- Kop Surat -->
        <div class="kop-header">
            <img src="{{ asset('images/logo-kemenag-grayscale.webp') }}" alt="Logo" class="kop-logo">
            <div class="kop-text">
                <h2>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h2>
                <h3>KANTOR KEMENTERIAN AGAMA KABUPATEN BENER MERIAH</h3>
                <p>Jln.Bandara Rembele – Pante Raya Redelong 24581</p>
                <p>Telepon (0643) 8001010 E-Mail <a href="mailto:kankemenag.bener.meriah@gmail.com" style="color: blue; text-decoration: underline;">kankemenag.bener.meriah@gmail.com</a></p>
            </div>
        </div>

        <div class="content">
            <div class="judul-surat">SURAT TUGAS</div>
            <div class="nomor-surat">Nomor : {{ $nomor_surat }}</div>

            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <tr>
                    <td style="width: 100px; vertical-align: top;">Menimbang</td>
                    <td style="width: 20px; vertical-align: top;">:</td>
                    <td style="vertical-align: top;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 20px; vertical-align: top;">1.</td>
                                <td style="text-align: justify;">Bahwa dalam rangka {{ $tujuan_tugas ?? '...' }}</td>
                            </tr>
                            <tr>
                                <td style="width: 20px; vertical-align: top;">2.</td>
                                <td style="text-align: justify;">Bahwa nama yang tersebut dibawah ini dipandang relevan untuk mengikuti kegiatan dimaksud.</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="height: 10px;"></td>
                </tr>
                <tr>
                    <td style="width: 100px; vertical-align: top;">Dasar</td>
                    <td style="width: 20px; vertical-align: top;">:</td>
                    <td style="vertical-align: top;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 20px; vertical-align: top;">1.</td>
                                <td style="text-align: justify;">Peraturan Menteri Agama No. 6 Tahun 2022 Tentang Perubahan Atas Peraturan Menteri Agama Nomor 19 Tahun 2019 tentang Organisasi dan Tata Kerja Instansi Vertikal Kementerian Agama;</td>
                            </tr>
                            <tr>
                                <td style="width: 20px; vertical-align: top;">2.</td>
                                <td style="text-align: justify;">Surat Keputusan Kepala Kantor Kementerian Agama Kabupaten Bener Meriah, {{ $dasar_hukum }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div style="text-align: center; margin: 20px 0; font-weight: bold;">MEMBERI TUGAS</div>

            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 100px; vertical-align: top;">Kepada</td>
                    <td style="vertical-align: top;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="width: 100px;">Nama</td>
                                <td style="width: 20px;">:</td>
                                <td style="font-weight: bold;">{{ $pegawai->nama_lengkap }}</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>:</td>
                                <td>{{ $pegawai->nip }}</td>
                            </tr>
                            <tr>
                                <td>Jabatan</td>
                                <td>:</td>
                                <td>{{ $pegawai->jabatan }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="height: 10px;"></td>
                </tr>
                <tr>
                    <td style="width: 100px; vertical-align: top;">Untuk</td>
                    <td style="vertical-align: top; display: flex;">
                        <div style="margin-right: 5px;">:</div>
                        <div style="text-align: justify;">{{ $tujuan_tugas }} yang akan dilaksanakan pada tanggal {{ $tanggal_mulai_tugas }} s.d {{ $tanggal_selesai_tugas }} bertempat di {{ $lokasi_tugas }}.</div>
                    </td>
                </tr>
            </table>

            <div style="margin-top: 20px; text-align: justify;">
                Demikian untuk dilaksanakan sebagaimana mestinya.
            </div>

            <div class="ttd">
                <div class="ttd-content">
                    <div style="margin-bottom: 5px; text-align: left;">Redelong, {{ $tanggal_surat }}</div>
                    <div class="ttd-jabatan" style="text-align: left;">{{ $jabatan_penandatangan ?? 'Kepala' }}</div>
                    <div class="ttd-nama" style="text-align: left; margin-top: 60px;">{{ $nama_penandatangan }}</div>
                    <div class="ttd-nip" style="text-align: left;">NIP. {{ $nip_penandatangan }}</div>
                </div>
            </div>
        </div>
    </div>
    @if($trigger_print ?? false)
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
    @endif
</body>
</html>
