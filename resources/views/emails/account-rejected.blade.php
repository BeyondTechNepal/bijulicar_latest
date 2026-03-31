<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 24px;">
    <h2 style="color: #D85A30;">Application not approved</h2>
    <p>Hi {{ $user->name }},</p>
    <p>We reviewed your Bijulicar account application and unfortunately were unable to approve it at this time.</p>
    <div style="background:#fff4f0; border-left:4px solid #D85A30; padding:12px 16px; margin:16px 0; border-radius:4px;">
        <strong>Reason:</strong><br>
        {{ $reason }}
    </div>
    <p>If you believe this is a mistake or have corrected the issue, you are welcome to re-apply.</p>
    <p style="margin-top:32px; color:#888; font-size:13px;">Contact us at ...... if you need help.</p>
</body>
</html>