<?php

namespace App\Http\Controllers\Api\Admin;

use App\Contact;
use App\Http\Resources\ContactResource;
use App\Http\Resources\NewsletterResource;
use App\Newsletter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NewsletterController extends Controller
{
    public function index()
    {
        $this->authorize('view students');

        $contacts = QueryBuilder::for(Newsletter::class)->defaultSort('-updated_at')->get();

        return NewsletterResource::collection($contacts);
    }

    public function destroy($id)
    {
        $post = Newsletter::findOrFail($id);

        $this->authorize('delete', $post);

        $post->delete();
    }
}
