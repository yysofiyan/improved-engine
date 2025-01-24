<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSiuter extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = [
		'id',
		'nidn',
		'email',
        'kodeprodi',
        'google_id',
        'role',
	];

    public $incrementing = false;

    protected $casts = [
        'id'=>'string'
    ];
}
