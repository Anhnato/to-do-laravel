<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskLifecycleTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function a_user_can_update_their_own_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Old Title',
            'status' => 'pending'
        ]);

        //Act user try to update task
        $response = $this->actingAs($user)->put(route('task.update', $task),[
            'title' => 'Updated Title',
            'description' => 'New Description',
            'status' => 'completed',
            'priority' => 'high',
            'due_date' => '2025-12-31',
        ]);

        //Assert redirect usually means success in Laravel Controllers
        $response->assertStatus(200);

        //Check database
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title',
            'status' => 'completed',
        ]);
    }

    /** @test */
    public function a_user_can_delete_their_task(){
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        //Act: User delete their task
        $response = $this->actingAs($user)->delete(route('task.destroy', $task));

        //Asssert
        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
