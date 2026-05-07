<!DOCTYPE html>
<html>
<body style="font-family: 'Inter', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 32px 16px;">

    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0;">

        {{-- Top accent bar - blue for email verification --}}
        <div style="height: 4px; background: #2563eb;"></div>

        {{-- Header --}}
        <div style="padding: 32px 40px 24px; border-bottom: 1px solid #f1f5f9;">
            <table cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
                <tr>
                    <td style="vertical-align: middle; padding-right: 8px;">
                        <table cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
                            <tr>
                                <td style="width: 32px; height: 32px; background: #0f172a; border-radius: 8px; text-align: center; vertical-align: middle; font-size: 18px; line-height: 32px;">
                                    ⚡
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

            <p style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: #2563eb; margin: 0 0 8px;">
                Email Verification
            </p>

            <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0 0 16px; font-style: italic; text-transform: uppercase; letter-spacing: -0.02em;">
                Verify your email
            </h1>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 12px;">
                Hi <strong style="color: #0f172a;">{{ $notifiable->name }}</strong>,
            </p>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 24px;">
                Welcome to <strong style="color: #0f172a;">Bijulicar</strong> - Nepal's electric vehicle marketplace.
                Please verify your email address to activate your account and move on to document verification.
            </p>

            {{-- Step indicator --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px 20px; margin: 0 0 28px;">
                <p style="font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: #94a3b8; margin: 0 0 12px;">Your registration steps</p>
                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse: collapse;">
                    <tr>
                        {{-- Step 1 circle --}}
                        <td width="22" style="vertical-align: middle;">
                            <table cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
                                <tr>
                                    <td width="22" height="22" style="background: #2563eb; border-radius: 50%; text-align: center; vertical-align: middle; font-size: 9px; font-weight: 900; color: #ffffff; line-height: 22px; font-family: Arial, sans-serif;">
                                        1
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="vertical-align: middle; padding-left: 6px; white-space: nowrap;">
                            <span style="font-size: 10px; font-weight: 900; color: #2563eb; text-transform: uppercase; letter-spacing: 0.05em; font-family: Arial, sans-serif;">Verify Email</span>
                        </td>
                        {{-- Connector line --}}
                        <td style="vertical-align: middle; padding: 0 8px;">
                            <div style="height: 1px; background: #e2e8f0; font-size: 0; line-height: 0;">&nbsp;</div>
                        </td>
                        {{-- Step 2 circle --}}
                        <td width="22" style="vertical-align: middle;">
                            <table cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
                                <tr>
                                    <td width="22" height="22" style="background: #e2e8f0; border-radius: 50%; text-align: center; vertical-align: middle; font-size: 9px; font-weight: 900; color: #94a3b8; line-height: 22px; font-family: Arial, sans-serif;">
                                        2
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="vertical-align: middle; padding-left: 6px; white-space: nowrap;">
                            <span style="font-size: 10px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; font-family: Arial, sans-serif;">Documents</span>
                        </td>
                        {{-- Connector line --}}
                        <td style="vertical-align: middle; padding: 0 8px;">
                            <div style="height: 1px; background: #e2e8f0; font-size: 0; line-height: 0;">&nbsp;</div>
                        </td>
                        {{-- Step 3 circle --}}
                        <td width="22" style="vertical-align: middle;">
                            <table cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
                                <tr>
                                    <td width="22" height="22" style="background: #e2e8f0; border-radius: 50%; text-align: center; vertical-align: middle; font-size: 9px; font-weight: 900; color: #94a3b8; line-height: 22px; font-family: Arial, sans-serif;">
                                        3
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="vertical-align: middle; padding-left: 6px; white-space: nowrap;">
                            <span style="font-size: 10px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; font-family: Arial, sans-serif;">Approved</span>
                        </td>
                    </tr>
                </table>
            </div>

            {{-- CTA button --}}
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ $verificationUrl }}"
                   style="display: inline-block; background: #0f172a; color: #ffffff; padding: 14px 36px; border-radius: 10px; font-weight: 900; font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; text-decoration: none; font-style: italic;">
                    Verify Email Address →
                </a>
            </div>

            {{-- Expiry note --}}
            <p style="font-size: 12px; color: #94a3b8; text-align: center; margin: 0 0 24px; line-height: 1.6;">
                This link expires in <strong style="color: #475569;">60 minutes</strong>. If it expires, log in and request a new one.
            </p>

            {{-- Fallback URL --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 18px; margin: 0 0 20px;">
                <p style="font-size: 11px; color: #64748b; margin: 0 0 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em;">
                    Having trouble with the button?
                </p>
                <p style="font-size: 11px; color: #94a3b8; margin: 0; line-height: 1.6; word-break: break-all;">
                    Copy and paste this URL into your browser:<br>
                    <a href="{{ $verificationUrl }}" style="color: #2563eb;">{{ $verificationUrl }}</a>
                </p>
            </div>

            <p style="font-size: 13px; color: #94a3b8; line-height: 1.7; margin: 0;">
                If you did not create an account, you can safely ignore this email - no action is required.
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