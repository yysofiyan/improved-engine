<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Neomahasiswa extends Model
{
    use HasFactory;
    protected $table = 'neomahasiswas';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
        'id_operator',
		'nim',
        'id_reg_mahasiswa',
        'nomor_pendaftaran',
        'pin',
        'is_aktif',
		'nama_mahasiswa',
        'nisn',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'handphone',
        'agama',
        'kelurahan',
        'kodewilayah',
        'alamat_rumah',
        'nama_ibu_kandung',
        'kodeprodi_satu',
        'kodeprodi_dua',
        'tanggal_masuk',
        'semester_masuk',
        'jenis_daftar',
        'sks_diakui',
        'asal_sekolah',
        'nomor_ijasah',
        'tahun_lulus',
        'jenis_pmb_daftar',
        'kode_pt_asal',
        'kode_prodi_asal',
        'biaya_awal_masuk',
        'konfirmasi',
        'negara',
        'provinsi',
        'kota',
        'kecamatan',
        'tahun_masuk',
        'catatan',
        'kewarganegaraan',
        'created_at',
        'updated_at',
	];

    public $incrementing = false;

    protected $casts = [
        'id'=>'string'
    ];


}
