<?php

namespace Src\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EloquentHobby extends Model
{
    use HasUuids;

    protected $table = 'hobbies';
    
    protected $keyType = 'string';
    
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'image',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

