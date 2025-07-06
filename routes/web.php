<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PollResultController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserQuizController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware('SetLanguage')->group(function () {
    Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('socialite.redirect');
    Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('socialite.callback');

    Route::get('change-language/{code}', [UserQuizController::class, 'changeLanguage'])->name('change.language');

    // Route of Quiz player
    Route::get('q/{code}/player', [UserQuizController::class, 'createPlayer'])->name('create.quiz-player');
    Route::get('q/{code}', [UserQuizController::class, 'create'])->name('quiz-player');
    Route::post('q/quiz-player', [UserQuizController::class, 'store'])->name('store.quiz-player');
    Route::get('q/quiz/question', [UserQuizController::class, 'quizQuestion'])->name('quiz.question');
    Route::post('q/quiz/answer', [UserQuizController::class, 'quizAnswer'])->name('quiz.answer');
    Route::get('q/quiz/finished/{uuid}', [UserQuizController::class, 'quizResult'])->name('quiz.result');
    Route::get('q/result/{uuid}', [UserQuizController::class, 'show'])->name('show.quizResult');

    // Route of Subscrion RazorPay Payment
    Route::post('/razorpay/purchase', [RazorpayController::class, 'purchase'])->name('razorpay.purchase');
    Route::post('/razorpay/success', [RazorpayController::class, 'success'])->name('razorpay.success');
    Route::get('/razorpay/failed', [RazorpayController::class, 'failed'])->name('razorpay.failed');

    // Route of Subscrion Paypal Payment
    Route::post('paypal-purchase', [PaypalController::class, 'purchase'])->name('paypal.purchase');
    Route::get('paypal-success', [PaypalController::class, 'success'])->name('paypal.success');
    Route::get('paypal-failed', [PaypalController::class, 'failed'])->name('paypal.failed');

    // Route of Subscrion Stripe Payment
    Route::post('stripe/purchase', [StripeController::class, 'purchase'])->name('stripe.purchase');
    Route::get('stripe-success', [StripeController::class, 'success'])->name('stripe.success');
    Route::get('stripe-failed', [StripeController::class, 'failed'])->name('stripe.failed');

    // Route of Download subscription Invoice
    Route::get('invoice/{subscription}/pdf', [SubscriptionController::class, 'subscriptionInvoice'])->name('subscription.invoice');

    // Route for the landing home page
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
    Route::get('/privacy', [HomeController::class, 'policy'])->name('policy');
    Route::get('/cookie', [HomeController::class, 'cookie'])->name('cookie');

    // Route of Poll votes
    Route::get('p/{code}', [PollResultController::class, 'create'])->name('poll.create');
    Route::post('p/vote-poll', [PollResultController::class, 'store'])->name('store.poll_result');
    
    
});

include 'auth.php';
include 'upgrade.php';
