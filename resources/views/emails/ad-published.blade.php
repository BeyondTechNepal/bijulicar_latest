<!DOCTYPE html>
<html>
<body style="font-family: 'Inter', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 32px 16px;">

    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0;">

        {{-- Top accent bar --}}
        <div style="height: 4px; background: #16a34a;"></div>

        {{-- Header --}}
        <div style="padding: 32px 40px 24px; border-bottom: 1px solid #f1f5f9;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 32px; height: 32px; background: #0f172a; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    ⚡
                </div>
                <span style="font-weight: 900; font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em; color: #0f172a;">
                    Bijuli<span style="color: #16a34a;">Car</span>
                </span>
            </div>
        </div>

        {{-- Body --}}
        <div style="padding: 36px 40px;">

            <p style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: #16a34a; margin: 0 0 8px;">
                Ad Published
            </p>

            <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0 0 16px; font-style: italic; text-transform: uppercase; letter-spacing: -0.02em;">
                You're live!
            </h1>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 12px;">
                Hi <strong style="color: #0f172a;">{{ $advertisement->owner->name }}</strong>,
            </p>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 24px;">
                Great news — your advertisement is now <strong style="color: #16a34a;">live</strong> on Bijulicar
                and visible to all visitors.
            </p>

            {{-- Ad details box --}}
            <div style="background: #f8fafc; border-radius: 10px; padding: 20px 24px; margin: 0 0 24px;">
                <p style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.15em; color: #94a3b8; margin: 0 0 14px;">
                    Ad Summary
                </p>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="font-size: 13px; color: #94a3b8; padding: 5px 0; width: 40%;">Title</td>
                        <td style="font-size: 13px; color: #0f172a; font-weight: 700; padding: 5px 0;">{{ $advertisement->title }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #94a3b8; padding: 5px 0;">Placement</td>
                        <td style="font-size: 13px; color: #0f172a; font-weight: 700; padding: 5px 0;">{{ $advertisement->placementLabel() }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #94a3b8; padding: 5px 0;">Tier</td>
                        <td style="font-size: 13px; color: #0f172a; font-weight: 700; padding: 5px 0;">{{ $advertisement->priorityLabel() }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #94a3b8; padding: 5px 0;">Run dates</td>
                        <td style="font-size: 13px; color: #0f172a; font-weight: 700; padding: 5px 0;">
                            {{ $advertisement->starts_at->format('M d, Y') }} – {{ $advertisement->ends_at->format('M d, Y') }}
                            ({{ $advertisement->durationDays() }} days)
                        </td>
                    </tr>
                </table>
            </div>

            {{-- Payment confirmation box --}}
            <div style="background: #f0fdf4; border-left: 4px solid #16a34a; border-radius: 8px; padding: 16px 20px; margin: 0 0 24px;">
                <p style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.15em; color: #16a34a; margin: 0 0 10px;">
                    Payment Confirmed
                </p>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="font-size: 13px; color: #166534; padding: 4px 0; width: 40%;">Amount paid</td>
                        <td style="font-size: 13px; color: #14532d; font-weight: 700; padding: 4px 0;">Rs {{ number_format($advertisement->amount_paid, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #166534; padding: 4px 0;">Method</td>
                        <td style="font-size: 13px; color: #14532d; font-weight: 700; padding: 4px 0;">
                            {{ \App\Models\Advertisement::PAYMENT_METHODS[$advertisement->payment_method] ?? $advertisement->payment_method }}
                        </td>
                    </tr>
                    <tr>
                        <td style="font-size: 13px; color: #166534; padding: 4px 0;">Date</td>
                        <td style="font-size: 13px; color: #14532d; font-weight: 700; padding: 4px 0;">{{ \Carbon\Carbon::parse($advertisement->paid_at)->format('M d, Y') }}</td>
                    </tr>
                </table>
            </div>

            {{-- CTA button --}}
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ url('/dashboard/business/advertisements') }}"
                   style="display: inline-block; background: #0f172a; color: #ffffff; padding: 14px 32px; border-radius: 10px; font-weight: 900; font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; text-decoration: none; font-style: italic;">
                    View My Ads →
                </a>
            </div>

            <p style="font-size: 13px; color: #94a3b8; line-height: 1.7; margin: 0;">
                Questions? Contact us at
                <a href="mailto:support@bijulicar.com" style="color: #16a34a;">support@bijulicar.com</a>
            </p>
        </div>

        {{-- Footer --}}
        <div style="padding: 20px 40px; background: #f8fafc; border-top: 1px solid #f1f5f9;">
            <p style="font-size: 11px; color: #cbd5e1; margin: 0; text-align: center; text-transform: uppercase; letter-spacing: 0.1em;">
                © {{ date('Y') }} Bijulicar
            </p>
        </div>
    </div>

</body>
</html>