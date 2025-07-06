@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ getAppLogo() }}" class="logo" style="height:auto!important;width:auto!important;object-fit:cover"
                alt="{{ getAppName() }}">
        @endcomponent
    @endslot
    <h2>{{ __('messages.mail.hello') . ',' }} </h2>
    <p>{{ __('messages.mail.password_reset_request') }}</p>
    @component('mail::button', ['url' => $url])
        {{ __('messages.mail.reset_password') }}
    @endcomponent
    <p>{{ __('messages.mail.this_password_reset_link_will_expire_in_count_minutes', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]) }}
    </p>
    <p>{{ __('messages.mail.no_further_action_is_required') }}</p>
    <p>{{ __('messages.mail.best_regards') }}</p>
    <p>{{ getAppName() }}</p>
    <hr style="border: 1px solid #f2f0f5">
    <p style="line-height: 1.5em; margin-top: 0; text-align: left; font-size: 14px;">
        {{ __('messages.mail.trouble', [
            'actionText' => Lang::get('messages.mail.reset_password'),
        ]) }}
        <span style="word-break: break-all; display: block; overflow-wrap: break-word;">
            <a href="{{ $url }}">{{ $url }}</a>
        </span>
    </p>
    @slot('footer')
        @component('mail::footer')
            <h6>© {{ date('Y') }} {{ getAppName() }}.</h6>
        @endcomponent
    @endslot
@endcomponent
