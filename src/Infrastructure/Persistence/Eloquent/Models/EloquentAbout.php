<?php

namespace Src\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EloquentAbout extends Model
{
    use HasFactory;

    protected $table = 'abouts';
    
    protected $keyType = 'string';
    
    public $incrementing = false;

    protected $fillable = [
        'id',
        'image',
        'full_name',
        'title',
        'summary',
        'email',
        'phone',
        'street',
        'city',
        'state',
        'country',
        'postal_code',
        'github',
        'linkedin',
        'twitter',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}