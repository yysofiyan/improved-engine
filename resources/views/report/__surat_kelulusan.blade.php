<html>
<head>
<style>
body {
    font-family: Tahoma, Verdana, Segoe, sans-serif;
}
.header{
    width:1440px;
    font-weight:bold;
    text-align:center;
}
.table {		
    width:100%;
    border-collapse: collapse;   
    font-size: 12px; 
    margin-bottom: 10px;
}
.table th {
    border: 1px solid #000;	    
}
.table td {
    border: 1px solid #000;	  
    padding:5px 5px;    
}
h2{
    text-align:center;
}
</style>
</head>
<body>
<img src="{{$headers['HEADER_LOGO']}}" width="2000px" height="100%">    
<h2 style="margin-bottom: -50px">PENGUMUMAN KELULUSAN MAHASISWA BARU</h2>
<p style="font-size:14px;text-align:center;font-weight: bold;margin-top:-5px;">=======================================================</p>
<p style="font-size:14px;text-align:center">Panitia Penerimaan Mahasiswa Baru (PMB) Universitas Sebelas April dan STAI Sebelas April Sumedang Tahun 2024 menyatakan bahwa :</p>
    <tbody>
            
        <tr>
            <td><strong>Nama</strong></td>
            <td>:</td>
            <td>{{$formulir->nama_mhs}}</td>
        </tr>
        <tr>
            <td><strong>Nomor Pendaftaran</strong></td>
            <td>:</td>
            <td>{{$formulir->no_formulir}}</td>
        </tr>
        <tr>
            <td><strong>Asal Sekolah/PT</strong></td>
            <td>:</td>
            <td>{{$formulir->asal_sekolah}}</td>
        </tr>
        <tr>
            <td><strong>Kabupaten/Kota</strong></td>
            <td>:</td>
            <td>{{$formulir->kabupaten_kota}}</td>
        </tr>      
    </tbody>
</table>

<p style="font-size:14px;text-align:center">Dinyatakan LULUS sebagai Calon Mahasiswa Baru pada :</p>
 
<table style="font-size:14px">
<tbody>
    <tr>
        <td><strong>Program Studi</strong></td>
        <td>:</td>
        <td>{{$prodi->nama_prodi .' - '.$prodi->nama_jenjang}}</td>
    </tr>
    <tr>
        <td><strong>Fakultas</strong></td>
        <td>:</td>
        <td>{{$fakultas->nama_fakultas}}</td>
    </tr>
</tbody>
</table>  
<br/>
<table style="font-size:14px;width:100%">
<tbody>
    <tr>
        <td style="text-align:center;vertical-align:top;width:5%">1. </td><td style="text-align:justify;" colspan="2">Paling lambat <strong>satu minggu setelah hari ini</strong>, Saudara wajib melakukan registrasi dengan membayar lunas keuangan sesuai dengan ketentuan yang telah ditetapkan YPSA Sumedang.</td>
    </tr>
    <tr>
        <td style="text-align:center;vertical-align:top;width:5%">2. </td><td style="text-align:justify;" colspan="2">Seluruh pembayaran keuangan dilakukan pada <strong>Bank Jabar dan Banten (BJB) Cabang Sumedang</strong> dengan nomor rekening <strong>011.001.0011941</strong> atas nama Yayasan Pendidikan Sebelas April Sumedang</td>
    </tr>
    <tr>
        <td style="text-align:center;vertical-align:top;width:5%">3. </td><td style="text-align:justify;" colspan="2">Bukti pembayaran dari bank, harus Saudara serahkan langsung ke <strong>Sekretariat YPSA</strong> (setiap hari kerja) <strong>paling lambat 1 hari</strong> setelah pembayaran dari bank.</td>
    </tr>
    <tr>
        <td style="text-align:center;vertical-align:top;width:5%">4. </td><td style="text-align:justify;" colspan="2"><strong>Khusus </strong> Calon Mahasiswa Baru <strong>Program Studi Pendidikan Jasmani Kesehatan dan Rekreasi (PJKR) FKIP </strong>, wajib mengikuti seleksi kesehatan dan fisik yang akan dilaksanakan <strong>Kamis, 31 Agustus 2024 Pukul 08.00 WIB s.d. selesai</strong> di Kampus I Universitas Sebelas April dengan menggunakan pakaian olahraga.</td>
    </tr>
    <tr style="margin-bottom: 20px;">
        <td style="text-align:center;vertical-align:top;width:5%">5. </td><td style="text-align:justify;" colspan="2">Informasi ketentuan dan pelaksanaan kegiatan <strong>Pengenalan Kehidupan Kampus bagi Mahasiswa Baru (PKKMB)</strong> akan di informasikan kemudian melalui laman https://unsap.ac.id. dan group WA oleh panitia PKKMB.</td>
    </tr>

    <tr>
        <td colspan="3" style="text-align:right"></td>                                   
    </tr>  
    <tr>
        <td colspan="3" style="text-align:right"></td>                                   
    </tr> 
    <tr>
        <td colspan="3" style="text-align:right">Sumedang, {{$tanggal}}</td>                                   
    </tr>  
</tbody>
</table>
        
       

  
<table width="100%" cellspacing="0px" cellpadding="0px" style="margin-top:50px">
    <tr>
        <td style="text-align:center;vertical-align:top"><img src="{{$headers['TANDATANGAN']}}" width="600px" height="150px">  </td>
    </tr>
</table>
<br/>

<pagebreak></pagebreak>  
<h2 style="margin-bottom: -50px">RINCIAN PEMBAYARAN BIAYA KULIAH REGISTRASI PERTAMA  
</h2>
<p style="font-size:14px;text-align:center;font-weight: bold;margin-top:-5px;">=======================================================</p>

<p style="font-size:14px;text-align:justify">Kewajiban keuangan bagi calon mahasiswa yang dinyatakan lulus dan diterima sebagai mahasiswa baru di Universitas Sebelas April adalah sebagai berikut.</p>
<table style="font-size:14px;width:100%">
    <tbody>
        <tr>
            <td style="text-align:left;vertical-align:top;width:5%">1) </td><td style="text-align:justify;" colspan="2">Jaket Almamater (S1) sebesar Rp 250.000,-</td>
        </tr>
        <tr>
            <td style="text-align:left;vertical-align:top;width:5%">2) </td><td style="text-align:justify;" colspan="2">PKKMB, Kartu Tanda Mahasiswa, dan Kaos PKKMB sebesar Rp 850.000,-</td>
        </tr>
        <tr>
            <td style="text-align:left;vertical-align:top;width:5%">3) </td><td style="text-align:justify;" colspan="2">Prapasca dan Jaket Almamater untuk Magister S2 Administrasi Publik, Magister Manajemen, Magister Pendidikan Bahasa Indonesia, Magister Pendidikan Matematika Rp. 3.125.000,-</td>
        </tr>
        <tr>
            <td style="text-align:left;vertical-align:top;width:5%">4) </td><td style="text-align:justify;" colspan="2">Besarnya Uang Sumbangan Pembangunan (USP), Uang Kuliah (UK), dan Uang Praktik (UP) disajikan dalam tabel berikut.</td>
        </tr>
    </tbody>
    </table>
    <img src="{{ \App\Helpers\Helper::public_path("images/tabel_1.png") }}" width="2000px" height="100%">  

<h2 style="font-size:18px;text-align:left">Keterangan</h2>
<table style="font-size:14px;width:100%">
    <tbody>
        <tr>
            <td style="text-align:left;vertical-align:top;width:5%">a. </td><td style="text-align:justify;" colspan="2"><strong>Uang Sumbangan Pembangunan (USP)</strong> untuk jenjang S2 sebesar Rp 3.750.000,-</td>
        </tr>
        <tr>
            <td style="text-align:left;vertical-align:top;width:5%">b. </td><td style="text-align:justify;" colspan="2"><strong>Uang Sumbangan Pembangunan (USP)</strong> untuk jenjang S1 dan S2 dibayar satu kali  selama kuliah dan harus lunas pada semester pertama.</td>
        </tr>
        <tr>
            <td style="text-align:left;vertical-align:top;width:5%">c. </td><td style="text-align:justify;" colspan="2"><strong>Uang Kuliah (UK)</strong> dibayar Per Semester setiap jadwal registrasi</td>
        </tr>
        <tr>
            <td style="text-align:left;vertical-align:top;width:5%">d. </td><td style="text-align:justify;" colspan="2">Uang Kuliah untuk jenjang Strata Dua (S2) pada prodi Magister Pendidikan Bahasa Indonesia, Magister Pendidikan Matematika, dan Magister Administrasi Publik Rp 4.500.000,- <strong>per semester</strong> serta Magister Manajemen Rp 6.000.000,- <strong>per semester</strong></td>
        </tr>
    </tbody>
    </table>

    <h2 style="font-size:18px;text-align:left">Uang Praktik</h2>

    <img src="{{\App\Helpers\Helper::public_path("images/tabel_2.png")}}" width="2000px" height="100%">  


</body>
</html>
