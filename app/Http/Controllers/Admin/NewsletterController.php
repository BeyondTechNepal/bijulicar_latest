<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\NewsletterEmail;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class NewsletterController extends Controller
{
    // Show form
    // Show the newsletter form
    public function index()
    {
        return view('admin.newsletter.newsletter');
    }

    // Send the newsletter
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);

        $subscriberEmails = Subscriber::whereNotNull('verified_at')
            ->pluck('email');

        // use this when email verification is added
        $userEmails = User::whereNotNull('email_verified_at')
            ->where('wants_newsletter', true)
            ->pluck('email');


        // temp non-checking for email verification, remove this when email verification is added and use the one above instead
        // $userEmails = User::where('wants_newsletter', true)
        //     ->pluck('email');

        // Merge & remove duplicates
        $emails = $subscriberEmails
            ->merge($userEmails)
            ->unique();

//             dd([
//     'subscriber_count' => $subscriberEmails->count(),
//     'user_count' => $userEmails->count(),
//     'final_list' => $emails->toArray()
// ]);

        foreach ($emails as $index => $email) {
            Mail::to($email)
            ->later(now()->addSeconds($index * 2), new NewsletterEmail($request->subject, $request->body));
}

        return back()->with('success', 'Newsletter queued for ' . $emails->count() . ' recipients.');
    }
}
