<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'admin@smm.com')->first();

        if (!$author) {
            return;
        }

        News::updateOrCreate(
            ['title' => 'Welcome to SMM Intranet'],
            [
                'content' => 'Welcome to the SMM Intranet platform.',
                'author_id' => $author->id,
                'is_published' => true,
                'published_at' => now(),
            ]
        );
    }
}
