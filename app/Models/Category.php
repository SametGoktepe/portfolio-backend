<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = "categories";

    protected $fillable = [
        "id",
        "name",
        "slug",
    ];

    public static function boot(): void
    {
        parent::boot();
        self::creating(function ($category) {
            $category->slug = self::generateSlug($category->name);
        });
        self::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = self::generateSlug($category->name);
            }
        });
    }

    public static function generateSlug($name)
    {
        $slug = Str::slug($name);
        if (self::checkSlug($slug)) {
            $slug = $slug . '-' . self::where('slug', 'like', $slug . '%')->count() + 1;
        }
        return $slug;
    }

    public static function checkSlug($slug)
    {
        return self::where('slug', $slug)->exists();
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }
}
