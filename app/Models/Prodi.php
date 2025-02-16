<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    // Menentukan nama tabel yang digunakan
    protected $table = 'pe3_prodi';

    // Menentukan primary key tabel
    protected $primaryKey = 'kode_prodi';

    // Menonaktifkan auto increment karena kode_prodi bertipe string
    public $incrementing = false;

    // Menentukan tipe data primary key
    protected $keyType = 'string';

    // Menentukan kolom yang dapat diisi secara massal
    protected $fillable = [
        'id',                // ID unik
        'kode_prodi',        // Kode program studi
        'kode_fakultas',     // Kode fakultas induk
        'nama_prodi',        // Nama lengkap program studi
        'nama_prodi_alias',  // Nama singkat/alias program studi
        'kode_jenjang',      // Kode jenjang pendidikan
        'nama_jenjang',      // Nama jenjang pendidikan
        'config',            // Konfigurasi tambahan
        'nim_kode',          // Kode untuk pembuatan NIM
        'nim_jenjang'        // Jenjang untuk pembuatan NIM
    ];
}