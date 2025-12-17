<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function it_casts_due_date_to_carbon_instance()
    {
        $user = User::factory()->create();

        $task = Task::create([
            'title' => 'Test Task',
            'user_id' => $user->id,
            'due_date' => '2025-12-25', // Standard SQL Format
            'priority' => 'medium',
            'status' => 'pending'
        ]);

        $task->refresh();

        //Debugging if this fails, uncomment the next line
        //dd($task->due_date);

        $this->assertInstanceOf(Carbon::class, $task->due_date);
        $this->assertTrue($task->due_date->isSameDay(Carbon::parse('2025-12-25')));
        $this->assertEquals('25-12-2025', $task->due_date->format('d-m-Y'));
    }

    /** @test */
    public function it_belongs_to_a_user(){
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $task->user);
        $this->assertEquals($user->id, $task->user->id);
    }

    /** @test */
    public function it_can_have_a_category(){
        $user = User::factory()->create();

        $category = Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Development',
        ]);

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id
        ]);

        $this->assertInstanceOf(Category::class, $task->category);
        $this->assertEquals('Development', $task->category->name);
    }

    /** @test */
    public function it_can_have_no_category(){
        $task = Task::factory()->create(['category_id' => null]);

        $this->assertNull($task->category);
    }
}
