<?php

namespace Src\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EloquentWorkLife extends Model
{
    use HasUuids;

    protected $table = 'work_lives';
    
    protected $keyType = 'string';
    
    public $incrementing = false;

    protected $fillable = [
        'id',
        'company_name',
        'position',
        'start_year',
        'end_year',
        'is_ongoing',
        'description',
    ];

    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer',
        'is_ongoing' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

