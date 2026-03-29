<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContactDetailsController extends Controller
{
    public function index()
    {
        $contacts = ContactDetail::latest()->get();
        return view('admin.contact_detail.index', compact('contacts'));
    }

    public function create()
    {
        return view('admin.contact_detail.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'nullable|email',
            'phone_no' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'working_hours' => 'nullable|string|max:255',
        ]);

        ContactDetail::create($request->all());

        return redirect()->route('admin.contact_details.index');
    }

    public function edit(ContactDetail $contact_detail)
    {
        return view('admin.contact_detail.form', compact('contact_detail'));
    }

    public function update(Request $request, ContactDetail $contact_detail)
    {
        $request->validate([
            'email' => 'nullable|email',
            'phone_no' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'working_hours' => 'nullable|string|max:255',
        ]);

        $contact_detail->update($request->all());

        return redirect()->route('admin.contact_details.index');
    }

    public function destroy(ContactDetail $contact_detail)
    {
        $contact_detail->delete();
        return back();
    }
}
