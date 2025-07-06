@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ getAppLogo() }}" class="logo" style="height:auto!important;width:auto!important;object-fit:cover"
                alt="{{ getAppName() }}">
        @endcomponent
    @endslot
    <h2>{{ __('messages.mail.hello') }} {{ $input['user_name'] }} </h2>
    <p>{{ __('messages.mail.great_news'), ' ' }} <b>{{ $input['quiz_title'] }}</b></p>
    <p>{{ __('messages.mail.heres_who_completed_it') . ':' }}</p>
    <p>
        <li>
            <b>{{ __('messages.participant.player_name') . ' - ' }}</b>{{ $input['participant_name'] }}
        </li>
        <li>
            <b>{{ __('messages.user.email') . ' - ' }}</b>{{ $input['participant_email'] }}
        </li>
        <li>
            <b>{{ __('messages.participant.completed_at') . ' - ' }}</b>{{ $input['completed_at'] }}
        </li>
    </p>
    <p style="margin-top: 8px">{{ __('messages.mail.visit_your_dashboard_to_see_performance') }}</p>
    <p>{{ __('messages.mail.best_regards') }}</p>
    <p>{{ getAppName() }}</p>
    @slot('footer')
        @component('mail::footer')
            <h6>© {{ date('Y') }} {{ getAppName() }}.</h6>
        @endcomponent
    @endslot
@endcomponent
