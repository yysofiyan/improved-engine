@php
    header('Content-Type: application/pdf');
@endphp

<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            size: legal;
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

        Gaya khusus S1 .header-s1 {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .footer-s1 {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
        }
    </style>
</head>

{{-- <body style="line-height:1.5;">
    <div style="position: absolute; top: 0; left: 0; color: blue; font-size: 8pt">
        DOKUMEN RESMI SARJANA - {{ $formulir->prodi->nama_prodi ?? 'PRODI TIDAK TERDAFTAR' }}
    </div> --}}

{{-- <div class="header-s1">
        <img src="{{ public_path('images/kop_s1.png') }}" style="width:100%;height:auto;">
    </div> --}}

<div class="header-surat">
    <img src="{{ public_path('images/header_pmb2025.png') }}" style="width:700px;height:150px;margin-top:-60px;">
    <p style="font-size:20px;text-align:center;font-weight:bold;margin-top:20px;line-height:1;">PENGUMUMAN KELULUSAN
        MAHASISWA BARU</p>
    <p style="font-size:16px;text-align:center;font-weight:bold;margin-top:10px;line-height:1;">
        ======================================================================</p>
</div>

<p style="text-align:center; font-weight:bold; margin-bottom:20px">
    Panitia Penerimaan Mahasiswa Baru (PMB) Universitas Sebelas April dan STAI Sebelas April Sumedang<br>
    {{-- Tahun Akademik {{ \App\Helpers\AkademikHelpers::getTahunAktif() }} / 2026 menyatakan bahwa: --}}
    Tahun Akademik 2025 / 2026 menyatakan bahwa:

</p>

<table style="font-size:12px">
    <tbody>
        <tr>
            <td><strong>Nama</strong></td>
            <td style="padding-left: 20px">:</td>
            <td>{{ strtoupper($formulir->nama_mahasiswa ?? '-') }}</td>
        </tr>
        <tr>
            <td><strong>NIK</strong></td>
            <td style="padding-left: 20px">:</td>
            <td>{{ $formulir->nik }}</td>
        </tr>
        <tr>
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
            <td><strong>Asal Sekolah/PT</strong></td>
            <td style="padding-left: 20px">:</td>
            <td>
                @if ($formulir->jenis_daftar == 1)
                    {{ $formulir->asal_sekolah }}
                    <em style="font-size: 10px">(Reguler)</em>
                @elseif($formulir->jenis_daftar == 2)
                    {{ $formulir->kode_pt_asal ?? 'Belum diisi' }}
                    <em style="font-size: 10px">(Pindahan)</em>
                @elseif($formulir->jenis_daftar == 6)
                    {{ $formulir->kode_pt_asal ?? 'Belum diisi' }}
                    <em style="font-size: 10px">(Lanjutan)</em>
                @else
                    {{ $formulir->kode_pt_asal ?? 'Belum diisi' }}
                    @if (preg_match('/universitas|institut|politeknik/i', $formulir->kode_pt_asal))
                        <em style="font-size: 10px">(Lulusan PT)</em>
                    @elseif(preg_match('/smk|sma|madrasah/i', $formulir->asal_sekolah))
                        <em style="font-size: 10px">(Lulusan Sekolah)</em>
                    @endif
                @endif
            </td>
        </tr>
        <tr>
            <td><strong>Kabupaten/Kota</strong></td>
            <td style="padding-left: 20px">:</td>
            <td>{{ \App\Helpers\AkademikHelpers::getSekolahKab($formulir->asal_sekolah) }}</td>
    </tbody>
</table>

<p style="text-align:center; margin:20px 0; font-size:14px; font-weight:bold">
    Dinyatakan LULUS sebagai Calon Mahasiswa Baru Program Sarjana pada:
</p>

<table style="font-size:12px">
    <tbody>
        <tr>
            <td><strong>Program Studi</strong></td>
            <td style="padding-left: 45px">:</td>
            <td>{{ $prodi->nama_prodi ?? 'N/A' }} - {{ $prodi->nama_jenjang ?? '' }}</td>
        </tr>
        <tr>
            <td><strong>Fakultas</strong></td>
            <td style="padding-left: 45px">:</td>
            <td>{{ \App\Helpers\AkademikHelpers::getFakultasNama($prodi->kode_fakultas ?? 'DEFAULT') }}</td>
        </tr>
    </tbody>
</table>

<div class="konten-utama" style="text-align: justify; max-width: 600px; margin: 0 auto; line-height: 2;">
    <p><strong>INFORMASI PMB 2025/2026:</strong></p>
    <ol style="text-align: justify;">
        <li>Registrasi harus dilakukan paling lambat satu minggu setelah hari ini dengan melunasi keuangan sesuai ketentuan.</li>
        <li>Pembayaran dilakukan di <strong>Bank Jabar dan Banten (BJB) Cabang Sumedang</strong> dengan nomor rekening <strong>011.001.0011941</strong>.</li>
        <li>Bukti pembayaran diunggah di <a href="https://online.ypsa-sumedang.or.id/">https://online.ypsa-sumedang.or.id/</a> atau diserahkan ke Sekretariat YPSA.</li>
        <li>Calon Mahasiswa Baru Program Studi Pendidikan Jasmani Kesehatan dan Rekreasi (PJKR) FKIP wajib mengikuti seleksi kesehatan dan fisik pada 30 Agustus 2025.</li>
        <li>Informasi PKKMB akan diumumkan di <a href="https://unsap.ac.id">https://unsap.ac.id</a>.</li>
    </ol>
</div>

<div class="footer-surat" style="display: flex; flex-direction: column; align-items: center; margin-top: 50px; text-align: center; width: 100%; position: absolute; bottom: 2.5cm;">
    <p style="margin-bottom: 10px;">Sumedang, {{ date('d F Y') }}</p>
    <img src="{{ public_path('images/tanda_tangan2025.png') }}" style="max-width: 700px; height: auto; margin-top: 10px;">
</div>






<div class="page-break"></div>

<div style="text-align: center; line-height: 1.5">
    <h4>RINCIAN PEMBAYARAN BIAYA KULIAH</h4>
    <h5>REGISTRASI PERTAMA</h5>
    <img src="{{ public_path('images/usp_uk.png') }}" style="max-width:100%; height:auto; width:700px;">
    <div style="margin-top:20px">
        <img src="{{ public_path('images/up.png') }}" style="max-width:100%; height:auto; width:700px;">
    </div>
</div>

</body>

</html>
