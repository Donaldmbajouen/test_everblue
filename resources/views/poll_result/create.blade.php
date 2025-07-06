@extends('layout.quiz_app')
@section('content')
    <div class="container">
        <div class="flex flex-col min-h-screen justify-center items-center p-4">
            <div
                class="bg-white lg:pt-10 pt-7 lg:pb-10 pb-7 lg:px-10 px-7 rounded-2xl max-w-[1080px] w-full text-center relative">
                <h2 class="font-semibold sm:text-3xl text-2xl">
                    {{ $poll->question }}
                </h2>
                @if ($hasEnded)
                    <div class="mt-2 font-bold">
                        {{ __('messages.poll.poll_is_expiry') }}
                    </div>
                @endif
                <div class="grid lg:grid-cols-2 md:gap-10 gap-3 lg:mt-10 mt-8 lg:mb-10 mb-8">

                    @foreach (getOption() as $key => $option)
                        @if (isset($poll->$option))
                            <div class="relative">

                                @if ($hasEnded || $pollResult)
                                    @php
                                        $gradient = null;
                                        if (empty($pollResultPercentage)) {
                                            $gradient = 'white';
                                        } elseif (getActiveLanguage()['code'] == 'ar') {
                                            $gradient =
                                                'linear-gradient(to left, #dee2e6 ' .
                                                $pollResultPercentage[$poll->$option] .
                                                '% , white 0%)';
                                        } else {
                                            $gradient =
                                                'linear-gradient(to right, #dee2e6 ' .
                                                $pollResultPercentage[$poll->$option] .
                                                '% , white 0%)';
                                        }
                                    @endphp
                                    <div style="background-image: {{ $gradient }};"
                                        class="sm:p-3 md:p-5 relative border border-gray-300 rounded-lg w-full d-flex align-items-center {{ $pollResult ? ($poll->$option == $pollResult['ans'] ? 'shadow' : '') : '' }}">
                                        @if ($pollResult && $poll->$option == $pollResult['ans'])
                                            <span
                                                class="absolute w-2 h-full {{ getActiveLanguage()['code'] == 'ar' ? 'right-0' : 'left-0' }} bg-green-500 rounded-full"></span>
                                        @endif
                                        <span
                                            class="absolute lg:text-xl sm:text-lg text-sm font-semibold py-1 px-3 top-1 {{ getActiveLanguage()['code'] == 'ar' ? 'left-1' : 'right-1' }} border border-gray-300 rounded-md d-flex align-items-center justify-content-center {{ $pollResult ? ($poll->$option == $pollResult['ans'] ? 'shadow-sm' : '') : '' }}">{{ $pollResultPercentage[$poll->$option] ?? 0 . '%' }}</span>
                                        @if ($poll->hasMedia(\App\Models\Poll::POLL_IMAGES_1) && $key == 0)
                                            <img src="{{ $poll->getMedia(\App\Models\Poll::POLL_IMAGES_1)->first()->getUrl() }}"
                                                alt="Poll Option Image" width="100" height="100">
                                        @elseif ($poll->hasMedia(\App\Models\Poll::POLL_IMAGES_2) && $key == 1)
                                            <img src="{{ $poll->getMedia(\App\Models\Poll::POLL_IMAGES_2)->first()->getUrl() }}"
                                                alt="Poll Option Image" width="100" height="100">
                                        @elseif ($poll->hasMedia(\App\Models\Poll::POLL_IMAGES_3) && $key == 2)
                                            <img src="{{ $poll->getMedia(\App\Models\Poll::POLL_IMAGES_3)->first()->getUrl() }}"
                                                alt="Poll Option Image" width="100" height="100">
                                        @elseif ($poll->hasMedia(\App\Models\Poll::POLL_IMAGES_4) && $key == 3)
                                            <img src="{{ $poll->getMedia(\App\Models\Poll::POLL_IMAGES_4)->first()->getUrl() }}"
                                                alt="Poll Option Image" width="100" height="100">
                                        @endif
                                        <span
                                            class="{{ getActiveLanguage()['code'] == 'ar' ? 'mr-4' : 'ml-4' }} lg:text-xl sm:text-lg text-sm text-start my-3">{{ $poll->$option }}</span>
                                    </div>
                                @else
                                    <form action="{{ route('store.poll_result') }}" method="POST" class="poll-form">
                                        @csrf
                                        <input type="hidden" name="option" value="{{ $loop->iteration }}">
                                        <input type="hidden" name="poll_id" value="{{ $poll->id }}">
                                        <button type="submit"
                                            class="poll-option p-5 border border-gray-300 bg-gray-400 rounded-lg w-full d-flex align-items-center">
                                            @if ($poll->hasMedia(\App\Models\Poll::POLL_IMAGES_1) && $key == 0)
                                                <img src="{{ $poll->getMedia(\App\Models\Poll::POLL_IMAGES_1)->first()->getUrl() }}"
                                                    alt="Poll Option Image" width="100" height="100">
                                            @elseif ($poll->hasMedia(\App\Models\Poll::POLL_IMAGES_2) && $key == 1)
                                                <img src="{{ $poll->getMedia(\App\Models\Poll::POLL_IMAGES_2)->first()->getUrl() }}"
                                                    alt="Poll Option Image" width="100" height="100">
                                            @elseif ($poll->hasMedia(\App\Models\Poll::POLL_IMAGES_3) && $key == 2)
                                                <img src="{{ $poll->getMedia(\App\Models\Poll::POLL_IMAGES_3)->first()->getUrl() }}"
                                                    alt="Poll Option Image" width="100" height="100">
                                            @elseif ($poll->hasMedia(\App\Models\Poll::POLL_IMAGES_4) && $key == 3)
                                                <img src="{{ $poll->getMedia(\App\Models\Poll::POLL_IMAGES_4)->first()->getUrl() }}"
                                                    alt="Poll Option Image" width="100" height="100">
                                            @endif
                                            <span class="ml-4 lg:text-xl text-lg text-start">{{ $poll->$option }}</span>
                                        </button>
                                    </form>
                                @endif

                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="text-center">
                    @if (!$pollResult && !$hasEnded)
                        <span class="text-xl font-bold">{{ __('messages.poll.submit_vote') }}</span>
                    @else
                        <span class="text-xl font-bold">{{ __('messages.poll.thanks_you_for_your_response') }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
