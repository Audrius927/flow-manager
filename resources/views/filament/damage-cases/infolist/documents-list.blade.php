@php
    $documents = $documents ?? collect();
@endphp

@if ($documents->isEmpty())
    <p class="text-sm text-gray-500 dark:text-gray-400">Dokumentų dar nėra.</p>
@else
    <div class="space-y-3">
        @foreach ($documents as $document)
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm shadow-sm transition hover:border-emerald-400 hover:shadow-md dark:border-gray-700 dark:bg-gray-900">
                <div>
                    <p class="font-semibold text-gray-900 dark:text-gray-100">
                        {{ $document['name'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $document['uploaded_at'] ?? 'Įkėlimo data nežinoma' }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <!-- <a
                        href="{{ $document['url'] }}"
                        target="_blank"
                        rel="noopener"
                        class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 font-semibold"
                    >
                        Peržiūrėti
                    </a> -->
                    <a
                        href="{{ $document['url'] }}"
                        download
                        class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 font-semibold"
                    >
                        Atsisiųsti
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@endif

