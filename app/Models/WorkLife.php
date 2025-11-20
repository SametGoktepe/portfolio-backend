<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class WorkLife extends Model
{
    use HasFactory;
    protected $table = "work_lives";
    protected $fillable = [
        "id",
        "company_name",
        "position",
        "start_year",
        "end_year",
        "is_ongoing",
    ];

    protected $casts = [
        "is_ongoing" => "boolean",
    ];
}
