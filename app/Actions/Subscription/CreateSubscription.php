<?php

namespace App\Actions\Subscription;

use App\Enums\PlanFrequency;
use App\Enums\SubscriptionStatus;
use App\Mail\AdminManualPaymentMail;
use App\Mail\ManualPaymentGuideMail;
use App\Mail\SubscriptionPaymentSuccessMail;
use App\Models\PaymentSetting;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateSubscription
{
    use AsAction;

    public function handle(array $data)
    {
        try {
            DB::beginTransaction();

            $plan = $data['plan'];
            $notes = $data['notes'] ?? null;
            $attachment = $data['attachment'] ?? null;
            $paymentType = $data['payment_type'] ?? null;
            $transactionId = $data['transaction_id'] ?? null;
            $trialDays = $data['trial_days'] ?? $plan['trial_days'];

            $subscriptionData = [
                'user_id' => $data['user_id'],
                'plan_id' => $plan['id'],
                'transaction_id' => $transactionId,
                'plan_amount' => $plan['price'],
                'payable_amount' => $plan['price'],
                'plan_frequency' => $plan['frequency'],
                'starts_at' => Carbon::now(),
                'ends_at' => Carbon::now()->addMonth()->endOfDay(),
                'status' => SubscriptionStatus::PENDING->value,
                'notes' => $notes,
                'payment_type' => $paymentType,
            ];
            if ($trialDays != null && $trialDays > 0) {
                $subscriptionData['ends_at'] = Carbon::now()->addDays($plan['trial_days'])->endOfDay();
                $subscriptionData['trial_ends_at'] = Carbon::now()->addDays($trialDays)->endOfDay();
            } else {
                if ($plan['frequency'] == PlanFrequency::MONTHLY->value) {
                    $subscriptionData['ends_at'] = Carbon::now()->addMonth()->endOfDay();
                } elseif ($plan['frequency'] == PlanFrequency::WEEKLY->value) {
                    $subscriptionData['ends_at'] = Carbon::now()->addWeek()->endOfDay();
                } elseif ($plan['frequency'] == PlanFrequency::YEARLY->value) {
                    $subscriptionData['ends_at'] = Carbon::now()->addYear()->endOfDay();
                }
            }

            if ($paymentType == Subscription::TYPE_FREE) {
                $subscriptionData['payable_amount'] = null;
                $subscriptionData['status'] = SubscriptionStatus::ACTIVE->value;
            }

            $currentSubscription = GetCurrentSubscription::run();

            if (!empty($currentSubscription)) {
                $price = $subscriptionData['payable_amount'] - $currentSubscription['remaining_balance'];
                if ($price <= 0) {
                    $subscriptionData['payment_type'] = $currentSubscription['payment_type'];
                    $subscriptionData['payable_amount'] = $price > 0 ? $price : 0;
                    $subscriptionData['status'] = SubscriptionStatus::ACTIVE->value;
                } else {
                    $subscriptionData['payable_amount'] = $price > 0 ? $price : 0;
                }
            }

            if ($paymentType != null) {
                if ($paymentType == Subscription::TYPE_RAZORPAY || $paymentType == Subscription::TYPE_PAYPAL || $paymentType == Subscription::TYPE_STRIPE) {
                    $subscriptionData['status'] = SubscriptionStatus::ACTIVE->value;
                }
            }

            $subscription = Subscription::create($subscriptionData);

            // Inactive old subscription
            if ($subscription->status == SubscriptionStatus::ACTIVE->value) {
                Subscription::where('user_id', $subscription->user_id)
                    ->whereNot('id', $subscription->id)
                    ->whereIn('status', [SubscriptionStatus::ACTIVE->value])
                    ->update(['status' => SubscriptionStatus::INACTIVE->value]);
            }

            if ($attachment != null && !empty($attachment)) {
                $firstValue = array_shift($attachment);
                $subscription->addMedia($firstValue)->toMediaCollection(Subscription::ATTACHMENT);
            }

            DB::commit();

            // Send Email
            $manualPaymentGuide = PaymentSetting::first()->manual_payment_guide ?? null;
            $user = $subscription->user;
            $adminMailData = [
                'super_admin_msg' => __(":name created request for payment of :price", [
                    'name' => $user->name,
                    'price' => $subscription->plan->currency->symbol  . ' ' . $subscription->payable_amount
                ]),
                'attachment' => $subscription->getFirstMedia(Subscription::ATTACHMENT) ?? '',
                'notes' => $subscription->notes ?? '',
                'id' => $subscription->id,
            ];
            if ($paymentType != null && $paymentType == Subscription::TYPE_MANUALLY) {
                Mail::to($user->email)
                    ->send(new ManualPaymentGuideMail($manualPaymentGuide, $user));
                $email = getSetting() ? getSetting()->email : null;
                Mail::to($email)
                    ->send(new AdminManualPaymentMail($adminMailData, $email));
            }

            // Send Email Razorpay, paypal Payment Success
            if ($paymentType != null) {
                if ($paymentType == Subscription::TYPE_RAZORPAY || $paymentType == Subscription::TYPE_PAYPAL || $paymentType == Subscription::TYPE_STRIPE) {
                    $successData = [
                        'name' => $user->name,
                        'planName' => $subscription->plan->name,
                    ];
                    Mail::to($user->email)->send(new SubscriptionPaymentSuccessMail($successData));
                }
            }

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
