<?php

namespace App\Http\Controllers\Api\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Lesson;
use App\Episode;
use Illuminate\Support\Facades\DB;
use App\Post;
use App\SubscriptionPayment;
use App\Subscription;

class DashboardController extends Controller
{
    public function index()
    {
        $this->authorize('view dashboard');

        $futurePayments = Subscription::selectRaw('id, subscription_type_id, MONTH(next_charge_date) as month')
            ->with('subscriptionType')
            ->where('next_charge_date', '>=', now()->firstOfMonth())
            ->orderBy('next_charge_date')
            ->get()
            ->groupBy('month')
            ->map(function ($payments, $month) {
                return ['month' => $month, 'sum' => $payments->sum('subscriptionType.price')];
            })
            ->values();

        return response()->json([
            'users_count' => User::count(),
            'subscribers_count' => User::has('currentSubscription')->count(),
            'lessons_count' => Lesson::count(),
            'episodes_count' => Episode::count(),
            'watched_episodes_count' => DB::table('watched_episodes')->count(),
            'posts_count' => Post::count(),
            'payments_total' => SubscriptionPayment::paid()->sum('price'),
            'future_payments' => $futurePayments,
        ]);
    }
}
