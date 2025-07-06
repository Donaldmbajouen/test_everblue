<div>
    <div class="py-4">
        @if ($plans->count() <= 0)
            <div class="p-4 text-center border border-gray-200 rounded-lg dark:border-white/10">
                <h1 class="text-2xl font-bold text-gray-950 dark:text-white">
                    {{ __('messages.plan.no_plans') }}</h1>
            </div>
        @else
            <div class="flex">
                <nav class="flex max-w-full p-2 mx-auto overflow-x-auto bg-white shadow-sm fi-tabs gap-x-1 rounded-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
                    role="tablist">
                    @foreach ($plans as $tab => $tab_plans)
                        <button type="button"
                            class="fi-tabs-item group flex items-center gap-x-2 rounded-lg px-3 py-2 text-sm font-medium outline-none transition duration-75 {{ $loop->first ? 'fi-tabs-item-active bg-gray-50 dark:bg-white/5 text-primary-400 dark:text-primary-400' : 'hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5' }}"
                            role="tab"
                            href="#{{ str_replace(' ', '', \App\Enums\PlanFrequency::from($tab)->getLabel()) }}">
                            {{ \App\Enums\PlanFrequency::from($tab)->getLabel() }}
                        </button>
                    @endforeach
                </nav>
            </div>
            <div class="flex w-full py-5">
                @foreach ($plans as $tab => $tab_plans)
                    <div id="{{ str_replace(' ', '', \App\Enums\PlanFrequency::from($tab)->getLabel()) }}"
                        class="tab-content w-full flex justify-center gap-x-4 flex-wrap {{ $loop->first ? '' : 'hidden' }}">
                        @foreach ($tab_plans as $plan)
                            <div
                                class="w-full max-w-sm p-6 my-2 text-gray-600 bg-white shadow-lg dark:bg-gray-800 dark:text-gray-200 relative rounded-xl">
                                <h3 class="mb-1 text-xl font-bold text-gray-900">
                                    {{ $plan['name'] }}
                                </h3>

                                <div class="flex items-end gap-1 mt-3 mb-5">
                                    <h3 class="sm:text-4xl text-3xl font-bold text-gray-900">
                                        {{ getCurrencyPosition() ? $plan['currency_icon'] . ' ' . $plan['price'] : $plan['price'] . ' ' . $plan['currency_icon'] }}
                                    </h3>
                                    <span class="text-sm font-semibold text-gray-400" style="margin-bottom: 5px">/
                                        {{ __(\App\Enums\PlanFrequency::from($tab)->getLabel()) }}</span>
                                </div>
                                <hr />
                                <ul class="my-4 list-disc ps-4">
                                    @if ($plan['trial_days'] > 0)
                                        <li class="text-gray-900 font-medium text-sm mb-2">
                                            {{ __('messages.plan.trial_days') . ' (' . $plan['trial_days'] . ' ' . __('messages.subscription.days') . ')' }}
                                        </li>
                                    @endif
                                    <li class="text-gray-900 font-medium text-sm">
                                        {{ $plan['no_of_quiz'] }}
                                        {{ __('messages.subscription.no_of_quiz') }}
                                    </li>
                                </ul>
                                @if ($currentActivePlan != null && $currentActivePlan->plan_id == $plan['id'] && !$currentActivePlan->isExpired())
                                    <button
                                        style="--c-400:var(--success-400);--c-500:var(--success-500);--c-600:var(--success-600);"
                                        class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-success fi-color-success fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-400 dark:hover:bg-custom-600 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action cursor-default w-full">
                                        <span
                                            class="fi-btn-label">{{ __('messages.subscription.currently_active') }}</span>
                                    </button>
                                @else
                                    <a href="{{ route('filament.user.pages.choose-payment-type', ['plan' => $plan['id']]) }}"
                                        style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                                        class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50 fi-ac-action fi-ac-btn-action w-full">
                                        <span class="fi-btn-label">{{ __('messages.subscription.choose_plan') }}</span>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabLinks = document.querySelectorAll('.fi-tabs-item');
        tabLinks.forEach(function(tabLink) {
            tabLink.addEventListener('click', function(event) {
                event.preventDefault();
                tabLinks.forEach(function(link) {
                    link.classList.remove('fi-tabs-item-active', 'bg-gray-50',
                        'dark:bg-white/5', 'text-primary-400',
                        'dark:text-primary-400');
                    link.classList.add('hover:bg-gray-50', 'focus-visible:bg-gray-50',
                        'dark:hover:bg-white/5', 'dark:focus-visible:bg-white/5');
                });
                tabLink.classList.add('fi-tabs-item-active', 'bg-gray-50', 'dark:bg-white/5',
                    'text-primary-400', 'dark:text-primary-400');
                tabLink.classList.remove('hover:bg-gray-50', 'focus-visible:bg-gray-50',
                    'dark:hover:bg-white/5', 'dark:focus-visible:bg-white/5');
                const tabContents = document.querySelectorAll('.tab-content');
                tabContents.forEach(function(tabContent) {
                    tabContent.classList.add('hidden');
                });
                const targetId = tabLink.getAttribute('href').substring(1);
                const targetContent = document.getElementById(targetId);
                if (targetContent) {
                    targetContent.classList.remove('hidden');
                }
            });
        });
    });
</script>
