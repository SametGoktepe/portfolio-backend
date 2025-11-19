<?php

namespace Src\Infrastructure\Persistence\Eloquent\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EloquentProject extends Model
{
    use HasUuids;

    protected $table = 'projects';
    
    protected $keyType = 'string';
    
    public $incrementing = false;

    protected $fillable = [
        'id',
        'title',
        'description',
        'images',
        'github_url',
        'demo_link',
        'technologies',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
        'technologies' => 'array',
        'status' => Status::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

