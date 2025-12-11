<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function user_can_create_category(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('categories.store'), [
            'name' => 'Work Projects'
        ]);

        $response->assertStatus(200)
        ->assertJson(['name' => 'Work Projects']);

        $this->assertDatabaseHas('categories', ['name' => 'Work Projects']);
    }

    /** @test */
    public function duplicate_categories_are_not_allowed(){
        $user = User::factory()->create();
        Category::create(['name' => 'Work', 'user_id' => $user->id]);

        $response = $this->actingAs($user)->postJson(route('categories.store'), [
            'name' => 'Work'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function deleting_a_category_sets_related_tasks_to_null(){
        $user = User::factory()->create();
        $category = Category::factory()->create();

        //Create 3 tasks assign tot this category
        $tasks = Task::factory()->count(3)->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        //Delete category with API
        $response = $this->actingAs($user)->deleteJson(route('categories.destroy', $category));

        $response->assertStatus(200);

        //asser category is gone
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);

        //assert task still exists, but category_id is null
        foreach($tasks as $task){
            $this->assertDatabaseHas('tasks', [
                'id' => $task->id,
                'category_id' => null
            ]);
        }
    }
}
