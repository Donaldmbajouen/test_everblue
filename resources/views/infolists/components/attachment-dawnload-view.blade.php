<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        @if ($getState())
            <a href="{{ $getState() }}"
                style="--c-50:var(--success-50);--c-400:var(--success-400);--c-600:var(--success-600);"
                class="px-2 py-1 text-xs font-medium rounded-md fi-badge ring-1 ring-inset fi-color-custom bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30 fi-color-success"
                download>
                {{ __('Download') }}
            </a>
        @else
            <div class="text-sm leading-6 text-gray-950 dark:text-white  " style="">
                {{ __('messages.common.n/a') }}
            </div>
        @endif
    </div>
</x-dynamic-component>
