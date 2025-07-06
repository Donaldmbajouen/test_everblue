@extends('layout.app')

@section('title')
    {{ __('messages.home.home') }}
@endsection

@section('content')

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-content">
                    <div class="hero-text animate-fade-in">
                        <div class="badge badge-primary-light">
                            {{ getSetting()->hero_sub_title ?? __('messages.home.ai_powered_learning_tools') }}</div>
                        <h1 class="hero-title">
                            @if (!empty(getSetting()->hero_title))
                                <span
                                    class="gradient-text">{{ getSetting()->hero_title ?? __('messages.home.instantly_turn_documents_web') . ' ' . __('messages.home.content_into_interactive_quizzes') }}</span>
                            @else
                                <span>{{ __('messages.home.instantly_turn_documents_web') }}</span>
                                <span class="gradient-text">{{ __('messages.home.content_into_interactive_quizzes') }}</span>
                            @endif
                        </h1>
                        <p>{{ getSetting()->hero_description ?? __('messages.home.upload_pdfs_or_urls') }}</p>
                        <div class="hero-buttons">
                            <a href="@auth {{ auth()->user()->hasRole('admin') ? route('filament.admin.pages.dashboard') : route('filament.user.pages.dashboard') }} @else {{ route('filament.auth.auth.register') }} @endauth"
                                class="btn btn-primary btn-lg">{{ __('messages.home.get_started_free') }}</a>
                            <a href="#about" class="btn btn-outline btn-lg">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 5.14v14l11-7-11-7z" fill="currentColor" />
                                </svg>
                                {{ __('messages.home.see_how_it_works') }}
                            </a>
                        </div>
                        <div class="hero-benefits">
                            <div class="benefit">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="text-green">
                                    <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                {{ __('messages.home.no_credit_card_required') }}
                            </div>
                            <div class="benefit">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" class="text-green">
                                    <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                {{ __('messages.home.free_plan_available') }}
                            </div>
                        </div>
                    </div>
                    <div class="hero-image animate-fade-in-delayed">
                        <img src="{{ getSetting()->hero_section_img ?? asset('images/hero-img.png') }}" alt="Hero Image"
                            class="hero-img hero-img-animate">
                        <!-- Decorative elements -->
                        <div class="decorative-blob decorative-blob-1"></div>
                        <div class="decorative-blob decorative-blob-2"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trust badges -->
        {{-- <div class="trust-badges">
            <h6 class="trust-badges-title">TRUSTED BY EDUCATORS AND BUSINESSES WORLDWIDE</h6>
            <div class="trust-badges-container">
                <div class="trust-badge">
                <div class="colored-badge edu-badge">
                    <span>EDU LEARN</span>
                </div>
                </div>
                <div class="trust-badge">
                <div class="colored-badge tech-badge">
                    <span>TECH EDU</span>
                </div>
                </div>
                <div class="trust-badge">
                <div class="colored-badge quiz-badge">
                    <span>QUIZ MASTER</span>
                </div>
                </div>
                <div class="trust-badge">
                <div class="colored-badge smart-badge">
                    <span>SMART TEST</span>
                </div>
                </div>
                <div class="trust-badge">
                <div class="colored-badge learn-badge">
                    <span>LEARN PRO</span>
                </div>
                </div>
                <div class="trust-badge">
                <div class="colored-badge labs-badge">
                    <span>EDU LABS</span>
                </div>
                </div>
            </div>
            </div>
        </div>
        </section> --}}

        <!-- Features/Services Section -->
        <section id="features" class="features">
            <div class="container">
                <div class="section-header">
                    <div class="badge badge-primary-light">{{ __('messages.home.features') }}</div>
                    <h2>{{ __('messages.home.features_title') }}</h2>
                    <p>{{ __('messages.home.features_description') }}</p>
                </div>

                <!-- Featured services -->
                <div class="featured-services">
                    <div class="grid-2-col">
                        <!-- Service 1 -->
                        <div class="animate-fade-in-left">
                            <div class="feature-card-gradient">
                                <div class="feature-card-inner">
                                    <div class="feature-icon-large">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <h3>{{ __('messages.home.pdf_to_quiz') }}</h3>
                                    <p class="feature-description">{{ __('messages.home.pdf_to_quiz_description') }}</p>
                                    <div class="feature-benefits">
                                        <div class="feature-benefit">
                                            <div class="benefit-icon">
                                                <svg class="text-green" width="16" height="16" viewBox="0 0 24 24"
                                                    fill="none">
                                                    <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <span>{{ __('messages.home.multiple_choice') }}</span>
                                        </div>
                                        <div class="feature-benefit">
                                            <div class="benefit-icon">
                                                <svg class="text-green" width="16" height="16" viewBox="0 0 24 24"
                                                    fill="none">
                                                    <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <span>{{ __('messages.home.single_choice') }}</span>
                                        </div>
                                        <div class="feature-benefit">
                                            <div class="benefit-icon">
                                                <svg class="text-green" width="16" height="16" viewBox="0 0 24 24"
                                                    fill="none">
                                                    <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <span>{{ __('messages.home.open_ended') }}</span>
                                        </div>
                                        <div class="feature-benefit">
                                            <div class="benefit-icon">
                                                <svg class="text-green" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <span>{{ __('messages.home.explanations') }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Service 2 -->
                        <div class="animate-fade-in-right">
                            <div class="feature-card-gradient">
                                <div class="feature-card-inner">
                                    <div class="feature-icon-large">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18z" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M3.6 9h16.8M3.6 15h16.8" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M12 3a15 15 0 0 1 0 18" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <h3>{{ __('messages.home.url_content') }}</h3>
                                    <p class="feature-description">{{ __('messages.home.url_content_description') }}</p>
                                    <div class="feature-benefits">
                                        <div class="feature-benefit">
                                            <div class="benefit-icon">
                                                <svg class="text-green" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <span>{{ __('messages.home.deep_extraction') }}</span>
                                        </div>
                                        <div class="feature-benefit">
                                            <div class="benefit-icon">
                                                <svg class="text-green" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <span>{{ __('messages.home.context_aware') }}</span>
                                        </div>
                                        <div class="feature-benefit">
                                            <div class="benefit-icon">
                                                <svg class="text-green" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <span>{{ __('messages.home.blogs_articles') }}</span>
                                        </div>
                                        <div class="feature-benefit">
                                            <div class="benefit-icon">
                                                <svg class="text-green" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none">
                                                    <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <span>{{ __('messages.home.multi_page') }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional features -->
                <div class="additional-features">
                    <div class="grid-4-col">
                        <!-- Feature 1 -->
                        <div class="feature-card animate-fade-in" data-delay="0.1">
                            <div class="feature-icon-medium indigo-gradient">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M12 16.01l-3.01 3.01-1.5-1.5 3.01-3.02-3.01-3.01 1.5-1.5 3.01 3.01 3.01-3.01 1.5 1.5-3.01 3.01 3.01 3.02-1.5 1.5-3.01-3.01z"
                                        fill="currentColor" />
                                    <path d="M4 6h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h3>{{ __('messages.home.smart_mcq_questions') }}</h3>
                            <p>
                                {{ __('messages.home.smart_mcq_questions_description') }}
                            </p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="feature-card animate-fade-in" data-delay="0.2">
                            <div class="feature-icon-medium purple-gradient">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h3>{{ __('messages.home.interactive_polls') }}</h3>
                            <p>{{ __('messages.home.interactive_polls_description') }}</p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="feature-card animate-fade-in" data-delay="0.3">
                            <div class="feature-icon-medium green-gradient">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M7 10l5 5 5-5M12 15V3"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h3>{{ __('messages.home.export_results') }}</h3>
                            <p>{{ __('messages.home.export_results_description') }}</p>
                        </div>

                        <!-- Feature 4 -->
                        <div class="feature-card animate-fade-in" data-delay="0.4">
                            <div class="feature-icon-medium orange-gradient">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75M9 7a4 4 0 100-8 4 4 0 000 8z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h3>{{ __('messages.home.collaboration') }}</h3>
                            <p>{{ __('messages.home.collaboration_description') }}</p>
                        </div>
                    </div>
                </div>

                {{-- <div class="text-center mt-lg">
                        <button class="btn btn-primary btn-lg">
                            Explore All Features
                            <svg class="icon-right" width="20" height="20" viewBox="0 0 24 24" fill="none">
                            <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div> --}}
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="about">
            <div class="container">
                <div class="grid-2-col">
                    <div class="about-text animate-fade-in-left">
                        <div class="badge badge-primary-light">{{ __('messages.home.about_quizwhiz') }}</div>
                        <h2>{{ __('messages.home.about_quizwhiz_title') }}</h2>
                        <p class="lead">{{ __('messages.home.about_quizwhiz_description') }}</p>
                        <p>{{ __('messages.home.about_quizwhiz_description_2') }}</p>

                        <div class="about-users">
                            <h3>{{ __('messages.home.who_uses', ['app_name' => getAppName()]) }}</h3>
                            <ul class="user-types">
                                <li>
                                    <div class="check-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                            <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div class="text-light-gray"><strong>{{ __('messages.home.educators') }}</strong>
                                        {{ __('messages.home.educators_description') }}</div>
                                </li>
                                <li>
                                    <div class="check-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                            <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div class="text-light-gray">
                                        <strong>{{ __('messages.home.corporate_trainers') }}</strong>
                                        {{ __('messages.home.corporate_trainers_description') }}
                                    </div>
                                </li>
                                <li>
                                    <div class="check-icon">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                            <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div class="text-light-gray">
                                        <strong>{{ __('messages.home.content_creators') }}</strong>
                                        {{ __('messages.home.content_creators_description') }}
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="about-how-it-works animate-fade-in-right">
                        <div class="how-it-works-card">
                            <h3>{{ __('messages.home.how_it_works') }}</h3>

                            <div class="steps-container">
                                <!-- Step 1 -->
                                <div class="step">
                                    <div class="step-content">
                                        <div class="step-number">1</div>
                                        <div class="step-text">
                                            <h4>{{ __('messages.home.upload_content') }}</h4>
                                            <p class="text-light-gray">
                                                {{ __('messages.home.upload_content_description') }}</p>
                                        </div>
                                    </div>
                                    <div class="step-connector"></div>
                                </div>

                                <!-- Step 2 -->
                                <div class="step">
                                    <div class="step-content">
                                        <div class="step-number">2</div>
                                        <div class="step-text">
                                            <h4>{{ __('messages.home.ai_content_analysis') }}</h4>
                                            <p class="text-light-gray">
                                                {{ __('messages.home.ai_content_analysis_description') }}</p>
                                        </div>
                                    </div>
                                    <div class="step-connector"></div>
                                </div>

                                <!-- Step 3 -->
                                <div class="step">
                                    <div class="step-content">
                                        <div class="step-number">3</div>
                                        <div class="step-text">
                                            <h4>{{ __('messages.home.smart_quiz_generation') }}</h4>
                                            <p class="text-light-gray">
                                                {{ __('messages.home.smart_quiz_generation_description') }}</p>
                                        </div>
                                    </div>
                                    <div class="step-connector"></div>
                                </div>

                                <!-- Step 4 -->
                                <div class="step">
                                    <div class="step-content">
                                        <div class="step-number">4</div>
                                        <div class="step-text">
                                            <h4>{{ __('messages.home.customize_share') }}</h4>
                                            <p class="text-light-gray">
                                                {{ __('messages.home.customize_share_description') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mx-auto d-flex justify-content-center">
                                <a href="@auth {{ auth()->user()->hasRole('admin') ? route('filament.admin.pages.dashboard') : route('filament.user.pages.dashboard') }} @else {{ route('filament.auth.auth.register') }} @endauth"
                                    class="btn btn-primary btn-lg">
                                    {{ __('messages.home.try_it_free') }}
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <!-- Background decorative elements -->
                        <div class="decorative-blob decorative-blob-3"></div>
                        <div class="decorative-blob decorative-blob-4"></div>
                    </div>
                </div>
            </div>
        </section>

        @if ($quizzes->count() > 0)
            @php
                $grouped = $quizzes->groupBy('category_id');

                $categories = $quizzes
                    ->groupBy('category_id')
                    ->mapWithKeys(function ($group, $categoryId) {
                        $category = $group->first()->category;
                        return [
                            $categoryId => $category->name ?? 'Unknown',
                        ];
                    })
                    ->toArray();
            @endphp

            <!-- Latest Quizzes Examples Section -->
            <section id="examples" class="examples">
                <div class="container">
                    <div class="section-header">
                        <div class="badge badge-primary-light">{{ __('messages.dashboard.quizzes') }}</div>
                        <h2>{{ __('messages.home.latest_generated_quizzes') }}</h2>
                        <p class="text-light-gray">{{ __('messages.home.latest_generated_quizzes_description') }}</p>
                    </div>

                    <!-- Category selection -->
                    <div class="category-pills">
                        <button class="pill active" data-category="all">{{ __('messages.home.all_quizzes') }}</button>
                        @foreach ($categories as $id => $category)
                            <button class="pill" data-category="{{ $id }}">{{ $category }}</button>
                        @endforeach
                    </div>

                    <!-- Quiz content containers -->
                    <div id="all-quizzes" class="quiz-category-content active">
                        <!-- Featured quiz with larger card -->
                        @php
                            $allFirstQuiz = collect($quizzes)->first();
                        @endphp
                        <div class="featured-quiz animate-fade-in">
                            <div class="featured-quiz-card">
                                <div class="grid-2-col">
                                    <!-- Left side - Quiz preview -->
                                    <div class="quiz-info">
                                        <div class="quiz-header">
                                            <div class="badge badge-blue">{{ __('messages.home.featured') }} •
                                                {{ $allFirstQuiz->category->name ?? '' }}</div>
                                            <div class="question-count">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                {{ __('messages.home.questions_count', ['count' => collect($allFirstQuiz->questions ?? [])->count()]) }}
                                            </div>
                                        </div>

                                        <h3 class="line-clamp-2">
                                            {{ $allFirstQuiz->title ?? '' }}
                                        </h3>

                                        <p class="line-clamp-4">
                                            {{ $allFirstQuiz->quiz_description ?? '' }}
                                        </p>


                                        <div class="topics">
                                            <div class="topics-title">{{ __('messages.home.topics_covered') }}</div>
                                            <div class="topic-tags">
                                                <span class="topic-tag">{{ __('messages.home.cell_biology') }}</span>
                                                <span class="topic-tag">{{ __('messages.home.genetics') }}</span>
                                                <span class="topic-tag">{{ __('messages.home.evolution') }}</span>
                                                <span class="topic-tag">{{ __('messages.home.ecology') }}</span>
                                            </div>
                                        </div>

                                        <div class="quiz-footer">
                                            <div class="quiz-author">
                                                <div class="author-avatar blue-gradient">
                                                    <img src="{{ $allFirstQuiz->user->profile_url ?? '' }}"
                                                        alt="{{ $allFirstQuiz->user->name ?? '' }}">
                                                </div>
                                                <div class="author-info">
                                                    <div class="author-name">{{ $allFirstQuiz->user->name ?? '' }}</div>
                                                    <div class="quiz-date">
                                                        {{ __('messages.home.generated') }}
                                                        {{ \Carbon\Carbon::parse($allFirstQuiz->created_at)->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>

                                            <a href="{{ route('quiz-player', ['code' => $allFirstQuiz->unique_code]) }}"
                                                target="_blank" class="btn btn-primary icon-btn">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <circle cx="12" cy="12" r="3" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                {{ __('messages.home.preview_quiz') }}
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Right side - Quiz sample -->
                                    @php
                                        $firstQuestion = $allFirstQuiz->questions
                                            ? collect($allFirstQuiz->questions)->first()
                                            : null;
                                    @endphp

                                    @if ($firstQuestion)
                                        <div class="quiz-sample">
                                            <div class="sample-question-card">
                                                <div class="sample-question-header">
                                                    <div class="sample-title">{{ __('messages.home.sample_question') }}
                                                    </div>
                                                    <div class="sample-question line-clamp-4">
                                                        {{ $firstQuestion->title ?? '' }}</div>
                                                </div>

                                                <div class="sample-options">
                                                    @foreach ($firstQuestion->answers as $answer)
                                                        <div
                                                            class="sample-option {{ $answer->is_correct ? 'selected' : '' }}">
                                                            @if ($answer->is_correct)
                                                                <div class="option-circle selected">
                                                                    <svg width="10" height="10"
                                                                        viewBox="0 0 24 24" fill="none">
                                                                        <path d="M5 13l4 4L19 7" stroke="currentColor"
                                                                            stroke-width="3" stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                    </svg>
                                                                </div>
                                                            @else
                                                                <div class="option-circle"></div>
                                                            @endif
                                                            <span>{{ $answer->title }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Additional quizzes in grid -->
                        <div class="quizzes-grid">
                            @php
                                $allQuizzes = collect($quizzes)->skip(1)->take(3);
                            @endphp

                            <!-- Quiz 1 -->
                            @foreach ($allQuizzes as $quiz)
                                <div class="quiz-card animate-fade-in" data-delay="0.{{ $loop->index }}">
                                    <div
                                        class="quiz-card-header {{ $loop->index == 1 ? 'green-gradient' : ($loop->index == 2 ? 'orange-gradient' : 'indigo-gradient') }}">
                                    </div>
                                    <div class="quiz-card-content">
                                        <div class="quiz-card-top">
                                            <div
                                                class="badge {{ $loop->index == 1 ? 'badge-emerald' : ($loop->index == 2 ? 'badge-amber' : 'badge-indigo') }}">
                                                {{ $quiz->category->name ?? '' }}</div>
                                            <div class="questions-tag">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                                    <path
                                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                {{ __('messages.home.questions_count', ['count' => collect($quiz->questions ?? [])->count()]) }}
                                            </div>
                                        </div>
                                        <h3 class="line-clamp-1">{{ $quiz->title ?? '' }}</h3>
                                        <p class="line-clamp-2">{{ $quiz->quiz_description ?? '' }}</p>
                                        <div class="quiz-card-footer">
                                            <div class="quiz-author">
                                                <div
                                                    class="author-avatar {{ $loop->index == 1 ? 'green-gradient' : ($loop->index == 2 ? 'orange-gradient' : 'indigo-gradient') }}">
                                                    <img src="{{ $quiz->user->profile_url ?? '' }}"
                                                        alt="{{ $quiz->user->name ?? '' }}">
                                                </div>
                                                <div class="author-info">
                                                    <div class="author-name">{{ $quiz->user->name ?? '' }}</div>
                                                    <div class="quiz-date">
                                                        {{ __('messages.home.generated') }}
                                                        {{ \Carbon\Carbon::parse($quiz->created_at)->diffForHumans() }}
                                                    </div>
                                                </div>
                                            </div>

                                            <a href="{{ route('quiz-player', ['code' => $quiz->unique_code]) }}"
                                                target="_blank"
                                                class="btn btn-outline btn-sm indigo">{{ __('messages.home.view') }}</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>

                    @foreach ($categories as $id => $category)
                        <div id="{{ $id }}-quizzes" class="quiz-category-content">
                            @php
                                $categoryQuizzes = $quizzes->where('category_id', $id);
                                $categoryFirstQuiz = $categoryQuizzes->first();
                            @endphp
                            <div class="featured-quiz animate-fade-in">
                                <div class="featured-quiz-card">
                                    <div class="grid-2-col">
                                        <!-- Left side - Quiz preview -->
                                        <div class="quiz-info">
                                            <div class="quiz-header">
                                                <div class="badge badge-blue">{{ __('messages.home.featured') }} •
                                                    {{ $categoryFirstQuiz->category->name ?? '' }}</div>
                                                <div class="question-count">
                                                    <svg width="14" height="14" viewBox="0 0 24 24"
                                                        fill="none">
                                                        <path
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                    {{ __('messages.home.questions_count', ['count' => collect($categoryFirstQuiz->questions ?? [])->count()]) }}
                                                </div>
                                            </div>

                                            <h3 class="line-clamp-2">{{ $categoryFirstQuiz->title ?? '' }}</h3>
                                            <p class="line-clamp-4">{{ $categoryFirstQuiz->quiz_description ?? '' }}</p>

                                            <div class="topics">
                                                <div class="topics-title">{{ __('messages.home.topics_covered') }}</div>
                                                <div class="topic-tags">
                                                    <span class="topic-tag">{{ __('messages.home.cell_biology') }}</span>
                                                    <span class="topic-tag">{{ __('messages.home.genetics') }}</span>
                                                    <span class="topic-tag">{{ __('messages.home.evolution') }}</span>
                                                    <span class="topic-tag">{{ __('messages.home.ecology') }}</span>
                                                </div>
                                            </div>

                                            <div class="quiz-footer">
                                                <div class="quiz-author">
                                                    <div class="author-avatar blue-gradient">
                                                        <img src="{{ $categoryFirstQuiz->user->profile_url ?? '' }}"
                                                            alt="{{ $categoryFirstQuiz->user->name ?? '' }}">
                                                    </div>
                                                    <div class="author-info">
                                                        <div class="author-name">
                                                            {{ $categoryFirstQuiz->user->name ?? '' }}</div>
                                                        <div class="quiz-date">
                                                            {{ __('messages.home.generated') }}
                                                            {{ \Carbon\Carbon::parse($categoryFirstQuiz->created_at)->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <a href="{{ route('quiz-player', ['code' => $categoryFirstQuiz->unique_code]) }}"
                                                    target="_blank" class="btn btn-primary icon-btn">
                                                    <svg width="16" height="16" viewBox="0 0 24 24"
                                                        fill="none">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                        <circle cx="12" cy="12" r="3" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                    {{ __('messages.home.preview_quiz') }}
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Right side - Quiz sample -->
                                        @php
                                            $categoryFirstQuestion = $categoryFirstQuiz->questions
                                                ? collect($categoryFirstQuiz->questions)->first()
                                                : null;
                                        @endphp

                                        @if ($categoryFirstQuestion)
                                            <div class="quiz-sample">
                                                <div class="sample-question-card">
                                                    <div class="sample-question-header">
                                                        <div class="sample-title">
                                                            {{ __('messages.home.sample_question') }}</div>
                                                        <div class="sample-question line-clamp-4">
                                                            {{ $categoryFirstQuestion->title ?? '' }}</div>
                                                    </div>

                                                    <div class="sample-options">
                                                        @foreach ($categoryFirstQuestion->answers as $answer)
                                                            <div
                                                                class="sample-option {{ $answer->is_correct ? 'selected' : '' }}">
                                                                @if ($answer->is_correct)
                                                                    <div class="option-circle selected">
                                                                        <svg width="10" height="10"
                                                                            viewBox="0 0 24 24" fill="none">
                                                                            <path d="M5 13l4 4L19 7" stroke="currentColor"
                                                                                stroke-width="3" stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                    </div>
                                                                @else
                                                                    <div class="option-circle"></div>
                                                                @endif
                                                                <span>{{ $answer->title }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- Additional quizzes in grid -->
                            <div class="quizzes-grid">
                                @php
                                    $allCategoryQuizzes = collect($categoryQuizzes)->skip(1)->take(3);
                                @endphp

                                <!-- Quiz 1 -->
                                @foreach ($allCategoryQuizzes as $quiz)
                                    <div class="quiz-card animate-fade-in" data-delay="0.{{ $loop->index }}">
                                        <div
                                            class="quiz-card-header {{ $loop->index == 1 ? 'green-gradient' : ($loop->index == 2 ? 'orange-gradient' : 'indigo-gradient') }}">
                                        </div>
                                        <div class="quiz-card-content">
                                            <div class="quiz-card-top">
                                                <div
                                                    class="badge {{ $loop->index == 1 ? 'badge-emerald' : ($loop->index == 2 ? 'badge-amber' : 'badge-indigo') }}">
                                                    {{ $quiz->category->name ?? '' }}</div>
                                                <div class="questions-tag">
                                                    <svg width="14" height="14" viewBox="0 0 24 24"
                                                        fill="none">
                                                        <path
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                    {{ __('messages.home.questions_count', ['count' => collect($quiz->questions ?? [])->count()]) }}
                                                </div>
                                            </div>
                                            <h3 class="line-clamp-2">{{ $quiz->title ?? '' }}</h3>
                                            <p class="line-clamp-4">{{ $quiz->quiz_description ?? '' }}</p>
                                            <div class="quiz-card-footer">
                                                <div class="quiz-author">
                                                    <div
                                                        class="author-avatar {{ $loop->index == 1 ? 'green-gradient' : ($loop->index == 2 ? 'orange-gradient' : 'indigo-gradient') }}">
                                                        <img src="{{ $quiz->user->profile_url ?? '' }}"
                                                            alt="{{ $quiz->user->name ?? '' }}">
                                                    </div>
                                                    <div class="author-info">
                                                        <div class="author-name">{{ $quiz->user->name ?? '' }}
                                                        </div>
                                                        <div class="quiz-date">
                                                            {{ __('messages.home.generated') }}
                                                            {{ \Carbon\Carbon::parse($quiz->created_at)->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <a href="{{ route('quiz-player', ['code' => $quiz->unique_code]) }}"
                                                    target="_blank"
                                                    class="btn btn-outline btn-sm indigo">{{ __('messages.home.view') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @endforeach

                    {{-- <div class="text-center mt-lg">
                        <button class="btn btn-light-primary btn-lg">
                            Browse All Examples
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div> --}}
                </div>
            </section>
        @endif


        @if ($testimonials->count())
            <!-- Testimonials Section -->
            <section id="testimonials" class="testimonials">
                <div class="container">
                    <div class="section-header">
                        <div class="badge badge-primary-light">{{ __('messages.home.testimonials') }}</div>
                        <h2>{{ __('messages.home.testimonials_title') }}</h2>
                        <p class="text-light-gray">{{ __('messages.home.testimonials_description') }}</p>
                    </div>

                    <div class="testimonial-slider-container">
                        <button class="slider-arrow slider-prev">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>

                        <div class="testimonial-slider">

                            @foreach ($testimonials->chunk(2) as $twoTestimonial)
                                <div class="testimonial-slide {{ $loop->first ? 'active' : '' }}">
                                    <div class="testimonial-cards">

                                        @foreach ($twoTestimonial as $testimonial)
                                            <div class="testimonial-card">
                                                <div class="testimonial-profile">
                                                    <div class="testimonial-avatar">
                                                        <img src="{{ $testimonial->icon }}"
                                                            alt="{{ $testimonial->name }}">
                                                    </div>
                                                    <div>
                                                        <h4>{{ $testimonial->name }}</h4>
                                                        <p>{{ $testimonial->role }}</p>
                                                    </div>
                                                </div>
                                                <p class="testimonial-text">
                                                    "{{ $testimonial->description }}"
                                                </p>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            @endforeach

                        </div>

                        <button class="slider-arrow slider-next">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>

                    <!-- Slider dots -->
                    <div class="slider-dots">
                        @foreach ($testimonials->chunk(2) as $twoTestimonial)
                            <button class="slider-dot {{ $loop->first ? 'active' : '' }}"
                                data-slide="{{ $loop->index }}"></button>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif


        @if ($plans->count())
            <!-- Pricing Section -->
            <section id="pricing" class="pricing">
                <div class="container">
                    <div class="section-header pricing">
                        <div class="badge badge-purple-light">{{ __('messages.home.pricing') }}</div>
                        <h2>{{ __('messages.home.pricing_title') }}</h2>
                        <p>{{ __('messages.home.pricing_description') }}</p>
                    </div>

                    <div class="pricing-grid">

                        @foreach ($plans as $plan)
                            <div class="pricing-card animate-fade-in {{ $loop->index == 1 ? 'popular' : '' }}">
                                @if ($loop->index == 1)
                                    <span class="popular-badge">{{ __('messages.home.popular') }}</span>
                                @endif
                                <div class="pricing-header">
                                    <h3>{{ $plan->name }}</h3>
                                    @if (getCurrencyPosition())
                                        <div class="price">{{ $plan->currency->symbol ?? '' }}
                                            {{ $plan->price ?? 0 }} /
                                            <span
                                                class="frequency">{{ __(\App\Enums\PlanFrequency::from($plan->frequency)->getLabel()) }}</span>
                                        </div>
                                    @else
                                        <div class="price">
                                            {{ $plan->price ?? 0 }} {{ $plan->currency->symbol ?? '' }}
                                            <span
                                                class="frequency">{{ __(\App\Enums\PlanFrequency::from($plan->frequency)->getLabel()) }}</span>
                                        </div>
                                    @endif
                                    <p>{{ $plan->description }}</p>
                                </div>

                                <div class="pricing-divider"></div>
                                <ul class="pricing-features">
                                    <li class="feature-item">
                                        <svg class="feature-icon" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none">
                                            <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span>Create up to {{ $plan->no_of_quiz ?? 0 }} quizzes</span>
                                    </li>
                                    @if ($loop->index == 0)
                                        <li class="feature-item disabled">
                                            <svg class="feature-icon" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none">
                                                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Generate quizzes from PDFs/URLs</span>
                                        </li>
                                        <li class="feature-item disabled">
                                            <svg class="feature-icon" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none">
                                                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Display leaderboard</span>
                                        </li>
                                        <li class="feature-item disabled">
                                            <svg class="feature-icon" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none">
                                                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Share results with participants</span>
                                        </li>
                                        <li class="feature-item disabled">
                                            <svg class="feature-icon" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none">
                                                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Email participants</span>
                                        </li>
                                    @elseif ($loop->index == 1)
                                        <li class="feature-item">
                                            <svg class="feature-icon" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none">
                                                <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Generate quizzes from PDFs/URLs</span>
                                        </li>
                                        <li class="feature-item">
                                            <svg class="feature-icon" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none">
                                                <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Display leaderboard</span>
                                        </li>
                                        <li class="feature-item disabled">
                                            <svg class="feature-icon" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none">
                                                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Share results with participants</span>
                                        </li>
                                        <li class="feature-item disabled">
                                            <svg class="feature-icon" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none">
                                                <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Email participants</span>
                                        </li>
                                    @else
                                        <li class="feature-item">
                                            <svg class="feature-icon" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none">
                                                <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Generate quizzes from PDFs/URLs</span>
                                        </li>
                                        <li class="feature-item">
                                            <svg class="feature-icon" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none">
                                                <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Display leaderboard</span>
                                        </li>
                                        <li class="feature-item">
                                            <svg class="feature-icon" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none">
                                                <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Share results with participants</span>
                                        </li>
                                        <li class="feature-item">
                                            <svg class="feature-icon" width="20" height="20" viewBox="0 0 24 24"
                                                fill="none">
                                                <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <span>Email participants</span>
                                        </li>
                                    @endif
                                </ul>

                                @auth
                                    @if (getActiveSubscription() && getActiveSubscription()->plan_id == $plan->id)
                                        <button class="btn btn-outline btn-bg-white popular  full-width">
                                            {{ __('messages.subscription.currently_active') }}
                                        </button>
                                    @else
                                        @role('user')
                                            <a class="btn btn-outline btn-bg-white popular full-width"
                                                href="{{ route('filament.user.pages.choose-payment-type', ['plan' => $plan['id']]) }}">{{ __('messages.subscription.choose_plan') }}</a>
                                        @endrole
                                    @endif
                                @else
                                    <a class="btn btn-outline btn-bg-white popular full-width"
                                        href="{{ route('filament.auth.auth.register') }}">{{ __('messages.home.sign_up_free') }}</a>
                                @endauth

                            </div>
                        @endforeach

                    </div>

                    {{-- <div class="enterprise-card">
                        <h3>Need a custom solution?</h3>
                        <p>Contact us for enterprise plans with custom integrations, dedicated support, and advanced
                            features.</p>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-outline icon-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Contact Sales
                            </button>
                        </div>
                    </div> --}}
                </div>
            </section>
        @endif

        <!-- FAQ Section -->
        @if ($faqs->count() > 0)
            <section id="faq" class="faq">
                <div class="container">
                    <div class="section-header">
                        <div class="badge badge-primary-light">{{ __('messages.home.faq') }}</div>
                        <h2>{{ __('messages.home.faq_title') }}</h2>
                        <p class="text-light-gray">{{ __('messages.home.faq_description') }}</p>
                    </div>

                    <div class="faq-container">
                        @foreach ($faqs as $faq)
                            <div class="faq-item">
                                <div class="faq-question" data-accordion="faq-{{ $loop->index }}">
                                    <h3>{{ $faq->question }}</h3>
                                    <svg class="faq-icon" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none">
                                        <path d="M19 9l-7 7-7-7" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="faq-answer" id="faq-{{ $loop->index }}">
                                    <p>{{ $faq->answer }}</p>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    {{-- <div class="faq-footer">
                    <p>Still have questions?</p>
                    <div class="faq-actions">
                        <button class="btn btn-outline btn-lg icon-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            Contact Support
                        </button>
                        <button class="btn btn-outline btn-lg icon-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3V2z"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            Join Community
                        </button>
                    </div>
                </div> --}}
                </div>
            </section>
        @endif

        <!-- Call to Action -->
        <section class="cta">
            <div class="container">
                <div class="cta-content">
                    <h2>{{ __('messages.home.transform_content_into_learning') }}</h2>
                    <p>{{ __('messages.home.transform_content_into_learning_description') }}</p>
                    <div class="cta-buttons">
                        <a href="@auth {{ auth()->user()->hasRole('admin') ? route('filament.admin.pages.dashboard') : route('filament.user.pages.dashboard') }} @else {{ route('filament.auth.auth.register') }} @endauth"
                            class="btn btn-white">{{ __('messages.home.get_started_free') }}</a>
                        {{-- <button class="btn btn-outline-white">View Demo</button> --}}
                    </div>
                </div>
            </div>
        </section>
    </main>

@endsection
