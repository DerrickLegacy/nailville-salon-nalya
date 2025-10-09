@props([
    'id' => 'modal-' . \Illuminate\Support\Str::random(5),
    'title' => 'Modal Title',
])

<div x-data="{ modalIsOpen: false }" {{ $attributes->merge(['id' => $id]) }}>
    <!-- Trigger slot -->
    <div {{ $attributes->only('trigger') }}>
        {{ $trigger ?? '' }}
    </div>

    <!-- Modal -->
    <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
        x-on:keydown.esc.window="modalIsOpen = false" x-on:click.self="modalIsOpen = false"
        class="fixed inset-0 z-30 flex items-end justify-center bg-black/60 p-4 pb-8 backdrop-blur-lg sm:items-center lg:p-8"
        role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title">

        <!-- Modal Dialog -->
        <div x-show="modalIsOpen"
            x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
            x-transition:enter-start="scale-0" x-transition:enter-end="scale-100"
            class="flex max-w-3xl w-full flex-col gap-4 overflow-hidden bg-white rounded-radius border border-outline bg-surface text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">

            <div
                class="flex items-center justify-between border-b border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20">
                <h3 id="{{ $id }}-title"
                    class="font-semibold tracking-wide text-on-surface-strong dark:text-on-surface-dark-strong">
                    {{ $title }}
                </h3>
                <button x-on:click="modalIsOpen = false" aria-label="close modal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor"
                        fill="none" stroke-width="1.4" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="px-4 py-2"> <!-- more padding for bigger modal -->
                {{ $slot }}
            </div>

            <!-- Footer -->
            @if (isset($footer))
                <div
                    class="flex flex-col-reverse justify-between gap-2 border-t border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20 sm:flex-row sm:items-center md:justify-end">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
