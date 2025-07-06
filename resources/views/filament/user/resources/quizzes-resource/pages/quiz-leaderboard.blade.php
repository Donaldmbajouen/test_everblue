<x-filament-panels::page>

    @if (count($topThree) > 0)
        <div>
            <div class="flex flex-wrap gap-4">
                @foreach ($topThree as $user)
                    @php
                        $scoreImg = asset(
                            'images/avatar/' .
                                ($loop->iteration == 1 ? 'first' : ($loop->iteration == 2 ? 'second' : 'third')) .
                                '.png',
                        );
                    @endphp
                    <div class="flex-1 min-w-[200px] sm:flex-1">
                        <div
                            class="relative grid gap-y-8 rounded-xl p-6 bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 w-full h-full">
                            <span class="absolute top-0 end-0 z-10 px-3">
                                <img src="{{ $scoreImg }}" alt="{{ $loop->iteration }}" class="w-16" />
                            </span>
                            <span class="flex items-center gap-3 text-lg font-medium text-gray-950 dark:text-gray-200">
                                <img src="{{ asset('images/avatar/' . ($user->image ?? 1) . '.png') }}" alt="Avatar"
                                    class="w-16 h-16 rounded-full" />
                                <div class="flex flex-col items-start">
                                    <span>{{ $user->name }}</span>
                                    <span class="text-sm text-gray-500">{{ $user->email }}</span>
                                </div>
                            </span>
                            <span
                                class="flex items-center justify-between gap-3 text-lg font-medium text-gray-950 dark:text-gray-200">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 flex items-center justify-center border border-gray-200 dark:border-gray-700 rounded-full"
                                            style="background-image: conic-gradient(#07b007a1 0%, #07b007a1 {{ $user->score }}%, red {{ $user->score }}%, red {{ $user->unCompleteStart }}%, yellow {{ $user->unCompleteStart }}%, yellow 100%)">
                                            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-full"
                                                style="width: 70%; height: 70%;"></div>
                                        </div>
                                        <span class="text-lg">{{ $user->score }}%</span>
                                    </div>
                                </div>
                                <span class="bg-gray-200 dark:bg-gray-400 rounded-full"
                                    style="width: 1px; height: 70%;"></span>
                                <div class="flex flex-col items-center">
                                    <span>{{ __('messages.participant.total_time') }}</span>
                                    <span>{{ $user->time }}</span>
                                </div>
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if (count($quizUsers) > 0)
            <div
                class="divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">

                <div
                    class="relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10 !border-t-0">
                    <table class="w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                        <thead class="divide-y divide-gray-200 dark:divide-white/5">
                            <tr class="bg-gray-50 dark:bg-white/5">
                                <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                    <span
                                        class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                        <span class="text-sm font-semibold text-gray-950 dark:text-white">

                                        </span>
                                    </span>
                                </th>

                                <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                    <span
                                        class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                        <span class="text-sm font-semibold text-gray-950 dark:text-white">
                                            {{ __('messages.user.user_name') }}
                                        </span>
                                    </span>
                                </th>

                                <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                    <span
                                        class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                        <span class="text-sm font-semibold text-gray-950 dark:text-white">
                                            {{ __('messages.participant.correct_answers') }}
                                        </span>
                                    </span>
                                </th>

                                <th class="px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6">
                                    <span
                                        class="group flex w-full items-center gap-x-1 whitespace-nowrap justify-start">
                                        <span class="text-sm font-semibold text-gray-950 dark:text-white">
                                            {{ __('messages.participant.total_time') }}
                                        </span>
                                    </span>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">

                            @foreach ($quizUsers as $user)
                                <tr>
                                    <td class="p-0 w-20 text-md text-gray-950 dark:text-white">
                                        <div class="flex items-center justify-center">
                                            <span>{{ $user->number }}</span>
                                        </div>
                                    </td>

                                    <td
                                        class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div class="flex gap-3 items-center p-3">
                                            <div>
                                                <img src="{{ asset('images/avatar/' . ($user->image ?? 1) . '.png') }}"
                                                    style="height: 2.5rem; width: 40px;"
                                                    class="max-w-none object-cover object-center rounded-full ring-white dark:ring-gray-900">
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm leading-6 text-gray-950 dark:text-white">
                                                    {{ $user->name }}
                                                </span>
                                                <span
                                                    class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td
                                        class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <div>
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 flex items-center justify-center border border-gray-200 dark:border-gray-700 rounded-full"
                                                    style="background-image: conic-gradient(#07b007a1 0%, #07b007a1 {{ $user->score }}%, red {{ $user->score }}%, red {{ $user->unCompleteStart }}%, yellow {{ $user->unCompleteStart }}%, yellow 100%);">
                                                    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-full"
                                                        style="width: 70%; height: 70%;"></div>
                                                </div>
                                                <span class="text-lg">{{ $user->score }}%</span>
                                            </div>
                                        </div>
                                    </td>

                                    <td
                                        class="p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3">
                                        <span class="text-sm text-gray-950 dark:text-white">
                                            {{ $user->time }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @else
        <div
            class="fi-ta-ctn divide-y divide-gray-200 overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:divide-white/10 dark:bg-gray-900 dark:ring-white/10">
            <div
                class="fi-ta-content relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10 !border-t-0">
                <div class="fi-ta-empty-state px-6 py-12">
                    <div class="fi-ta-empty-state-content mx-auto grid max-w-lg justify-items-center text-center">
                        <div class="fi-ta-empty-state-icon-ctn mb-4 rounded-full bg-gray-100 p-3 dark:bg-gray-500/20">
                            <svg class="fi-ta-empty-state-icon h-6 w-6 text-gray-500 dark:text-gray-400"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true" data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12">
                                </path>
                            </svg>
                        </div>
                        <h4
                            class="fi-ta-empty-state-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                            {{ __('messages.participant.no_participants_found') }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
