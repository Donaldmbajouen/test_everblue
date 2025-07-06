@extends('layout.quiz_app')
@section('content')
    @php
        $firstUser = $topThree[0] ?? null;
        $secondUser = $topThree[1] ?? null;
        $thirdUser = $topThree[2] ?? null;
    @endphp
    <div class="flex flex-col min-h-screen justify-center items-center p-4">
        {{-- <h1 class="font-bold lg:text-[54px] md:text-5xl sm:text-4xl text-3xl lg:mb-16 sm:mb-10 mb-7">
            {{ __('messages.quiz.quiz') }}
        </h1> --}}
        <div
            class="bg-white lg:pt-10 pt-7 lg:pb-16 pb-12 lg:px-10 px-7 rounded-2xl max-w-[700px] w-full text-center relative mt-[60px]">
            <h2 class="font-semibold sm:text-3xl text-2xl">
                {{ $quiz->title ?? __('messages.common.n/a') }}
            </h2>
            <div class="flex sm:flex-row flex-col items-center gap-2 my-4">
                <div class="w-full flex items-center gap-4 border border-gray-300 bg-gray-400 rounded-lg py-3.5 px-3">
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
                <div class="w-full flex items-center gap-4 border border-gray-300 bg-gray-400 rounded-lg py-3.5 px-3">
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
            @if ($quiz->quiz_description && $quiz->type != 3)
                <h2 class="font-medium sm:text-xl text-lg">
                    {{ $quiz->quiz_description }}
                </h2>
            @endif

            <div class="absolute -bottom-8 left-0 right-0 mx-auto">
                @if (isset($quiz->quiz_expiry_date) && \Carbon\Carbon::parse($quiz->quiz_expiry_date)->isPast())
                    <a type="button"
                        class="rounded-full text-center py-3 md:px-[100px] md:py-5 bg-red-600 text-white w-full sm:max-w-[383px] max-w-[280px] sm:text-xl text-md font-semibold block mx-auto">
                        {{ __('Quiz is expired') }}
                    </a>
                @elseif ($quiz->status == 0)
                    <a type="button"
                        class="rounded-full text-center py-3 md:px-[100px] md:py-5 bg-red-600 text-white w-full sm:max-w-[383px] max-w-[280px] sm:text-xl text-md font-semibold block mx-auto">
                        {{ __('Quiz is Not Active') }}
                    </a>
                @else
                    <a href="{{ route('create.quiz-player', $quiz->unique_code) }}"
                        class="rounded-full text-center py-3 md:px-[100px] md:py-5 bg-primary text-white w-full sm:max-w-[383px] max-w-[280px] sm:text-xl text-md font-semibold block mx-auto gradient-button">
                        {{ __('messages.quiz.start_quiz_now') }}
                    </a>
                @endif
            </div>

        </div>

        <div
            class="bg-white lg:pt-10 pt-7 lg:pb-10 pb-7 lg:px-10 sm:px-7 px-3 rounded-2xl max-w-[700px] w-full text-center mb-5 mt-[60px]">
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
                                    <td class="py-3 text-center w-[150px]">
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
