@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ getAppLogo() }}" class="logo" style="height:auto!important;width:auto!important;object-fit:cover"
                alt="{{ getAppName() }}">
        @endcomponent
    @endslot
    <h2>{{ __('messages.mail.hello') }} {{ $input['user_name'] }} </h2>
    <p> {{ __('messages.mail.new_participant_start_your_quiz') }}</p>
    <p>{{ __('messages.mail.here_are_the_details') . ';' }}</p>
    <p>
        <li>
            <b>{{ __('messages.participant.player_name') . ' - ' }}</b>{{ $input['participant_name'] }}
        </li>
        <li>
            <b>{{ __('messages.user.email') . ' - ' }}</b>{{ $input['participant_email'] }}
        </li>
        <li>
            <b>{{ __('messages.mail.registered_date') . ' - ' }}</b>{{ $input['started_at'] }}
        </li>
    </p>
    <p style="margin-top: 8px">{{ __('messages.mail.thank_you_or_your_attention') }}</p>
    <p>{{ __('messages.mail.best_regards') }}</p>
    <p>{{ getAppName() }}</p>
    @slot('footer')
        @component('mail::footer')
            <h6>© {{ date('Y') }} {{ getAppName() }}.</h6>
        @endcomponent
    @endslot
@endcomponent
