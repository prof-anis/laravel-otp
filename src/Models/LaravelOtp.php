<?php

namespace Tobexkee\LaravelOtp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaravelOtp extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'type',
        'purpose',
        'identifier',
        'code',
        'expire_at',
        'status'
    ];

    protected $casts = [
        'expire_at' => 'datetime'
    ];
}
