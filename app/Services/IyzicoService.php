<?php

namespace App\Services;

use App\Models\CreditPackage;
use App\Models\User;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\CheckoutForm;
use Iyzipay\Model\CheckoutFormInitialize;
use Iyzipay\Model\Currency;
use Iyzipay\Model\Locale;
use Iyzipay\Options;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Iyzipay\Request\RetrieveCheckoutFormRequest;

class IyzicoService
{
    private Options $options;

    public function __construct()
    {
        $this->options = new Options();
        $this->options->setApiKey(config('services.iyzico.api_key'));
        $this->options->setSecretKey(config('services.iyzico.secret_key'));
        $this->options->setBaseUrl(config('services.iyzico.base_url'));
    }

    public function createCheckoutForm(
        User $user,
        CreditPackage $package,
        string $conversationId,
        string $callbackUrl
    ): CheckoutFormInitialize {
        $price  = (float) $package->price;
        $locale = app()->getLocale() === 'tr' ? Locale::TR : Locale::EN;

        $req = new CreateCheckoutFormInitializeRequest();
        $req->setLocale($locale);
        $req->setConversationId($conversationId);
        $req->setPrice((string) $price);
        $req->setPaidPrice((string) $price);
        $req->setCurrency(Currency::TL);
        $req->setBasketId('pkg-' . $package->id);
        $req->setPaymentGroup('PRODUCT');
        $req->setCallbackUrl($callbackUrl);
        $req->setEnabledInstallments([1, 2, 3]);

        $buyer = new Buyer();
        $buyer->setId((string) $user->id);
        $buyer->setName($user->name);
        $buyer->setSurname($user->name);
        $buyer->setGsmNumber($user->phone ?? '+905000000000');
        $buyer->setEmail($user->email);
        $buyer->setIdentityNumber('11111111111');
        $buyer->setIp(request()->ip() ?? '127.0.0.1');
        $buyer->setCity('Istanbul');
        $buyer->setCountry('Turkey');
        $buyer->setRegistrationAddress('-');
        $req->setBuyer($buyer);

        $address = new Address();
        $address->setContactName($user->name);
        $address->setCity('Istanbul');
        $address->setCountry('Turkey');
        $address->setAddress('-');
        $req->setShippingAddress($address);
        $req->setBillingAddress($address);

        $item = new BasketItem();
        $item->setId('pkg-' . $package->id);
        $item->setName($package->credits . ' Kontör Paketi');
        $item->setCategory1('Kontör');
        $item->setItemType(BasketItemType::VIRTUAL);
        $item->setPrice((string) $price);
        $req->setBasketItems([$item]);

        return CheckoutFormInitialize::create($req, $this->options);
    }

    public function retrieveCheckoutForm(string $token): CheckoutForm
    {
        $req = new RetrieveCheckoutFormRequest();
        $req->setToken($token);

        return CheckoutForm::retrieve($req, $this->options);
    }
}
