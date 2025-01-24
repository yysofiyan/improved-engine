<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persyaratan extends Model
{
    use HasFactory;
    protected $table = 'persyaratans';
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
        'id_user',
        'id_operator',
		'ijasah',
        'is_ijasah',
        'ktp_kk',
        'is_ktp',
        'foto',
        'is_foto',
		'ket_sehat',
        'is_ket_sehat',
        'khs',
        'is_khs',
        'ktm',
        'is_ktm',
        'surat_pindah',
        'is_surat_pindah',
        'screen_pddikti',
        'is_screen_pddikti',
        'ijasah_lanjutan',
        'is_ijasah_lanjutan',
        'transkrip_nilai',
        'is_transkrip_nilai',
        'created_at',
        'updated_at',

	];
	public $incrementing = false;

    protected $casts = [
        'id'=>'string'
    ];
}
