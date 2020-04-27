<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ConversationResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = auth()->user()->conversations()->with('messages', 'userOne', 'userTwo')->get();

        return ConversationResource::collection($conversations);
    }

    public function show($id)
    {
        $conversation = auth()->user()->conversations()->with('messages.user', 'userOne', 'userTwo')->findOrFail($id);

        return new ConversationResource($conversation);
    }
}
