<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskSecurityTest extends TestCase
{
    use RefreshDatabase;
    #[Test()]
    public function user_cannot_update_another_users_task(): void
    {
        //Attack
        $attacker = User::factory()->create();

        //Victim
        $victim = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $victim->id,
            'title' => 'Secret Task'
        ]);

        //Act Attack try to update the victim task
        $response = $this->actingAs($attacker)->put(route('task.update', $task), [
            'title' => 'Hacked Title',
            'status' => 'completed'
        ]);

        //Return Forbidden 403 or Not found 404
        $status = $response->status();
        $this->assertTrue(in_array($status, [403, 404]), "Expected 403 or 404, got $status");

        //Verify DB not changed
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Secret Task' //Should be the old title
        ]);
    }

    #[Test()]
        public function user_cannot_delete_another_users_task(){
            $attacker = User::factory()->create();
            $victim = User::factory()->create();
            $task = Task::factory()->create(['user_id' => $victim->id]);

            $response = $this->actingAs($attacker)->delete(route('task.destroy', $task));

            $status = $response->status();
            $this->assertTrue(in_array($status, [403, 404]), "Expected 403 or 404, got $status");

            //Task should not be deleted
            $this->assertDatabaseHas('tasks', ['id' => $task->id]);
        }
}
