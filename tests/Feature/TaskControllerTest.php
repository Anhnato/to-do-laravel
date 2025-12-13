<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function guest_cannot_manage_tasks(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->post(route('task.store'),[])->assertRedirect(route('login'));
        $this->delete(route('task.destroy', 1))->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_view_only_their_own_tasks(){
        $userA = User::factory()->create();
        $taskA = Task::factory()->create(['user_id' => $userA->id, 'title' => 'User A Task']);

        $userB = User::factory()->create();
        $taskB = Task::factory()->create(['user_id' => $userB->id, 'title' => 'User B Task']);

        $response = $this->actingAs($userA)->get('/');

        $response->assertSee('User A Task');
        $response->assertDontSee('User B Task');
    }

    /** @test */
    public function task_creation_validates_required_fields(){
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('task.store'), [
            'title' => '', //Empty title
            'status' => 'invalid-status', //Wrong enum
        ]);

        $response->assertSessionHasErrors(['title', 'status', 'category_id']);
    }

    /** @test */
    public function user_can_create_task_and_notifcation_is_sent(){
        Notification::fake();

        $user = User::factory()->create();
        $category = Category::factory()->create();

        $data = [
            'title' => 'Launch Rocket',
            'description' => 'Go to mars',
            'status' => 'pending',
            'priority' => 'high',
            'due_date' => '2025-12-31',
            'category_id' => $category->id
        ];

        //Submit form
        $response = $this->actingAs($user)->post(route('task.store'), $data);

        //Assert redirect & DB
        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('tasks', [
            'title' => 'Launch Rocket',
            'user_id' => $user->id
        ]);

        //Assert Slack
        Notification::assertSentTo(
            new \Illuminate\Notifications\AnonymousNotifiable(),
            TaskCreated::class
        );
    }

    /** @test */
    public function user_can_update_their_task(){
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put(route('task.update', $task), [
            'title' => 'Updated Title',
            'description' => 'Updated Desc',
            'status' => 'completed',
            'priority' => 'low',
            'category_id' => $task->category_id
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title',
            'status' => 'completed'
        ]);
    }

    /** @test */
    public function user_cannot_update_other_tasks_security_check(){
        $userA = User::factory()->create();
        $taskA = Task::factory()->create(['user_id' => $userA->id, 'title' => 'Original']);

        //The hacker
        $userB = User::factory()->create();

        $response = $this->actingAs($userB)->put(route('task.update', $taskA), [
            'title' => 'Hacked Title'
        ]);

        $response->assertStatus(403);

        //DB is not change
        $this->assertDatabaseHas('tasks', [
            'id' => $taskA->id,
            'title' => 'Original'
        ]);
    }

    /** @test */
    public function user_can_delete_task(){
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('task.destroy', $task));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function user_can_create_task_and_notification_is_queued(){
        Notification::fake();

        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post(route('task.store'), [
            'title' => 'Async Task',
        'status' => 'pending',
        'priority' => 'high',
        'category_id' => $category->id
        ]);

        //Assert the request was fast and successful
        $response->assertRedirect(route('dashboard'));

        //Assert Notification was pushed to the Queue
        //We check assertSentTo but because we faked it, Laravel checks if it would have queued
        Notification::assertSentTo(
            new \Illuminate\Notifications\AnonymousNotifiable,
            TaskCreated::class,
            function ($notification, $channels){
                //verify if targets the Slack channel
                return in_array('slack', $channels);
            }
        );
    }
}
