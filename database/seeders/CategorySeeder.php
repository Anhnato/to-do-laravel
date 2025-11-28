<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'enmail' => 'example@gmail.com',
        ]);

        $categories = [
            ['name' => 'Study', 'user_id'=>$user->id],
            ['name' => 'Reading List','user_id'=>$user->id],
            ['name' => 'Work','user_id'=>$user->id],
            ['name' => 'Fitness','user_id'=>$user->id],
        ];

        foreach($categories as $category){
            Category::create($category);
        }
    }
}
