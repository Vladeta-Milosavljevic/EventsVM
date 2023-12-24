<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Event;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'Victor',
            'email' => 'victor@test.com',
            'password'=>bcrypt('victor123')
        ]);

        User::factory()->create([
            'name' => 'Miki',
            'email' => 'miki@test.com',
            'password'=>bcrypt('miki123')
        ]);

        User::factory()->create([
            'name' => 'Jovan',
            'email' => 'jovan@test.com',
            'password'=>bcrypt('jovan123')
        ]);

        Category::factory()->create([
            'name' => 'concert',
        ]);
        Category::factory()->create([
            'name' => 'festival',
        ]);
        Category::factory()->create([
            'name' => 'museum',
        ]);
        Category::factory()->create([
            'name' => 'gallery',
        ]);
        Event::factory(100)->create();
    }
}
