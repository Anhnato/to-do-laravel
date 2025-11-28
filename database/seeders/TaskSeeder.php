<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $categories = Category::all();

        if ($categories->count() === 0){
            $this->command->warn('No categories found! Run CategorySeeder first');
            return;
        }

        //Create 10 sample task for first user
        foreach (range(1, 10) as $i){
            Task::create([
                'title'=>'Sample Task $i',
                'desciption'=>fake()->sentence(),
                'status'=> fake()->randomElement(['pending','completed']),
                'priority'=>fake()->randomElement(['high', 'medium', 'low']),
                'due_date'=>fake()->optional()->date(),
                'user_id'=>$user->id,
                'category_id'=>$categories->random()->id,
            ]);
        }
    }
}
