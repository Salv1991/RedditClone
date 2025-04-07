<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '1234'
        ]);

        //User::factory(100)->create();

        $post = Post::factory()->create();

        Post::factory(20)->create();

        Post::factory(2)->create([
            'user_id' => $user->id,
            'title' => 'dawdawdwadwa dwadwadawd awd awd wada',
            'text' => 'TEXT DJWDJ ADWJADWJAD JWADJWADJWAD WAJD WAD JADWJJWDJAWd wajdjwa djawdjawdj awdjawdjawdj awjdawjdajwdjawjd awj djawdjawjdawj',
        ]);

        $user->votedPosts()->attach($post);
    }
}
