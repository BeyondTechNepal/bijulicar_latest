<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\NewsletterEmail;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;

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

        $subscribers = Subscriber::whereNotNull('verified_at')->get();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->queue(new NewsletterEmail($request->subject, $request->body));
        }

        return back()->with('success', 'Newsletter queued for ' . $subscribers->count() . ' verified subscribers.');
    }
}
