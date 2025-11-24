@php
    $cards = $this->getCards();
@endphp

<x-filament-widgets::widget>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($cards as $card)
            <a
                href="{{ $card['url'] }}"
                class="group border border-gray-100 bg-white p-5 text-gray-900 shadow-sm transition hover:-translate-y-1 hover:shadow-lg dark:border-gray-800 dark:bg-gray-900 dark:text-gray-100"
            >
                <div class="flex items-center gap-3 text-sm font-semibold tracking-wide uppercase text-gray-500 dark:text-gray-300">
                    <x-dynamic-component :component="$card['icon']" class="h-5 w-5 text-emerald-600 dark:text-emerald-400" />
                    {{ $card['title'] }}
                </div>
                <div class="mt-6 text-xs font-semibold text-emerald-700 underline decoration-emerald-600 underline-offset-4 transition group-hover:text-emerald-800 dark:text-emerald-300 dark:group-hover:text-emerald-200">
                    Atidaryti
                </div>
            </a>
        @endforeach
    </div>
</x-filament-widgets::widget>
