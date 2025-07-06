@extends('layout.quiz_app')
@section('content')

    <div class="flex flex-col min-h-screen justify-center items-center p-4 md:my-0 my-10">
        {{-- <h1 class="font-bold lg:text-[54px] md:text-5xl sm:text-4xl text-3xl lg:mb-16 sm:mb-10 mb-7">
            {{ __('messages.quiz.play_quiz') }}
        </h1> --}}
        <div class="bg-white lg:pt-10 pt-7 lg:pb-16 pb-12 lg:px-10 px-7 rounded-2xl max-w-[700px] w-full relative">

            <a href="{{ route('quiz-player', $quiz->unique_code) }}" class="absolute top-5 left-5 text-black">
                <svg width="24" height="16" viewBox="0 0 24 16" fill="none" xmlns="http://www.w3.org/2000/svg"
                    class="rotate-180 text-gray-500">
                    <path
                        d="M23.707 7.28071L16.707 0.280712C16.5184 0.0985542 16.2658 -0.00224062 16.0036 3.78025e-05C15.7414 0.00231622 15.4906 0.107485 15.3052 0.292894C15.1198 0.478302 15.0146 0.729114 15.0123 0.991311C15.01 1.25351 15.1108 1.50611 15.293 1.69471L20.586 6.98771H1C0.734784 6.98771 0.48043 7.09307 0.292893 7.28061C0.105357 7.46814 0 7.7225 0 7.98771C0 8.25293 0.105357 8.50728 0.292893 8.69482C0.48043 8.88235 0.734784 8.98771 1 8.98771H20.586L15.293 14.2807C15.1975 14.373 15.1213 14.4833 15.0689 14.6053C15.0165 14.7273 14.9889 14.8585 14.9877 14.9913C14.9866 15.1241 15.0119 15.2558 15.0622 15.3787C15.1125 15.5016 15.1867 15.6132 15.2806 15.7071C15.3745 15.801 15.4861 15.8753 15.609 15.9255C15.7319 15.9758 15.8636 16.0011 15.9964 16C16.1292 15.9988 16.2604 15.9712 16.3824 15.9188C16.5044 15.8664 16.6148 15.7902 16.707 15.6947L23.707 8.69471C23.8945 8.50718 23.9998 8.25288 23.9998 7.98771C23.9998 7.72255 23.8945 7.46824 23.707 7.28071Z"
                        class="fill-gray-500" />
                </svg>
            </a>

            <form id="quizPlayerForm" method="POST" action="{{ route('store.quiz-player') }}"
                class="w-full flex items-center flex-col gap-4 mt-3">
                @csrf

                <div class="w-full flex flex-col gap-3 items-center">
                    <div>
                        <span class="text-gray-100 font-medium text-2xl">{{ __('messages.user.avatar') }}</span>
                    </div>

                    <ul class="w-full flex flex-col items-center gap-1 mt-3">
                        @for ($i = 1; $i <= 12; $i += 6)
                            <div class="flex flex-wrap justify-center items-center items-center md:gap-3 gap-1">
                                @for ($j = 0; $j < 6; $j++)
                                    @php $id = $i + $j; @endphp
                                    <li>
                                        <input type="radio" id="image-{{ $id }}" name="image"
                                            value="{{ $id }}" class="hidden peer"
                                            @if ($i == 1 && $j == 0) checked @endif />
                                        <label for="image-{{ $id }}"
                                            class="p-1 rounded-full cursor-pointer bg-gray-300 peer-checked:shadow peer-checked:bg-[#bf83ffc4] hover:scale-105 transition duration-300">
                                            <img src="{{ asset("images/avatar/$id.png") }}" alt="image"
                                                class="sm:w-[80px] sm:h-[80px] w-[70px] h-[70px] rounded-full" />
                                        </label>
                                    </li>
                                @endfor
                            </div>
                        @endfor
                    </ul>

                </div>

                <div class="w-full">
                    <div class="m-auto">
                        <input type="hidden" value="{{ $quiz->id }}" name="quiz_id">
                        
                        @session('error')
                            <div class="text-danger text-center">
                                {{ session('error') }}
                            </div>
                        @endsession


                        <div class="mb-4">
                            <div class="">
                                <label for="name"
                                    class="form-label px-2 text-lg">{{ __('messages.user.nickname') }}:<sup
                                        class="text-red-500">*</sup></label>
                                <input name="name" id="name" type="text"
                                    class="form-control rounded-lg py-2 px-3 input-gradient-focus"
                                    placeholder="{{ __('messages.user.enter_nickname') }}" value="{{ old('name') }}"
                                    required />
                                @if ($errors->first('name'))
                                    <span class="text-red-500 text-sm px-1">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="">
                                <label for="email" class="form-label px-2 text-lg">{{ __('messages.user.email') }}:<sup
                                        class="text-red-500">*</sup></label>
                                <input name="email" id="email" type="email"
                                    class="form-control rounded-lg py-2 px-3 input-gradient-focus"
                                    placeholder="{{ __('messages.user.enter_email_address') }}"
                                    value="{{ old('email') }}" required />
                                @if ($errors->first('email'))
                                    <span class="text-red-500 text-sm px-1">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                        {{-- @if ($enabledCaptcha)
                            <div class="g-recaptcha mt-4" data-sitekey={{ $siteKey }}></div>
                            @error('g-recaptcha-response')
                                <div class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        @endif --}}
                        <div class="absolute -bottom-8 right-0 left-0 mx-auto">
                            <button type="submit" form="quizPlayerForm"
                                class="rounded-full text-center py-3 md:px-[100px] md:py-5 bg-primary text-white w-full sm:max-w-[383px] max-w-[280px] sm:text-xl text-md font-semibold block mx-auto gradient-button">
                                {{ __('messages.quiz.play_now') }}
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
