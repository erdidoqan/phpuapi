<?php

namespace App;

use App\Notifications\PasswordResetNotification;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Services\Payment;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    protected $guard_name = 'api';
    protected $withCount = ['comments'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function paidSubscriptions()
    {
        return $this->subscriptions()->paid();
    }

    public function currentSubscription()
    {
        return $this->hasOne(Subscription::class)->current();
    }

    public function episodes()
    {
        return $this->hasManyThrough(Episode::class, Lesson::class);
    }

    public function watchedEpisodes()
    {
        return $this->belongsToMany(Episode::class, 'watched_episodes');
    }

    public function watchlists()
    {
        return $this->belongsToMany(Lesson::class, 'watchlists');
    }

    public function notifylists()
    {
        return $this->belongsToMany(Lesson::class, 'notifylists');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function card()
    {
        return $this->hasOne(CreditCard::class)->latest();
    }

    public function subscriptionPayments()
    {
        return $this->hasManyThrough(SubscriptionPayment::class, Subscription::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function conversations()
    {
        return Conversation::where('user_one_id', $this->id)->orWhere('user_two_id', $this->id);
    }

    public function conversation($userId)
    {
        return Conversation::where(function ($query) use ($userId) {
            $query->where('user_one_id', $this->id)->where('user_two_id', $userId);
        })
        ->orWhere(function ($query) use ($userId) {
            $query->where('user_one_id', $userId)->where('user_two_id', $this->id);
        })
        ->first();
    }

    public function scopeSearchText($query, $text)
    {
        return $query->where('name', 'like', '%' . $text . '%')
            ->orWhere('username', 'like', '%' . $text . '%')
            ->orWhere('email', 'like', '%' . $text . '%');
    }

    public function getAvatarAttribute()
    {
        $hash = md5(strtolower(trim($this->email)));

        return 'https://www.gravatar.com/avatar/' . $hash;
    }

    public function findForPassport($username)
    {
        return $this->where('email', $username)->where('active', 1)->first();
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isInstructor()
    {
        return $this->hasRole('instructor');
    }

    public function nextStartDate()
    {
        $latest = $this->subscriptionPayments()->paid()->latest('end_date')->first();

        if ($latest && $latest->end_date >= today()) {
            return $latest->end_date->copy()->addDay();
        }

        return today();
    }

    public function subscribe($subscriptionType, $paymentCard = null)
    {
        $conversationId = uniqid();

        $payment = new Payment();
        $payment->setConversationId($conversationId);
        $payment->setUser($this);
        $payment->setSubscriptionType($subscriptionType);
        $paymentCard ? $payment->setPaymentCard($paymentCard) : $payment->setUserPaymentCard();
        $payment->pay();

        if (!$payment->isSuccess()) {
            return ['success' => false, 'message' => $payment->getErrorMessage()];
        }

        $startDate = $this->nextStartDate();
        $endDate = $subscriptionType->endDate($startDate);

        $this->unsubscribe();

        $subscription = Subscription::create([
            'user_id' => $this->id,
            'subscription_type_id' => $subscriptionType->id,
            'next_charge_date' => $endDate->copy()->addDay()
        ]);

        SubscriptionPayment::create([
            'subscription_id' => $subscription->id,
            'paid' => 1,
            'price' => $payment->getPaidPrice(),
            'conversation_id' => $conversationId,
            'payment_id' => $payment->getPaymentId(),
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        if ($paymentCard) {
            CreditCard::create([
                'user_id' => $this->id,
                'card_user_key' => $payment->getCardUserKey(),
                'card_token' => $payment->getCardToken(),
                'bin_number' => $payment->getBinNumber()
            ]);
        }

        return ['success' => true];
    }

    public function unsubscribe()
    {
        $this->subscriptions()->update(['next_charge_date' => null]);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }

}
