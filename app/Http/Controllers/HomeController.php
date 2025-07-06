<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Plan;
use App\Models\Quiz;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Subscription;
use App\Enums\SubscriptionStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (isset(getSetting()->enable_landing_page) && getSetting()->enable_landing_page == 0) {
            if (Auth::check() && Auth::user()->hasRole('admin')) {
                return redirect()->route('filament.admin.pages.dashboard');
            }

            if (Auth::check() && Auth::user()->hasRole('user')) {
                return redirect()->route('filament.user.pages.dashboard');
            }

            return redirect()->route('filament.auth.auth.login');
        }
        $plans = Plan::take(3)->orderBy('updated_at', 'desc')->get();
        $testimonials = Testimonial::all();
        $quizzes = Quiz::with('category')->whereNotNull('category_id')
            ->where('status', 1)->where('is_show_home', 1)
            ->where(function ($query) {
                $query->whereNull('quiz_expiry_date')
                    ->orWhere('quiz_expiry_date', '>=', Carbon::now());
            })->orderBy('id', 'desc')->get();
        $faqs = Faq::where('status', 1)->get();

        return view('home.index', compact('plans', 'testimonials', 'quizzes', 'faqs'));
    }

    public function terms()
    {
        $seeting = Setting::first();

        $terms = $seeting->terms_and_condition;

        return view('home.terms', compact('terms'));
    }

    public function policy()
    {
        $seeting = Setting::first();

        $policy = $seeting->privacy_policy;

        return view('home.policy', compact('policy'));
    }

    public function cookie()
    {
        $seeting = Setting::first();

        $cookie = $seeting->cookie_policy;

        return view('home.cookie', compact('cookie'));
    }

    // public function index()
    // {
    //     /** @var User $user */
    //     $user = auth()->user();

    //     if ($user) {
    //         $role = $user->roles()->first();

    //         if ($role && $role->name === User::ADMIN_ROLE) {
    //             return redirect()->route('filament.admin.pages.dashboard');
    //         }

    //         if ($role && $role->name === User::USER_ROLE) {
    //             return redirect()->route('filament.user.pages.dashboard');
    //         }
    //     }

    //     return redirect()->route('filament.auth.auth.login');
    // }
}
