<?php

namespace App\Services\Payments;

use App\Services\PaymentServices;
use Exception;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class PaypalService implements PaymentServices
{
    private $apiContext;
    private $secret;
    private $clientId;

    public function __construct()
    {
        if (config('paypal.settings.mode') == 'live') {
            $this->clientId = config('paypal.live.client_id');
            $this->secret = config('paypal.live.client_secret');
        } else {
            $this->clientId = config('paypal.sandbox.client_id');
            $this->secret = config('paypal.sandbox.client_secret');
        }
        $this->apiContext = new ApiContext(new OAuthTokenCredential($this->clientId, $this->secret));
        $this->apiContext->setConfig(config('paypal.settings'));
    }

    public function createPayment($data)
    {
        // dd('ok');
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $item1 = new Item();
        $item1->setName($data['name'])
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setDescription($data['desc'])
            ->setPrice($data['price']);
        $itemList = new ItemList();
        $itemList->setItems([$item1]);

        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($data['price']);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Buying package from mujib");

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(config('paypal.PAY_SUCCESS'))
            ->setCancelUrl(config('paypal.PAY_FAIL'));


        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->apiContext);
        } catch (PayPalConnectionException $ex) {
            throw new Exception($ex->getMessage());
        }

        return $payment->getApprovalLink();
    }

    public function cancel()
    {
        return 'Your payment is canceled. You can create cancel page here.';
    }

    public function success($data)
    {
        // dd($data);
        $paymentId = $data['paymentId'];
        $token = $data['token'];
        $payerID = $data['payerID'];
        if (empty($paymentId) and empty($token)) {
            return response()->json(['message' => 'Your payment failed .'], 500);
        }

        $payment = Payment::get($paymentId, $this->apiContext);

        $execution = new paymentExecution();
        $execution->setPayerId($payerID);

        try {
            $result = $payment->execute($execution, $this->apiContext);
            if ($result->getState() == 'approved') {
                return $result;
            }
            return 'payment failed';
        } catch (PayPalConnectionException $ex) {
            throw new Exception($ex->getMessage());
        }
    }
}
