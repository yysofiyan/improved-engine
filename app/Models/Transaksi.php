<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksis';
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
        'pin',
		'no_transaksi',
        'nomor_rekening',
        'status',
        'total',
        'tanggal',
		'desc',
        'created_at',
        'updated_at',
	];

	public $incrementing = false;

    protected $casts = [
        'id'=>'string'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Neomahasiswa::class, 'pin', 'pin');
    }

}
