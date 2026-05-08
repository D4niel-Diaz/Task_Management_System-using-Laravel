@extends('emails.layout', [
    'title' => 'Successful Login',
    'heading' => 'Successful login detected',
    'preheader' => 'Your account was used to sign in to Task Management System.',
])

@section('content')
<p style="margin:0 0 18px;font-size:16px;line-height:1.7;">Hello {{ $user->name }},</p>
<p style="margin:0 0 22px;font-size:16px;line-height:1.7;color:#475569;">Your account was successfully used to log in. If this was you, no action is needed.</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e6eaf0;border-radius:14px;overflow:hidden;">
    <tr><td style="padding:14px 18px;background:#f8fafc;font-weight:700;">Login Details</td></tr>
    <tr><td style="padding:14px 18px;color:#475569;">Timestamp: <strong style="color:#172033;">{{ $details['timestamp'] ?? 'Unavailable' }}</strong></td></tr>
    <tr><td style="padding:14px 18px;color:#475569;border-top:1px solid #eef2f7;">IP address: <strong style="color:#172033;">{{ $details['ip'] ?? 'Unavailable' }}</strong></td></tr>
    <tr><td style="padding:14px 18px;color:#475569;border-top:1px solid #eef2f7;">Device/browser: <strong style="color:#172033;">{{ $details['user_agent'] ?? 'Unavailable' }}</strong></td></tr>
</table>

<p style="margin:22px 0 0;font-size:15px;line-height:1.7;color:#b42318;"><strong>Security note:</strong> If you did not initiate this login, change your password immediately and contact your administrator.</p>
@endsection
