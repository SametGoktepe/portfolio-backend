<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Education extends Model
{
    use HasFactory;
    protected $table = "educations";
    protected $fillable = [
        "id",
        "school",
        "degree",
        "field_of_study",
        "start_date",
        "end_date",
    ];

    protected $casts = [
        "start_date" => "date",
        "end_date" => "date",
    ];
}
