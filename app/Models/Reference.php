<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reference extends Model
{
    use HasFactory;

    protected $table = 'references';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'company',
        'position',
        'quote',
        'image',
    ];
}
