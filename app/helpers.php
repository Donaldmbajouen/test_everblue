<?php

use Carbon\Carbon;
use App\Models\Poll;
use App\Models\Quiz;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Question;
use App\Models\UserQuiz;
use App\Models\UserSetting;
use Illuminate\Support\Str;
use App\Models\Subscription;
use Smalot\PdfParser\Parser;
use App\Models\PaymentSetting;
use App\Models\QuestionAnswer;
use PhpOffice\PhpWord\IOFactory;
use App\Enums\SubscriptionStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\Element\TextRun;
use Illuminate\Support\Facades\Session;

if (! function_exists('pdfToText')) {
    function pdfToText($filePath)
    {
        $text = '';
        $tempFilePath = null;
        try {
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
            $content = file_get_contents($filePath, false, $context);
            if (! $content) {
                return '';
            }
            $fileName = Str::random(6) . basename($filePath);
            $tempFilePath = public_path('uploads/temp-file/' . $fileName);

            $res = file_put_contents($tempFilePath, $content);
            if (! $res) {
                return '';
            }

            $parser = new Parser;
            $pdf = $parser->parseFile($tempFilePath);

            $text = $pdf->getText();

            // unlink($tempFilePath);
        } catch (\Exception $e) {
            // unlink($tempFilePath);
            Log::error($e->getMessage());
        }

        return $text;
    }
}

if (! function_exists('docxToText')) {
    function docxToText(string $filePath): string
    {
        $text = '';
        $tempFilePath = null;
        try {
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
            $content = file_get_contents($filePath, false, $context);
            if (! $content) {
                return '';
            }
            $fileName = Str::random(6) . basename($filePath);
            $tempFilePath = public_path('uploads/temp-file/' . $fileName);
            $res = file_put_contents($tempFilePath, $content);
            if (! $res) {
                return '';
            }
            $phpWord = IOFactory::load($tempFilePath);
            if ($phpWord !== null) {
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        $elementText = extractTextFromElement($element);
                        $text .= $elementText;
                    }
                }

                // unlink($tempFilePath);
            }
        } catch (\Exception $e) {
            // unlink($tempFilePath);
            Log::error($e->getMessage());
        }

        return $text;
    }
}

if (! function_exists('extractTextFromElement')) {
    function extractTextFromElement($element)
    {
        $text = '';

        if ($element instanceof TextRun) {
            foreach ($element->getElements() as $textElement) {
                $elementText = extractTextFromElement($textElement);

                if (is_string($elementText)) {
                    $text .= $elementText;
                }
            }
        } elseif (method_exists($element, 'getText')) {
            $elementText = $element->getText();

            if (is_string($elementText)) {
                $text .= $elementText;
            }
        }

        return $text;
    }
}

if (! function_exists('generateUniqueCode')) {
    function generateUniqueCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Quiz::where('unique_code', $code)->exists());

        return $code;
    }
}

if (! function_exists('generateUniqueUUID')) {
    function generateUniqueUUID()
    {
        do {
            $code = substr(Str::uuid()->toString(), 0, 16);
        } while (UserQuiz::where('uuid', $code)->exists());

        return $code;
    }
}

if (! function_exists('getQuestionCount')) {
    function getQuestionCount($record)
    {
        $results = json_decode($record->result, true);
        if (isset($results['total_unanswered'])) {
            $totalQuestions = (int) $results['total_question'];
            $unAns = (int) $results['total_unanswered'];
            return $totalQuestions > 0 ? "{$totalQuestions} / {$unAns}" : '0/0';
        }

        $quizId = $record->quiz_id;
        $userQuizId = $record->id;

        $answeredQuestionsCount = QuestionAnswer::where('quiz_user_id', $userQuizId)
            ->distinct('question_id')
            ->count('question_id');

        $totalQuestionsCount = Question::where('quiz_id', $quizId)
            ->count();

        return $totalQuestionsCount > 0 ? "{$totalQuestionsCount} / {$answeredQuestionsCount}" : '0/0';
    }
}

if (! function_exists('calculateAnswerTime')) {
    function calculateAnswerTime($questionAnswer, $userQuiz)
    {
        $previousAnswer = $userQuiz->questionAnswers()
            ->where('id', '<', $questionAnswer->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($previousAnswer) {
            if ($questionAnswer->completed_at) {
                $answerTime = Carbon::parse($questionAnswer->completed_at)->diffInSeconds(Carbon::parse($previousAnswer->completed_at));
            } else {
                $answerTime = Carbon::parse($questionAnswer->created_at)->diffInSeconds(Carbon::parse($previousAnswer->created_at));
            }
        } else {
            if ($questionAnswer->completed_at) {
                $answerTime = Carbon::parse($questionAnswer->completed_at)->diffInSeconds(Carbon::parse($userQuiz->started_at));
            } else {
                $answerTime = Carbon::parse($questionAnswer->created_at)->diffInSeconds(Carbon::parse($userQuiz->started_at));
            }
        }

        $hours = floor($answerTime / 3600);
        $minutes = floor(($answerTime % 3600) / 60);
        $seconds = $answerTime % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm %ds', $hours, $minutes, $seconds);
        } elseif ($minutes > 0) {
            return sprintf('%dm %ds', $minutes, $seconds);
        }

        return sprintf('%ds', $seconds);
    }
}

if (! function_exists('getAppName')) {
    function getAppName()
    {
        try {
            $record = getSetting() ?? null;

            return ! empty($record) && ! empty($record->app_name) ? $record->app_name : config('app.name');
        } catch (\Exception $e) {
            return config('app.name');
        }
    }
}

if (! function_exists('getFaviconUrl')) {
    function getFaviconUrl()
    {
        $record = getSetting() ?? null;

        return ! empty($record) && ! empty($record->favicon) ? $record->favicon : null;
    }
}

if (! function_exists('getAppLogo')) {
    function getAppLogo()
    {
        $record = getSetting() ?? null;

        $logo = ! empty($record) && ! empty($record->app_logo) ? $record->app_logo : null;

        if (! empty($logo)) {
            return $logo;
        }

        return asset('images/logo-ai.png');
    }
}

if (! function_exists('getLoginPageBg')) {
    function getLoginPageBg()
    {
        $record = getSetting() ?? null;

        if ($record && $record->login_page_img) {
            return $record->login_page_img;
        }

        return asset('images/login-page-bg.jpg');
    }
}

if (! function_exists('getSetting')) {
    function getSetting()
    {
        try {
            static $setting;

            if (! empty($setting)) {
                return $setting;
            }
            $setting = Setting::first();

            return $setting;
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (! function_exists('getPaymentSetting')) {
    function getPaymentSetting()
    {
        return PaymentSetting::first();
    }
}

if (! function_exists('getPayPalSupportedCurrencies')) {
    function getPayPalSupportedCurrencies()
    {
        return [
            'AUD',
            'BRL',
            'CAD',
            'CNY',
            'CZK',
            'DKK',
            'EUR',
            'HKD',
            'HUF',
            'ILS',
            'JPY',
            'MYR',
            'MXN',
            'TWD',
            'NZD',
            'NOK',
            'PHP',
            'PLN',
            'GBP',
            'RUB',
            'SGD',
            'SEK',
            'CHF',
            'THB',
            'USD',
        ];
    }
}

if (! function_exists('getLoggedInUserId')) {
    function getLoggedInUserId()
    {
        return Auth::id();
    }
}

if (! function_exists('zeroDecimalCurrencies')) {
    function zeroDecimalCurrencies()
    {
        return [
            'BIF',
            'CLP',
            'DJF',
            'GNF',
            'JPY',
            'KMF',
            'KRW',
            'MGA',
            'PYG',
            'RWF',
            'UGX',
            'VND',
            'VUV',
            'XAF',
            'XOF',
            'XPF',
        ];
    }
}

if (! function_exists('removeCommaFromNumbers')) {
    function removeCommaFromNumbers($number)
    {
        return (gettype($number) == 'string' && ! empty($number)) ? str_replace(',', '', $number) : $number;
    }
}

if (! function_exists('generatePollUniqueCode')) {

    function generatePollUniqueCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Poll::where('unique_code', $code)->exists());

        return $code;
    }
}

if (! function_exists('getOption')) {
    function getOption(): array
    {
        return [
            'option1',
            'option2',
            'option3',
            'option4',
        ];
    }
}

if (! function_exists('TotalSpendTime')) {
    function TotalSpendTime($userQuiz)
    {
        $totalTime = 0;

        $questionAnswers = $userQuiz->questionAnswers()
            ->orderBy('created_at')
            ->get();

        if ($questionAnswers->isEmpty()) {
            return '0s';
        }
        $start = \Carbon\Carbon::parse($userQuiz->started_at);

        if ($userQuiz->completed_at === null) {
            $end = \Carbon\Carbon::parse($questionAnswers->last()->created_at);
            $totalTime = $end->diffInSeconds($start);
        } else {
            $end = \Carbon\Carbon::parse($questionAnswers->last()->completed_at);
            $totalTime = $end->diffInSeconds($start);
        }

        $hours = floor($totalTime / 3600);
        $minutes = floor(($totalTime % 3600) / 60);
        $seconds = $totalTime % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm %ds', $hours, $minutes, $seconds);
        } elseif ($minutes > 0) {
            return sprintf('%dm %ds', $minutes, $seconds);
        }

        return sprintf('%ds', $seconds);
    }
}

// if (! function_exists('TotalSpendTime')) {
//     function TotalSpendTime($userQuiz)
//     {
//         $totalTime = 0;

//         $questionAnswers = $userQuiz->questionAnswers()
//             ->orderBy('created_at')
//             ->get();

//         if ($questionAnswers->isEmpty()) {
//             return '0s';
//         }

//         $firstAnswer = $questionAnswers->first();
//         $totalTime += Carbon::parse($firstAnswer->created_at)->diffInSeconds(Carbon::parse($userQuiz->started_at));

//         for ($i = 1; $i < $questionAnswers->count(); $i++) {
//             $currentAnswer = $questionAnswers[$i];
//             $previousAnswer = $questionAnswers[$i - 1];
//             $totalTime += Carbon::parse($currentAnswer->created_at)->diffInSeconds(Carbon::parse($previousAnswer->created_at));
//         }

//         $hours = floor($totalTime / 3600);
//         $minutes = floor(($totalTime % 3600) / 60);
//         $seconds = $totalTime % 60;

//         if ($hours > 0) {
//             return sprintf('%dh %dm %ds', $hours, $minutes, $seconds);
//         } elseif ($minutes > 0) {
//             return sprintf('%dm %ds', $minutes, $seconds);
//         }

//         return sprintf('%ds', $seconds);
//     }
// }

if (! function_exists('getTabType')) {
    function getTabType()
    {
        $previousUrl = URL::previous();
        parse_str(parse_url($previousUrl)['query'] ?? '', $queryParams);
        $tab = $queryParams['tab'] ?? null;

        $tabType = [
            '-subject-tab' => Quiz::SUBJECT_TYPE,
            '-text-tab' => Quiz::TEXT_TYPE,
            '-url-tab' => Quiz::URL_TYPE,
            '-upload-tab' => Quiz::UPLOAD_TYPE,
        ];

        return $tabType[$tab] ?? Quiz::TEXT_TYPE;
    }
}

if (! function_exists('getTimeFormat')) {
    function getTimeFormat($seconds)
    {
        if ($seconds < 60) {
            $time = $seconds . ' ' . __('messages.common.seconds');
        } elseif ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;
            $time = $minutes . ($remainingSeconds ? ':' . $remainingSeconds : null) . ' ' . __('messages.common.minute');
        } else {
            $hours = floor($seconds / 3600);
            $remainingMinutes = floor(($seconds % 3600) / 60);
            $time = $hours . ($remainingMinutes ? ':' . $remainingMinutes : null) . ' ' . __('messages.common.hour');
        }

        return $time;
    }
}

if (! function_exists('getAllLanguages')) {
    function getAllLanguages()
    {
        $languages = Language::where('is_active', 1)->get();
        $languageList = [];
        foreach ($languages as $language) {
            $languageList[$language->code] = $language->name;
        }

        return $languageList;
    }
}

if (! function_exists('getAllLanguageFlags')) {
    function getAllLanguageFlags()
    {
        return [
            'ar' => asset('images/flags/arabic.svg'),
            'en' => asset('images/flags/english.png'),
            'fr' => asset('images/flags/france.png'),
            'de' => asset('images/flags/german.png'),
            'es' => asset('images/flags/spain.png'),
            'pt' => asset('images/flags/portuguese.png'),
            'it' => asset('images/flags/italian.png'),
            'ru' => asset('images/flags/russian.png'),
            'tr' => asset('images/flags/turkish.png'),
            'zh' => asset('images/flags/china.png'),
            'vi' => asset('images/flags/vietnamese.png'),
        ];
    }
}

if (! function_exists('getActiveLanguage')) {
    function getActiveLanguage()
    {
        $languageCode = 'en';
        if (Session::has('locale')) {
            $languageCode = Session::get('locale');
        }

        $language = getAllLanguages()[$languageCode] ?? 'English';

        return [
            'code' => $languageCode,
            'name' => $language,
        ];
    }
}

if (! function_exists('getHeaderQuiz')) {
    function getHeaderQuiz()
    {
        return Quiz::with('category')->whereNotNull('category_id')->exists();
    }
}

//Currency Position
if (! function_exists('getCurrencyPosition')) {
    function getCurrencyPosition()
    {
        return getSetting()->currency_before_amount ?? 0;
    }
}

// if (! function_exists('enableCaptcha')) {
//     function enableCaptcha()
//     {
//         $record = getSetting() ?? null;
//         config([
//             'captcha.secret' => $record->captcha_secret_key,
//             'captcha.sitekey' => $record->captcha_site_key,
//         ]);
//         return ! empty($record) && !empty($record->enable_captcha) ? $record->enable_captcha : 0;
//     }
// }

if (!function_exists('checkCaptcha')) {
    function checkCaptcha($value)
    {
        $record = getSetting() ?? null;

        $data = [];
        $data['enabled_captcha_in_quiz'] = ! empty($record) && !empty($record->enabled_captcha_in_quiz) ? $record->enabled_captcha_in_quiz : 0;
        $data['enabled_captcha_in_register'] = ! empty($record) && !empty($record->enabled_captcha_in_register) ? $record->enabled_captcha_in_register : 0;
        $data['enabled_captcha_in_login'] = ! empty($record) && !empty($record->enabled_captcha_in_login) ? $record->enabled_captcha_in_login : 0;

        return $data[$value];
    }
}

if (!function_exists('getActiveSubscription')) {
    function getActiveSubscription()
    {
        return Subscription::with('plan')->where('user_id', Auth::id())->where('status', SubscriptionStatus::ACTIVE->value)->first() ?? null;
    }
}

if (!function_exists('getUserSettings')) {
    function getUserSettings($key, $userId = null)
    {
        if (is_null($userId)) {
            $userId = Auth::id();
        }
        $settings = UserSetting::where('user_id', $userId)->pluck('value', 'key')->toArray();
        if (array_key_exists($key, $settings)) {
            return $settings[$key];
        }
        return null;
    }
}
