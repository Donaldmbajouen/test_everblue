<x-filament-widgets::widget>
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4 ">

        <div class="flex">
            <div class="admin-dashboard-card flex w-full max-w-full flex-col break-words rounded-lg text-white p-4">
                <div class="flex justify-end">
                    <div class="icon flex items-center justify-center w-12 h-12">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-11 w-11" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                        </svg>
                    </div>
                </div>
                <div class="py-5 my-5"></div>
                <div class="pt-3">
                    <p class="text-sm font-medium opacity-70">{{ __('messages.dashboard.active_users') }}</p>
                    <h4 class="text-3xl font-bold tracking-tight xl:text-2xl">{{ $activeUsers }}</h4>
                    <p class="text-sm opacity-70">
                        <span>{{ __('messages.dashboard.total_users') . ':' }}</span>
                        <span>{{ $totalUser }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex">
            <div class="admin-dashboard-card flex w-full max-w-full flex-col break-words rounded-lg text-white p-4">
                <div class="flex justify-end">
                    <div class="icon flex items-center justify-center w-12 h-12 rounded-full bg-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-11 w-11" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                        </svg>
                    </div>
                </div>
                <div class="py-5 my-5"></div>
                <div class="pt-3">
                    <p class="text-sm font-medium opacity-70">{{ __('messages.dashboard.paid_users') }}</p>
                    <h4 class="text-3xl font-bold tracking-tight xl:text-2xl">{{ $paidUser }}</h4>
                    <p class="text-sm opacity-70">
                        <span>{{ __('messages.dashboard.total') . ' ' . number_format($payableAmount) }}</span>
                        <span>{{ __('messages.dashboard.earnings') }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex">
            <div class="admin-dashboard-card flex w-full max-w-full flex-col break-words rounded-lg text-white p-4">
                <div class="flex justify-end">
                    <div class="icon flex items-center justify-center w-12 h-12 rounded-full bg-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-11 w-11" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                </div>
                <div class="py-5 my-5"></div>
                <div class="pt-3">
                    <p class="text-sm font-medium opacity-70">{{ __('messages.dashboard.active_quizzes') }}</p>
                    <h4 class="text-3xl font-bold tracking-tight xl:text-2xl">{{ $activeQuiz }}</h4>
                    <p class="text-sm opacity-70">
                        <span>{{ __('messages.dashboard.total_quizzes') . ':' }}</span>
                        <span>{{ $totalQuiz }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex">
            <div class="admin-dashboard-card flex w-full max-w-full flex-col break-words rounded-lg text-white p-4">
                <div class="flex justify-end">
                    <div class="icon flex items-center justify-center w-12 h-12 rounded-full bg-white/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-11 w-11" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path
                                d="M7 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM14.5 9a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5ZM1.615 16.428a1.224 1.224 0 0 1-.569-1.175 6.002 6.002 0 0 1 11.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 0 1 7 18a9.953 9.953 0 0 1-5.385-1.572ZM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 0 0-1.588-3.755 4.502 4.502 0 0 1 5.874 2.636.818.818 0 0 1-.36.98A7.465 7.465 0 0 1 14.5 16Z">
                            </path>
                        </svg>
                        {{-- <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                            <path
                                d="M7 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM14.5 9a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5ZM1.615 16.428a1.224 1.224 0 0 1-.569-1.175 6.002 6.002 0 0 1 11.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 0 1 7 18a9.953 9.953 0 0 1-5.385-1.572ZM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 0 0-1.588-3.755 4.502 4.502 0 0 1 5.874 2.636.818.818 0 0 1-.36.98A7.465 7.465 0 0 1 14.5 16Z">
                            </path>
                        </svg> --}}
                    </div>
                </div>
                <div class="py-5 my-5"></div>
                <div class="pt-3">
                    <p class="text-sm font-medium opacity-70">{{ __('messages.dashboard.participants') }}</p>
                    <h4 class="text-3xl font-bold tracking-tight xl:text-2xl">{{ $participant }}</h4>
                    <p class="text-sm opacity-70">
                        <span>{{ $completedQuiz }}%</span>
                        <span>{{ __('messages.dashboard.participants_completed_quiz') }}</span>
                    </p>
                </div>
            </div>
        </div>

    </div>
</x-filament-widgets::widget>
