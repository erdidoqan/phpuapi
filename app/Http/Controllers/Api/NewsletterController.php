<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NewsletterResource;
use App\Newsletter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email'
        ]);

        return new NewsletterResource(Newsletter::firstOrCreate($validatedData));
    }
}
