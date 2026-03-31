<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 24px;">
    <h2 style="color: #1D9E75;">Your account has been approved!</h2>
    <p>Hi {{ $user->name }},</p>
    <p>Great news — your Bijulicar account has been reviewed and approved. You can now log in and start listing your vehicles.</p>
    <a href="{{ url('/login') }}"
       style="display:inline-block; background:#1D9E75; color:#fff; padding:12px 24px; border-radius:6px; text-decoration:none; margin-top:16px;">
        Log in to Bijulicar
    </a>
    <p style="margin-top:32px; color:#888; font-size:13px;">If you have any questions, contact us at ........</p>
</body>
</html>