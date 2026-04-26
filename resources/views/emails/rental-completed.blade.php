<!DOCTYPE html>
<html>
<body style="font-family: 'Inter', Arial, sans-serif; background: #f1f5f9; margin: 0; padding: 32px 16px;">

    <div style="max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0;">

        <div style="height: 4px; background: #16a34a;"></div>

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

        <div style="padding: 36px 40px;">

            <p style="font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: #16a34a; margin: 0 0 8px;">
                Rental Completed
            </p>

            <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0 0 16px; font-style: italic; text-transform: uppercase; letter-spacing: -0.02em;">
                Hope you had a great ride!
            </h1>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 12px;">
                Hi <strong style="color: #0f172a;">{{ $rental->renter_name }}</strong>,
            </p>

            <p style="font-size: 14px; color: #475569; line-height: 1.7; margin: 0 0 24px;">
                Your rental has been marked as <strong style="color: #16a34a;">completed</strong>.
                Thank you for using BijuliCar — we hope to see you again soon!
            </p>

            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px 24px; margin: 0 0 24px;">
                <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                    <tr>
                        <td style="padding: 8px 0; color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em; width: 40%;">Car</td>
                        <td style="padding: 8px 0; color: #0f172a; font-weight: 700;">{{ $rental->carDisplayName() }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #f1f5f9;">
                        <td style="padding: 8px 0; color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em;">Pick-up</td>
                        <td style="padding: 8px 0; color: #0f172a; font-weight: 700;">{{ $rental->pickup_date->format('D, d M Y') }}</td>
                    </tr>
                    <tr style="border-top: 1px solid #f1f5f9;">
                        <td style="padding: 8px 0; color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em;">Returned</td>
                        <td style="padding: 8px 0; color: #0f172a; font-weight: 700;">
                            {{ ($rental->actual_return_date ?? $rental->return_date)->format('D, d M Y') }}
                        </td>
                    </tr>
                    <tr style="border-top: 1px solid #f1f5f9;">
                        <td style="padding: 8px 0; color: #94a3b8; font-weight: 700; text-transform: uppercase; font-size: 10px; letter-spacing: 0.1em;">Total Paid</td>
                        <td style="padding: 8px 0; color: #16a34a; font-weight: 900; font-size: 15px;">{{ $rental->formattedTotalPrice() }}</td>
                    </tr>
                </table>
            </div>

            {{-- Review CTA --}}
            <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px 20px; margin: 0 0 24px;">
                <p style="font-size: 13px; color: #166534; margin: 0; font-weight: 600;">
                    ⭐ Leave a review for this rental from your
                    <a href="{{ route('buyer.rentals.show', $rental->id) }}" style="color: #16a34a;">rental dashboard</a>
                    and help other users make better decisions.
                </p>
            </div>

            <p style="font-size: 13px; color: #94a3b8; line-height: 1.7; margin: 0;">
                Any concerns? Contact us at
                <a href="mailto:support@bijulicar.com" style="color: #16a34a;">support@bijulicar.com</a>
            </p>
        </div>

        <div style="padding: 20px 40px; background: #f8fafc; border-top: 1px solid #f1f5f9;">
            <p style="font-size: 11px; color: #cbd5e1; margin: 0; text-align: center; text-transform: uppercase; letter-spacing: 0.1em;">
                © {{ date('Y') }} Bijulicar
            </p>
        </div>

    </div>

</body>
</html>
