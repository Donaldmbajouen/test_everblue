@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ getAppLogo() }}" class="logo" style="height:auto!important;width:auto!important;object-fit:cover"
                alt="{{ getAppName() }}">
        @endcomponent
    @endslot
    <h2>{{ __('Hello') }} {{ $emailData['name'] }}</h2>
    <p>{{ __('messages.mail.you_have_purchased_the') . '' }} {{ $emailData['planName'] . ' ' }}
        {{ __('messages.mail.plan_successfully') }}</p>
    <p>{{ __('messages.mail.thanks_regards') }}</p>
    <p>{{ getAppName() }}</p>
    @slot('footer')
        @component('mail::footer')
            <h6>© {{ date('Y') }} {{ getAppName() }}.</h6>
        @endcomponent
    @endslot
@endcomponent
