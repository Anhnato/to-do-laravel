<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskPaginationTest extends TestCase
{
    use RefreshDatabase;
    #[Test()]
    public function dashboard_displays_maximum_50_tasks_a_page()
    {
        $user = User::factory()->create();

        //Create 51 tasks for test user
        Task::factory()->count(19)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/');

        //Assert we see 50 items not 51
        //This relies on passing $tasks to the view
        $response->assertViewHas('tasks', function($tasks){
            return $tasks->count() === 18 && $tasks->total() === 19;
        });
    }

    #[Test()]
    public function tasks_are_ordered_by_lastest_first(){
        $user = User::factory()->create();

        $oldTask = Task::factory()->create(['user_id' => $user->id, 'created_at' => now()->subDay(), 'title' => 'Old Task']);
        $newTask = Task::factory()->create(['user_id' => $user->id, 'created_at' => now(), 'title' => 'New Task']);

        $response = $this->actingAs($user)->get('/');

        //The first task in the list should be the NEW one
        $response->assertSeeInOrder(['New Task', 'Old Task']);
    }
}
