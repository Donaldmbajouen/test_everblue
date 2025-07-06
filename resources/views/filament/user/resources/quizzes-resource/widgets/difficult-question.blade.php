@php
    $widgetClass = 'parent-h-full h-full';
@endphp

<x-filament-widgets::widget :class="$widgetClass">
    <x-filament::section class="h-full">

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">{{ __('messages.quiz_report.top_difficult_questions') }}</h3>
        </div>

        <div class="grid gap-4">

            @forelse ($questions as $question)
                <div style="--c-50:var(--sky-50);--c-400:var(--sky-400);--c-600:var(--sky-600);"
                    class="p-2 space-x-4 rounded-lg shadow-md ring-1 ring-inset bg-custom-50 ring-custom-600/10 dark:bg-custom-400/10 text-sm">
                    <div class="flex flex-col gap-1 p-2">
                        <span>{{ $question['title'] }}</span>
                    </div>
                    <hr class="border-gray-950/5 dark:border-white/10">
                    <div class="flex items-center justify-between text-center mt-2">
                        <span class="text-center flex items-center justify-center w-full gap-2">
                            <div class="w-5 h-5"
                                style="border-radius: 50%; background-image: conic-gradient(#07b007a1 0%, #07b007a1 {{ $question['currentPercentage'] }}%, red {{ $question['currentPercentage'] }}%, red 100%);">
                            </div>
                            {{ $question['currentPercentage'] }}% {{ __('messages.common.correct') }}
                        </span>
                        <div class="w-px h-8 bg-gray-200 dark:bg-white/10"></div>
                        <span
                            class="text-center flex items-center justify-center w-full">{{ __('messages.common.avg') . '.' }}
                            {{ $question['avgTime'] . ' ' . __('messages.common.sec') }} </span>
                    </div>
                </div>
            @empty
                <div style="--c-50:var(--primary-50);--c-400:var(--primary-400);--c-600:var(--primary-600);"
                    class="p-2 space-x-4 rounded-lg shadow-md ring-1 ring-inset bg-custom-50 ring-custom-600/10 dark:bg-custom-400/10 text-sm">
                    <div class="flex flex-col gap-1 p-2 text-center">
                        <span>{{ __('messages.quiz_report.no_questions_found') }}</span>
                    </div>
                </div>
            @endforelse

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
