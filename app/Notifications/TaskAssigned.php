<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification
{
    use Queueable;

    public function __construct(protected Task $task) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Task Assigned: ' . $this->task->title)
            ->greeting('Hello, ' . $notifiable->name . '!')
            ->line('You have been assigned a new task in the Task Management System.')
            ->line('Task: ' . $this->task->title)
            ->line('Description: ' . ($this->task->description ?? 'No description provided.'))
            ->line('Status: ' . ucfirst(str_replace('_', ' ', $this->task->status)))
            ->line('Due Date: ' . ($this->task->due_date?->format('F j, Y') ?? 'No due date'))
            ->action('View Task', route('tasks.show', $this->task->id))
            ->line('Please log in to the system to review and update this task.')
            ->salutation('Regards, Task Management System');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id'    => $this->task->id,
            'task_title' => $this->task->title,
        ];
    }
}
