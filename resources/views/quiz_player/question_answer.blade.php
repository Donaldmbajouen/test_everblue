@extends('layout.quiz_app')
@section('content')
    <div class="flex flex-col min-h-screen justify-center items-center p-4">
        {{-- <h1 class="font-bold lg:text-[54px] md:text-5xl sm:text-4xl text-3xl lg:mb-16 sm:mb-10 mb-7">
            {{ __('messages.quiz.quiz_question') }}
        </h1> --}}
        
        <div
            class="mb-4 bg-white lg:pt-10 pt-7 lg:pb-10 pb-7 lg:px-10 px-7 rounded-2xl max-w-[700px] w-full text-center relative">
            <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-white rounded-full p-2">
                <div class="relative w-20 h-20">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="16" fill="none" stroke="#e5e7eb" stroke-width="4"></circle>
                        <circle cx="18" cy="18" r="16" fill="none" stroke="#8a35c8" stroke-width="4"
                            stroke-dasharray="100" stroke-dashoffset="{{ 100 - $data['complatedQuesPer'] }}"
                            stroke-linecap="round"></circle>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-semibold text-gray-700">{{ $data['totalQuestions'] }}</span>
                    </div>
                </div>
            </div>

            <div class="border-b border-dotted border-gray-300 my-3 pb-2">
                <h4 class="font-semibold sm:text-2xl text-xl">
                    Question {{ $data['currentQuestionNumber'] }}
                </h4>
            </div>

            @if (isset($data['countdown']) && $data['countdown'])
                <div id="countdown-timer" class="mb-3 w-full max-w-md mx-auto text-center">
                    <div class="flex items-center justify-center gap-3 mb-3">
                        <span
                            class="px-3 py-1  rounded-full text-white font-semibold text-lg bg-gradient-to-r from-[#ac4be0] via-[#8a35c8] to-[#651fae] shadow-lg flex items-center gap-2">
                            <img src="{{ asset('images/stopwatch.gif') }}" alt="Loading" class="w-9 h-9"> <span
                                id="time-remaining">00:00</span>
                        </span>
                    </div>

                    <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden shadow-inner">
                        <div id="timer-progress-bar"
                            class="h-full rounded-full bg-gradient-to-r from-[#f3c6fd] to-[#651fae]"
                            style="width: 100%; transition: width 0.3s ease;"></div>
                    </div>
                </div>
            @endif
            <pre>
                {{$data['question']->title}}
            </pre>
            <h2 class="font-semibold sm:text-3xl text-2xl">
                {{ $data['question']->title }}
            </h2>
        </div>
        

        @php
            $limit = $data['question']->answers->where('is_correct', 1)->count();
            if ($limit < 1) {
                $limit = 1; // Par défaut, autorise au moins 1 sélection
            }
            $dataAttributes = [
                'data-answer-limit' => $limit,
                'data-no-selected-error-message' => __('messages.quiz.no_selected_error_message'),
                'data-limit-error-message' => __('messages.quiz.limit_error_message', ['limit' => $limit]),
            ];
            $attributeString = collect($dataAttributes)
                ->map(function ($value, $key) {
                    return $key . '="' . e($value) . '"';
                })
                ->implode(' ');

            $timerAttributes = [];
            if ($data['time_configuration']) {
                $timerAttributes = [
                    'data-countdown' => $data['countdown'],
                    'data-time-type' => $data['time_type'],
                ];
            }

            $timerAttributeString = collect($timerAttributes)
                ->map(function ($value, $key) {
                    return $key . '="' . e($value) . '"';
                })
                ->implode(' ');
        @endphp

        @if ($data['question']->answers->where('is_correct', 1)->count() < 1)
            <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded">
                ⚠️ Cette question n'a aucune bonne réponse définie. Merci d'en corriger la configuration côté admin.
            </div>
        @endif

        <div
            class="bg-white lg:pt-10 pt-7 lg:pb-10 pb-7 lg:px-10 px-7 rounded-2xl max-w-[700px] w-full text-center relative">
            <form method="POST" action="{{ route('quiz.answer') }}" id="questionForm"
                data-is-enabled-timer="{{ $data['time_configuration'] }}" {!! $timerAttributeString !!}>
                @csrf
                <input type="hidden" name="question_id" value="{{ $data['lastQuestionId'] }}" id="questionId">
                <input type="hidden" name="quiz_user_id" value="{{ $data['quizUserId'] }}" id="quizUserId">
                <input type="hidden" name="time_expired" id="time_expired" value="0">
                <div class="grid grid-cols-1 gap-3 mb-8" id="answers-container"
                    data-is-multiple-choice="{{ $data['isMultipleChoice'] }}" {!! $attributeString !!}>
                    @foreach ($data['question']->answers as $answer)
                        <label for="answer-{{ $answer->id }}"
                            class="flex justify-between items-center px-5 py-4 border border-gray-300 rounded-lg cursor-pointer transition-all duration-300"
                            style="background-color: #f3f4f6;">
                            @if ($data['isMultipleChoice'])
                                <input type="checkbox" id="answer-{{ $answer->id }}" class="hidden peer" name="answers[]"
                                    value="{{ $answer->id }}" />
                            @else
                                <input type="radio" id="answer-{{ $answer->id }}" class="hidden peer" name="answer_id"
                                    value="{{ $answer->id }}" />
                            @endif
                            <span class="text-start lg:text-xl text-lg text-gray-800 peer-checked:text-[#ca77ff]">
                                {{ $answer->title }}
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 hidden peer-checked:block"
                                viewBox="0 0 512 512">
                                <path fill="#ca77ffc9"
                                    d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 block peer-checked:hidden"
                                viewBox="0 0 512 512">
                                <circle cx="256" cy="256" r="208" stroke="lightgray" stroke-width="40"
                                    fill="none" />
                            </svg>
                        </label>
                    @endforeach
                </div>

                <div class="absolute -bottom-9 left-0 right-0 mx-auto">
                    @if ($data['isLastQuestion'])
                        <button type="submit"
                            class="rounded-[50px] bg-primary text-white w-full sm:max-w-[383px] submitAnswerButton max-w-[280px] lg:p-5 p-4 text-xl font-semibold flex gap-3 justify-center items-center mx-auto gradient-button">
                            {{ __('messages.common.submit') }}
                        </button>
                    @else
                        <button type="submit"
                            class="rounded-[50px] bg-primary text-white w-full sm:max-w-[383px] submitAnswerButton max-w-[280px] lg:p-5 p-4 text-xl font-semibold flex gap-3 justify-center items-center mx-auto gradient-button">
                            {{ __('messages.quiz.next_question') }}
                            <span><svg width="24" height="16" viewBox="0 0 24 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M23.707 7.28071L16.707 0.280712C16.5184 0.0985542 16.2658 -0.00224062 16.0036 3.78025e-05C15.7414 0.00231622 15.4906 0.107485 15.3052 0.292894C15.1198 0.478302 15.0146 0.729114 15.0123 0.991311C15.01 1.25351 15.1108 1.50611 15.293 1.69471L20.586 6.98771H1C0.734784 6.98771 0.48043 7.09307 0.292893 7.28061C0.105357 7.46814 0 7.7225 0 7.98771C0 8.25293 0.105357 8.50728 0.292893 8.69482C0.48043 8.88235 0.734784 8.98771 1 8.98771H20.586L15.293 14.2807C15.1975 14.373 15.1213 14.4833 15.0689 14.6053C15.0165 14.7273 14.9889 14.8585 14.9877 14.9913C14.9866 15.1241 15.0119 15.2558 15.0622 15.3787C15.1125 15.5016 15.1867 15.6132 15.2806 15.7071C15.3745 15.801 15.4861 15.8753 15.609 15.9255C15.7319 15.9758 15.8636 16.0011 15.9964 16C16.1292 15.9988 16.2604 15.9712 16.3824 15.9188C16.5044 15.8664 16.6148 15.7902 16.707 15.6947L23.707 8.69471C23.8945 8.50718 23.9998 8.25288 23.9998 7.98771C23.9998 7.72255 23.8945 7.46824 23.707 7.28071Z"
                                        fill="white" />
                                </svg>
                            </span>
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
