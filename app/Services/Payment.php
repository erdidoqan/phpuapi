<?php

namespace App\Services;

use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Currency;
use Iyzipay\Model\Locale;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Model\PaymentChannel;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Options;
use Iyzipay\Request\CreatePaymentRequest;

class Payment
{
    private $conversationId;
    private $user;
    private $subscriptionType;
    private $installment = 1;
    private $paymentCard;
    private $options;
    private $response;

    public function __construct()
    {
        $options = new Options();
        $options->setBaseUrl(env('IYZIPAY_BASE_URL'));
        $options->setApiKey(env('IYZIPAY_API_KEY'));
        $options->setSecretKey(env('IYZIPAY_SECRET_KEY'));
        $this->options = $options;
    }

    public function setConversationId($conversationId)
    {
        $this->conversationId = $conversationId;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function setSubscriptionType($subscriptionType)
    {
        $this->subscriptionType = $subscriptionType;
    }

    public function setInstallment($installment)
    {
        $this->installment = $installment;
    }

    public function setPaymentCard($data)
    {
        $paymentCard = new PaymentCard();
        $paymentCard->setCardHolderName($data['card_holder_name']);
        $paymentCard->setCardNumber($data['card_number']);
        $paymentCard->setExpireMonth($data['expire_month']);
        $paymentCard->setExpireYear('20' . $data['expire_year']);
        $paymentCard->setCvc($data['cvc']);
        $paymentCard->setRegisterCard(1);

        $this->paymentCard = $paymentCard;
    }

    public function setUserPaymentCard()
    {
        $paymentCard = new PaymentCard();
        $paymentCard->setCardUserKey($this->user->card->card_user_key);
        $paymentCard->setCardToken($this->user->card->card_token);

        $this->paymentCard = $paymentCard;
    }

    public function pay()
    {
        $request = $this->createRequest();
        $buyer = $this->createBuyer();
        $address = $this->createAddress();
        $basketItem = $this->createBasketItem();

        $request->setBuyer($buyer);
        $request->setPaymentCard($this->paymentCard);
        $request->setBillingAddress($address);
        $request->setBasketItems([$basketItem]);

        $paidPrice = $this->subscriptionType->price;

        $request->setPrice($paidPrice);
        $request->setPaidPrice($paidPrice);
        $basketItem->setPrice($paidPrice);

        $this->response = \Iyzipay\Model\Payment::create($request, $this->options);
    }

    public function isSuccess()
    {
        return $this->response->getStatus() == 'success';
    }

    public function getErrorMessage()
    {
        return $this->response->getErrorMessage();
    }

    public function getPaymentId()
    {
        return $this->response->getPaymentId();
    }

    public function getCardUserKey()
    {
        return $this->response->getCardUserKey();
    }

    public function getCardToken()
    {
        return $this->response->getCardToken();
    }

    public function getBinNumber()
    {
        return $this->response->getBinNumber();
    }

    public function getPaidPrice()
    {
        return $this->response->getPaidPrice();
    }

    private function createRequest()
    {
        $request = new CreatePaymentRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId($this->conversationId);
        $request->setCurrency(Currency::TL);
        $request->setInstallment(1);
        $request->setPaymentChannel(PaymentChannel::WEB);
        $request->setPaymentGroup(PaymentGroup::SUBSCRIPTION);

        return $request;
    }

    private function createBuyer()
    {
        $buyer = new Buyer();
        $buyer->setId($this->user->id);
        $buyer->setName($this->user->name);
        $buyer->setSurname('guest');
        $buyer->setEmail($this->user->email);
        $buyer->setIdentityNumber('74300864791');
        $buyer->setRegistrationAddress('phpuzem');
        $buyer->setIp('85.34.78.112');
        $buyer->setCity('Istanbul');
        $buyer->setCountry('Turkey');

        return $buyer;
    }

    private function createAddress()
    {
        $address = new Address();
        $address->setContactName($this->user->name);
        $address->setCity("Istanbul");
        $address->setCountry("Turkey");
        $address->setAddress("Istanbul");

        return $address;
    }

    private function createBasketItem()
    {
        $basketItem = new BasketItem();
        $basketItem->setId($this->subscriptionType->slug);
        $basketItem->setName($this->subscriptionType->name);
        $basketItem->setCategory1($this->subscriptionType->name);
        $basketItem->setItemType(BasketItemType::VIRTUAL);

        return $basketItem;
    }
}
