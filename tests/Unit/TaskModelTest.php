<?php

namespace Tests\Unit;

use App\Models\Task;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class TaskModelTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function it_casts_due_date_to_carbon_object(): void
    {
        //Dummy task
        $task = new Task([
            'title' => "Test task",
            'due_date' => '2025-12-25',
        ]);

        //Check if Laravel automatically converted the string to Carbon object
        $this->assertInstanceOf(Carbon::class, $task->due_date);

        //Check if date match
        $this->assertEquals('2025-12-25', $task->due_date->format('Y--m-d'));
    }
}
