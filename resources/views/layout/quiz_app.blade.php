<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ getActiveLanguage()['code'] == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ getFaviconUrl() }}" type="image/png">

    <title>{{ getAppName() }}</title>

    <link rel="preconnect" href="//fonts.bunny.net">
    <link href="//fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bootstrap.min.js') }}" ></script>
    <script async src="https://www.google.com/recaptcha/api.js"></script>
    @vite('resources/css/app.css')
    @vite('resources/assets/js/pages.js')
    @vite('resources/js/app.js')   

</head>

<body class="font-['outfit'] text-black antialiased bg-cover bg-no-repeat bg-center min-h-screen"
    style="background-image: url('{{ asset('images/bg-img.png') }}');">

    <div class="absolute top-5 end-10">
        <!-- Button to open the dropdown modal -->
        <button id="languageButton"
            class="d-flex items-center bg-primary hover:bg-gray-400 text-white font-bold py-2 ps-3 pe-4 rounded-lg">
            @foreach (getAllLanguageFlags() as $imageKey => $imageValue)
                @if ($imageKey == getActiveLanguage()['code'])
                    <img src="{{ asset($imageValue) }}" width="20" class="me-2" height="20">
                    {{ getActiveLanguage()['name'] }}
                @endif
            @endforeach
        </button>

        <div id="languageModal"
            class="hidden z-10 absolute right-0 mt-2 p-1 min-w-[120px] bg-white border rounded-lg shadow-lg">
            <ul class="flex flex-col gap-1">
                @foreach (getAllLanguages() as $code => $language)
                    @foreach (getAllLanguageFlags() as $imageKey => $imageValue)
                        @if ($imageKey == $code)
                            <li>
                                <button
                                    class="w-full text-left rounded-md ps-3 pe-4 py-2 flex items-center {{ $code == getActiveLanguage()['code'] ? 'bg-primary text-white' : 'hover:bg-gray-300' }}"
                                    data-lang="{{ $code }}">
                                    <img src="{{ asset($imageValue) }}" width="20" class="me-2" height="20">
                                    {{ $language }}</button>
                            </li>
                        @endif
                    @endforeach
                @endforeach
            </ul>
        </div>
    </div>

    @yield('content')

</body>

</html>
