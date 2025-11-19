<?php

namespace App\Http\Resources\Skills;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SkillResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'category' => $this->name,
            'slug' => $this->slug,
            'skills' => $this->skills->pluck('name'),
        ];
    }
}
