@php
    $widgetClass = 'parent-h-full h-full';
@endphp

<x-filament-widgets::widget :class="$widgetClass">

    <x-filament::section class="h-full">

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">{{ __('messages.quiz_report.top_scoring_participants') }}</h3>
        </div>

        <div class="grid gap-6 pt-2">

            @forelse ($participants as $participant)
                <div style="--c-50:var(--sky-50);--c-400:var(--sky-400);--c-600:var(--sky-600);"
                    class="flex items-center p-4 py-5 space-x-4 bg-custom-50 rounded-lg shadow-md dark:bg-custom-400/10">
                    <div class="flex items-center justify-between mx-3 gap-2 w-full">
                        <div class="flex flex-col gap-1">
                            <h4 class="text-sm font-medium">{{ $participant['name'] }}</h4>
                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $participant['email'] }}</span>
                        </div>
                        <div style="--c-50:var(--sky-50);--c-400:var(--sky-400);--c-600:var(--sky-600);"
                            class="items-stretch px-4 py-2 bg-white rounded-lg dark:text-gray-200 dark:bg-gray-900 ring-1 ring-inset ring-custom-600/10 dark:ring-white/10">
                            <p class="text-sm font-medium text-gray-800"> {{ $participant['percentage'] }} %</p>
                        </div>
                    </div>
                </div>
            @empty
                <div style="--c-50:var(--primary-50);--c-400:var(--primary-400);--c-600:var(--primary-600);"
                    class="p-2 space-x-4 rounded-lg shadow-md ring-1 ring-inset bg-custom-50 ring-custom-600/10 dark:bg-custom-400/10 text-sm">
                    <div class="flex flex-col gap-1 p-2 text-center">
                        <span>{{ __('messages.participant.no_participants_found') }}</span>
                    </div>
                </div>
            @endforelse

        </div>
    </x-filament::section>
</x-filament-widgets::widget>
