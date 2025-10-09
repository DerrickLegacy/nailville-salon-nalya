@props([
    'title' => __('Confirm Password'),
    'content' => __('For your security, please confirm your password to continue.'),
    'button' => __('Confirm'),
])

@php
    $method = $attributes->wire('then')->value();
    $confirmableId = md5($method);
@endphp

<span x-data x-ref="span" x-on:click="$wire.startConfirmingPassword('{{ $confirmableId }}')"
    x-on:password-confirmed.window="
        setTimeout(() => {
            if ($event.detail.id === '{{ $confirmableId }}') {
                $wire.call('{{ $method }}'); {{-- 🔥 call Livewire method directly --}}
            }
        }, 250);
    ">
    {{ $slot }}
</span>

@once
    <x-dialog-modal wire:model.live="confirmingPassword">
        <x-slot name="title">
            {{ $title }}
        </x-slot>

        <x-slot name="content">
            {{ $content }}

            <div class="mt-4" x-data="{}"
                x-on:confirming-password.window="setTimeout(() => $refs.confirmable_password.focus(), 250)">
                <x-input type="password" class="mt-1 block w-3/4" placeholder="{{ __('Password') }}"
                    x-ref="confirmable_password" wire:model="confirmablePassword" wire:keydown.enter="confirmPassword" />

                <x-input-error for="confirmablePassword" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="stopConfirmingPassword" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ml-3" dusk="confirm-password-button" wire:click="confirmPassword"
                wire:loading.attr="disabled">
                {{ $button }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
@endonce
