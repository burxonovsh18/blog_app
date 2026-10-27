<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name'     => 'Demo Author',
            'email'    => 'demo@example.com',
            'password' => 'password123',
        ]);

        $samples = [
            ['Getting started with the blog', 'A short tour of what you can do here.', "Welcome!\n\nSign in at /login with demo@example.com / password123, then open the dashboard to write your own posts."],
            ['Why a REST API under a Blade site?', 'One backend, many clients.', "The Blade pages read posts directly for speed and SEO, while the dashboard talks to the same REST API that a mobile app could use.\n\nThat keeps the rules in one place."],
            ['A draft you will not see publicly', null, "This one is unpublished, so it only appears in the dashboard."],
        ];

        foreach ($samples as $i => [$title, $excerpt, $body]) {
            $user->posts()->create([
                'title'        => $title,
                'excerpt'      => $excerpt,
                'body'         => $body,
                'published_at' => $i < 2 ? now()->subDays(2 - $i) : null,
            ]);
        }
    }
}
