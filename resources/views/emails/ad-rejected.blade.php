<!DOCTYPE html>
<html>
<body style="font-family: 'Inter', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 32px 16px;">

    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0;">

        {{-- Top accent bar --}}
        <div style="height: 4px; background: #dc2626;"></div>

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

            <p style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: #dc2626; margin: 0 0 8px;">
                Ad Update
            </p>

            <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0 0 16px; font-style: italic; text-transform: uppercase; letter-spacing: -0.02em;">
                Not approved
            </h1>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 12px;">
                Hi <strong style="color: #0f172a;">{{ $advertisement->owner->name }}</strong>,
            </p>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 24px;">
                We reviewed your advertisement <strong style="color: #0f172a;">{{ $advertisement->title }}</strong>
                and were unable to approve it at this time. Please see the reason below.
            </p>

            {{-- Rejection reason box --}}
            <div style="background: #fff5f5; border-left: 4px solid #dc2626; border-radius: 8px; padding: 16px 20px; margin: 0 0 24px;">
                <p style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.15em; color: #dc2626; margin: 0 0 6px;">
                    Reason
                </p>
                <p style="font-size: 14px; color: #7f1d1d; line-height: 1.6; margin: 0;">
                    {{ $advertisement->rejection_reason }}
                </p>
            </div>

            {{-- Ad details for reference --}}
            <div style="background: #f8fafc; border-radius: 10px; padding: 20px 24px; margin: 0 0 24px;">
                <p style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.15em; color: #94a3b8; margin: 0 0 14px;">
                    Submitted Ad
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
                </table>
            </div>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 24px;">
                You can update your submission and resubmit it from your dashboard.
            </p>

            {{-- CTA button --}}
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ url('/dashboard/business/advertisements') }}"
                   style="display: inline-block; background: #0f172a; color: #ffffff; padding: 14px 32px; border-radius: 10px; font-weight: 900; font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; text-decoration: none; font-style: italic;">
                    Edit &amp; Resubmit →
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