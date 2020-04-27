<?php

namespace App;

use App\Services\Payment;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $guarded = [];
    protected $dates = ['next_charge_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptionType()
    {
        return $this->belongsTo(SubscriptionType::class);
    }

    public function subscriptionPayments()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    public function scopePaid($query)
    {
        return $query->whereHas('subscriptionPayments', function ($query) {
            $query->paid();
        });
    }

    public function scopeCurrent($query)
    {
        return $query->whereHas('subscriptionPayments', function ($query) {
            $query->current();
        });
    }

    public function getExpireDateAttribute()
    {
        return $this->next_charge_date ? $this->next_charge_date->copy()->subDay() : null;
    }

    public function pay()
    {
        $conversationId = uniqid();
        $subscriptionType = $this->subscriptionType;

        $payment = new Payment();
        $payment->setConversationId($conversationId);
        $payment->setUser($this->user);
        $payment->setSubscriptionType($subscriptionType);
        $payment->setUserPaymentCard();
        $payment->pay();

        if ($payment->isSuccess()) {
            $startDate = $this->user->nextStartDate();
            $endDate = $subscriptionType->endDate($startDate);

            $this->update(['next_charge_date' => $endDate->copy()->addDay()]);
            SubscriptionPayment::create([
                'subscription_id' => $this->id,
                'paid' => 1,
                'price' => $payment->getPaidPrice(),
                'conversation_id' => $conversationId,
                'payment_id' => $payment->getPaymentId(),
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);
        }
    }
}
