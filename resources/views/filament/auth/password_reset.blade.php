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
                <x-filament-panels::form wire:submit="request">
                    {{ $this->form }}
                    <x-filament-panels::form.actions :actions="$this->getFormActions()" />
                </x-filament-panels::form>
                <div class="or-continue">
                    <span class="left-line"></span>
                    <span>{{ __('messages.home.or_continue') }}</span>
                    <span class="right-line"></span>
                </div>
                <div class="text-center">
                    <span>{{ __('messages.home.remember_password') }}</span>
                    <a href="{{ route('filament.auth.auth.login') }}">{{ __('messages.home.back_to_login') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
