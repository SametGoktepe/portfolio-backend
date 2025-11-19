<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
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

    public function getImagesAttribute($value)
    {
        return $value ? json_decode($value) : null;
    }
}
