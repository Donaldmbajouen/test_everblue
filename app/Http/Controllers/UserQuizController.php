<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\UserQuiz;
use Illuminate\Http\Request;
use App\Models\QuestionAnswer;
use App\Mail\NewParticipantMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Filament\Notifications\Notification;
use App\Mail\NotifyQuizOwnerOnCompletion;
use App\Http\Requests\CreateUserQuizRequest;
use App\Mail\NotifyParticipantOfQuizCompletion;
use App\Models\Answer;
use App\Models\Setting;

class UserQuizController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the quiz form based on the quiz code.
     */
    public function create($code)
    {
        $quiz = Quiz::with('questions')->where('unique_code', $code)->first();
        if (!$quiz) {
            return redirect()->route('home');
        }

        $sessionKey = 'quiz_viewed_' . $code;
        if (!session()->has($sessionKey)) {
            $quiz->increment('view_count');
            session()->put($sessionKey, true);
        }

        $cookieData = json_decode(request()->cookie('quiz_data'), true);
        $lastQuestionId = $cookieData['last_question_id'] ?? null;
        $quizUserId = $cookieData['quiz_user_id'] ?? null;
        $isExpired = isset($quiz->quiz_expiry_date) && Carbon::parse($quiz->quiz_expiry_date)->isPast();

        if ($lastQuestionId && $quizUserId && !$isExpired) {
            return redirect()->route('quiz.question')
                ->cookie('quiz_data', json_encode(['last_question_id' => $lastQuestionId, 'quiz_user_id' => $quizUserId]), 60 * 24 * 365);
        }

        $quizUsers = UserQuiz::selectRaw('*, TIMEDIFF(completed_at, started_at) AS total_time')
            ->where('quiz_id', $quiz->id)
            ->orderBy('score', 'desc')->orderBy('total_time', 'asc')->get();

        $quizUsers->map(function ($quizUser, $index) {
            $quizUser->number = ($index + 1) . 'th';
        });

        return view('quiz_player.index', [
            'quiz' => $quiz,
            'code' => $code,
            'topThree' => collect($quizUsers)->take(3),
            'quizUsers' => collect($quizUsers)->skip(3),
        ]);
    }


    public function createPlayer($code)
    {
        $quiz = Quiz::with('questions')->where('unique_code', $code)->first();
        if ($quiz->status == 0) {
            Notification::make()
                ->title('Quiz is not active')
                ->body('You cannot create a player for this quiz as it is not active.')
                ->danger()
                ->send();

            return redirect()->route('home');
        }

        // $enabledCaptcha = enableCaptcha() && checkCaptcha('enabled_captcha_in_quiz');

        // $siteKey = Setting::where('captcha_site_key', '!=', '')->value('captcha_site_key');

        return view('quiz_player.create', compact('quiz'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserQuizRequest $request)
    {
        // if (enableCaptcha() && checkCaptcha('enabled_captcha_in_quiz')) {

        //     $request->validate([
        //         'g-recaptcha-response' => 'required',
        //     ]);

        //     $secretKey = Setting::where('captcha_secret_key', '!=', '')->value('captcha_secret_key');

        //     $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        //         'secret' => $secretKey,
        //         'response' => $request->input('g-recaptcha-response'),
        //         'remoteip' => $request->ip(),
        //     ]);

        //     if (!optional($response->json())['success']) {
        //         return back()
        //             ->withErrors(['g-recaptcha-response' => 'reCAPTCHA verification failed. Please try again.'])
        //             ->withInput();
        //     }
        // }

        $quiz = Quiz::with('user')->find($request->quiz_id);
        $userExist = UserQuiz::where('email', $request->email)
            ->where('quiz_id', $request->quiz_id)
            ->first();


        if ($userExist) {
            return redirect()->back()->with('error', __('messages.quiz.you_already_completed_this_quiz'));
        }

        $input = $request->all();
        $question = Question::where('quiz_id', $request->quiz_id)->first();

        if (!$question) {
            return redirect()->route('quiz-player', $quiz->unique_code)->with('error', __('No questions found for this quiz'));
        }

        $quizUser = UserQuiz::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'image' => $input['image'],
            'quiz_id' => $input['quiz_id'],
            'started_at' => Carbon::now(),
            'last_question_id' => $question->id,
            'uuid' => generateUniqueUUID()
        ]);


        if (isset(getSetting()->new_participant_mail_to_creator) && getSetting()->new_participant_mail_to_creator) {
            $mailData = [
                'participant_name' => $quizUser->name,
                'participant_email' => $quizUser->email,
                'started_at' => Carbon::parse($quizUser->started_at)->format('d, M Y h:i A'),
                'quiz_title' => $quiz->title,
                'user_name' => $quiz->user->name
            ];

            // if ($quiz->user->email) {
            //     $email = $quiz->user->email;
            //     Mail::to($email)
            //         ->send(new NewParticipantMail($mailData, $email));
            // }
        }

        $cookieData = json_encode(['last_question_id' => $question->id, 'quiz_user_id' => $quizUser->id]);

        return redirect()->route('quiz.question')
            ->cookie('quiz_data', $cookieData, 60 * 24 * 365);
    }

    /**
     * Display the specified resource.
     */
    public function show($uuid)
    {
        $userQuiz = UserQuiz::where('uuid', $uuid)->first();
        if (!$userQuiz) {
            return redirect()->route('home');
        }
        $seconds = $userQuiz->started_at->diffInSeconds($userQuiz->completed_at);
        $userQuiz->total_time = getTimeFormat($seconds);
        $results = json_decode($userQuiz->result, true);
        if (isset($results['total_current_question'])) {
            $userQuiz->correct_answers = round($results['total_current_question']);
        } else {
            $userQuiz->correct_answers = $userQuiz->questionAnswers->where('is_correct', 1)->count();
        }
        if (!isset($userQuiz)) {
            return redirect()->route('home');
        }

        $quiz = $userQuiz->quiz;
        $totalQuestions = $quiz->questions->count();

        $quizUsers = UserQuiz::selectRaw('*, TIMEDIFF(completed_at, started_at) AS total_time')
            ->where('quiz_id', $quiz->id)
            ->orderBy('score', 'desc')->orderBy('total_time', 'asc')->get();

        $quizUsers->map(function ($quizUser, $index) {
            $quizUser->number = ($index + 1) . 'th';
        });

        return view('quiz_player.participant_result', [
            'topThree' => collect($quizUsers)->take(3),
            'quizUsers' => collect($quizUsers)->skip(3),
            'userQuiz' => $userQuiz,
            'quiz' => $quiz,
            'totalQuestions' => $totalQuestions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Display the current quiz question.
     */

    public function quizQuestion()
    {
        $cookieData = json_decode(request()->cookie('quiz_data'), true);
        $data['lastQuestionId'] = $cookieData['last_question_id'] ?? null;
        $data['quizUserId'] = $cookieData['quiz_user_id'] ?? null;
        $lastQuestion = Question::with('answers')->find($data['lastQuestionId']);

        if ($lastQuestion && $data['quizUserId']) {
            $quizUser = UserQuiz::find($data['quizUserId']);
            if ($quizUser->completed_at) {
                return redirect()->route('quiz.result', $quizUser->uuid)->cookie(Cookie::forget('quiz_data'));
            }
            $allQuestions = Question::with('answers')->where('quiz_id', $lastQuestion->quiz_id)->get();
            $data['question'] = Question::with('answers')->find($lastQuestion->id);
            $data['totalQuestions'] = $allQuestions->count();
            $data['currentQuestionNumber'] = $allQuestions->where('id', '<=', $data['lastQuestionId'])->count();

            $data['time_configuration'] = $data['question']->quiz->time_configuration;
            if ($data['time_configuration']) {
                $data['time_type'] = $data['question']->quiz->time_type;
                $data['countdown'] = $data['question']->quiz->time;
            }

            $data['isMultipleChoice'] = true;

            $complatedQues = $data['currentQuestionNumber'] - 1;
            $data['complatedQuesPer'] = $data['totalQuestions'] > 0 ? ($complatedQues / $data['totalQuestions']) * 100 : 0;

            $data['isLastQuestion'] = $data['question']->id === $allQuestions->sortByDesc('id')->first()->id;

            $quesAnswer = QuestionAnswer::firstOrCreate([
                'quiz_user_id' => $data['quizUserId'],
                'question_id' => $data['lastQuestionId'],
            ]);

            return view('quiz_player.question_answer', compact('data'));
        }

        // Send Error Message !
        return redirect()->back();
    }

    /**
     * Handle the user's answer submission.
     */
    public function quizAnswer(Request $request)
    {
        $currentQuestion = Question::with('answers')->where('id', $request->question_id)->first();
        $answers = $request->input('answers') ?? [];
        if (count($answers) > 0) {
            $request->merge([
                'answer_id' => $answers[0],
            ]);
        }
        $answerId = $request->input('answer_id') ?? null;

        $correctAnswerIds = $currentQuestion->answers->where('is_correct', true)->pluck('id')->toArray();

        $userAnswerTitle  = $answerId ? $currentQuestion->answers->where('id', $answerId)->first()->title : null;

        $quizUser = UserQuiz::where('id', $request->quiz_user_id)->first();
        if (empty($quizUser->uuid)) {
            $quizUser->update(['uuid' => generateUniqueUUID()]);
        }

        $nextQuestion = Question::with('answers')
            ->where('id', '>', $currentQuestion->id)
            ->where('quiz_id', $quizUser->quiz_id)
            ->orderBy('id')
            ->first();

        $quiz = Quiz::find($quizUser->quiz_id);
        $checkQuizTimeExpired = !$quiz->time_configuration || ($quiz->time_configuration && $quiz->time_type == Quiz::TIME_OVER_QUESTION) || ($quiz->time_configuration && $quiz->time_type == Quiz::TIME_OVER_QUIZ && $request->time_expired != Quiz::TIME_OVER_QUIZ);

        $quesAnswer = QuestionAnswer::where('quiz_user_id', $quizUser->id)->where('question_id', $currentQuestion->id)->first();

        if ($quiz->time_configuration && $quiz->time_type == Quiz::TIME_OVER_QUIZ && $request->time_expired == Quiz::TIME_OVER_QUIZ) {
            $questions = Question::where('quiz_id', $quiz->id)->get();

            $attemptedQuestions = QuestionAnswer::where('quiz_user_id', $quizUser->id)
                ->pluck('question_id')
                ->toArray();

            $remainingQuestions = $questions->filter(function ($question) use ($attemptedQuestions) {
                return !in_array($question->id, $attemptedQuestions);
            });

            if ($remainingQuestions->count() > 0) {
                foreach ($remainingQuestions as $question) {
                    QuestionAnswer::create([
                        'quiz_user_id' => $request->quiz_user_id,
                        'question_id' => $question->id,
                        'question_title' => $question->title ?? null,
                        'answer_id' =>  null,
                        'answer_title' =>  null,
                        'is_correct' => 0,
                        'ans_text' =>  null,
                        'completed_at' => Carbon::now(),
                        'is_time_out' => Quiz::TIME_OVER_QUIZ
                    ]);
                }
            }
        } elseif ($quesAnswer->completed_at) {
            if ($nextQuestion && $checkQuizTimeExpired) {
                $userAlreadyAnswer = QuestionAnswer::where('quiz_user_id', $quizUser->id)->where('question_id', $nextQuestion->id)->first();
                if (!$userAlreadyAnswer) {
                    $quizUser->update(['last_question_id' => $nextQuestion->id]);
                    $cookieData = json_encode(['last_question_id' => $nextQuestion->id, 'quiz_user_id' => $quizUser->id]);
                    return redirect()->route('quiz.question')
                        ->cookie('quiz_data', $cookieData, 60 * 24 * 365);
                }
            }
        } else {
            $isTimeOut = (int) $request->time_expired ? ($answerId ? 0 : 1) : 0;
            if ($quesAnswer) {
                $quesAnswer->update([
                    'question_title' => $currentQuestion->title ?? null,
                    'answer_id' => $answerId ?? null,
                    'multi_answer' => $answers ?? null,
                    'answer_title' => $userAnswerTitle ?? null,
                    'is_correct' => in_array($answerId, $correctAnswerIds),
                    'completed_at' => Carbon::now(),
                    'is_time_out' => $isTimeOut
                ]);
            } else {
                QuestionAnswer::create([
                    'quiz_user_id' => $request->quiz_user_id,
                    'question_id' => $request->question_id,
                    'question_title' => $currentQuestion->title ?? null,
                    'answer_id' => $answerId ?? null,
                    'multi_answer' => $answers ?? null,
                    'answer_title' => $userAnswerTitle ?? null,
                    'is_correct' => in_array($answerId, $correctAnswerIds),
                    'completed_at' => Carbon::now(),
                    'is_time_out' => $isTimeOut
                ]);
            }
        }

        $questionsIds = Question::where('quiz_id', $quizUser->quiz_id)->pluck('id')->toArray();
        $totalQuestions = count($questionsIds);
        $userQueAns = QuestionAnswer::where('quiz_user_id', $quizUser->id)->whereIn('question_id', $questionsIds)->get();
        $userCurrentAns = $userQueAns->where('is_correct', 1)->count();
        $answeredQuestions = $userQueAns->whereNotNull('answer_id')->count();
        if ($quiz->quiz_type == Quiz::MULTIPLE_CHOICE) {
            $answeredQuestions = 0;
            $userCurrentAns = 0;
            foreach ($userQueAns as $queAns) {
                $userAnswers = collect($queAns->multi_answer ?? []);
                if ($userAnswers->isEmpty()) {
                    continue;
                }
                $answeredQuestions++;
                $correctAnswers = Answer::where('question_id', $queAns->question_id)
                    ->where('is_correct', 1)
                    ->pluck('id');
                $userCorrect = $userAnswers->intersect($correctAnswers)->count();
                $userWrong = $userAnswers->diff($correctAnswers)->count();
                $totalCorrect = $correctAnswers->count();
                if ($userCorrect === $totalCorrect && $userWrong === 0) {
                    $userCurrentAns += 1;
                } elseif ($userCorrect > 0 && $userWrong === 0) {
                    $userCurrentAns += 0.5;
                } else {
                    $userCurrentAns += 0;
                }
            }
        }
        $score = 0;
        $wrongPercent = 0;
        $pendingPercent = 0;
        if ($totalQuestions > 0) {
            $score = ($userCurrentAns / $totalQuestions) * 100;
            $pendingPercent = (($totalQuestions - $answeredQuestions) / $totalQuestions) * 100;
            $wrongPercent = 100 - $score - $pendingPercent;
        }

        $quizUser->update([
            'score' => $score,
            'result' => json_encode([
                'total_question' => $totalQuestions,
                'total_unanswered' => $totalQuestions - $answeredQuestions,
                'total_current_question' => $userCurrentAns,
                'current_score_percent' => $score,
                'wrong_score_percent' => $wrongPercent,
                'pending_score_percent' => $pendingPercent,
            ])
        ]);

        if ($nextQuestion  && $checkQuizTimeExpired) {
            $quizUser->update(['last_question_id' => $nextQuestion->id]);

            $cookieData = json_encode(['last_question_id' => $nextQuestion->id, 'quiz_user_id' => $quizUser->id]);

            return redirect()->route('quiz.question')
                ->cookie('quiz_data', $cookieData, 60 * 24 * 365);
        }

        if ($quizUser->completed_at == null) {
            $quizUser->update(['completed_at' => Carbon::now()]);
        }

        $mailData = [
            'participant_name' => $quizUser->name,
            'participant_email' => $quizUser->email,
            'completed_at' => Carbon::parse($quizUser->completed_at)->format('d, M Y h:i A'),
            'quiz_title' => $quiz->title,
            'user_name' => $quiz->user->name
        ];

        $mailData['result_url'] = route('show.quizResult', $quizUser->uuid);

        // if (isset(getSetting()->quiz_complete_mail_to_participant) && getSetting()->quiz_complete_mail_to_participant) {
        //     Mail::to($quizUser->email)
        //         ->send(new NotifyParticipantOfQuizCompletion($mailData, $quizUser->email));
        // }

        // if (isset(getSetting()->quiz_complete_mail_to_creator) && getSetting()->quiz_complete_mail_to_creator) {
        //     $email = $quiz->user->email;
        //     Mail::to($email)
        //         ->send(new NotifyQuizOwnerOnCompletion($mailData, $email));
        // }

        return redirect()->route('quiz.result', $quizUser->uuid)
            ->cookie(Cookie::forget('quiz_data'));
    }

    /**
     * Return View of finished quiz
     */
    public function quizResult($uuid)
    {
        $userQuiz = UserQuiz::where('uuid', $uuid)->first();

        if (!isset($userQuiz)) {
            return redirect()->route('home');
        }
        $data['userQuiz'] = $userQuiz;
        $result = json_decode($userQuiz->result, true);
        if (isset($result['total_current_question'])) {
            $data['totalQuestions'] = $result['total_question'];
            $data['totalCurrentAns'] = $result['total_current_question'];
        } else {
            $data['totalQuestions'] = $userQuiz->quiz->questions->count();
            $data['totalCurrentAns'] = $userQuiz->questionAnswers->where('is_correct', 1)->count();
        }
        $data['questionAnswers'] = $userQuiz->questionAnswers;

        $data['result_url'] = route('show.quizResult', $userQuiz->uuid);

        $start = Carbon::parse($userQuiz->started_at);
        $end = Carbon::parse($userQuiz->completed_at);

        $seconds = $start->diffInSeconds($end);
        $data['totalTime'] = getTimeFormat($seconds);

        $quiz = $userQuiz->quiz;

        $data['isMultipleChoice'] = $quiz->quiz_type == Quiz::MULTIPLE_CHOICE;
        if ($quiz->quiz_type == Quiz::MULTIPLE_CHOICE) {
            foreach ($data['questionAnswers'] as $questionAnswer) {
                $multiAnswer = $questionAnswer->multi_answer;
                if ($multiAnswer) {
                    foreach ($multiAnswer as $key => $answerId) {
                        $multiAnswer[$key] = Answer::find($answerId)?->toArray();
                    }
                    $questionAnswer->multi_answer = $multiAnswer;
                }
            }
        }

        $data['quiz'] = $quiz;
        $quizUsers = UserQuiz::selectRaw('*, TIMEDIFF(completed_at, started_at) AS total_time')
            ->where('quiz_id', $quiz->id)
            ->orderBy('score', 'desc')->orderBy('total_time', 'asc')->get();

        $quizUsers->map(function ($quizUser, $index) {
            $quizUser->number = ($index + 1) . 'th';
        });

        $data['topThree'] = collect($quizUsers)->take(3);
        $data['quizUsers'] = collect($quizUsers)->skip(3);

        return view('quiz_player.finished', $data);
    }

    public function changeLanguage($code)
    {
        Session::put('locale', $code);
        return $this->sendSuccess('Language changed successfully.');
    }
}
