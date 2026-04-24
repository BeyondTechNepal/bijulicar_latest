<!DOCTYPE html>
<html>
<body style="font-family: 'Inter', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 32px 16px;">

    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0;">

        {{-- Top accent bar --}}
        <div style="height: 4px; background: #2563eb;"></div>

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

            <p style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: #2563eb; margin: 0 0 8px;">
                New Rental Request
            </p>

            <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0 0 16px; font-style: italic; text-transform: uppercase; letter-spacing: -0.02em;">
                Someone wants to rent your car!
            </h1>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 12px;">
                Hi <strong style="color: #0f172a;">{{ $rental->owner?->name ?? 'there' }}</strong>,
            </p>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 24px;">
                You have received a new rental booking request for your listing.
                Please review the details below and confirm or decline from your dashboard.
            </p>

            {{-- Booking details card --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 24px; margin: 0 0 24px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <tr>
                        <td style="padding: 8px 0; color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em; width: 40%;">Car</td>
                        <td style="padding: 8px 0; color: #0f172a; font-weight: 700;">{{ $rental->carDisplayName() }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #f1f5f9;">
                        <td style="padding: 8px 0; color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em;">Renter</td>
                        <td style="padding: 8px 0; color: #0f172a; font-weight: 700;">{{ $rental->renter_name }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #f1f5f9;">
                        <td style="padding: 8px 0; color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em;">Phone</td>
                        <td style="padding: 8px 0; color: #0f172a; font-weight: 700;">{{ $rental->renter_phone }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #f1f5f9;">
                        <td style="padding: 8px 0; color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em;">Pick-up</td>
                        <td style="padding: 8px 0; color: #0f172a; font-weight: 700;">{{ $rental->pickup_date->format('D, d M Y') }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #f1f5f9;">
                        <td style="padding: 8px 0; color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em;">Return</td>
                        <td style="padding: 8px 0; color: #0f172a; font-weight: 700;">{{ $rental->return_date->format('D, d M Y') }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #f1f5f9;">
                        <td style="padding: 8px 0; color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em;">Duration</td>
                        <td style="padding: 8px 0; color: #0f172a; font-weight: 700;">{{ $rental->total_days }} day{{ $rental->total_days > 1 ? 's' : '' }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #f1f5f9;">
                        <td style="padding: 8px 0; color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em;">Total</td>
                        <td style="padding: 8px 0; color: #0f172a; font-weight: 700;">{{ $rental->formattedTotalPrice() }}</td>
                    </tr>
                    @if ($rental->deposit_amount)
                    <tr style="border-top: 1px solid #f1f5f9;">
                        <td style="padding: 8px 0; color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em;">Deposit</td>
                        <td style="padding: 8px 0; color: #0f172a; font-weight: 700;">{{ $rental->formattedDeposit() }}</td>
                    </tr>
                    @endif
                    @if ($rental->notes)
                    <tr style="border-top: 1px solid #f1f5f9;">
                        <td style="padding: 8px 0; color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em;">Notes</td>
                        <td style="padding: 8px 0; color: #475569;">{{ $rental->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <p style="font-size: 13px; color: #94a3b8; line-height: 1.7; margin: 0;">
                Log in to your dashboard to confirm or decline this booking. If you have questions,
                contact us at <a href="mailto:support@bijulicar.com" style="color: #16a34a;">support@bijulicar.com</a>
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
