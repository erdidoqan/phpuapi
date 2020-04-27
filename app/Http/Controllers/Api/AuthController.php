<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\User;

class AuthController extends Controller
{
    public function user()
    {
        $user = auth()->user();
        $user->load(
            'currentSubscription',
            'paidSubscriptions',
            'card',
            'notifications',
            'subscriptionPayments',
            'watchlists.skill',
            'watchedEpisodes'
        );

        return new UserResource($user);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|alpha_num|max:30|unique:users,username,' . auth()->id(),
            'name' => 'required|max:70',
            'email' => 'required|email|max:70|unique:users,email,' . auth()->id(),
            'password' => 'nullable|min:6'
        ]);

        if ($validatedData['password'] == null) {
            unset($validatedData['password']);
        } else {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        auth()->user()->update($validatedData);

        return new UserResource(auth()->user()->fresh());
    }

    public function login(Request $request)
    {
        $client = DB::table('oauth_clients')->first();

        $request->request->add([
            'username' => $request->username,
            'password' => $request->password,
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'scope' => '',
        ]);

        return Route::dispatch(Request::create('oauth/token', 'POST'));
    }

    public function logout()
    {
        auth()->user()->token()->revoke();

        return response()->json([
            'status' => 'ok',
            'msg' => 'Çıkış yapıldı.'
        ]);
    }
}
