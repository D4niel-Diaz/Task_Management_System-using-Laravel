<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Task Management System' }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f7fb;font-family:Arial,Helvetica,sans-serif;color:#172033;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f7fb;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;background:#ffffff;border-radius:18px;overflow:hidden;border:1px solid #e6eaf0;box-shadow:0 18px 50px rgba(15,23,42,.08);">
                    <tr>
                        <td style="background:#0f172a;padding:28px 32px;color:#ffffff;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <div style="display:inline-block;width:42px;height:42px;line-height:42px;text-align:center;border-radius:12px;background:#2563eb;font-size:20px;font-weight:700;">TM</div>
                                    </td>
                                    <td align="right" style="font-size:13px;color:#cbd5e1;font-weight:700;">Task Management System</td>
                                </tr>
                            </table>
                            <h1 style="margin:22px 0 0;font-size:26px;line-height:1.25;">{{ $heading ?? $title ?? 'Notification' }}</h1>
                            @isset($preheader)
                                <p style="margin:8px 0 0;color:#cbd5e1;font-size:15px;line-height:1.6;">{{ $preheader }}</p>
                            @endisset
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            @yield('content')
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px 32px;background:#f8fafc;border-top:1px solid #e6eaf0;color:#64748b;font-size:13px;line-height:1.6;">
                            This message was sent by Task Management System. If you did not expect this notification, review your account or contact your administrator.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
