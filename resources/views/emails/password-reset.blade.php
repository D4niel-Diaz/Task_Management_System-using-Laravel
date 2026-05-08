@extends('emails.layout', [
    'title' => 'Password Reset',
    'heading' => 'Reset your password',
    'preheader' => 'Use the secure link below to reset your password.',
])

@section('content')
<p style="margin:0 0 18px;font-size:16px;line-height:1.7;">Hello {{ $user->name }},</p>
<p style="margin:0 0 22px;font-size:16px;line-height:1.7;color:#475569;">A password reset was requested for your account. This link is time-sensitive. If you did not request this, you can safely ignore this email.</p>

@include('emails.partials.button', ['url' => $resetUrl, 'label' => 'Reset Password'])

<p style="margin:0;font-size:13px;line-height:1.7;color:#64748b;">If the button does not work, copy and paste this URL into your browser:<br>{{ $resetUrl }}</p>
@endsection
