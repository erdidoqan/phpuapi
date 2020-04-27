<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function destroy($notificationId)
    {
        auth()->user()->notifications()->where('id', $notificationId)->delete();
    }
}
