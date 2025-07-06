@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ getAppLogo() }}" class="logo" style="height:auto!important;width:auto!important;object-fit:cover"
                alt="{{ getAppName() }}">
        @endcomponent
    @endslot
    <h2>{{ __('messages.mail.hello') . ',' }} {{ $input['participant_name'] }} </h2>
    <p>{{ __('messages.mail.well_done_on_completing_the'), ' ' }} <b>{{ $input['quiz_title'] }}</b>
        {{ __('messages.quiz.quiz') . '.' }}
    </p>
    <p>{{ __('messages.mail.we_hope_you_enjoyed_the_quiz_and_keep_great_work') }}
    </p>
    @component('mail::button', ['url' => $input['result_url']])
        {{ __('messages.mail.show_results') }}
    @endcomponent
    <p>{{ __('messages.mail.remember_learning_never_stops') }}</p>
    <p>{{ __('messages.mail.best_regards') }}</p>
    <p>{{ getAppName() }}</p>
    <hr style="border: 1px solid #f2f0f5">
    <p style="line-height: 1.5em; margin-top: 0; text-align: left; font-size: 14px;">
        {{ __('messages.mail.trouble', [
            'actionText' => Lang::get('messages.mail.show_results'),
        ]) }}
        <br>
        <span style="word-break: break-all; display: block; overflow-wrap: break-word;">
            <a href="{{ $input['result_url'] }}">{{ $input['result_url'] }}</a>
        </span>
    </p>
    @slot('footer')
        @component('mail::footer')
            <h6>© {{ date('Y') }} {{ getAppName() }}.</h6>
        @endcomponent
    @endslot
@endcomponent
