<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonfirmasiPembayaran extends Model
{
    use HasFactory;
    protected $table = 'konfirmasi_pembayarans';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'transaksi_id';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'transaksi_id',
        'id_channel',
		'total_bayar',
        'nomor_rekening_pengirim',
        'nama_rekening_pengirim',
        'nama_bank_pengirim',
        'desc',
		'tanggal_bayar',
        'bukti_bayar',
        'verified',
        'created_at',
        'updated_at',
	];
	public $incrementing = false;

    protected $casts = [
        'transaksi_id'=>'string'
    ];

}
