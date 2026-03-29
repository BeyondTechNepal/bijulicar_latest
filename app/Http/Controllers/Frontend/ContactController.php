<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactBanner;
use App\Models\ContactDetail;

class ContactController extends Controller
{
    public function index()
    {
        $banner = ContactBanner::where('is_active', true)->latest()->first();

        $contact_details = ContactDetail::latest()->first();

        return view('frontend.pages.contact', compact('banner', 'contact_details'));
    }
}
