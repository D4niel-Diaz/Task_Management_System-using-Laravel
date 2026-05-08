@extends('emails.layout', [
    'title' => 'New Task Assigned',
    'heading' => 'You have a new task',
    'preheader' => 'A task has been assigned to you in Task Management System.',
])

@section('content')
<p style="margin:0 0 18px;font-size:16px;line-height:1.7;">Hello {{ $user->name }},</p>
<p style="margin:0 0 22px;font-size:16px;line-height:1.7;color:#475569;">You have been assigned a task. Review the details below and open the workspace when you are ready to update progress.</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e6eaf0;border-radius:14px;overflow:hidden;">
    <tr><td style="padding:18px 20px;background:#f8fafc;font-weight:700;font-size:17px;">{{ $task->title }}</td></tr>
    <tr><td style="padding:18px 20px;color:#475569;line-height:1.7;">{{ $task->description ?: 'No description provided.' }}</td></tr>
    <tr>
        <td style="padding:0 20px 18px;">
            <div style="font-size:14px;color:#64748b;">Status: <strong style="color:#172033;">{{ ucwords(str_replace('_', ' ', $task->status)) }}</strong></div>
            <div style="font-size:14px;color:#64748b;margin-top:6px;">Priority: <strong style="color:#172033;">{{ ucfirst($task->priority ?? 'medium') }}</strong></div>
            <div style="font-size:14px;color:#64748b;margin-top:6px;">Due date: <strong style="color:#172033;">{{ $task->due_date?->format('F j, Y') ?? 'No due date' }}</strong></div>
        </td>
    </tr>
</table>

@include('emails.partials.button', ['url' => route('tasks.show', $task->id), 'label' => 'View Task'])
@endsection
