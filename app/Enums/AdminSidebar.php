<?php

namespace App\Enums;

enum AdminSidebar: int
{
    case TUTOR = 1;
    case CATEGORIES = 2;
    case QUIZZES = 3;
    case PLANS = 4;
    case SUBSCRIPTIONS = 5;
    case CASH_PAYMENTS = 6;
    case TRANSACTIONS = 7;
    case LANGUAGES = 8;
    case CURRENCIES = 9;
    case SETTINGS = 10;
}
