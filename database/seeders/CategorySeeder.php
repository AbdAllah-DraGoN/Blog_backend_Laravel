<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['test', 'Business', 'News', 'Personal', 'Sports', 'Fitness', 'Travel', 'Food'];
        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
            ]);
        }
    }
}
