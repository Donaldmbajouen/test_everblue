@extends('layout.quiz_app')
@section('content')
    @php
        $firstUser = $topThree[0] ?? null;
        $secondUser = $topThree[1] ?? null;
        $thirdUser = $topThree[2] ?? null;
    @endphp
    <div class="flex flex-col min-h-screen justify-center items-center p-4">
        <h1 class="font-bold lg:text-[54px] md:text-5xl sm:text-4xl text-3xl lg:mb-16 sm:mb-10 mb-7 text-light"
            style="text-shadow: 2px 2px 45px #000000e6;">
            {{ __('messages.participant_result.you_have_completed_quiz') }}
        </h1>

        <div class="flex xl:flex-row flex-col justify-center  gap-4 content-stretch">
            <div class="pt-7 lg:pb-10 pb-7 lg:px-10 px-7 rounded-2xl max-w-[700px] w-full text-center bg-white">
                <h3 class="text-xl font-semibold">{{ $quiz->title }}</h3>
                <p class="lg:text-xl text-lg font-medium text-gray-100">
                    {{ __('messages.participant_result.here_are_your_results') . ':' }}</p>
                <div class="mt-8  mb-8">
                    <div class="flex justify-between items-center px-2">
                        <div class="flex gap-3 items-center">
                            <div>
                                <img class="w-12 h-12 rounded-full"
                                    src="{{ asset('images/avatar/' . $userQuiz->image . '.png') }}" alt="avatar">
                            </div>
                            <div
                                class="flex flex-col {{ getActiveLanguage()['code'] == 'ar' ? 'text-end' : 'text-start' }}">
                                <span class="text-xl font-medium">{{ $userQuiz->name }}</span>
                                <span>{{ $userQuiz->email }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col text-end">
                            <span>Score</span>
                            <span class="text-xl font-medium">{{ $userQuiz->score }}%</span>
                        </div>
                    </div>
                    <div class="px-2 mt-2 mb-4">
                        <div class="w-full bg-gray-300 rounded-full h-2.5 mb-1">
                            <div class="h-2.5 rounded-full"
                                style="width: {{ $userQuiz->score }}%; background: linear-gradient(145deg, #c66bea, #651dad); box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px 2px rgba(255, 255, 255, 0.2);">
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3 my-3">
                        <div class="flex flex-1 items-center justify-between gap-4 rounded-lg p-3.5 px-4 sm:w-auto w-full bg-gray-400"
                            style="box-shadow: inset 0 0 10px -5px #000000;">
                            <div class="flex flex-col">
                                <p class="text-base font-medium">
                                    {{ __('messages.participant_result.total_questions') }}
                                </p>
                                <p class="text-2xl font-semibold tracking-tight mt-1 flex">
                                    {{ $totalQuestions }}
                                </p>
                            </div>
                            <div class="flex items-center">
                                <img src="{{ asset('images/question.png') }}" alt="questions"
                                    class="sm:w-[30px] sm:min-w-[30px] w-7 min-w-7" />
                            </div>
                        </div>
                        <div class="flex flex-1 items-center justify-between gap-4 rounded-lg p-3.5 px-4 sm:w-auto w-full bg-gray-400"
                            style="box-shadow: inset 0 0 10px -5px #000000;">
                            <div class="flex flex-col">
                                <p class="text-base font-medium">
                                    {{ __('messages.participant.correct_answers') }}</p>
                                <p class="text-2xl font-semibold tracking-tight mt-1 flex">
                                    {{ $totalCurrentAns }}</p>
                            </div>
                            <div class="flex items-center">
                                <img src="{{ asset('images/checked.png') }}" alt="correct-ans"
                                    class="sm:w-[30px] sm:min-w-[30px] w-7 min-w-7" />
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-1 items-center justify-between gap-4 rounded-lg p-3.5 px-4 sm:w-auto w-full mb-2 bg-gray-400"
                        style="box-shadow: inset 0 0 10px -5px #000000;">
                        <div class="flex flex-col">
                            <p
                                class="{{ getActiveLanguage()['code'] == 'ar' ? 'text-end' : 'text-start' }} text-base font-medium">
                                {{ __('messages.participant.total_time') . ':' }}
                            </p>
                            <p class="text-2xl font-semibold tracking-tight mt-1 flex">
                                {{ $totalTime }}
                            </p>
                        </div>
                        <div class="flex items-center">
                            <img src="{{ asset('images/time-left.png') }}" alt="total-time"
                                class="sm:w-[30px] sm:min-w-[30px] w-7 min-w-7" />
                        </div>
                    </div>

                    @if ($firstUser != null && $firstUser->id == $userQuiz->id)
                        <span class="text-base font-semibold text-gray-800 px-2">
                            {{ __('messages.participant_result.first_place_winner') }}
                        </span>
                    @elseif ($secondUser != null && $secondUser->id == $userQuiz->id)
                        <span class="text-base font-semibold text-gray-800 px-2">
                            {{ __('messages.participant_result.second_place_winner') }}
                        </span>
                    @elseif ($thirdUser != null && $thirdUser->id == $userQuiz->id)
                        <span class="text-base font-semibold text-gray-800 px-2">
                            {{ __('messages.participant_result.third_place_winner') }}
                        @else
                            @php
                                $winningUser = $quizUsers->firstWhere('id', $userQuiz->id);
                            @endphp
                            @if ($winningUser)
                                <span class="text-base font-semibold text-gray-800 px-2">
                                    {{ __('messages.participant_result.number_place_winner', ['number' => $winningUser->number]) }}
                                </span>
                            @endif
                    @endif
                </div>
                <div>
                    <button data-bs-toggle="modal" data-bs-target="#shareResultModal"
                        class="rounded-[50px] bg-gradient-to-r from-purple-500 to-blue-500 text-white border-0 w-full sm:max-w-[383px] max-w-[280px] p-3 text-xl font-semibold flex gap-3 justify-center items-center mx-auto hover:from-purple-600 hover:to-blue-600 shadow-lg hover:shadow-xl transition-all">
                        <span><svg width="22" height="25" viewBox="0 0 22 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M21.25 4.49994C21.25 6.29492 19.795 7.75006 18 7.75006C16.2051 7.75006 14.7501 6.29492 14.7501 4.49994C14.7501 2.70514 16.2051 1.25 18 1.25C19.795 1.25 21.25 2.70514 21.25 4.49994Z"
                                    fill="#ffffff" />
                                <path
                                    d="M18.0001 8.50006C15.794 8.50006 14.0001 6.70599 14.0001 4.49994C14.0001 2.29407 15.794 0.5 18.0001 0.5C20.2061 0.5 22 2.29407 22 4.49994C22 6.70599 20.2061 8.50006 18.0001 8.50006ZM18.0001 2C16.6211 2 15.5001 3.12207 15.5001 4.49994C15.5001 5.87799 16.6211 7.00006 18.0001 7.00006C19.379 7.00006 20.5 5.87799 20.5 4.49994C20.5 3.12207 19.379 2 18.0001 2Z"
                                    fill="#ffffff" />
                                <path
                                    d="M21.25 20.5001C21.25 22.2949 19.795 23.75 18 23.75C16.2051 23.75 14.7501 22.2949 14.7501 20.5001C14.7501 18.7051 16.2051 17.2499 18 17.2499C19.795 17.2499 21.25 18.7051 21.25 20.5001Z"
                                    fill="#ffffff" />
                                <path
                                    d="M18.0001 24.5C15.794 24.5 14.0001 22.7059 14.0001 20.5001C14.0001 18.294 15.794 16.4999 18.0001 16.4999C20.2061 16.4999 22 18.294 22 20.5001C22 22.7059 20.2061 24.5 18.0001 24.5ZM18.0001 17.9999C16.6211 17.9999 15.5001 19.122 15.5001 20.5001C15.5001 21.8779 16.6211 23 18.0001 23C19.379 23 20.5 21.8779 20.5 20.5001C20.5 19.122 19.379 17.9999 18.0001 17.9999Z"
                                    fill="#ffffff" />
                                <path
                                    d="M7.25008 12.5C7.25008 14.295 5.79493 15.7499 3.99995 15.7499C2.20514 15.7499 0.749999 14.295 0.749999 12.5C0.749999 10.705 2.20514 9.25006 3.99995 9.25006C5.79493 9.25006 7.25008 10.705 7.25008 12.5Z"
                                    fill="#ffffff" />
                                <path
                                    d="M3.99995 16.4999C1.79407 16.4999 0 14.7061 0 12.5C0 10.2939 1.79407 8.50006 3.99995 8.50006C6.20601 8.50006 8.00008 10.2939 8.00008 12.5C8.00008 14.7061 6.20601 16.4999 3.99995 16.4999ZM3.99995 10.0001C2.62098 10.0001 1.5 11.1219 1.5 12.5C1.5 13.878 2.62098 14.9999 3.99995 14.9999C5.3791 14.9999 6.50008 13.878 6.50008 12.5C6.50008 11.1219 5.3791 10.0001 3.99995 10.0001Z"
                                    fill="#ffffff" />
                                <path
                                    d="M6.3611 12.0201C6.01301 12.0201 5.675 11.839 5.49098 11.5151C5.21797 11.0361 5.38606 10.425 5.86506 10.1509L15.144 4.86102C15.623 4.586 16.234 4.75409 16.5081 5.23492C16.7811 5.71393 16.613 6.32495 16.134 6.59906L6.85493 11.889C6.69893 11.978 6.52901 12.0201 6.3611 12.0201Z"
                                    fill="#ffffff" />
                                <path
                                    d="M15.6391 20.2701C15.471 20.2701 15.3011 20.228 15.1451 20.139L5.86598 14.8491C5.38698 14.576 5.21907 13.965 5.49208 13.4849C5.76399 13.005 6.37593 12.836 6.85603 13.111L16.1351 18.4009C16.6141 18.6739 16.782 19.285 16.509 19.7651C16.3241 20.089 15.9861 20.2701 15.6391 20.2701Z"
                                    fill="#ffffff" />
                            </svg>
                        </span>
                        {{ __('messages.participant_result.share_your_result') }}
                    </button>

                </div>
            </div>
            <div
                class="bg-white lg:p-10 p-7 rounded-2xl max-w-[700px] w-full text-center overflow-y-auto bg-white max-h-[590px] h-screen overflow-auto">
                <div class="flex flex-col gap-3">
                    @foreach ($questionAnswers as $queAnsKey => $queAns)
                        @php
                            $bgColor = '#ffe9e9bf';
                            $shadowColor = '#ff7171';
                            if (!$queAns->is_time_out) {
                                if ($isMultipleChoice && $queAns->multi_answer) {
                                    $isCorrectArray = array_column($queAns->multi_answer, 'is_correct');
                                    if (!in_array(false, $isCorrectArray, true)) {
                                        $bgColor = '#e8ffeaa6';
                                        $shadowColor = '#61ff70';
                                    } elseif (
                                        in_array(true, $isCorrectArray, true) &&
                                        in_array(false, $isCorrectArray, true)
                                    ) {
                                        $bgColor = '#ffe69382';
                                        $shadowColor = '#ffe691';
                                    }
                                } else {
                                    if ($queAns->is_correct) {
                                        $bgColor = '#e8ffeaa6';
                                        $shadowColor = '#61ff70';
                                    }
                                }
                            }
                        @endphp
                        <div class="p-3 px-4 rounded-xl"
                            style="background-color: {{ $bgColor }};box-shadow: 0 0 10px -5px {{ $shadowColor }};">
                            <div class="flex gap-3 sm:text-xl text-lg font-medium mb-1">
                                <span>{{ $loop->iteration . '.' }}</span>
                                <span class="text-start">{{ $queAns->question_title }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-x-6 gap-y-2 flex-wrap">
                                @if ($queAns->is_time_out)
                                    <div class="flex gap-3 sm:text-xl text-lg">
                                        <span class="mt-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21"
                                                fill="none" viewBox="0 0 24 24">
                                                <path fill="#F59E0B"
                                                    d="M12 1.75a10.25 10.25 0 1 1-10.25 10.25A10.262 10.262 0 0 1 12 1.75Zm0 18.5a8.25 8.25 0 1 0-8.25-8.25 8.259 8.259 0 0 0 8.25 8.25Z" />
                                                <path fill="#F59E0B"
                                                    d="M12.75 7.25a.75.75 0 0 0-1.5 0v5a.75.75 0 0 0 .38.65l3.5 2a.75.75 0 1 0 .73-1.3l-3.11-1.78V7.25Z" />
                                            </svg>
                                        </span>
                                        <span class="text-start text-gray-100 font-normal" style="font-size: 17px;">
                                            {{ __('messages.common.time_out') }}
                                        </span>
                                    </div>
                                @else
                                    @if ($isMultipleChoice && $queAns->multi_answer)
                                        @foreach ($queAns->multi_answer as $answer)
                                            <div class="flex gap-2 sm:text-xl text-lg">
                                                <span class="mt-1.5">
                                                    @if ($answer['is_correct'])
                                                        <svg width="16" height="17" viewBox="0 0 16 13"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M6.1267 12.7496C5.97397 12.9103 5.76559 13 5.54914 13C5.33268 13 5.12431 12.9103 4.97158 12.7496L0.359013 7.92228C-0.119671 7.42139 -0.119671 6.60918 0.359013 6.10923L0.936574 5.50473C1.41541 5.00384 2.19073 5.00384 2.66941 5.50473L5.54914 8.51818L13.3306 0.375664C13.8094 -0.125221 14.5855 -0.125221 15.0634 0.375664L15.641 0.980169C16.1197 1.48105 16.1197 2.29311 15.641 2.79322L6.1267 12.7496Z"
                                                                fill="#4BC857" />
                                                        </svg>
                                                    @else
                                                        <svg width="16" height="17" viewBox="0 0 16 17"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M15.5907 13.4502L3.0507 0.910178C2.50379 0.363274 1.61712 0.363274 1.07108 0.910178L0.410178 1.57005C-0.136726 2.11713 -0.136726 3.00379 0.410178 3.54984L12.9502 16.0898C13.4972 16.6367 14.3839 16.6367 14.9299 16.0898L15.5898 15.4299C16.1378 14.8839 16.1378 13.9971 15.5907 13.4502Z"
                                                                fill="#F44336" />
                                                            <path
                                                                d="M12.9502 0.910178L0.410177 13.4502C-0.136726 13.9971 -0.136726 14.8839 0.410177 15.4299L1.07005 16.0898C1.61712 16.6367 2.50379 16.6367 3.04984 16.0898L15.5907 3.5507C16.1377 3.00379 16.1377 2.11712 15.5907 1.57108L14.9308 0.911203C14.3839 0.363274 13.4972 0.363274 12.9502 0.910178Z"
                                                                fill="#F44336" />
                                                        </svg>
                                                    @endif
                                                </span>

                                                <span class="text-start text-gray-100 font-normal"
                                                    style="font-size: 17px;">
                                                    {{ $answer['title'] ?? '' }}
                                                </span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="flex gap-3 sm:text-xl text-lg">
                                            <span class="mt-1.5">
                                                @if ($queAns->is_correct)
                                                    <svg width="16" height="17" viewBox="0 0 16 13"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M6.1267 12.7496C5.97397 12.9103 5.76559 13 5.54914 13C5.33268 13 5.12431 12.9103 4.97158 12.7496L0.359013 7.92228C-0.119671 7.42139 -0.119671 6.60918 0.359013 6.10923L0.936574 5.50473C1.41541 5.00384 2.19073 5.00384 2.66941 5.50473L5.54914 8.51818L13.3306 0.375664C13.8094 -0.125221 14.5855 -0.125221 15.0634 0.375664L15.641 0.980169C16.1197 1.48105 16.1197 2.29311 15.641 2.79322L6.1267 12.7496Z"
                                                            fill="#4BC857" />
                                                    </svg>
                                                @else
                                                    <svg width="16" height="17" viewBox="0 0 16 17"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M15.5907 13.4502L3.0507 0.910178C2.50379 0.363274 1.61712 0.363274 1.07108 0.910178L0.410178 1.57005C-0.136726 2.11713 -0.136726 3.00379 0.410178 3.54984L12.9502 16.0898C13.4972 16.6367 14.3839 16.6367 14.9299 16.0898L15.5898 15.4299C16.1378 14.8839 16.1378 13.9971 15.5907 13.4502Z"
                                                            fill="#F44336" />
                                                        <path
                                                            d="M12.9502 0.910178L0.410177 13.4502C-0.136726 13.9971 -0.136726 14.8839 0.410177 15.4299L1.07005 16.0898C1.61712 16.6367 2.50379 16.6367 3.04984 16.0898L15.5907 3.5507C16.1377 3.00379 16.1377 2.11712 15.5907 1.57108L14.9308 0.911203C14.3839 0.363274 13.4972 0.363274 12.9502 0.910178Z"
                                                            fill="#F44336" />
                                                    </svg>
                                                @endif
                                            </span>

                                            <span class="text-start text-gray-100 font-normal" style="font-size: 17px;">
                                                {{ $queAns->answer->title ?? '' }}
                                            </span>
                                        </div>
                                    @endif
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="shareResultModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content relative p-1 w-full shadow rounded-2xl">
                <div class="modal-body">
                    <div class="flex justify-between items-center">
                        <h2 class="font-semibold sm:text-3xl text-2xl d-flex align-items-center gap-3">
                            <span>
                                <svg width="22" height="25" viewBox="0 0 22 25" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M21.25 4.49994C21.25 6.29492 19.795 7.75006 18 7.75006C16.2051 7.75006 14.7501 6.29492 14.7501 4.49994C14.7501 2.70514 16.2051 1.25 18 1.25C19.795 1.25 21.25 2.70514 21.25 4.49994Z"
                                        fill="#0B98FF" />
                                    <path
                                        d="M18.0001 8.50006C15.794 8.50006 14.0001 6.70599 14.0001 4.49994C14.0001 2.29407 15.794 0.5 18.0001 0.5C20.2061 0.5 22 2.29407 22 4.49994C22 6.70599 20.2061 8.50006 18.0001 8.50006ZM18.0001 2C16.6211 2 15.5001 3.12207 15.5001 4.49994C15.5001 5.87799 16.6211 7.00006 18.0001 7.00006C19.379 7.00006 20.5 5.87799 20.5 4.49994C20.5 3.12207 19.379 2 18.0001 2Z"
                                        fill="#0B98FF" />
                                    <path
                                        d="M21.25 20.5001C21.25 22.2949 19.795 23.75 18 23.75C16.2051 23.75 14.7501 22.2949 14.7501 20.5001C14.7501 18.7051 16.2051 17.2499 18 17.2499C19.795 17.2499 21.25 18.7051 21.25 20.5001Z"
                                        fill="#0B98FF" />
                                    <path
                                        d="M18.0001 24.5C15.794 24.5 14.0001 22.7059 14.0001 20.5001C14.0001 18.294 15.794 16.4999 18.0001 16.4999C20.2061 16.4999 22 18.294 22 20.5001C22 22.7059 20.2061 24.5 18.0001 24.5ZM18.0001 17.9999C16.6211 17.9999 15.5001 19.122 15.5001 20.5001C15.5001 21.8779 16.6211 23 18.0001 23C19.379 23 20.5 21.8779 20.5 20.5001C20.5 19.122 19.379 17.9999 18.0001 17.9999Z"
                                        fill="#0B98FF" />
                                    <path
                                        d="M7.25008 12.5C7.25008 14.295 5.79493 15.7499 3.99995 15.7499C2.20514 15.7499 0.749999 14.295 0.749999 12.5C0.749999 10.705 2.20514 9.25006 3.99995 9.25006C5.79493 9.25006 7.25008 10.705 7.25008 12.5Z"
                                        fill="#0B98FF" />
                                    <path
                                        d="M3.99995 16.4999C1.79407 16.4999 0 14.7061 0 12.5C0 10.2939 1.79407 8.50006 3.99995 8.50006C6.20601 8.50006 8.00008 10.2939 8.00008 12.5C8.00008 14.7061 6.20601 16.4999 3.99995 16.4999ZM3.99995 10.0001C2.62098 10.0001 1.5 11.1219 1.5 12.5C1.5 13.878 2.62098 14.9999 3.99995 14.9999C5.3791 14.9999 6.50008 13.878 6.50008 12.5C6.50008 11.1219 5.3791 10.0001 3.99995 10.0001Z"
                                        fill="#0B98FF" />
                                    <path
                                        d="M6.3611 12.0201C6.01301 12.0201 5.675 11.839 5.49098 11.5151C5.21797 11.0361 5.38606 10.425 5.86506 10.1509L15.144 4.86102C15.623 4.586 16.234 4.75409 16.5081 5.23492C16.7811 5.71393 16.613 6.32495 16.134 6.59906L6.85493 11.889C6.69893 11.978 6.52901 12.0201 6.3611 12.0201Z"
                                        fill="#0B98FF" />
                                    <path
                                        d="M15.6391 20.2701C15.471 20.2701 15.3011 20.228 15.1451 20.139L5.86598 14.8491C5.38698 14.576 5.21907 13.965 5.49208 13.4849C5.76399 13.005 6.37593 12.836 6.85603 13.111L16.1351 18.4009C16.6141 18.6739 16.782 19.285 16.509 19.7651C16.3241 20.089 15.9861 20.2701 15.6391 20.2701Z"
                                        fill="#0B98FF" />
                                </svg>
                            </span>
                            {{ __('messages.participant_result.share_your_result') }}
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <hr class="border-gray-950/5 my-3">

                    <div class="flex flex-wrap items-center justify-center gap-4 mt-3">

                        <a class="sm:mb-2.5 mb-2 bg-[#25D366] hover:bg-[#1EBE59] transition duration-200 border border-gray-200 rounded-lg p-2"
                            href="https://wa.me/?text={{ $result_url }}" target="_blank" aria-label="social-media">
                            <svg class="w-6 h-6 fill-current text-white" viewBox="0 0 50 50" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M25,2C12.318,2,2,12.318,2,25c0,3.96,1.023,7.854,2.963,11.29L2.037,46.73c-0.096,0.343-0.003,0.711,0.245,0.966 C2.473,47.893,2.733,48,3,48c0.08,0,0.161-0.01,0.24-0.029l10.896-2.699C17.463,47.058,21.21,48,25,48c12.682,0,23-10.318,23-23 S37.682,2,25,2z M36.57,33.116c-0.492,1.362-2.852,2.605-3.986,2.772c-1.018,0.149-2.306,0.213-3.72-0.231 c-0.857-0.27-1.957-0.628-3.366-1.229c-5.923-2.526-9.791-8.415-10.087-8.804C15.116,25.235,13,22.463,13,19.594 s1.525-4.28,2.067-4.864c0.542-0.584,1.181-0.73,1.575-0.73s0.787,0.005,1.132,0.021c0.363,0.018,0.85-0.137,1.329,1.001 c0.492,1.168,1.673,4.037,1.819,4.33c0.148,0.292,0.246,0.633,0.05,1.022c-0.196,0.389-0.294,0.632-0.59,0.973 s-0.62,0.76-0.886,1.022c-0.296,0.291-0.603,0.606-0.259,1.19c0.344,0.584,1.529,2.493,3.285,4.039 c2.255,1.986,4.158,2.602,4.748,2.894c0.59,0.292,0.935,0.243,1.279-0.146c0.344-0.39,1.476-1.703,1.869-2.286 s0.787-0.487,1.329-0.292c0.542,0.194,3.445,1.604,4.035,1.896c0.59,0.292,0.984,0.438,1.132,0.681 C37.062,30.587,37.062,31.755,36.57,33.116z">
                                </path>
                            </svg>
                        </a>


                        <a class="sm:mb-2.5 mb-2 bg-[#006fff] hover:bg-[#4998ff] transition duration-200 border border-gray-200 rounded-lg p-2"
                            href="https://www.facebook.com/sharer/sharer.php?u={{ $result_url }}" target="_blank"
                            aria-label="social-media">
                            <svg class="w-6 h-6 fill-current text-white" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20 10.0256C20 4.49172 15.52 0.000488281 10 0.000488281C4.48 0.000488281 0 4.49172 0 10.0256C0 14.8777 3.44 18.9178 8 19.8501V13.0331H6V10.0256H8V7.51929C8 5.58445 9.57 4.01051 11.5 4.01051H14V7.01803H12C11.45 7.01803 11 7.46916 11 8.02054V10.0256H14V13.0331H11V20.0005C16.05 19.4992 20 15.2286 20 10.0256Z" />
                            </svg>
                        </a>

                        <a class="sm:mb-2.5 mb-2 bg-[#1aa8ff] hover:bg-[#6ec8ff] transition duration-200 border border-gray-200 rounded-lg p-2"
                            href="https://twitter.com/intent/tweet?url={{ urlencode($result_url) }}" target="_blank"
                            aria-label="social-media">
                            <svg class="w-6 h-6 fill-current text-white" viewBox="0 0 20 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M20 2.27976C19.2645 2.58044 18.4744 2.78359 17.6438 2.87542C18.5008 2.4023 19.142 1.65766 19.4477 0.780455C18.6425 1.22171 17.7612 1.5323 16.8422 1.69873C16.2242 1.08996 15.4057 0.686455 14.5136 0.550865C13.6216 0.415276 12.706 0.555186 11.909 0.948875C11.1119 1.34256 10.4781 1.96801 10.1058 2.72809C9.73358 3.48818 9.64374 4.3404 9.85026 5.15242C8.2187 5.07684 6.62259 4.6856 5.16553 4.00409C3.70847 3.32258 2.42301 2.36603 1.39258 1.19652C1.04025 1.75724 0.837664 2.40735 0.837664 3.09971C0.837271 3.72299 1.00364 4.33673 1.32201 4.88647C1.64038 5.43621 2.10091 5.90495 2.66273 6.2511C2.01117 6.23197 1.37397 6.06954 0.804193 5.77733V5.82609C0.804127 6.70027 1.13189 7.54755 1.73186 8.22417C2.33184 8.90078 3.16707 9.36505 4.09583 9.5382C3.4914 9.68911 2.85769 9.71134 2.24258 9.60321C2.50462 10.3554 3.01506 11.0131 3.70243 11.4844C4.3898 11.9556 5.21969 12.2168 6.07593 12.2313C4.62242 13.284 2.82735 13.855 0.979477 13.8525C0.652146 13.8525 0.325091 13.8349 0 13.7996C1.87569 14.9123 4.05914 15.5028 6.28909 15.5005C13.8378 15.5005 17.9644 9.73242 17.9644 4.72985C17.9644 4.56732 17.96 4.40317 17.9521 4.24064C18.7548 3.7051 19.4476 3.04193 19.9982 2.2822L20 2.27976Z" />
                            </svg>
                        </a>

                        <a class="sm:mb-2.5 mb-2 bg-[#0084c9] hover:bg-[#2a9fdc] transition duration-200 border border-gray-200 rounded-lg p-2"
                            href="https://www.linkedin.com/shareArticle?mini=true&url={{ $result_url }}"
                            target="_blank" aria-label="social-media">
                            <svg class="w-6 h-6 fill-current text-white" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.429 6.96949H11.143V8.81949C11.678 7.75549 13.05 6.79949 15.111 6.79949C19.062 6.79949 20 8.91749 20 12.8035V20.0005H16V13.6885C16 11.4755 15.465 10.2275 14.103 10.2275C12.214 10.2275 11.429 11.5725 11.429 13.6875V20.0005H7.429V6.96949ZM0.57 19.8305H4.57V6.79949H0.57V19.8305ZM5.143 2.55049C5.14315 2.88576 5.07666 3.21772 4.94739 3.52708C4.81812 3.83643 4.62865 4.117 4.39 4.35249C3.9064 4.83311 3.25181 5.10214 2.57 5.10049C1.88939 5.10003 1.23631 4.83169 0.752 4.35349C0.514211 4.1172 0.325386 3.83631 0.196344 3.52692C0.0673015 3.21753 0.000579132 2.88571 0 2.55049C0 1.87349 0.27 1.22549 0.753 0.747488C1.23689 0.268647 1.89024 0.000189071 2.571 0.000488532C3.253 0.000488532 3.907 0.269489 4.39 0.747488C4.872 1.22549 5.143 1.87349 5.143 2.55049Z" />
                            </svg>
                        </a>

                        {{-- <a class="sm:mb-2.5 mb-2 bg-gradient-to-r from-[#f09433] via-[#e6683c] to-[#bc1888] transition duration-200 border border-gray-200 rounded-lg p-2 relative overflow-hidden"
                            href="https://www.instagram.com" target="_blank" aria-label="social-media">
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                class="w-6 h-6 ill-current text-white" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M17 4.5C17 5.32843 16.3284 6 15.5 6C14.6716 6 14 5.32843 14 4.5C14 3.67157 14.6716 3 15.5 3C16.3284 3 17 3.67157 17 4.5Z"
                                    fill="currentColor" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10 15C12.7614 15 15 12.7614 15 10C15 7.23858 12.7614 5 10 5C7.23858 5 5 7.23858 5 10C5 12.7614 7.23858 15 10 15ZM10 13C11.6569 13 13 11.6569 13 10C13 8.34315 11.6569 7 10 7C8.34315 7 7 8.34315 7 10C7 11.6569 8.34315 13 10 13Z"
                                    fill="currentColor" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M0 9.6C0 6.23969 0 4.55953 0.653961 3.27606C1.2292 2.14708 2.14708 1.2292 3.27606 0.653961C4.55953 0 6.23969 0 9.6 0H10.4C13.7603 0 15.4405 0 16.7239 0.653961C17.8529 1.2292 18.7708 2.14708 19.346 3.27606C20 4.55953 20 6.23969 20 9.6V10.4C20 13.7603 20 15.4405 19.346 16.7239C18.7708 17.8529 17.8529 18.7708 16.7239 19.346C15.4405 20 13.7603 20 10.4 20H9.6C6.23969 20 4.55953 20 3.27606 19.346C2.14708 18.7708 1.2292 17.8529 0.653961 16.7239C0 15.4405 0 13.7603 0 10.4V9.6ZM9.6 2H10.4C12.1132 2 13.2777 2.00156 14.1779 2.0751C15.0548 2.14674 15.5032 2.27659 15.816 2.43597C16.5686 2.81947 17.1805 3.43139 17.564 4.18404C17.7234 4.49684 17.8533 4.94524 17.9249 5.82208C17.9984 6.72225 18 7.88684 18 9.6V10.4C18 12.1132 17.9984 13.2777 17.9249 14.1779C17.8533 15.0548 17.7234 15.5032 17.564 15.816C17.1805 16.5686 16.5686 17.1805 15.816 17.564C15.5032 17.7234 15.0548 17.8533 14.1779 17.9249C13.2777 17.9984 12.1132 18 10.4 18H9.6C7.88684 18 6.72225 17.9984 5.82208 17.9249C4.94524 17.8533 4.49684 17.7234 4.18404 17.564C3.43139 17.1805 2.81947 16.5686 2.43597 15.816C2.27659 15.5032 2.14674 15.0548 2.0751 14.1779C2.00156 13.2777 2 12.1132 2 10.4V9.6C2 7.88684 2.00156 6.72225 2.0751 5.82208C2.14674 4.94524 2.27659 4.49684 2.43597 4.18404C2.81947 3.43139 3.43139 2.81947 4.18404 2.43597C4.49684 2.27659 4.94524 2.14674 5.82208 2.0751C6.72225 2.00156 7.88684 2 9.6 2Z"
                                    fill="currentColor" />
                            </svg>
                        </a> --}}

                        <a class="sm:mb-2.5 mb-2 bg-[#E60023] hover:bg-[#ff6179] transition duration-200 border border-gray-200 rounded-lg p-2"
                            href="https://pinterest.com/pin/create/button/?url={{ $result_url }}" target="_blank"
                            aria-label="social-media">
                            <svg class="w-6 h-6 fill-current text-white" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.0068 2.01286e-06C7.67509 -0.00147676 5.41613 0.811885 3.62054 2.29945C1.82495 3.78702 0.60561 5.85527 0.173355 8.14659C-0.2589 10.4379 0.123104 12.8083 1.25332 14.8478C2.38353 16.8873 4.19089 18.4678 6.36291 19.3159C6.27541 18.5246 6.1954 17.3083 6.39666 16.4445C6.57917 15.6632 7.56922 11.473 7.56922 11.473C7.56922 11.473 7.27045 10.8743 7.27045 9.98922C7.27045 8.59791 8.07674 7.56036 9.08054 7.56036C9.93308 7.56036 10.3456 8.20039 10.3456 8.96917C10.3456 9.82671 9.79932 11.1093 9.51681 12.2981C9.2818 13.2931 10.0168 14.1057 10.9981 14.1057C12.7757 14.1057 14.142 12.2306 14.142 9.52545C14.142 7.13159 12.4207 5.45776 9.96433 5.45776C7.1192 5.45776 5.44912 7.59161 5.44912 9.79671C5.44912 10.6568 5.78038 11.578 6.1929 12.0793C6.22843 12.1171 6.25355 12.1635 6.26582 12.2138C6.27809 12.2642 6.27708 12.3169 6.26291 12.3668C6.18665 12.6818 6.01789 13.3619 5.98539 13.5006C5.94164 13.6831 5.84038 13.7219 5.65038 13.6344C4.40032 13.0531 3.62028 11.2268 3.62028 9.75921C3.62028 6.60531 5.91289 3.70893 10.2281 3.70893C13.697 3.70893 16.3934 6.18029 16.3934 9.4842C16.3934 12.9306 14.2195 15.7045 11.2044 15.7045C10.1906 15.7045 9.23804 15.1782 8.91178 14.5557L8.28925 16.9333C8.06299 17.8021 7.45296 18.8909 7.04544 19.5547C8.42047 19.9798 9.87107 20.1032 11.2981 19.9163C12.7252 19.7294 14.0951 19.2366 15.3143 18.4718C16.5335 17.7069 17.5732 16.6878 18.3624 15.4842C19.1516 14.2807 19.6718 12.9209 19.8873 11.4979C20.1028 10.0749 20.0086 8.62207 19.6111 7.23878C19.2137 5.85549 18.5223 4.5743 17.5843 3.48271C16.6462 2.39112 15.4836 1.51487 14.1759 0.913813C12.8682 0.312754 11.4461 0.00105293 10.0068 2.01286e-06Z" />
                            </svg>
                        </a>


                        {{-- <a class="sm:mb-2.5 mb-2 bg-[#dad80c] hover:bg-[#eae959] transition duration-200 border border-gray-200 rounded-lg p-2"
                            href="https://www.snapchat.com" target="_blank" aria-label="social-media">
                            <svg class="w-6 h-6 fill-current text-white" version="1.1" viewBox="0 0 500 500"
                                xml:space="preserve">
                                <g>
                                    <g>
                                        <path
                                            d="M500.459,375.368c-64.521-10.633-93.918-75.887-97.058-83.294c-0.06-0.145-0.307-0.666-0.375-0.819 c-3.234-6.571-4.036-11.904-2.347-15.838c3.388-8.013,17.741-12.553,26.931-15.462c2.586-0.836,5.009-1.604,6.938-2.372 c18.586-7.339,27.913-16.717,27.716-27.895c-0.179-8.866-7.134-17.007-17.434-20.651c-3.55-1.485-7.774-2.295-11.887-2.295 c-2.842,0-7.066,0.401-11.102,2.287c-7.868,3.678-14.865,5.658-20.156,5.888c-2.355-0.094-4.139-0.486-5.427-0.922 c0.162-2.79,0.35-5.658,0.529-8.585l0.094-1.493c2.193-34.807,4.915-78.123-6.673-104.081 c-34.27-76.834-106.999-82.807-128.478-82.807l-10.018,0.094c-21.436,0-94.029,5.965-128.265,82.756 c-11.614,26.018-8.866,69.316-6.664,104.115c0.213,3.422,0.427,6.758,0.614,10.01c-1.468,0.503-3.584,0.947-6.46,0.947 c-6.161,0-13.542-1.997-21.931-5.922c-12.126-5.683-34.295,1.911-37.291,17.647c-1.63,8.516,1.801,20.796,27.383,30.908 c1.988,0.785,4.489,1.587,7.561,2.56c8.576,2.722,22.929,7.27,26.325,15.266c1.681,3.951,0.879,9.284-2.662,16.512 c-1.263,2.944-31.65,72.124-98.765,83.174c-6.963,1.143-11.93,7.322-11.537,14.353c0.111,1.954,0.563,3.917,1.399,5.897 c5.641,13.193,27.119,22.349,67.55,28.766c0.887,2.295,1.92,7.006,2.509,9.737c0.853,3.9,1.749,7.927,2.97,12.1 c1.229,4.224,4.881,11.307,15.445,11.307c3.575,0,7.714-0.811,12.211-1.681c6.468-1.271,15.309-2.995,26.274-2.995 c6.084,0,12.416,0.546,18.825,1.604c12.092,2.005,22.699,9.506,35.004,18.202c18.116,12.809,34.586,22.605,67.524,22.605 c0.87,0,1.732-0.026,2.577-0.085c1.22,0.06,2.449,0.085,3.695,0.085c28.851,0,54.246-7.62,75.494-22.63 c11.742-8.311,22.835-16.162,34.935-18.176c6.426-1.058,12.766-1.604,18.85-1.604c10.513,0,18.901,1.348,26.385,2.816 c5.06,0.998,9.02,1.476,12.672,1.476c7.373,0,12.8-4.053,14.874-11.127c1.195-4.113,2.091-8.021,2.961-12.015 c0.461-2.125,1.57-7.211,2.509-9.66c39.851-6.34,60.203-15.138,65.835-28.297c0.845-1.894,1.34-3.9,1.476-6.033 C512.372,382.707,507.422,376.529,500.459,375.368z" />
                                    </g>
                                </g>
                            </svg>
                        </a> --}}

                    </div>

                    <div class="w-full flex items-center">

                        <div class="relative w-full mt-4">
                            <input type="text"
                                class="col-span-6 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 {{ getActiveLanguage()['code'] == 'ar' ? 'pl-12' : 'pr-12' }}"
                                value="{{ $result_url }}" readonly>
                            <button
                                class="absolute end-2 top-1/2 -translate-y-1/2 text-gray-500 bg-white hover:bg-gray-100 rounded-lg p-2 inline-flex items-center justify-center"
                                type="button" onclick="copyToClipboard(this)">
                                <span class="copy-icon">
                                    <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 18 20">
                                        <path
                                            d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z" />
                                    </svg>
                                </span>
                                <span class="right-icon hidden">
                                    <svg width="16" height="13" viewBox="0 0 16 13" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.1267 12.7496C5.97397 12.9103 5.76559 13 5.54914 13C5.33268 13 5.12431 12.9103 4.97158 12.7496L0.359013 7.92228C-0.119671 7.42139 -0.119671 6.60918 0.359013 6.10923L0.936574 5.50473C1.41541 5.00384 2.19073 5.00384 2.66941 5.50473L5.54914 8.51818L13.3306 0.375664C13.8094 -0.125221 14.5855 -0.125221 15.0634 0.375664L15.641 0.980169C16.1197 1.48105 16.1197 2.29311 15.641 2.79322L6.1267 12.7496Z"
                                            fill="#4BC857"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
