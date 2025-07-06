<div>
    <h1 class="fi-header-heading text-2xl font-bold tracking-tight text-gray-950 dark:text-white sm:text-3xl pb-4">
        {{ __('messages.poll.poll_result') }}
    </h1>
    <x-filament::section>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">{{ $pollResults['question'] ?? '' }}</h3>
        </div>
        <hr class="border-gray-950/5 dark:border-white/10 mb-4">

        <div class="grid gap-4">
            <div class="w-full flex flex-col space-y-4">
                {{-- <div
                    class="p-2 flex justify-center rounded-lg shadow-md ring-1 ring-inset bg-custom-50 ring-custom-600/10 dark:bg-custom-400/10">
                    <span class="text-base text-gray-700 dark:text-gray-200">{{ $pollResults['question'] ?? '' }}</span>
                </div> --}}

                @foreach ($pollResults['options'] as $option)
                    <div class="flex justify-between my-4 gap-3 items-center text-base">

                        <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700 h-10 overflow-hidden">
                            <div class="bg-gray-200 text-xs font-medium text-gray-700 dark:text-white text-center leading-none rounded-full"
                                style="width: {{ $option['percentage'] }}%; height: 100%; background-color: {{ $option['percentage'] > 0 ? $option['color'] : 'gray' }};">
                                <p class="text-sm whitespace-nowrap pt-2 ps-2  text-center">{{ $option['label'] }}</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-gray-700 dark:text-gray-200">{{ $option['percentage'] }}%</span>
                            <span
                                class="text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">{{ number_format($option['count']) }}
                                {{ __('messages.poll.votes') }}</span>
                        </div>
                    </div>
                    <hr class="border-gray-950/5 dark:border-white/10">
                @endforeach
            </div>
        </div>
    </x-filament::section>
</div>
