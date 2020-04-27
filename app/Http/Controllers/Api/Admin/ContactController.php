<?php

namespace App\Http\Controllers\Api\Admin;

use App\Contact;
use App\Http\Resources\ContactResource;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactController extends Controller
{
    public function index()
    {
        $this->authorize('view students');

        $contacts = QueryBuilder::for(Contact::class)->defaultSort('-updated_at')->get();

        return ContactResource::collection($contacts);
    }

    public function destroy($id)
    {
        $post = Contact::findOrFail($id);

        $this->authorize('delete', $post);

        $post->delete();
    }
}
