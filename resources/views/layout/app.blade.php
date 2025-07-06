<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @hasSection('title')
            @yield('title') |
        @endif
        {{ !empty(getSetting()->seo_title) ? getSetting()->seo_title : getAppName() }}
    </title>
    <meta name="keywords" content="{{ !empty(getSetting()->seo_keywords) ? getSetting()->seo_keywords : getAppName() }}">
    <meta name="description"
        content="{{ !empty(getSetting()->seo_description) ? getSetting()->seo_description : 'QuizWhiz AI is an intelligent platform that instantly converts PDFs and web content into engaging quizzes, multiple choice questions, and interactive polls for education and training.' }}">

    <link rel="icon" type="image/png" href="{{ getFaviconUrl() }}" />
    <link
        href="//fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    @vite('resources/css/home.css')
</head>

<body>
    <!-- Navigation -->
    <header class="sticky-header">
        <nav class="container">
            <div class="logo">
                <a href="{{ route('home') }}">
                    <img src="{{ getAppLogo() }}" alt="{{ getAppName() }}">
                </a>
            </div>
            <div class="header-menu">
                <div id="navbar-scrollspy" class="mobile-view-nav">
                    <ul class="nav-links">
                        <li>
                            <a class="nav-link scrollspy-link"
                                href="{{ Route::currentRouteName() == 'home' ? '#features' : route('home') . '#features' }}">
                                {{ __('messages.home.features') }}
                            </a>
                        </li>
                        <li><a class="nav-link scrollspy-link"
                                href="{{ Route::currentRouteName() == 'home' ? '#about' : route('home') . '#about' }}">{{ __('messages.home.about') }}</a>
                        </li>
                        @if (getHeaderQuiz())
                            <li><a class="nav-link scrollspy-link"
                                    href="{{ Route::currentRouteName() == 'home' ? '#examples' : route('home') . '#examples' }}">{{ __('messages.dashboard.quizzes') }}</a>
                            </li>
                        @endif
                        <li><a class="nav-link scrollspy-link"
                                href="{{ Route::currentRouteName() == 'home' ? '#pricing' : route('home') . '#pricing' }}">{{ __('messages.home.pricing') }}</a>
                        </li>
                        <li class="language-dropdown">
                            <a href="javascript:void(0)">{{ __('messages.home.language') }}</a>
                            <ul class="dropdown-menu">

                                @foreach (getAllLanguages() as $lang => $value)
                                    <li>
                                        <img src="{{ asset('images/language/' . $lang . '.png') }}"
                                            alt="{{ $value }}" />
                                        <a class="dropdown-item change-language" href="javascript:void(0)"
                                            data-url="{{ route('change.language', ['code' => $lang]) }}">
                                            {{ $value }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                    <div class="sign-up-button">
                        @auth
                            <a href="{{ auth()->user()->hasRole('admin') ? route('filament.admin.pages.dashboard') : route('filament.user.pages.dashboard') }}"
                                class="btn btn-primary">{{ __('messages.dashboard.dashboard') }}</a>
                        @else
                            <a href="{{ route('filament.auth.auth.register') }}"
                                class="btn btn-primary">{{ __('messages.home.sign_up_free') }}</a>
                        @endauth
                    </div>
                </div>

                <div class="auth-buttons">
                    @auth
                        <a href="{{ auth()->user()->hasRole('admin') ? route('filament.admin.pages.dashboard') : route('filament.user.pages.dashboard') }}"
                            class="btn btn-primary">{{ __('messages.dashboard.dashboard') }}</a>
                    @else
                        <a href="{{ route('filament.auth.auth.login') }}"
                            class="btn btn-outline desktop-only">{{ __('messages.home.log_in') }}</a>
                        <a href="{{ route('filament.auth.auth.register') }}"
                            class="btn btn-primary sign-up-button-desktop">{{ __('messages.home.sign_up_free') }}</a>
                    @endauth
                    <button class="mobile-menu-toggle mobile-only">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M3 12h18M3 6h18M3 18h18" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>
            </div>

        </nav>
    </header>

    {{-- content --}}
    @yield('content')


    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-info">
                    <div class="footer-logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ getAppLogo() }}" alt="{{ getAppName() }}">
                        </a>
                        <span>{{ getAppName() }}</span>
                    </div>
                </div>

                @if (getSetting() && (getSetting()->terms_and_condition || getSetting()->privacy_policy || getSetting()->cookie_policy))
                    <div class="footer-links">
                        <ul>
                            @if (getSetting() && getSetting()->terms_and_condition)
                                <li><a href="{{ route('terms') }}">{{ __('messages.home.terms_and_conditions') }}</a>
                                </li>
                            @endif
                            @if (getSetting() && getSetting()->privacy_policy)
                                <li><a href="{{ route('policy') }}">{{ __('messages.home.privacy_policy') }}</a></li>
                            @endif
                            @if (getSetting() && getSetting()->cookie_policy)
                                <li><a href="{{ route('cookie') }}">{{ __('messages.home.cookie_policy') }}</a></li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>

            <div class="footer-bottom">
                <div class="copyright">
                    &copy; <span>{{ date('Y') }}</span> {{ getAppName() }}.
                    {{ __('messages.home.all_rights_reserved') }}
                </div>
                @if (getSetting() &&
                        (getSetting()->facebook_url ||
                            getSetting()->twitter_url ||
                            getSetting()->instagram_url ||
                            getSetting()->linkedin_url ||
                            getSetting()->pinterest_url))
                    <div class="social-media">
                        @if (getSetting() && getSetting()->facebook_url)
                            <a href="{{ getSetting()->facebook_url }}" target="_blank">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3V2z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </a>
                        @endif
                        @if (getSetting() && getSetting()->twitter_url)
                            <a href="{{ getSetting()->twitter_url }}" target="_blank">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </a>
                        @endif
                        @if (getSetting() && getSetting()->linkedin_url)
                            <a href="{{ getSetting()->linkedin_url }}" target="_blank">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <circle cx="4" cy="4" r="2" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        @endif
                        @if (getSetting() && getSetting()->instagram_url)
                            <a href="{{ getSetting()->instagram_url }}" target="_blank">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </footer>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const links = document.querySelectorAll(".scrollspy-link");
            const sections = Array.from(links).map(link =>
                document.querySelector(link.getAttribute("href"))
            );

            function onScroll() {
                const scrollPos = window.scrollY + 120;

                sections.forEach((section, i) => {
                    if (
                        section.offsetTop <= scrollPos &&
                        section.offsetTop + section.offsetHeight > scrollPos
                    ) {
                        links.forEach(link => link.classList.remove("active"));
                        links[i].classList.add("active");
                    }
                });
            }

            window.addEventListener("scroll", onScroll);
            onScroll();

        });

        document.querySelectorAll('.change-language').forEach(function(el) {
            el.addEventListener('click', function() {
                const dataUrl = this.dataset.url;
                fetch(dataUrl)
                    .then(response => {
                        if (response.ok) {
                            window.location.reload();
                        } else {
                            alert('Failed to change language.');
                        }
                    });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.querySelector('.language-dropdown');

            dropdown.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent event from bubbling
                dropdown.classList.toggle('open');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function() {
                dropdown.classList.remove('open');
            });
        });

        // Mobile menu toggle
        const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', () => {
                const navLinks = document.querySelector('.mobile-view-nav');
                navLinks.classList.toggle('mobile-visible');
            });
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // FAQ Accordion
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const item = question.dataset.accordion;
                const answer = document.getElementById(item);

                // Toggle answer visibility
                answer.classList.toggle('active');

                // Toggle question active state
                question.classList.toggle('active');
            });
        });

        // Testimonial slider
        const testimonialSlider = document.querySelector('.testimonial-slider');
        const slides = document.querySelectorAll('.testimonial-slide');
        const prevBtn = document.querySelector('.slider-prev');
        const nextBtn = document.querySelector('.slider-next');
        const dots = document.querySelectorAll('.slider-dot');

        let currentSlide = 0;

        function updateSlider() {
            // Hide all slides
            slides.forEach(slide => {
                slide.classList.remove('active');
            });

            // Show current slide
            slides[currentSlide].classList.add('active');

            // Update dots
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        // Event listeners for slider controls
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                updateSlider();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentSlide = (currentSlide + 1) % slides.length;
                updateSlider();
            });
        }

        // Event listeners for slider dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentSlide = index;
                updateSlider();
            });
        });

        // Quiz Category Tabs
        const categoryPills = document.querySelectorAll('.category-pills .pill');
        const quizContents = document.querySelectorAll('.quiz-category-content');

        categoryPills.forEach(pill => {
            pill.addEventListener('click', () => {
                // Remove active class from all pills
                categoryPills.forEach(p => p.classList.remove('active'));

                // Add active class to clicked pill
                pill.classList.add('active');

                // Get the category from data attribute
                const category = pill.dataset.category;

                // Hide all quiz content
                quizContents.forEach(content => content.classList.remove('active'));

                const matchedContent = document.getElementById(`${category}-quizzes`);
                if (matchedContent) {
                    matchedContent.classList.add('active');
                }
            });
        });

        // Animation on scroll
        const animateElements = document.querySelectorAll(
            '.animate-fade-in, .animate-fade-in-left, .animate-fade-in-right, .animate-fade-in-delayed');

        function checkElementsInView() {
            animateElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;

                if (elementTop < windowHeight - 100) {
                    element.classList.add('visible');
                }
            });
        }

        // Initial check
        checkElementsInView();

        // Check on scroll
        window.addEventListener('scroll', checkElementsInView);
    </script>
</body>

</html>
