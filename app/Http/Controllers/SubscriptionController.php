<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscriptionInvoice(Request $request, Subscription $subscription)
    {
        if (! $subscription) {
            return abort(404);
        }

        if ($subscription->transaction_id == null) {
            $subscription->transaction_date = $subscription->created_at;
        } else {
            $transaction = Transaction::where('id', $subscription->transaction_id)->first();
            $subscription->transaction_date = ($transaction) ? $transaction->created_at : $subscription->created_at;
        }

        $pdf = Pdf::loadView('subscription.invoice-pdf', ['subscription' => $subscription]);

        return $pdf->stream('subscription_' . $subscription->id . '.pdf');
    }
}
