<?php

namespace App\Http\Controllers;

use App\Actions\Subscription\CreateSubscription;
use App\Models\PaymentSetting;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Razorpay\Api\Api;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RazorpayController extends AppBaseController
{
    public function purchase(Request $request)
    {
        $plan = $request->plan;

        $razorpayPayment = getPaymentSetting();
        $api = new Api($razorpayPayment->razorpay_key, $razorpayPayment->razorpay_secret);

        $orderData = [
            'receipt' => '1',
            'amount' => $plan['payable_amount'] * 100,
            'currency' => isset($plan['currency']['code']) ? strtoupper($plan['currency']['code']) : "INR",
            'notes' => [
                'plan_id' => $plan['id'],
                'payable_amount' => $plan['payable_amount'],
                'payment_mode' => Subscription::TYPE_RAZORPAY,
            ],
        ];
        $razorpayOrder = $api->order->create($orderData);

        $data['order_id'] = $razorpayOrder->id;
        $data['payable_amount'] = $plan['payable_amount'];
        $data['currency'] = isset($plan['currency']['code']) ? strtoupper($plan['currency']['code']) : "INR";
        $data['payment_mode'] = Subscription::TYPE_RAZORPAY;
        $data['plan_id'] =  $plan['id'];

        return $this->sendResponse($data, 'Razorpay order created successfully.');
    }

    public function success(Request $request)
    {
        $razorpayPayment = getPaymentSetting();
        $api = new Api($razorpayPayment->razorpay_key, $razorpayPayment->razorpay_secret);
        $response = $api->payment->fetch($request->razorpay_payment_id);


        $planId = $response['notes']['plan_id'] ?? null;
        $plan = Plan::find($planId);

        $generatedSignature = hash_hmac(
            'sha256',
            $response['order_id'] . '|' . $request->razorpay_payment_id,
            $razorpayPayment->razorpay_secret
        );

        $transactionID = $response['id'] ?? null;


        if ($generatedSignature !== $request->razorpay_signature) {
            Notification::make()
                ->danger()
                ->title(__('Invalid signature.'))
                ->send();

            return redirect()->route('filament.user.pages.manage-subscription');
        }

        $subscriptionAmount = $response['amount'] / 100;

        DB::beginTransaction();

        try {

            $transaction = Transaction::create([
                'transaction_id' => $transactionID,
                'type' => Subscription::TYPE_RAZORPAY,
                'amount' => $subscriptionAmount,
                'status' => Transaction::SUCCESS,
                'user_id' => getLoggedInUserId(),
            ]);


            $planData = [
                'plan' => $plan ? $plan->toArray() : [],
                'user_id' => getLoggedInUserId(),
                'payment_type' => Subscription::TYPE_RAZORPAY,
                'transaction_id' => $transaction->id,
            ];


            $subscription = CreateSubscription::run($planData);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => __('messages.subscription.subscription_created_successfully'),
                'redirect' => route('filament.user.pages.manage-subscription'),
            ]);
        } catch (HttpException $ex) {
            DB::rollBack();
            throw $ex;
        }
    }


    public function failed()
    {
        return redirect()->route('filament.user.pages.manage-subscription');
    }
}
