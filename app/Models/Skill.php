<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    protected $table = "skills";
    protected $fillable = [
        "id",
        "category_id",
        "name",
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
