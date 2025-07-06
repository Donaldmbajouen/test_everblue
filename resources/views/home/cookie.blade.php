@extends('layout.app')

@section('title')
    {{ __('messages.home.cookie_policy') }}
@endsection

@section('content')
    <main>

        <!-- FAQ Section -->
        <section id="faq" class="faq">
            <div class="container">
                <div class="section-header">
                    {{-- <div class="badge badge-primary-light">{{ __('messages.home.faq') }}</div> --}}
                    <h2>{{ __('messages.home.cookie_policy') }}</h2>
                    {{-- <p class="text-light-gray">{{ __('messages.home.faq_description') }}</p> --}}
                </div>

                <div class="page-content">
                    {!! $cookie !!}
                </div>
            </div>
        </section>

    </main>
@endsection
