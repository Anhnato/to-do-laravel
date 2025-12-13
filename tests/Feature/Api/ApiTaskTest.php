<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_and_get_token()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'device_name' => 'TestDevice'
        ]);

        $response->assertStatus(200)
        ->assertJsonStructure(['token']);
    }

    /** @test */
    public function login_fails_with_bad_credentials(){
        $user = User::factory()->create(['password' => bcrypt('correct')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong',
            'device_name' => 'TestDevice'
        ]);

        $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function user_can_fetch_their_tasks(){
        $user = User::factory()->create();
        Task::factory()->create(['user_id' => $user->id, 'title' => 'My Task']);

        Sanctum::actingAs($user, ['tasks:read']);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
        ->assertJsonFragment(['title' => 'My Task']);
    }

    /** @test */
    public function user_can_create_task_via_api(){
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['tasks:write']);

        $response = $this->postJson('/api/tasks', [
            'title' => 'API Task',
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => '2025-12-31'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', ['title' => 'API Task']);
    }

    /** @test */
    public function read_only_token_cannot_delete_task(){
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        Sanctum::actingAs($user, ['tasks:read']);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(403);

        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function user_cannot_access_others_tasks_even_with_valid_token(){
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $taskB = Task::factory()->create(['user_id' => $userB->id]);

        Sanctum::actingAs($userA, ['tasks:read']);

        $response = $this->getJson("/api/tasks/{$taskB->id}");

        $response->assertStatus(403);
    }
}
