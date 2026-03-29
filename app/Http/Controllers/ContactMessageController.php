<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactMessageController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'category' => 'required|string',
        'message' => 'required|string',
    ]);

    ContactMessage::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Message sent successfully!'
    ]);
}
}
