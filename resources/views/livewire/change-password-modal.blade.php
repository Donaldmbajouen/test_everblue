<div style="height: 0px;">
    <x-filament::modal id="change-password-modal">
        <x-slot name="heading">
            {{ __('messages.user.change_password') }}
        </x-slot>

        <div>
            <x-filament-panels::form wire:submit="save">
                {{ $this->form }}
                <div class="flex justify-end">
                    <x-filament::button type="submit"
                        class="px-6 mx-3">{{ __('messages.common.save') }}</x-filament::button>
                    <button type="button" x-on:click="close(); $wire.call('resetFormData')"
                        class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg  fi-btn-color-gray fi-color-gray fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-white text-gray-950 hover:bg-gray-50 dark:bg-white/5 dark:text-white dark:hover:bg-white/10 ring-1 ring-gray-950/10 dark:ring-white/20 fi-ac-action fi-ac-btn-action">{{ __('messages.common.cancel') }}</button>
                </div>
            </x-filament-panels::form>
        </div>
    </x-filament::modal>
</div>
