<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\About>
 */
class AboutFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => Uuid::uuid4()->toString(),
            "full_name"=> "Samet GOKTEPE",
            "title"=> "Backend Developer",
            "summary"=> "A Backend Developer with 3+ years of experience specializing
in PHP (Laravel), Node.js (Nest.JS, Express.js), and microservice-
supporting technologies (Docker, RabbitMQ, Redis). Focused
on developing high-performance, scalable, and testable APIs.
In my current role as a Project Lead, I am leading technical
architecture design, code quality processes, and providing
technical mentorship to the teams.",
            "email"=> "sametgoktepe74@gmail.com",
            "phone"=> "+90 546 790 50 43",
            "city"=> "Istanbul",
            "state"=> "Şişli",
            "country"=> "Turkey",
            "postal_code"=> "34384",
            "github"=> "https://github.com/SametGoktepe",
            "linkedin"=> "https://linkedin.com/in/sametgoktepe",
            "twitter"=> "https://x.com/samet74goktepe",
        ];
    }
}
