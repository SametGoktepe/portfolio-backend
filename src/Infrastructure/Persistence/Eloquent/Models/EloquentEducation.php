<?php

namespace Src\Infrastructure\Persistence\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class EloquentEducation extends Model
{
    use HasUuids;

    protected $table = 'educations';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'school',
        'degree',
        'field_of_study',
        'start_year',
        'end_year',
    ];

    protected $casts = [
        'start_year' => 'integer',
        'end_year' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}

