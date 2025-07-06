@extends('layout.quiz_app')
@section('content')
    @php
        $firstUser = $topThree[0] ?? null;
        $secondUser = $topThree[1] ?? null;
        $thirdUser = $topThree[2] ?? null;
    @endphp
    <div class="flex flex-col min-h-screen justify-center items-center p-4">
        <div class="relative bg-white pb-16 lg:px-10 px-7 rounded-2xl max-w-[700px] w-full mb-3 mt-5">
            <h2 class="font-semibold sm:text-3xl text-2xl py-4">
                {{ $userQuiz->quiz->title ?? __('messages.common.n/a') }}
            </h2>
            <div class="w-full flex md:flex-row flex-col items-center gap-5 justify-around">

                <!-- Circle with quiz score -->
                <div class="relative size-[206px]">
                    <svg class="size-full -rotate-90" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" style="stop-color: #c66bea; stop-opacity: 1;" />
                                <stop offset="100%" style="stop-color: #651dad; stop-opacity: 1;" />
                            </linearGradient>
                        </defs>
                        <circle cx="18" cy="18" r="16" fill="none" class="stroke-current text-gray-200"
                            stroke-width="2"></circle>
                        <circle cx="18" cy="18" r="16" fill="none" stroke="url(#gradient)" stroke-width="2"
                            stroke-dasharray="100" stroke-dashoffset="{{ 100 - $userQuiz->score }}" stroke-linecap="round">
                        </circle>
                    </svg>
                    <div
                        class="absolute top-1/2 {{ getActiveLanguage()['code'] == 'ar' ? 'end-1/2' : 'start-1/2' }} transform -translate-y-1/2 -translate-x-1/2">
                        <div class="w-full flex flex-col text-sm justify-center items-center text-center">
                            <div>
                                <span class="text-4xl font-bold text-[#c66bea]">{{ $userQuiz->correct_answers }}</span>
                                <span class="text-2xl font-bold text-[#e7a9ff]"> / {{ $totalQuestions }}</span>
                            </div>
                            <span class="text-sm text-[#e7a9ff]">{{ __('messages.participant.correct_answers') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-4 md:w-1/2">
                    <div class="flex items-center gap-3">
                        <div>
                            <img class="w-14 h-14 rounded-full"
                                src="{{ asset('images/avatar/' . $userQuiz->image . '.png') }}" alt="avatar">
                        </div>
                        <div class="flex flex-col {{ getActiveLanguage()['code'] == 'ar' ? 'text-end' : 'text-start' }}">
                            <span class="text-xl font-medium">{{ $userQuiz->name }}</span>
                            <span>{{ $userQuiz->email }}</span>
                        </div>
                    </div>
                    <div class="flex flex-1 items-center justify-between gap-4 bg-gray-400 rounded-lg px-4 p-3.5 sm:w-auto w-full"
                        style="box-shadow: inset 0 0 10px -5px #000000;">
                        <div class="flex flex-col">
                            <p class="text-start font-medium">{{ __('messages.participant.total_time') }}</p>
                            <p class="lg:text-xl text-lg font-semibold text-primary">{{ $userQuiz->total_time }}</p>
                        </div>
                        <img src="{{ asset('images/time.png') }}" alt="total-time"
                            class="sm:w-[30px] sm:min-w-[30px] w-7 min-w-7" />
                    </div>

                </div>
            </div>

            <div class="flex sm:flex-row flex-col items-center gap-3 mt-4">
                <div class="w-full flex items-center gap-4 bg-gray-400 rounded-lg py-3.5 px-3"
                    style="box-shadow: inset 0 0 15px -5px #631fac;">
                    <div class="flex flex-col justify-center flex-grow">
                        <div class="flex justify-between items-center">
                            <p class="fi-wi-stats-overview-stat-label text-base font-medium">
                                {{ __('messages.quiz.quiz_type') }}
                            </p>
                        </div>
                        <div class="text-2xl font-semibold tracking-tight mt-1 flex">
                            {{ App\Models\Quiz::QUIZ_TYPE[$quiz->quiz_type] ?? __('messages.common.n/a') }}
                        </div>
                    </div>
                    <div class="flex items-center">
                        <img src="{{ asset('images/logo-ai.png') }}"
                            alt="{{ App\Models\Quiz::QUIZ_TYPE[$quiz->quiz_type] ?? __('messages.common.n/a') }}"
                            class="sm:w-[40px] sm:min-w-[30px] w-7 min-w-7" />
                    </div>
                </div>
                <div class="w-full flex items-center gap-4 bg-gray-400 rounded-lg py-3.5 px-3"
                    style="box-shadow: inset 0 0 15px -5px #631fac;">
                    <div class="flex flex-col justify-center flex-grow">
                        <div class="flex justify-between items-center">
                            <p class="fi-wi-stats-overview-stat-label text-base font-medium">
                                {{ __('messages.quiz.quiz_level') }}
                            </p>
                        </div>
                        <p class="text-2xl font-semibold tracking-tight mt-1 flex">
                            {{ App\Models\Quiz::DIFF_LEVEL[$quiz->diff_level] ?? __('messages.common.n/a') }}
                        </p>
                    </div>
                    <div class="flex items-center">
                        <img src="{{ asset('images/volume-control.png') }}"
                            alt="{{ App\Models\Quiz::QUIZ_TYPE[$quiz->diff_level] ?? __('messages.common.n/a') }}"
                            class="sm:w-[40px] sm:min-w-[30px] w-7 min-w-7" />
                    </div>
                </div>
            </div>

            <div class="absolute -bottom-8 right-0 left-0 mx-auto">
                <a href="{{ route('quiz-player', $quiz->unique_code) }}"
                    class="rounded-full text-center py-3 md:px-[100px] md:py-5 bg-primary text-white w-full sm:max-w-[383px] max-w-[280px] sm:text-xl text-md font-semibold block mx-auto gradient-button">
                    {{ __('messages.quiz.play_now') }}
                </a>
            </div>
        </div>


        <div
            class="bg-white lg:pt-10 pt-7 lg:pb-10 pb-7 lg:px-10 sm:px-7 px-3 rounded-2xl max-w-[700px] w-full text-center mb-5 mt-10">
            <div class="w-full flex gap-3 items-end justify-center">
                <!-- Second User -->
                <div class="flex flex-col items-center">
                    @if ($secondUser)
                        <span class="translate-y-[20px]" style="color: #007bd3;">{{ $secondUser->name }}</span>
                        <img class="translate-y-[25px] border border-5 border-white w-14 h-14 rounded-full"
                            src="{{ asset('images/avatar/' . $secondUser->image . '.png') }}" alt="avatar">
                    @endif
                    <div class="rounded-t-xl py-14 sm:px-10 px-8 flex flex-col text-sm justify-center items-center"
                        style="background: linear-gradient(180deg, #a2d8ff, #007bd3); color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <span class="text-4xl font-bold">2</span>
                    </div>
                </div>

                <!-- First User -->
                <div class="flex flex-col items-center">
                    @if ($firstUser)
                        <span class="translate-y-[20px]" style="color: #1100ff;">{{ $firstUser->name }}</span>
                        <img class="translate-y-[25px] border border-5 border-white w-14 h-14 rounded-full"
                            src="{{ asset('images/avatar/' . $firstUser->image . '.png') }}" alt="avatar">
                    @endif
                    <div class="rounded-t-xl py-20 sm:px-10 px-8 flex flex-col text-sm justify-center items-center"
                        style="background: linear-gradient(180deg, #b99aff, #1100ff); color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <span class="text-4xl font-bold">1</span>
                    </div>
                </div>

                <!-- Third User -->
                <div class="flex flex-col items-center">
                    @if ($thirdUser)
                        <span class="translate-y-[20px]" style="color: #ff5a00;">{{ $thirdUser->name }}</span>
                        <img class="translate-y-[25px] border border-5 border-white w-14 h-14 rounded-full"
                            src="{{ asset('images/avatar/' . $thirdUser->image . '.png') }}" alt="avatar">
                    @endif
                    <div class="rounded-t-xl py-10 sm:px-10 px-8 flex flex-col text-sm justify-center items-center"
                        style="background: linear-gradient(180deg, #ffc682, #ff5a00); color: white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                        <span class="text-4xl font-bold">3</span>
                    </div>
                </div>
            </div>


            <div class="relative overflow-x-auto rounded-2xl">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-300">
                        <tr>
                            <th scope="col" class="p-3 sm:w-16"></th>
                            <th scope="col" class="py-3 px-6">
                                {{ __('messages.user.user_name') }}
                            </th>
                            <th scope="col" class="py-2 text-center">
                                {{ __('messages.participant_result.score') }}
                            </th>
                        </tr>
                    </thead>
                </table>
                <div class="overflow-y-auto max-h-[700px] rounded-b-2xl">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <tbody>
                            @foreach ($quizUsers as $user)
                                <tr class="bg-gray-50 {{ $loop->last ? '' : 'border-b' }}">
                                    <th scope="row" class="px-6 sm:w-16 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $user->number }}
                                    </th>
                                    <td class="py-3">
                                        <div class="flex gap-3 items-center">
                                            <div>
                                                <img src="{{ asset('images/avatar/' . ($user->image ?? 1) . '.png') }}"
                                                    style="height: 2.5rem; width: 40px;"
                                                    class="max-w-none object-cover object-center rounded-full ring-white">
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm leading-6 text-gray-950">
                                                    {{ $user->name }}
                                                </span>
                                                @if (getUserSettings('hide_participant_email_in_leaderboard', $user->quiz->user->id) != 0)
                                                    <span class="text-sm text-gray-500">{{ $user->email }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        {{ $user->score }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
@endsection
