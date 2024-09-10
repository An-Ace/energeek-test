<?php

namespace Database\Seeders;

use App\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Todo', 'label' => 'To Do'],
            ['name' => 'InProgress', 'label' => 'In Progress'],
            ['name' => 'Testing', 'label' => 'Testing'],
            ['name' => 'Done', 'label' => 'Done'],
            ['name' => 'Pending', 'label' => 'Pending'],
        ];

        Category::insert($categories);
    }
}
