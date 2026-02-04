<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Tugas - {{ $nomor_surat }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 21cm;
            margin: 0 auto;
            padding: 2.5cm 2.5cm 3cm 2.5cm;
            box-sizing: border-box;
            background: white;
        }

        /* Kop Surat (Hardcoded for now, similar to Cuti Melahirkan) */
        .kop-surat {
            text-align: center;
            border-bottom: 3px double black;
            padding-bottom: 15px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .kop-surat img {
            width: 90px;
            height: auto;
        }

        .kop-text h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .kop-text h3 {
            font-size: 12pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .kop-text p {
            font-size: 10pt;
            margin: 0;
        }

        /* Judul Dokumen */
        .header-doc {
            text-align: center;
            margin-bottom: 15px;
        }
        .header-doc h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .header-doc p {
            margin: 5px 0 0 0;
            font-size: 12pt;
        }

        /* Grid Data Pegawai (Bentuk Form) */
        .data-grid {
            margin-top: 10px;
            margin-bottom: 20px;
        }
        .data-grid table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-grid td {
            padding: 2px 5px;
            vertical-align: top;
        }
        .label {
            width: 180px; /* Lebar label tetap */
        }
        .separator {
            width: 20px;
            text-align: center;
        }
        .text-bold {
            font-weight: bold;
        }

        /* List for assigned employees */
        .pegawai-list {
            margin-left: 20px;
            list-style-type: decimal;
        }
        .pegawai-list li {
            margin-bottom: 5px;
        }


        /* Footer & Tanda Tangan */
        .footer-doc {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end; /* Tanda tangan di kanan */
        }
        .ttd-box {
            text-align: center;
            width: 40%;
        }
        .ttd-box p {
            margin: 0;
        }

        /* Print Setting */
        @media print {
            body { background: white; }
            .container { width: 100%; margin: 0; padding: 2cm; box-shadow: none; }
            @page { margin: 0; size: A4 portrait; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- 1. Kop Surat -->
        <header class="kop-surat">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Lambang_Kementerian_Agama_Republik_Indonesia_baru.png/492px-Lambang_Kementerian_Agama_Republik_Indonesia_baru.png" alt="Logo">
            <div class="kop-text">
                <h2>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h2>
                <h3>KANTOR KABUPATEN BENER MERIAH</h3>
                <p>Jalan Utama No. 123, Kabupaten Bener Meriah</p>
            </div>
        </header>

        <!-- 2. Judul & Nomor -->
        <div class="header-doc">
            <h2>SURAT PERINTAH TUGAS</h2>
            <p>Nomor: {{ $nomor_surat }}</p>
        </div>

        <!-- 3. Isi Utama -->
        <main>
            <p>Yang bertanda tangan di bawah ini:</p>

            <div class="data-grid" style="margin-left: 30px;">
                <table>
                    <tr>
                        <td class="label">Nama</td>
                        <td class="separator">:</td>
                        <td><span class="text-bold">{{ $nama_penandatangan }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">NIP</td>
                        <td class="separator">:</td>
                        <td>{{ $nip_penandatangan }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jabatan</td>
                        <td class="separator">:</td>
                        <td>{{ $jabatan_penandatangan }}</td>
                    </tr>
                </table>
            </div>

            <p style="margin-top: 20px;">Memerintahkan kepada:</p>

            <div class="data-grid" style="margin-left: 30px;">
                @if($pegawais_ditugaskan->isNotEmpty())
                <ol class="pegawai-list">
                    @foreach($pegawais_ditugaskan as $pegawai)
                        <li>
                            <table>
                                <tr>
                                    <td class="label">Nama</td>
                                    <td class="separator">:</td>
                                    <td><span class="text-bold">{{ $pegawai->nama_lengkap }}</span></td>
                                </tr>
                                <tr>
                                    <td class="label">NIP</td>
                                    <td class="separator">:</td>
                                    <td>{{ $pegawai->nip }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Pangkat/Gol. Ruang</td>
                                    <td class="separator">:</td>
                                    <td>{{ $pegawai->pangkat_golongan }}</td>
                                </tr>
                                <tr>
                                    <td class="label">Jabatan</td>
                                    <td class="separator">:</td>
                                    <td>{{ $pegawai->jabatan }}</td>
                                </tr>
                            </table>
                        </li>
                    @endforeach
                </ol>
                @else
                    <p>Tidak ada pegawai yang ditugaskan.</p>
                @endif
            </div>

            <p style="margin-top: 20px;">Untuk melaksanakan tugas:</p>
            <p style="margin-left: 30px;">"{{ $tujuan_tugas }}"</p>

            <p style="margin-top: 20px;">dengan ketentuan:</p>
            <div class="data-grid" style="margin-left: 30px;">
                <table>
                    <tr>
                        <td class="label">Dasar Hukum</td>
                        <td class="separator">:</td>
                        <td>{{ $dasar_hukum }}</td>
                    </tr>
                    <tr>
                        <td class="label">Lokasi Tugas</td>
                        <td class="separator">:</td>
                        <td>{{ $lokasi_tugas }}</td>
                    </tr>
                    <tr>
                        <td class="label">Waktu Pelaksanaan</td>
                        <td class="separator">:</td>
                        <td>{{ $tanggal_mulai_tugas }} s.d. {{ $tanggal_selesai_tugas }}</td>
                    </tr>
                </table>
            </div>

            <p style="margin-top: 20px;">Demikian surat perintah tugas ini dibuat untuk dilaksanakan dengan sebaik-baiknya.</p>

            <!-- 4. Tanda Tangan -->
            <div class="footer-doc">
                <div class="ttd-box">
                    <p>Bener Meriah, {{ $tanggal_surat }}</p>
                    <p style="margin-bottom: 5px;">Pejabat Pemberi Tugas,</p>
                    <br><br><br>
                    <p style="text-decoration: underline; font-weight: bold;">{{ $nama_penandatangan }}</p>
                    <p>NIP. {{ $nip_penandatangan }}</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
