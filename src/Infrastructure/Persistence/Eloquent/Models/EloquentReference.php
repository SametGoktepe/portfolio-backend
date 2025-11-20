<?php

namespace Src\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EloquentReference extends Model
{
    use HasUuids;

    protected $table = 'references';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'full_name',
        'email',
        'phone',
        'company',
        'position',
        'quote',
        'image',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

