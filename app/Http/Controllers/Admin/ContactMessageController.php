<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;


class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->paginate(10);

        return view('admin.contact_messages.index', compact('messages'));
    }

    public function show($id)
{
    $message = ContactMessage::findOrFail($id);

    return view('admin.contact_messages.show', compact('message'));
}

// Mark as Read
public function markAsRead($id)
{
    $message = ContactMessage::findOrFail($id);
    $message->update(['status' => 'read']);
    return back()->with('success', 'Message marked as read.');
}

// Undo (Mark as New)
public function undoRead($id)
{
    $message = ContactMessage::findOrFail($id);
    $message->update(['status' => 'new']);
    return back()->with('success', 'Message status reverted to new.');
}

public function destroy($id)
{
    $message = ContactMessage::findOrFail($id);
    $message->delete();

    return redirect()->route('admin.contact_messages.index')
        ->with('success', 'Message deleted');
}
}
