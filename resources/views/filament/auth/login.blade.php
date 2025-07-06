<div class="w-full h-full">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var url = document.getElementById('bg-image').getAttribute('data-url');
            var bgImageDiv = document.createElement('div');
            bgImageDiv.classList.add('bg-image');

            var img = document.createElement('img');
            img.src = url;
            img.alt = '';

            bgImageDiv.appendChild(img);
            document.body.prepend(bgImageDiv);
        });

        document.addEventListener('DOMContentLoaded', function() {
            var elements = document.querySelectorAll('.fls-display-on');
            elements.forEach(function(element) {
                element.style.right = '0';
            });
        });
    </script>
    @vite('resources/css/login.css')
    <div id="bg-image" data-url="{{ getLoginPageBg() }}"></div>
    <div class="login-container">
        <div class="logo-image">
            <a href="{{ route('home') }}">
                <img src="{{ getAppLogo() }}" alt="{{ getAppName() }}">
            </a>
        </div>
        <div class="form-container">
            <div class="my-0">
                <x-filament-panels::form wire:submit="authenticate">
                    {{ $this->form }}
                    <x-filament-panels::form.actions :actions="$this->getFormActions()" />
                </x-filament-panels::form>
                <div class="or-continue">
                    <span class="left-line"></span>
                    <span>{{ __('messages.home.or_continue') }}</span>
                    <span class="right-line"></span>
                </div>
                <div class="text-center">
                    <span>{{ __('messages.home.dont_have_account') }}</span>
                    <a href="{{ route('filament.auth.auth.register') }}">{{ __('messages.home.sign_up') }}</a>
                </div>
                @if (env('GOOGLE_CLIENT_ID') && env('GOOGLE_CLIENT_SECRET'))
                    <a href="{{ route('socialite.redirect', 'google') }}" class="google-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                            preserveAspectRatio="xMidYMid" viewBox="0 0 256 262" id="google">
                            <path fill="#4285F4"
                                d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027">
                            </path>
                            <path fill="#34A853"
                                d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1">
                            </path>
                            <path fill="#FBBC05"
                                d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782">
                            </path>
                            <path fill="#EB4335"
                                d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251">
                            </path>
                        </svg>
                        {{ __('messages.home.sign_in_with_google') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
