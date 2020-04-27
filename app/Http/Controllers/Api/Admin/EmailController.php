<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Newsletter;
use App\Notifications\EmailNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        $validatedData = $request->validate([
            'subject' => 'required',
            'content' => 'required'
        ]);

        foreach (User::all() as $user) {
            $user->notify(new EmailNotification($validatedData));
        }

        foreach (Newsletter::all() as $newsletter) {
            Notification::route('mail', $newsletter->email)->notify(new EmailNotification($validatedData));
        }
    }
}
