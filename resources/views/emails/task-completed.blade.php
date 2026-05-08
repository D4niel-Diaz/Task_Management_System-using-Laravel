@extends('emails.layout', [
    'title' => 'Task Completed',
    'heading' => 'A task was completed',
    'preheader' => 'Task progress has changed to completed.',
])

@section('content')
<p style="margin:0 0 18px;font-size:16px;line-height:1.7;">Hello {{ $user->name }},</p>
<p style="margin:0 0 22px;font-size:16px;line-height:1.7;color:#475569;">
    The task <strong>{{ $task->title }}</strong> has been marked as completed@if($actor) by {{ $actor->name }}@endif.
</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e6eaf0;border-radius:14px;overflow:hidden;">
    <tr><td style="padding:18px 20px;background:#ecfdf3;color:#166534;font-weight:700;">Completed</td></tr>
    <tr><td style="padding:18px 20px;color:#475569;line-height:1.7;">{{ $task->description ?: 'No description provided.' }}</td></tr>
</table>

@include('emails.partials.button', ['url' => route('tasks.show', $task->id), 'label' => 'Review Task'])
@endsection
