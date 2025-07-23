<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    public function paypalPaymentLink()
    {

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $data = [
            "intent" => "CAPTURE",
            "application_context" => [
                'return_url' => route('paypal.paymentSuccess'),
                'cancel_url' => route('paypal.paymentCancel')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => '100'
                    ]
                ]
            ]
        ];
        $order = $provider->createOrder($data);

        $url = collect($order['links'])->Where('rel', 'approve')->first()['href'];


        return redirect()->away($url);
    }


    public function paypalPaymentSuccess(Request $request)
    {

        $token = $request->token;
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $order = $provider->capturePaymentOrder($token);
        
        if (isset($order['status']) && $order['status'] == 'COMPLETED') {
            session()->flash('success', "Payment was successfully");
            return redirect(route('user.add-credits'));
        }
        session()->flash('error', "Payment is Canceled");
        return redirect(route('user.add-credits'));
    }

    public function paypalPaymentCancel(Request $request)
    {
         session()->flash('error', "Payment is Canceled");
        return redirect(route('user.add-credits'));
    }
}
