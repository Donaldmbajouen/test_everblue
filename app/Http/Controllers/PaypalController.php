<?php

namespace App\Http\Controllers;

use App\Actions\Subscription\CreateSubscription;
use App\Models\PaymentSetting;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\PayPal;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PaypalController extends Controller
{

    public function purchase(Request $request)
    {
        $plan = json_decode($request->plan);

        $data = [
            'user_id' => Auth::id(),
            'plan_id' => $plan->id,
        ];

        if ($plan->currency->code != null && ! in_array(strtoupper($plan->currency->code), getPayPalSupportedCurrencies())) {
            Notification::make()
                ->danger()
                ->title(__('messages.subscription.paypal_not_support_this_currency'))
                ->send();

            return redirect()->back();
        }

        session(['data' => $data]);

        $paypalSetting = getPaymentSetting();
        $mode = $paypalSetting->paypal_mode;
        $clientId = $paypalSetting->paypal_client_id;
        $clientSecret = $paypalSetting->paypal_secret;

        config([
            'paypal.mode' => $mode,
            'paypal.sandbox.client_id' => $clientId,
            'paypal.sandbox.client_secret' => $clientSecret,
            'paypal.live.client_id' => $clientId,
            'paypal.live.client_secret' => $clientSecret,
        ]);

        $provider = new PayPal();
        $provider->getAccessToken();

        $data = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => $plan->id,
                    'amount' => [
                        'value' => $plan->payable_amount,
                        'currency_code' => $plan->currency->code,
                    ],
                ],
            ],
            'application_context' => [
                'cancel_url' => route('paypal.failed') . '?error=subscription_failed',
                'return_url' => route('paypal.success'),
            ],
        ];

        $order = $provider->createOrder($data);

        return redirect($order['links'][1]['href']);
    }

    public function success(Request $request)
    {
        $data = session('data');
        $plan = Plan::find($data['plan_id']);
        $mode = PaymentSetting::first()->paypal_mode;
        $clientId = PaymentSetting::first()->paypal_client_id;
        $clientSecret = PaymentSetting::first()->paypal_secret;

        $config = [
            'mode' => $mode,
            $mode => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            ],
            'payment_action' => config('paypal.payment_action'),
            'currency' => $plan->currency->code,
            'notify_url' => config('paypal.notify_url'),
            'locale' => config('paypal.locale'),
            'validate_ssl' => config('paypal.validate_ssl'),
        ];

        config([
            'paypal.mode' => $mode,
            'paypal.sandbox.client_id' => $clientId,
            'paypal.sandbox.client_secret' => $clientSecret,
            'paypal.live.client_id' => $clientId,
            'paypal.live.client_secret' => $clientSecret,
        ]);

        $provider = new PayPal();

        $provider->getAccessToken();
        $token = $request->get('token');
        $orderInfo = $provider->showOrderDetails($token);
        $response = $provider->capturePaymentOrder($token);

        if (isset($response['purchase_units'][0]['payments']['captures'][0]['amount']['value'])) {
            $subscriptionAmount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
        }
        if (isset($response['id'])) {
            $transactionID = $response['id'];
        }

        try {

            DB::beginTransaction();

            $transaction = Transaction::create([
                'transaction_id' => $transactionID,
                'type' => Transaction::PAYPAL,
                'amount' => $subscriptionAmount,
                'status' => Transaction::SUCCESS,
                'user_id' => $data['user_id'],
                'meta' => json_encode($response),
            ]);

            $planData['plan'] = $plan->toArray();
            $planData['user_id'] = $data['user_id'];
            $planData['payment_type'] = Subscription::TYPE_PAYPAL;
            $planData['transaction_id'] = $transaction->id;

            $subscription = CreateSubscription::run($planData);

            DB::commit();

            if ($subscription) {
                Notification::make()
                    ->success()
                    ->title(__('messages.subscription.subscription_created_successfully'))
                    ->send();

                return redirect()->route('filament.user.pages.manage-subscription');
            }
        } catch (HttpException $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function failed(Request $request)
    {
        if ($request->error == 'subscription_failed') {
            $redirect = route('filament.user.pages.manage-subscription');
        }
        return view('filament.user.payment.payment-cancel', compact('redirect'));
    }
}
