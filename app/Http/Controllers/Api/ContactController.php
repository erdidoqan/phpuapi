<?php

namespace App\Http\Controllers\Api;

use App\Contact;
use App\Http\Resources\ContactResource;
use App\Notifications\ContactCompletedNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);

        $contact = Contact::create($validatedData);

        Notification::route('mail', env('MAIL_DESTEK'))
            ->notify(new ContactCompletedNotification($contact));

        return new ContactResource($contact);
    }
}
