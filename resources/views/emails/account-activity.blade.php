@extends('emails.layout', [
    'title' => 'Account Activity Alert',
    'heading' => 'Account activity alert',
    'preheader' => 'A change was made to your Task Management System account.',
])

@section('content')
<p style="margin:0 0 18px;font-size:16px;line-height:1.7;">Hello {{ $user->name }},</p>
<p style="margin:0 0 22px;font-size:16px;line-height:1.7;color:#475569;">{{ $activity }}</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e6eaf0;border-radius:14px;overflow:hidden;">
    <tr><td style="padding:14px 18px;background:#f8fafc;font-weight:700;">Activity Details</td></tr>
    @foreach($details as $label => $value)
        <tr><td style="padding:14px 18px;color:#475569;border-top:1px solid #eef2f7;">{{ ucfirst(str_replace('_', ' ', $label)) }}: <strong style="color:#172033;">{{ $value }}</strong></td></tr>
    @endforeach
</table>
@endsection
