<?php

namespace App\Http\Controllers\Api;

use App\CreditCard;
use Illuminate\Http\Request;
use Iyzipay\Model\Card;
use Iyzipay\Model\CardInformation;
use Iyzipay\Model\Locale;
use Iyzipay\Options;
use Iyzipay\Request\CreateCardRequest;

class CreditCardController
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'card_holder_name' => 'required',
            'card_number' => 'required|digits:16',
            'expire_month' => 'required|digits:2',
            'expire_year' => 'required|digits:2',
        ]);

        $request = new CreateCardRequest();
        $request->setLocale(Locale::TR);
        $request->setEmail(auth()->user()->email);
        $cardInformation = new CardInformation();
        $cardInformation->setCardAlias($validatedData['card_holder_name']);
        $cardInformation->setCardHolderName($validatedData['card_holder_name']);
        $cardInformation->setCardNumber($validatedData['card_number']);
        $cardInformation->setExpireMonth($validatedData['expire_month']);
        $cardInformation->setExpireYear($validatedData['expire_year']);
        $request->setCard($cardInformation);

        $options = new Options();
        $options->setBaseUrl(env('IYZIPAY_BASE_URL'));
        $options->setApiKey(env('IYZIPAY_API_KEY'));
        $options->setSecretKey(env('IYZIPAY_SECRET_KEY'));

        $card = Card::create($request, $options);

        if ($card->getStatus() != 'success') {
            return response()->json(['errors' => ['card' => [$card->getErrorMessage()]]], 422);
        }

        CreditCard::create([
            'user_id' => auth()->id(),
            'card_user_key' => $card->getCardUserKey(),
            'card_token' => $card->getCardToken(),
            'bin_number' => $card->getBinNumber()
        ]);
    }
}
