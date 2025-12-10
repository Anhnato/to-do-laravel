<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class TaskCreated extends Notification
{
    use Queueable;

    protected $task;

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
    public function via($notifiable): array
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
                ->field('Priority', ucfirst($this->task->prority), true)
                ->field('Due Date', $this->task-> due_date ? $this->task->due_date->format('Y-m-d') : 'None', true);
            });
    }
}
