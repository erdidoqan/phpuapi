<?php

namespace App\Http\Controllers\Api;

use App\Conversation;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'message' => 'required',
            'recipient_id' => 'required'
        ]);

        $conversation = auth()->user()->conversation($validatedData['recipient_id']);

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => auth()->id(),
                'user_two_id' => $validatedData['recipient_id']
            ]);
        }

        $message = $conversation->messages()->create([
            'user_id' => auth()->id(),
            'message' => $validatedData['message']
        ]);

        return new MessageResource($message);
    }
}
