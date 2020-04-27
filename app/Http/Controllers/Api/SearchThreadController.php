<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ThreadResource;
use App\Thread;

class SearchThreadController extends Controller
{
    public function search(Request $request)
    {
        $threads = Thread::search('*' . request('search') . '*')
            ->with(['user', 'category'])
            ->orderBy('id', 'desc');

        if ($request->filled('categories')) {
            $threads->whereIn('category_id', $request->input('categories'));
        }
        if ($request->filled('solved')) {
            $request->input('solved')
                ? $threads->whereExists('best_comment_id')
                : $threads->whereNotExists('best_comment_id');
        }

        return ThreadResource::collection($threads->paginate());
    }
}
