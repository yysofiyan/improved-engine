@php
ini_set('memory_limit', '256M');
@endphp

<html>

<head>
    <style>
        @page {
            size: Legal;
            margin: 2.5cm 1.5cm;
        }

        body {
            font-family: Tahoma, Verdana, Segoe, sans-serif;
            line-height: 1.6;
            font-size: 10pt;
            position: relative;
        }

        .header-surat {
            width: 100%;
            margin: 0 auto 20px;
            text-align: center;
        }

        .header-surat img {
            max-width: 700px;
            height: auto;
        }

        .footer-surat {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            text-align: center;
        }

        .footer-surat img {
            width: 700px;
            height: auto;
        }

        .konten-utama {
            min-height: 70vh;
        }

        .text-center {
            text-align: center;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

{{-- <body style="line-height:1.5;">
    <div style="position: absolute; top: 0; left: 0; background: yellow; padding: 5px; font-size: 8pt">
        DEBUG: 
        Prodi: {{ $formulir->prodi->nama_prodi }} | 
        Jenjang: {{ $formulir->prodi->nama_jenjang }} | 
        Jenis Daftar: {{ $formulir->jenis_daftar }}
    </div>
    <div style="position: absolute; top: 0; left: 0; color: red; font-size: 8pt">
        DOKUMEN RESMI PASCASARJANA - {{ $formulir->prodi->nama_prodi }}
    </div> --}}
    <div class="header-surat">
        <img src="{{ public_path('images/header_pmb2025.png') }}" style="width:700px;height:150px;margin-top:-60px;">
        <p style="font-size:20px;text-align:center;font-weight:bold;margin-top:20px;line-height:1;">PENDAFTARAN MAHASISWA BARU PASCASARJANA</p>
        <p style="font-size:16px;text-align:center;font-weight:bold;margin-top:10px;line-height:1;">======================================================================</p>
    </div>

    {{-- <!-- Header khusus Pascasarjana -->
    <div style="text-align:center; margin:15px 0">
        <h3>Tahun Akademik {{ \App\Helpers\AkademikHelpers::getTahunAktif() }}/2026</h3>
        <p>Program Pascasarjana Universitas Sebelas April</p>
    </div> --}}

    <!-- Pernyataan Panitia -->
    <p style="text-align:center; font-weight:bold; margin-bottom:20px">
        Panitia Penerimaan Mahasiswa Baru (PMB) Universitas Sebelas April dan STAI Sebelas April Sumedang<br>
        Tahun Akademik 2025 / 2026 menyatakan bahwa: </p>
    
    <!-- Data Pribadi Pascasarjana -->
    <table style="font-size:12px">
        <tbody>
            <tr>
                <td><strong>Nama</strong></td>
                <td style="padding-left: 20px">:</td>
                <td>{{ strtoupper($formulir->nama_mahasiswa) }}</td>
            </tr>
            <tr>
                <td><strong>NIK</strong></td>
                <td style="padding-left: 20px">:</td>
                <td>{{ $formulir->nik }}</td>
            </tr>
                <td><strong>PIN Aktif</strong></td>
                <td style="padding-left: 20px">:</td>
                <td><strong>{{ $formulir->pin }}</strong></td>
            </tr>
            <tr>
                <td><strong>Nomor Pendaftaran</strong></td>
                <td style="padding-left: 20px">:</td>
                <td>{{ $formulir->nomor_pendaftaran ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Asal Perguruan Tinggi</strong></td>
                <td style="padding-left: 20px">:</td>
                <td>
                    @if($formulir->jenis_daftar == 1)
                        {{ $formulir->kode_pt_asal }}
                        <em style="font-size: 10px">(Reguler)</em>
                    @elseif($formulir->jenis_daftar == 2)
                        {{ $formulir->kode_pt_asal }}
                        <em style="font-size: 10px">(Pindahan)</em>
                    @elseif($formulir->jenis_daftar == 6)
                        {{ $formulir->kode_pt_asal }}
                        <em style="font-size: 10px">(Lanjutan)</em>
                    @else
                        {{ $formulir->kode_pt_asal ?? 'Belum diisi' }}
                        @if(preg_match('/universitas|institut|politeknik/i', $formulir->kode_pt_asal))
                            <em style="font-size: 10px">(Lulusan PT)</em>
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Kabupaten/Kota</strong></td>
                <td style="padding-left: 20px">:</td>
                <td>{{ \App\Helpers\AkademikHelpers::getSekolahKab($formulir->asal_sekolah) }}</td>
            </tr>
            <tr>
                <td><strong>Instansi</strong></td>
                <td style="padding-left: 20px">:</td>
                <td>{{ $formulir->instansi }}</td>
            </tr>
        </tbody>
    </table>

    <p style="text-align:center; margin:20px 0; font-size:14px; font-weight:bold">
        Dinyatakan MEMENUHI SYARAT sebagai Calon Mahasiswa Baru Program Magister pada:
    </p>

    <!-- Program Studi Pascasarjana -->
    <table style="font-size:12px">
        <tbody>
            <tr>
                <td><strong>Program Studi</strong></td>
                <td style="padding-left: 60px">:</td>
                <td>{{ $prodi->nama_prodi ?? 'Program Studi Tidak Diketahui' }} - {{ $prodi->nama_jenjang ?? '' }}</td>
            </tr>
            <tr>
                <td><strong>Fakultas</strong></td>
                <td style="padding-left: 60px">:</td>
                <td>{{ \App\Helpers\AkademikHelpers::getFakultasNama($prodi->kode_fakultas ?? 'DEFAULT') }}</td>
            </tr>
        </tbody>
    </table>

   

    <!-- Informasi PMB Pascasarjana -->
    <div style="text-align: justify; width: 100%; max-width: 700px; font-size: 12px; line-height: 1.8">
        <p><strong>INFORMASI PMB 2025 / 2026:</strong></p>
        <ol>
            <li>
                Paling lambat 3 hari setelah hari ini, Saudara wajib menghubungi staff prodi pascasarjana di fakultas masing-masing:
                <ul style="list-style-type: none; padding-center: 20px">
                    <li>- Magister Administrasi Publik (FISIP): Sdri. Suci Rahmania A, S.Sos (082115200908)</li>
                    <li>- Magister Manajemen (FEB): Sdr. Yogi Sudrajat, S.Pd, MM (085315654194)</li>
                    <li>- Magister Pendidikan Matematika (FKIP): Sdri. Sri Lestari, S.Sos (081322781178)</li>
                    <li>- Magister Bahasa Indonesia (FKIP): Sdri. Sri Lestari, S.Sos (081322781178)</li>
                </ul>
            </li>
            <li>Informasi ketentuan pelaksanaan kegiatan <strong>Test Pascasarjana (Tulis & Wawancara)</strong> dan <strong>Prapasca</strong> akan diinformasikan oleh petugas masing-masing Prodi.</li>
            <li>Seluruh pembayaran keuangan dilakukan pada <strong>Bank Jabar dan Banten (BJB) Cabang Sumedang</strong> dengan nomor rekening <strong>011.001.0011941</strong> atas nama <strong>Yayasan Pendidikan Sebelas April Sumedang</strong>.</li>
            <li>Bukti pembayaran dari bank, diserahkan langsung ke <strong>Sekretariat YPSA</strong> (setiap hari kerja) paling lambat <strong>1 hari </strong>setelah pembayaran dari bank atau Saudara bisa upload pada laman <a href="https://online.ypsa-sumedang.or.id/">https://online.ypsa-sumedang.or.id/</a></li>
        </ol>
    </div>
    
    <!-- Tanggal Surat -->
    <div class="footer-surat" style="display: flex; flex-direction: column; align-items: center; margin-top: 50px; text-align: center; width: 100%; position: absolute; bottom: 2.5cm;">
        <p style="margin-bottom: 10px;">Sumedang, {{ date('d F Y') }}</p>
        <img src="{{ public_path('images/tanda_tangan2025.png') }}" style="max-width: 700px; height: auto; margin-top: 10px;">
    </div>

    <!-- Halaman Baru untuk Rincian Pembayaran -->
    <div style="page-break-before: always; text-align: center; line-height: 1.5"> 
        <!-- Daftar Kewajiban -->
        <!-- Tabel USP dan UK S2 -->
        <div style="text-align:center; margin-top:20px">
        <img src="{{ public_path('images/usp_uk_s2.png') }}" 
             style="max-width:100%; height:auto; width:700px;">
        <!-- Keterangan -->

</body>
</html>

