<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\UserResource;
use Spatie\QueryBuilder\Filter;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('view users');

        $users = QueryBuilder::for(User::class)
            ->defaultSort('-updated_at')
            ->allowedFilters(Filter::scope('search_text'))
            ->allowedIncludes('roles')
            ->with('permissions')
            ->paginate();

        return UserResource::collection($users);
    }

    public function show($id)
    {
        $this->authorize('view users');

        $user = User::with('roles')->findOrFail($id);

        return new UserResource($user);
    }

    public function store(Request $request)
    {
        $this->authorize('create users');

        $validatedData = $request->validate([
            'username' => 'required|alpha_num|unique:users|max:30',
            'name' => 'required|max:70',
            'email' => 'required|max:70|unique:users|email',
            'password' => 'required|min:6',
            'active' => 'required|in:0,1',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = User::create($validatedData);
        $user->roles()->sync($validatedData['roles']);

        return new UserResource($user);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update users');

        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'username' => 'required|alpha_num|max:30|unique:users,username,' . $user->id,
            'name' => 'required|max:70',
            'email' => 'required|max:70|email|unique:users,email,' . $user->id,
            'password' => 'string|min:6',
            'active' => 'required|in:0,1',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id'
        ]);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }
        $user->update($validatedData);
        $user->roles()->sync($validatedData['roles']);

        return new UserResource($user);
    }

    public function destroy($id)
    {
        $this->authorize('delete users');

        $user = User::findOrFail($id);

        if ($user->posts()->count() > 0) {
            return response()->json(['message' => 'Bu kullanıcının yazısı var.'], 403);
        }

        if ($user->subscriptions()->count() > 0) {
            return response()->json(['message' => 'Bu kullanıcının aboneliği var.'], 403);
        }

        if ($user->threads()->count() > 0) {
            return response()->json(['message' => 'Bu kullanıcının forumu var.'], 403);
        }

        if ($user->comments()->count() > 0) {
            return response()->json(['message' => 'Bu kullanıcının yorumu var.'], 403);
        }

        if ($user->conversations()->count() > 0) {
            return response()->json(['message' => 'Bu kullanıcının özel mesajı var.'], 403);
        }

        if ($user->messages()->count() > 0) {
            return response()->json(['message' => 'Bu kullanıcının özel mesajı var.'], 403);
        }

        $user->notifylists()->detach();

        $user->delete();
    }
}
