<!DOCTYPE html>
<html>
<body style="font-family: 'Inter', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 32px 16px;">

    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0;">

        {{-- Top accent bar --}}
        <div style="height: 4px; background: #f59e0b;"></div>

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

            <p style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: #f59e0b; margin: 0 0 8px;">
                Slot Request Update
            </p>

            <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0 0 16px; font-style: italic; text-transform: uppercase; letter-spacing: -0.02em;">
                Slot unavailable
            </h1>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 12px;">
                Hi <strong style="color: #0f172a;">{{ $customer->name }}</strong>,
            </p>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 24px;">
                Unfortunately, the EV station could not confirm your slot request for 
                <strong style="color: #0f172a;">Port #{{ $slot->slot_number }}</strong>.
            </p>

            @if ($reason)
            <div style="background: #fff7ed; border: 1px solid #fed7aa; border-radius: 12px; padding: 20px 24px; margin: 0 0 24px;">
                <p style="font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.15em; color: #f59e0b; margin: 0 0 8px;">
                    Reason from station
                </p>
                <p style="font-size: 14px; color: #78350f; line-height: 1.7; margin: 0;">
                    {{ $reason }}
                </p>
            </div>
            @endif

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 24px;">
                You can check the map for other nearby EV stations with available slots.
            </p>

            {{-- CTA --}}
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ url('/map-location') }}"
                   style="display: inline-block; background: #0f172a; color: #ffffff; padding: 14px 32px; border-radius: 10px; font-weight: 900; font-size: 11px; text-transform: uppercase; letter-spacing: 0.15em; text-decoration: none; font-style: italic;">
                    Find another station →
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