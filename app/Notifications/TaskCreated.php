<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskCreated extends Notification implements ShouldQueue
{
    use Queueable; //Handle tthe queue logic

    protected $task;

    //Reliability: Retry 3 times if Slack is down
    public $tries = 3;

    //Reliability: Wait 10 seconds before trying
    public $backoff = 10;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage())
            ->content('A new task has been created!')
            ->attachment(function($attachment){
                $attachment->title($this->task->title)
                ->content($this->task->description ?? 'No description')
                ->field('Priority', ucfirst($this->task->priority), true)
                ->field('Due Date', $this->task-> due_date ? $this->task->due_date->format('Y-m-d') : 'None', true);
            });
    }

    //Error handling: What to do if it fails 3 times
    public function failed(\Throwable $e){
        \Illuminate\Support\Facades\Log::error('Failed to send Slack notification for Task ' . $this->task->id);
    }
}
