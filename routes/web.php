<?php

use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MabaController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\SecondDatabaseController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\File;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/home', function () {
    return redirect('/login'); // Untuk login admin/operator
});

Route::get('/', function () {
    return redirect('/daftar-maba'); // Tampilan pertama akan redirect ke /daftar-maba // Landing page utama ke registrasi maba
});

/* Route::get('/', function () {
    return redirect('/login-maba');
}); */


// Rute untuk autentikasi Google
Route::get('authorized/google', [SocialController::class, 'redirectToGoogle']);
Route::get('authorized/google/callback', [SocialController::class, 'handleGoogleCallback']);

// Rute utama dan pendaftaran mahasiswa baru
Route::get('/', [MabaController::class, 'index'])->name('daftar.maba');
Route::get('/daftar-maba', [MabaController::class, 'index'])->name('daftar.mabaumum');

// Rute untuk database kedua
Route::get('/db', [SecondDatabaseController::class, 'index'])->name('db.index');

// Rute login untuk mahasiswa baru dan fakultas  
Route::get('/login-maba', [MabaController::class, 'loginmaba'])->name('login.maba');
Route::get('/login-fakultas', [FakultasController::class, 'login'])->name('login.fakultas');

// Rute terkait ujian mahasiswa
Route::get('/ujian', [MabaController::class, 'ujian'])->name('ujian.maba');
Route::post('/cekUjian', [MabaController::class, 'cekUjian'])->name('cek.ujian');
Route::get('/cekUjianOnline', [MabaController::class, 'cekUjianOnline'])->name('cek.ujianOnline');

// Rute login operator dan pendaftaran
Route::post('/login-operator', [AuthController::class, 'authenticate'])->name('login.operator');
Route::post('/simpan-daftar', [MabaController::class, 'simpandaftar'])->name('simpan.daftar');

// Rute verifikasi PIN dan CAPTCHA
Route::post('/cekPin', [MabaController::class, 'cekPin'])->name('cek.pin');
Route::get('/reload-captcha', [MabaController::class, 'reloadCaptcha']);

// Rute logout dan pengiriman WA
Route::get('/keluar', [MabaController::class, 'keluar'])->name('logout.perform');
Route::get('/keluar_operator', [OperatorController::class, 'logout'])->name('logout.operator');
Route::get('/sendwa', [MabaController::class, 'sendWa'])->name('home.sendwa');

// Grup rute dengan middleware authneofeeder
Route::middleware('authneofeeder')->group(function() {
    Route::prefix('maba')->group(function () {
        // Dashboard dan fitur mahasiswa
        Route::get('/dashboard', [MabaController::class, 'home'])->name('home.maba');


        //Route::get('/send', [MabaController::class, 'sendWa'])->name('home.sendwa');

        // Rute untuk memperbarui data mahasiswa
        Route::post('/update-mhs', [MabaController::class, 'updateMhs'])->name('update.maba');
        
        // Rute untuk mengunggah bukti pembayaran
        Route::post('/upload-bukti', [MabaController::class, 'uploadBukti'])->name('uploadBukti.maba');
        
        // Rute untuk mengunggah dokumen persyaratan
        Route::post('/upload-syarat', [MabaController::class, 'uploadSyarat'])->name('uploadSyarat.maba');

        // Referensi wilayah
        Route::get('ref-wilayah-provinsi', [MabaController::class, 'wilayahProvinsi']);
        Route::get('ref-wilayah-kota', [MabaController::class, 'wilayahKota']);
        Route::get('ref-wilayah-kecamatan', [MabaController::class, 'wilayahKecamatan']);
    });
});


// Grup rute dengan middleware authYPSA untuk modul keuangan
Route::middleware('authYPSA')->group(function() {
    // Grup rute dengan prefix 'keuangan'
    Route::prefix('keuangan')->group(function () {
        // Rute untuk reload captcha
        Route::get('/reload-captcha', [MabaController::class, 'reloadCaptcha']);
        
        // Rute dashboard keuangan
        Route::get('/dashboard', [KeuanganController::class, 'index'])->name('keuangan.dashboard');

        // Rute manajemen PIN
        Route::get('/buatpin', [KeuanganController::class, 'buatpin'])->name('buatpin');
        Route::get('/tambah-pin', [KeuanganController::class, 'tambahpin'])->name('keuangan.tambahpin');
        Route::post('/simpan-pin', [KeuanganController::class, 'simpanpin'])->name('simpan.pin');

        // Rute transaksi keuangan
        Route::get('/transaksi', [KeuanganController::class, 'transaksi'])->name('keuangan.transaksi');

        // Rute konfirmasi pembayaran
        Route::get('/konfirmasi-bayar', [KeuanganController::class, 'konfirmasi'])->name('keuangan.konfirmasi');
        Route::post('/verifikasi/{id}', [KeuanganController::class, 'verifikasi'])->name('keuangan.verifikasi');

        // Rute pengiriman reminder
        Route::post('/reminder/{id}', [KeuanganController::class, 'reminder'])->name('keuangan.reminder');

        // Rute manajemen NIM
        Route::get('/buatnim', [KeuanganController::class, 'buatnim'])->name('keuangan.buatnim');
        Route::get('/tambah-nim', [KeuanganController::class, 'tambahnim'])->name('keuangan.tambahnim');
        Route::post('/simpan-nim', [KeuanganController::class, 'simpannim'])->name('simpan.nim');

        // Rute untuk mendapatkan data PIN
        Route::post('/get-pin', [KeuanganController::class, 'getPin'])->name('keuangan.getpin');
        
        // Rute untuk reload captcha (duplikat)
        Route::get('/reload-captcha', [MabaController::class, 'reloadCaptcha']);

    });


});

Route::middleware('authYPSA')->group(function() {
    Route::prefix('admin')->group(function () {
        Route::get('/sendsekolah', [SuperadminController::class, 'sendbroadcast'])->name('home.sendbroadcast');

        Route::get('/sendWa', [MabaController::class, 'sendWas'])->name('home.sendwas');
        Route::post('/sendpin/{id}', [SuperadminController::class, 'sendpin'])->name('home.sendpin');
        Route::get('/reload-captcha', [MabaController::class, 'reloadCaptcha']);
        Route::get('/dashboard', [SuperadminController::class, 'index'])->name('admin.dashboard');

        Route::get('/camaba', [SuperadminController::class, 'camaba'])->name('admin.camaba');
        Route::get('/lihatpdf/{nomor_pendaftaran}', [SuperadminController::class, 'lihatpdf'])
            ->name('admin.lihatpdf');
        Route::get('/lihat/{id}', [SuperadminController::class, 'lihatPin'])->name('admin.lihatpin');
        Route::get('/form-maba', [SuperadminController::class, 'formmaba'])->name('admin.formmaba');
        Route::post('/update-mhs', [SuperadminController::class, 'updateMhs'])->name('admin.updatemaba');
        Route::post('/upload-syarat', [SuperadminController::class, 'uploadSyarat'])->name('admin.uploadsyarat');

        Route::get('/transaksi', [SuperadminController::class, 'transaksi'])->name('admin.transaksi');

        Route::get('/konfirmasi-bayar', [SuperadminController::class, 'konfirmasi'])->name('admin.konfirmasi');
        Route::post('/verifikasi/{id}', [SuperadminController::class, 'verifikasi'])->name('admin.verifikasi');

        Route::post('/reminder/{id}', [SuperadminController::class, 'reminder'])->name('admin.reminder');

        Route::get('/listnim', [SuperadminController::class, 'buatnim'])->name('admin.buatnim');
        Route::get('/tambah-nim', [SuperadminController::class, 'tambahnim'])->name('admin.tambahnim');
        Route::post('/simpan-nim', [SuperadminController::class, 'simpannim'])->name('admin.simpannim');

        Route::get('ref-wilayah-provinsi', [MabaController::class, 'wilayahProvinsi']);
        Route::get('ref-wilayah-kota', [MabaController::class, 'wilayahKota']);
        Route::get('ref-wilayah-kecamatan', [MabaController::class, 'wilayahKecamatan']);
        Route::get('/reload-captcha', [MabaController::class, 'reloadCaptcha']);

        Route::get('/online', [SuperadminController::class, 'online'])->name('admin.online');
        Route::post('/pos_online', [SuperadminController::class, 'pos_online'])->name('admin.pos_online');
        Route::get('/reset-online', [SuperadminController::class, 'resetOnline'])->name('admin.reset-online');
        Route::get('/offline', [SuperadminController::class, 'offline'])->name('admin.offline');
        Route::post('/pos_offline', [SuperadminController::class, 'pos_offline'])->name('admin.pos_offline');
        Route::get('/reset-offline', [SuperadminController::class, 'resetOffline'])->name('admin.reset-offline');

        Route::get('/migrasi', [SuperadminController::class, 'migrasi'])->name('admin.migrasi');
        Route::post('/pos_migrasi', [SuperadminController::class, 'postMigrasi'])->name('admin.postmigrasi');

        Route::get('/printpdf', [SuperadminController::class, 'printpdf']);
        Route::get('/printofflinepdf', [SuperadminController::class, 'printofflinepdf']);
        Route::get('/konvert', [SuperadminController::class, 'konvert']);

        Route::get('/panitia', [PenggunaController::class, 'panitia']);
        Route::get('/tambah-panitia', [PenggunaController::class, 'tambahPanitia'])->name('panitia.tambah');
        Route::get('/panitia/edit/{id}', [PenggunaController::class, 'editPanitia'])->name('panitia.edit');
        Route::post('/panitia', [PenggunaController::class, 'storePanitia']);
        Route::delete('/panitia/hapus/{id}', [PenggunaController::class, 'hapusPanitia'])->name('panitia.hapus');
        Route::post('/panitia/password/{id}', [PenggunaController::class, 'gantiPasswordPanitia'])->name('panitia.password');

        Route::get('/panitia/setpassword/{id}', [SuperadminController::class, 'gantiPasswordPanitia'])->name('panitia.setpassword');

        Route::get('/fakultas', [PenggunaController::class, 'fakultas']);
        Route::get('/tambah-fakultas', [PenggunaController::class, 'tambahfakultas'])->name('fakultas.tambah');
        Route::get('/fakultas/edit/{id}', [PenggunaController::class, 'editfakultas'])->name('fakultas.edit');
        Route::post('/fakultas', [PenggunaController::class, 'storefakultas']);
        Route::delete('/fakultas/hapus/{id}', [PenggunaController::class, 'hapusfakultas'])->name('fakultas.hapus');

        // Rute untuk mendapatkan data calon mahasiswa baru (CAMABA) 
        // Menggunakan method camaba dari SuperadminController
        // Data dikembalikan dalam format JSON untuk keperluan DataTables
        Route::get('camaba/data', [SuperadminController::class, 'camaba'])
            ->name('camaba.data'); // Nama rute untuk referensi

    });


});


Route::middleware('authPMB')->group(function() {
    Route::prefix('operator')->group(function () {
        Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('operator.rekomendasi');
        Route::post('/insert-mhs', [RekomendasiController::class, 'updateMhs'])->name('operator.insertmaba');
        Route::post('/insert-syarat', [RekomendasiController::class, 'uploadSyarat'])->name('operator.insertsyarat');
        Route::get('/selesai-rekom', [RekomendasiController::class, 'selesai'])->name('operator.selesai');

        Route::get('/dashboard', [OperatorController::class, 'index'])->name('operator.dashboard');
        Route::get('/riwayat-daftar', [OperatorController::class, 'riwayat'])->name('operator.riwayatdaftar');
        Route::get('/hasil-kelulusan', [OperatorController::class, 'kelulusan'])->name('operator.hasillulus');

        Route::get('/data-sekolah', [OperatorController::class, 'datasekolah'])->name('operator.datasekolah');
        Route::get('/tambah-sekolah', [OperatorController::class, 'tambahsekolah'])->name('operator.tambahsekolah');
        Route::post('/simpan-sekolah', [OperatorController::class, 'simpansekolah'])->name('operator.simpansekolah');

        Route::get('/form-maba', [OperatorController::class, 'formmaba'])->name('operator.formmaba');
        Route::post('/update-mhs', [OperatorController::class, 'updateMhs'])->name('operator.updatemaba');
        Route::post('/upload-syarat', [OperatorController::class, 'uploadSyarat'])->name('operator.uploadsyarat');

        Route::get('/lihatpdf/{nomor_pendaftaran}', [OperatorController::class, 'lihatpdf'])
            ->name('operator.lihatpdf');

        Route::get('/buatpin', [OperatorController::class, 'buatpin'])->name('operator.buatpin');
        Route::get('/tambah-pin', [OperatorController::class, 'tambahpin'])->name('operator.tambahpin');
        Route::post('/simpan-pin', [OperatorController::class, 'simpanpin'])->name('operator.simpanpin');

        Route::post('/get-pin', [OperatorController::class, 'getPin'])->name('operator.getpin');
        Route::get('/lihat/{id}', [OperatorController::class, 'lihatPin'])->name('operator.lihatpin');
        Route::delete('/hapus/{id}', [OperatorController::class, 'hapus'])->name('hapusmember');
        Route::post('/import-member', [OperatorController::class, 'simpanImport'])->name('import-member');

        Route::get('ref-wilayah-provinsi', [MabaController::class, 'wilayahProvinsi']);
        Route::get('ref-wilayah-kota', [MabaController::class, 'wilayahKota']);
        Route::get('ref-wilayah-kecamatan', [MabaController::class, 'wilayahKecamatan']);
        Route::get('/reload-captcha', [MabaController::class, 'reloadCaptcha']);

         // Registration...
         Route::get('/register', [RegisteredUserController::class, 'create'])
         ->name('register');

            Route::post('/register', [RegisteredUserController::class, 'store']);
        });



});

Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    $enableViews = config('fortify.views', true);

    // Authentication...
    if ($enableViews) {
        Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->middleware(['guest:'.config('fortify.guard')])
            ->name('login');
    }

    $limiter = config('fortify.limiters.login');
    $twoFactorLimiter = config('fortify.limiters.two-factor');
    $verificationLimiter = config('fortify.limiters.verification', '6,1');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest:'.config('fortify.guard'),
            $limiter ? 'throttle:'.$limiter : null,
        ]));

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Password Reset...
    if (Features::enabled(Features::resetPasswords())) {
        if ($enableViews) {
            Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware(['guest:'.config('fortify.guard')])
                ->name('password.request');

            Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware(['guest:'.config('fortify.guard')])
                ->name('password.reset');
        }

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->middleware(['guest:'.config('fortify.guard')])
            ->name('password.email');

        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->middleware(['guest:'.config('fortify.guard')])
            ->name('password.update');
    }



    // Email Verification...
    if (Features::enabled(Features::emailVerification())) {
        if ($enableViews) {
            Route::get('/email/verify', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
                ->name('verification.notice');
        }

        Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'signed', 'throttle:'.$verificationLimiter])
            ->name('verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'throttle:'.$verificationLimiter])
            ->name('verification.send');
    }

    // Profile Information...
    if (Features::enabled(Features::updateProfileInformation())) {
        Route::put('/user/profile-information', [ProfileInformationController::class, 'update'])
            ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
            ->name('user-profile-information.update');
    }

    // Passwords...
    if (Features::enabled(Features::updatePasswords())) {
        Route::put('/user/password', [PasswordController::class, 'update'])
            ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
            ->name('user-password.update');
    }

    // Password Confirmation...
    if ($enableViews) {
        Route::get('/user/confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')]);
    }

    Route::get('/user/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])
        ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
        ->name('password.confirmation');

    Route::post('/user/confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
        ->name('password.confirm');

    // Two Factor Authentication...
    if (Features::enabled(Features::twoFactorAuthentication())) {
        if ($enableViews) {
            Route::get('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'create'])
                ->middleware(['guest:'.config('fortify.guard')])
                ->name('two-factor.login');
        }

        Route::post('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store'])
            ->middleware(array_filter([
                'guest:'.config('fortify.guard'),
                $twoFactorLimiter ? 'throttle:'.$twoFactorLimiter : null,
            ]));

        $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
            ? [config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'password.confirm']
            : [config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')];

        Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.enable');

        Route::post('/user/confirmed-two-factor-authentication', [ConfirmedTwoFactorAuthenticationController::class, 'store'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.confirm');

        Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.disable');

        Route::get('/user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.qr-code');

        Route::get('/user/two-factor-secret-key', [TwoFactorSecretKeyController::class, 'show'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.secret-key');

        Route::get('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'index'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.recovery-codes');

        Route::post('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'store'])
            ->middleware($twoFactorMiddleware);
    }
});

Route::get('/soal', SoalController::class);
Route::post('/selesai', [\App\Http\Livewire\SoalUjian::class,'selesai']);

Route::get('/printpdf/{id}', [MabaController::class, 'printpdf']);
Route::get('/downloadpdf/{id}', [MabaController::class, 'downloadpdf']);

// Rute untuk injeksi konfirmasi pembayaran berdasarkan PIN
Route::get('/injekbayar/{pin}', [RekomendasiController::class, 'injekKonfirmasi']);

// Rute untuk logout dari sistem fakultas
Route::get('/keluar_fakultas', [SocialController::class, 'logout']);

// Grup rute dengan middleware googleauth
Route::middleware('googleauth')->group(function() {
    // Grup rute dengan prefix 'fakultas'
    Route::prefix('fakultas')->group(function () {
        // Rute dashboard fakultas
        Route::get('/dashboard', [FakultasController::class, 'index']);
        
        // Rute untuk melihat data calon mahasiswa
        Route::get('/camaba', [FakultasController::class, 'camaba'])->name('fakultas.camaba');
        
        // Rute untuk melihat data pendaftaran
        Route::get('/pendaftaran', [FakultasController::class, 'pendaftaran'])->name('fakultas.pendaftaran');
        
        // Rute untuk melihat data rekomendasi
        Route::get('/rekomendasi', [FakultasController::class, 'rekomendasi']);
        
        // Rute untuk melihat hasil kelulusan
        Route::get('/hasil-kelulusan', [FakultasController::class, 'kelulusan'])->name('fakultas.kelulusan');
        
        // Rute untuk mendapatkan data program studi
        Route::post('/get-prodi', [FakultasController::class, 'pilihprodi'])->name('fakultas.pilihprodi');
        
        // Rute untuk mereset pilihan program studi
        Route::get('/reset-prodi', [FakultasController::class, 'resetProdi'])->name('fakultas.reset-prodi');
        
        // Rute untuk mengirim reminder
        Route::post('/reminder/{id}', [FakultasController::class, 'reminder'])->name('fakultas.reminder');
        
        // Rute untuk melihat detail mahasiswa
        Route::get('/lihatmhs/{id}', [FakultasController::class, 'lihatMhs'])->name('fakultas.lihatmhs');

        // Rute untuk melihat PDF surat kelulusan berdasarkan nomor pendaftaran
        // Menggunakan metode lihatPdf dari FakultasController
        // Nomor pendaftaran harus berupa kombinasi huruf kapital dan angka
        Route::get('/lihatpdf/{nomor_pendaftaran}', [FakultasController::class, 'lihatPdf'])
            ->name('fakultas.lihatpdf') // Nama rute untuk referensi
            ->where('nomor_pendaftaran', '[A-Z0-9]+'); // Validasi format nomor pendaftaran
    });



});

// Tambahkan route khusus untuk S2
Route::get('/downloadpdf-s2/{nomor_pendaftaran}', [MabaController::class, 'downloadpdf'])
    ->name('downloadpdf.s2');

// Rute untuk mengekspor data pendaftaran dalam format Excel
// Parameter tahun dapat diopsionalkan untuk memfilter data berdasarkan tahun tertentu
Route::get('/export-pendaftaran', [ExportController::class, 'export'])
    ->name('export.pendaftaran')
    ->middleware('auth'); // Tambahkan middleware auth untuk keamanan

Route::get('/images/persyaratan/{filename}', function ($filename) {
    // Membuat path file berdasarkan disk 'public' yang dikonfigurasi di filesystems.php
    $path = Storage::disk('public')->path('persyaratan/'.$filename);
    
    // Memeriksa apakah file ada di storage
    if(!Storage::disk('public')->exists('persyaratan/'.$filename)) {
        abort(404, 'File tidak ditemukan'); // Mengembalikan error 404 jika file tidak ada
    }
    
    // Mengembalikan file sebagai response dengan header yang sesuai
    return response()->file($path, [
        'Content-Type' => Storage::disk('public')->mimeType('persyaratan/'.$filename)
    ]);
})->where('filename', '.*');

// Rute API untuk mendapatkan jumlah pendaftar hari ini
// Menggunakan method getPendaftarHariIni dari OperatorController
// Data dikembalikan dalam format JSON
Route::get('/api/pendaftar-hari-ini', [OperatorController::class, 'getPendaftarHariIni'])
    ->name('api.pendaftar-hari-ini'); // Menambahkan nama rute untuk referensi

// Rute untuk mendapatkan data kelulusan mahasiswa
// Menggunakan method kelulusan dari FakultasController
// Data dikembalikan dalam format JSON untuk keperluan DataTables
Route::get('kelulusan/data', [FakultasController::class, 'kelulusan'])
    ->name('kelulusan.data'); // Nama rute untuk referensi

Route::get('pendaftaran/data', [FakultasController::class, 'pendaftaran'])->name('pendaftaran.data');


