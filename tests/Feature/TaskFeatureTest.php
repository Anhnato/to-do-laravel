<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskFeatureTest extends TestCase
{
    //Reset the RAM database after every single test
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function guest_are_redirect_to_login()
    {
        $response = $this->get('/');

        $response->assertRedirect('login');
    }

    /** @test  */
    public function authenticated_user_can_see_dashboard(){
        //Dummy user
        $user = User::factory()->create();

        //Act as that user (log in)
        $response = $this->actingAs($user)->get('/');

        //Response with 200 status error
        $response->assertStatus(200);
        $response->assertSee('SunnyDay Tasks');
    }

    /** @test  */
    public function user_can_create_a_task(){
        $user = User::factory()->create();
        $category = Category::factory()->create();

        //Smiulate submitting the form
        $response = $this->actingAs($user)->post(route('task.store'), [
            'title' => 'Buy Milk',
            'description' => '2 Gallons',
            'due_date' => '2025-12-30',
            'status' => 'pending',
            'priority' => 'high',
            'category_id' => $category->id
        ]);

        //Redirect back to home with success message
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        //Verify it actually exist on database
        $this->assertDatabaseHas('tasks',[
            'title' => 'Buy Milk',
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function user_cannot_delete_another_user_task(){
        //User A
        $userA = User::factory()->create();
        $taskA = Task::factory()->create(['user_id' => $userA->id]);

        //User B
        $userB = User::factory()->create();

        //User B try to delete user A task
        $response = $this->actingAs($userB)->delete(route('task.destroy', $taskA));

        //Forbidden 403
        $response->assertStatus(403);

        //Ensure task is still in database
        $this->assertDatabaseHas('tasks', ['id' => $taskA->id]);
    }
}
