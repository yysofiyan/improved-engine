<html>

<head>
    <style>
        body {
            font-family: Tahoma, Verdana, Segoe, sans-serif;
        }

        .header {
            width: 1440px;
            font-weight: bold;
            text-align: center;
        }

        table {
            width: 100%;
        }

        th,
        td,
        tr {
            border: 1px solid black;
            border-collapse: collapse;

        }

        img {
            max-width: 100%;
            max-height: 100%;
        }

        .square {
            height: 200px;
            width: 800px;
        }

        h2 {
            text-align: center;
        }

        .page_break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <h2 style="margin-bottom: -50px">REKAP PEMBAYARAN OFFLINE CALON MAHASISWA BARU</h2>
    <p style="font-size:14px;text-align:center;font-weight: bold;margin-top:50px;">
        ===================================================================================</p>
    <p style="font-size:14px;text-align:center">Panitia Penerimaan Mahasiswa Baru (PMB) Universitas Sebelas April dan
        STAI Sebelas April Sumedang <br /> Tahun 2025 </p>
    @php
        $i = 1;
        $jumlah = 0;
    @endphp
    <table style="font-size:14px;margin-top:10px">
        <tr>
            <td style="text-align: center;font-weight:bold;">Identitas Maba</td>
            <td style="text-align: center;font-weight:bold;">Tanggal Bayar</td>
            <td style="text-align: center;font-weight:bold;">Nama Pengirim</td>
            <td style="text-align: center;font-weight:bold;">Status</td>
            <td style="text-align: center;font-weight:bold;">Jumlah Bayar</td>
            <td style="text-align: center;font-weight:bold;">Cek Petugas</td>
        </tr>
        <tbody>
            @foreach ($pembayaran as $pembayaran)
                <tr>
                    <td>{{ $i . '. ' . strtoupper($pembayaran['nama_mahasiswa']) }}</td>
                    <td style="text-align: center">{{ $pembayaran['tanggal_bayar'] }}</td>
                    <td style="text-align: center">{{ $pembayaran['nama_rekening_pengirim'] }}</td>
                    <td style="text-align: center">
                        {{ $pembayaran['lunas'] == 11 ? 'Valid' : 'Belum Valid' }}</td>
                    <td style="text-align: center">{{ $pembayaran['total_bayar'] }}</td>
                    <td></td>
                </tr>
                @php
                    $i++;
                    $jumlah += $pembayaran['jumlah'];
                @endphp


                <script type="text/php">
    if ( isset($pdf) ) {
      $w = $pdf->get_width();
      $h = $pdf->get_height();

      $size = 6;
      $color = [0, 0, 0];
      $font = $fontMetrics->getFont("helvetica");
      $text_height = $fontMetrics->getFontHeight($font, $size);
      $y = $h - 2 * $text_height - 24;

      // a static object added to every page
      $foot = $pdf->open_object();
      // Draw a line along the bottom
      $pdf->line(16, $y, $w - 16, $y, $color, 1);
      $y += $text_height;
      $pdf->text(16, $y, "PMB UNSAP dan STAI Sebelas April Sumedang", $font, $size, $color);
      
      $pdf->close_object();
      $pdf->add_object($foot, "all");
    }
  </script>
            @endforeach
        </tbody>
    </table>

    <h2>Total Keseluruhan = {{ 'Rp. ' . number_format($jumlah, 0) }}</h2>

    <script type="text/php">
    if ( isset($pdf) ) {
      $h = $pdf->get_height();
      $tgl = date('H:i:s');

      $size = 6;
      $font_bold = $fontMetrics->getFont("helvetica", "bold");
      $text_height = $fontMetrics->getFontHeight($font_bold, $size);
      $y = $h - $text_height - 24;

      // generated text written to every page after rendering
      $pdf->page_text(525, $y, "Halaman {PAGE_NUM} dari {PAGE_COUNT}", $font_bold, $size, [0, 0, 0]);
      $pdf->page_text(200, $y, "Waktu Print :".$tgl." WIB", $font_bold, $size, [0, 0, 0]);
    }
  </script>

</body>

</html>
