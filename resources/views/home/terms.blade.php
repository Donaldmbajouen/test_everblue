@extends('layout.app')

@section('title')
    {{ __('messages.home.terms_and_conditions') }}
@endsection

@section('content')
    <main>

        <!-- FAQ Section -->
        <section id="faq" class="faq">
            <div class="container">
                <div class="section-header">
                    {{-- <div class="badge badge-primary-light">{{ __('messages.home.faq') }}</div> --}}
                    <h2>{{ __('messages.home.terms_and_conditions') }}</h2>
                    {{-- <p class="text-light-gray">{{ __('messages.home.faq_description') }}</p> --}}
                </div>

                <div class="page-content">
                    {!! $terms !!}
                </div>
            </div>
        </section>

    </main>
@endsection
