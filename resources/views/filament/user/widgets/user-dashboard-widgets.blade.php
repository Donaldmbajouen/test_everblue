<x-filament-widgets::widget>
    <div class=" grid gap-6 md:grid-cols-2 xl:grid-cols-4 custom-grids">

        <div class="flex">
            <div class="admin-dashboard-card flex w-full max-w-full flex-col break-words rounded-lg text-white p-4">
                <div class="flex justify-end">
                    <div class="icon flex items-center justify-center w-12 h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-11 w-11" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z" />
                        </svg>
                    </div>
                </div>
                <div class="py-5 my-5"></div>
                <div class="pt-3">
                    <p class="text-sm font-medium opacity-70">{{ __('messages.dashboard.expiry_date') }}</p>
                    <h4 class="text-3xl font-bold tracking-tight xl:text-2xl">
                        {{ isset($subscription) ? date('d/m/Y', strtotime($subscription->ends_at)) : __('messages.common.n/a') }}
                    </h4>
                    <p class="text-sm opacity-70">
                        <span>{{ __('messages.dashboard.your_current_plan') . ':' }}</span>
                        <span
                            class="font-semibold text-primary">{{ isset($subscription) ? $subscription->plan->name : __('messages.common.n/a') }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex">
            <div class="admin-dashboard-card flex w-full max-w-full flex-col break-words rounded-lg text-white p-4">
                <div class="flex justify-end">
                    <div class="icon flex items-center justify-center w-12 h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-11 w-11" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="py-5 my-5"></div>
                <div class="pt-3">
                    <p class="text-sm font-medium opacity-70">{{ __('messages.dashboard.active_quizzes') }}</p>
                    <h4 class="text-3xl font-bold tracking-tight xl:text-2xl">{{ $activeQuizzes }}</h4>
                    <p class="text-sm opacity-70">
                        <span>{{ __('messages.dashboard.total_quizzes') . ':' }}</span>
                        <span>{{ $totalQuizzes }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex">
            <div class="admin-dashboard-card flex w-full max-w-full flex-col break-words rounded-lg text-white p-4">
                <div class="flex justify-end">
                    <div class="icon flex items-center justify-center w-12 h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-11 w-11" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="py-5 my-5"></div>
                <div class="pt-3">
                    <p class="text-sm font-medium opacity-70">{{ __('messages.dashboard.participants') }}</p>
                    <h4 class="text-3xl font-bold tracking-tight xl:text-2xl">{{ $participants }}</h4>
                    <p class="text-sm opacity-70">
                        <span>{{ $completedPer }}%</span>
                        <span>{{ __('messages.dashboard.participants_completed_quiz') }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex">
            <div class="admin-dashboard-card flex w-full max-w-full flex-col break-words rounded-lg text-white p-4">
                <div class="flex justify-end">
                    <div class="icon flex items-center justify-center w-12 h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-11 w-11" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                        </svg>
                    </div>
                </div>
                <div class="py-5 my-5"></div>
                <div class="pt-3">
                    <p class="text-sm font-medium opacity-70">{{ __('messages.subscription.no_of_quiz') }}</p>
                    <h4 class="text-3xl font-bold tracking-tight xl:text-2xl">
                        {{ isset($subscription) ? $subscription->plan->no_of_quiz ?? __('messages.common.n/a') : __('messages.common.n/a') }}
                    </h4>

                    <p class="text-sm opacity-70">
                        <span>Total Used Quizzes : </span>
                        <span>{{ $totalQuizzes }}</span>
                    </p>
                </div>
            </div>
        </div>

    </div>
</x-filament-widgets::widget>
