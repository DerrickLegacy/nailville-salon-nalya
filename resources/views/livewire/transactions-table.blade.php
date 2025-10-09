<div class="fi-ta-content grid gap-y-6">
    <div class="flex items-center justify-between gap-3">
        {{-- Search input --}}
        <div class="fi-ta-search-field flex items-center gap-2">
            <label class="flex items-center gap-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">Search:</span>
                <input type="search" wire:model.live.debounce.500ms="tableSearchQuery"
                    placeholder="Search transactions..."
                    class="block w-full max-w-xs rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm" />
            </label>
        </div>
    </div>

    {{-- Table --}}
    <div class="fi-ta-table-container overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        {{ $this->table }}
    </div>

    {{-- Pagination
    @if ($this->getTableRecords()->hasPages())
        <div class="fi-ta-pagination-container flex items-center justify-between">
            {{ $this->table }}
        </div>
    @endif --}}
</div>
