<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskDashboardTest extends TestCase
{
    use RefreshDatabase;
    #[Test]
    public function it_shows_tasks_on_the_dashoard(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'title'=>'My Special Task']);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertSee('My Special Task');
    }

    #[Test]
    public function it_paginates_tasks_correcctly(){
        $user = User::factory()->create();
        $category = Category::factory()->create();

        //Create 19 tasks a last task will be in next page
        Task::factory()->count(19)->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        //Visit page 1
        $responsePage1 = $this->actingAs($user)->get('/');

        //Visit page 2
        $responsePage2 = $this->actingAs($user)->get('/?page=2');

        $responsePage2->assertStatus(200);
    }

    #[Test]
    public function search_filters_tasks_correctly(){
        $user = User::factory()->create();

        //Create 2 tasks
        Task::factory()->create(['user_id' => $user->id, 'title' => 'Buy Milk']);
        Task::factory()->create(['user_id' => $user->id, 'title' => 'Fix Car']);

        //Search for milk
        $response = $this->actingAs($user)->get('/?search=Milk');

        //See Buy Milk but not see Fix car
        $response->assertSee('Buy Milk');
        $response->assertDontSee('Fix Car');
    }

    #[Test]
    public function search_keeps_query_string_during_pagination(){
        $user = User::factory()->create();
        $category = Category::factory()->create();

        //Create 26 tasks with the word Project in title
        Task::factory()->count(26)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Project Task'
        ]);

        //Search and go to page 1
        $response = $this->actingAs($user)->get('/?search=Project');

        //The pagination links should contain "search=Project"
        $response->assertSee('search=Project');
    }

    #[Test()]
    public function user_cannot_see_others_tasks()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $task1 = Task::factory()->create(['user_id' => $user1->id, 'title' => 'User 1 Secret']);
        $task2 = Task::factory()->create(['user_id' => $user2->id, 'title' => 'User 2 Secret']);

        // Act: Login as User 1
        $response = $this->actingAs($user1)->get('/');

        // Assert: See own task, don't see other's task
        $response->assertSee('User 1 Secret');
        $response->assertDontSee('User 2 Secret');
    }
}
