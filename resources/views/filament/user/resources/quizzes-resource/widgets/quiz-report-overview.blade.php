<div>
    <div class="flex flex-wrap gap-4">
        <div class="flex-1 sm:flex-1 items-stretch">
            <div
                class="grid gap-y-2 rounded-xl p-6 bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 w-full h-full">
                <span class="flex items-center justify-between text-xl font-medium text-gray-950 dark:text-white">
                    <span class="flex items-center gap-2">
                        <x-filament::icon-button icon="heroicon-o-user-group" size="lg" />
                        {{ __('messages.quiz_report.players') }}
                    </span>
                    <span>{{ $players }}</span>
                </span>
                <hr class="border-gray-950/5 dark:border-white/10">
                <span class="flex items-center justify-between text-xl font-medium text-gray-950 dark:text-white">
                    <span class="flex items-center gap-2">
                        <x-filament::icon-button icon="heroicon-o-academic-cap" size="lg" />
                        {{ __('messages.common.questions') }}
                    </span>
                    <span>{{ $questions }}</span>
                </span>
                <hr class="border-gray-950/5 dark:border-white/10">
                <span class="flex items-center justify-between text-xl font-medium text-gray-950 dark:text-white">
                    <span class="flex items-center gap-2">
                        <x-filament::icon-button icon="heroicon-o-clock" size="lg" />
                        {{ __('messages.quiz_report.time') }}
                    </span>
                    <span>{{ $time }}</span>
                </span>
            </div>
        </div>

        <div class="flex-1 flex flex-col gap-4">
            <div
                class="rounded-xl p-6 bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 w-full h-full">
                <div class="flex items-center">
                    <div style="--c-50:var(--primary-50);--c-400:var(--primary-400);--c-600:var(--primary-600);"
                        class="w-16 h-16 rounded-xl flex items-center justify-center bg-custom-50 dark:bg-custom-400/10 ring-1 ring-inset ring-custom-600/10 dark:ring-custom-400/30 me-4">
                        <x-filament::icon-button icon="heroicon-m-eye" size="xl" color="primary" />
                    </div>
                    <div class="grid gap-y-2">
                        <span
                            class="fi-wi-stats-overview-stat-label text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ __('messages.quiz_report.views') }}
                        </span>
                        <div
                            class="fi-wi-stats-overview-stat-value text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                            {{ $views }}
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="rounded-xl p-6 bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 w-full h-full">
                <div class="flex items-center">
                    <div style="--c-50:var(--primary-50);--c-400:var(--primary-400);--c-600:var(--primary-600);"
                        class="w-16 h-16 rounded-xl flex items-center justify-center bg-custom-50 dark:bg-custom-400/10 ring-1 ring-inset ring-custom-600/10 dark:ring-custom-400/30 me-4">
                        <x-filament::icon-button icon="heroicon-m-sparkles" size="xl" />
                    </div>
                    <div class="grid gap-y-2">
                        <span
                            class="fi-wi-stats-overview-stat-label text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ __('messages.quiz_report.started') }}
                        </span>
                        <div
                            class="fi-wi-stats-overview-stat-value text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                            {{ $startedPercentage }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-1 flex flex-col gap-4">
            <div
                class="rounded-xl p-6 bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 w-full h-full">
                <div class="flex items-center">
                    <div style="--c-50:var(--primary-50);--c-400:var(--primary-400);--c-600:var(--primary-600);"
                        class="w-16 h-16 rounded-xl flex items-center justify-center bg-custom-50 dark:bg-custom-400/10 ring-1 ring-inset ring-custom-600/10 dark:ring-custom-400/30 me-4">
                        <x-filament::icon-button icon="heroicon-m-clock" size="xl" />
                    </div>
                    <div class="grid gap-y-2">
                        <span
                            class="fi-wi-stats-overview-stat-label text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ __('messages.quiz_report.average_time') }}
                        </span>
                        <div
                            class="fi-wi-stats-overview-stat-value text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                            {{ $avgTime }}
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="rounded-xl p-6 bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 w-full h-full">
                <div class="flex items-center">
                    <div style="--c-50:var(--primary-50);--c-400:var(--primary-400);--c-600:var(--primary-600);"
                        class="w-16 h-16 rounded-xl flex items-center justify-center bg-custom-50 dark:bg-custom-400/10 ring-1 ring-inset ring-custom-600/10 dark:ring-custom-400/30 me-4">
                        <x-filament::icon-button icon="heroicon-m-check-badge" size="xl" />
                    </div>
                    <div class="grid gap-y-2">
                        <span
                            class="fi-wi-stats-overview-stat-label text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ __('messages.quiz_report.completed') }}
                        </span>
                        <div
                            class="fi-wi-stats-overview-stat-value text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">
                            {{ $completedPercentage }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
