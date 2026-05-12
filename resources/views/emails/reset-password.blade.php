<!DOCTYPE html>
<html>
<body style="font-family: 'Inter', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 32px 16px;">

    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0;">

        {{-- Top accent bar - amber for password reset --}}
        <div style="height: 4px; background: #d97706;"></div>

        {{-- Header --}}
        <div style="padding: 32px 40px 24px; border-bottom: 1px solid #f1f5f9;">
            <table cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
                <tr>
                    <td style="vertical-align: middle; padding-right: 8px;">
                        <table cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
                            <tr>
                                <td style="vertical-align: middle; padding-right: 12px;">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="BijuliCar Logo"
                     style="height: 42px; width: auto; display: block;">
            </td>
                            </tr>
                        </table>
                    </td>
                    <td style="vertical-align: middle;">
                        <span style="font-weight: 900; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; color: #0f172a;">
                            Bijuli<span style="color: #16a34a;">Car</span>
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        {{-- Body --}}
        <div style="padding: 36px 40px;">

            <p style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: #d97706; margin: 0 0 8px;">
                Password Reset
            </p>

            <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0 0 16px; font-style: italic; text-transform: uppercase; letter-spacing: -0.02em;">
                Reset your password
            </h1>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 12px;">
                Hi <strong style="color: #0f172a;">{{ $notifiable->name }}</strong>,
            </p>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 28px;">
                We received a request to reset the password for your Bijulicar account.
                Click the button below to choose a new password. If you didn't request this, you can safely ignore this email.
            </p>

            {{-- CTA button --}}
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ $resetUrl }}"
                   style="display: inline-block; background: #0f172a; color: #ffffff; padding: 14px 36px; border-radius: 10px; font-weight: 900; font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; text-decoration: none; font-style: italic;">
                    Reset Password →
                </a>
            </div>

            {{-- Expiry note --}}
            <p style="font-size: 12px; color: #94a3b8; text-align: center; margin: 0 0 24px; line-height: 1.6;">
                This link expires in <strong style="color: #475569;">60 minutes</strong>. Request a new one from the login page if it expires.
            </p>

            {{-- Fallback URL --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 18px; margin: 0 0 20px;">
                <p style="font-size: 11px; color: #64748b; margin: 0 0 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;">
                    Having trouble with the button?
                </p>
                <p style="font-size: 11px; color: #94a3b8; margin: 0; line-height: 1.6; word-break: break-all;">
                    Copy and paste this URL into your browser:<br>
                    <a href="{{ $resetUrl }}" style="color: #d97706;">{{ $resetUrl }}</a>
                </p>
            </div>

            <p style="font-size: 13px; color: #94a3b8; line-height: 1.7; margin: 0;">
                If you did not request a password reset, no action is required. Your password will remain unchanged.
            </p>
        </div>

        {{-- Footer --}}
        <div style="padding: 20px 40px; background: #f8fafc; border-top: 1px solid #f1f5f9;">
            <p style="font-size: 11px; color: #cbd5e1; margin: 0; text-align: center; text-transform: uppercase; letter-spacing: 0.1em;">
                © {{ date('Y') }} Bijulicar &nbsp;·&nbsp;
                <a href="mailto:support@bijulicar.com" style="color: #94a3b8; text-decoration: none;">support@bijulicar.com</a>
            </p>
        </div>
    </div>

</body>
</html>