<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>

<body
    style="margin: 0; padding: 0; background-color: #000000; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #ffffff;">

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
        style="background-color: #000000;">
        <tr>
            <td align="center" style="padding: 50px 0;">

                <table role="presentation" width="500" cellspacing="0" cellpadding="0" border="0"
                    style="background-color: #0a0a0a; border: 1px solid #1a1a1a; border-radius: 16px; padding: 40px; text-align: center;">

                    <tr>
                        <td style="padding-bottom: 30px;">
                            <span
                                style="font-size: 20px; font-weight: 900; letter-spacing: 2px; color: #ffffff;">BIJULI<span
                                    style="color: #4ade80;">CAR</span></span>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <h2 style="margin: 0 0 15px 0; font-size: 24px; font-weight: 800; color: #ffffff;">Confirm
                                your subscription</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-bottom: 30px;">
                            <p style="margin: 0; font-size: 16px; line-height: 1.6; color: #94a3b8;">
                                Hi there! You're just one step away from joining the future of mobility. Please confirm
                                your email to start receiving our latest EV news and marketplace updates.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <a href="{{ route('newsletter.verify', $subscriber->token) }}"
                                style="display: inline-block; padding: 14px 32px; background-color: #4ade80; color: #000000; font-weight: bold; font-size: 14px; text-decoration: none; border-radius: 8px; text-transform: uppercase; letter-spacing: 1px;">
                                Verify Email Address
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top: 30px;">
                            <p style="margin: 0; font-size: 12px; color: #475569;">
                                If you did not subscribe to <strong>BijuliCar.com</strong>, you can safely ignore this
                                email.
                            </p>
                        </td>
                    </tr>

                </table>

                <table role="presentation" width="500" cellspacing="0" cellpadding="0" border="0"
                    style="text-align: center; margin-top: 20px;">
                    <tr>
                        <td>
                            <p style="font-size: 11px; color: #334155; text-transform: uppercase; letter-spacing: 1px;">
                                © 2026 BijuliCar Mobility Solutions
                            </p>
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>

</html>
