<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $table = "abouts";
    protected $fillable = [
        'id',
        'image',
        'full_name',
        'title',
        'summary',
        'email',
        'phone',
        'address',
        'github',
        'linkedin',
        'twitter',
    ];

    public function getImageAttribute($value)
    {
        return $value ? asset($value) : null;
    }
}
