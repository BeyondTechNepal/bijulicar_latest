<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscriber;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\VerifyNewsletter;
use App\Models\User;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribers,email',
        ], [
            'email.unique' => 'This email is already subscribed!',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->first('email')]);
        }

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'error' => 'This email is already registered. Please login and enable newsletter from your account.'
        ]);
    }

        $subscriber = Subscriber::create([
            'email' => $request->email,
            'token' => Str::random(64),
        ]);

        Mail::to($subscriber->email)->send(new VerifyNewsletter($subscriber));

        return response()->json(['success' => 'Thanks for subscribing! Please check your email to verify.']);
    }

    public function verify($token)
    {
        $subscriber = Subscriber::where('token', $token)->firstOrFail();

        $subscriber->update([
            'verified_at' => now(),
            'token' => null, // clear token
        ]);

        return redirect('/')->with('success', 'Your email is now verified! You are subscribed.');
    }
}
