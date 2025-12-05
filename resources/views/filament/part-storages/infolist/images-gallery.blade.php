@php
    $images = ($images ?? collect())->values();
@endphp

<div
    x-data="{
        showModal: false,
        modalImage: null,
        modalTitle: null,
        images: @js($images),
        open(image) {
            this.modalImage = image.url
            this.modalTitle = image.name ?? ''
            this.showModal = true
            document.body.classList.add('overflow-hidden')
        },
        close() {
            this.showModal = false
            document.body.classList.remove('overflow-hidden')
        },
    }"
    class="space-y-4"
>
    @if ($images->isEmpty())
        <p class="text-sm text-gray-500 dark:text-gray-400">Nuotraukų dar nėra.</p>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach ($images as $image)
                <button
                    type="button"
                    class="group flex flex-col rounded-2xl border border-gray-200 bg-white p-4 text-left shadow-sm transition hover:shadow-lg dark:border-gray-700 dark:bg-gray-900"
                    @click="open(images[{{ $loop->index }}])"
                >
                    <div class="relative flex h-[300px] w-full items-center justify-center overflow-hidden rounded-xl bg-gray-50 dark:bg-gray-800">
                        <img
                            src="{{ $image['url'] }}"
                            alt="{{ $image['name'] }}"
                            class="h-full w-full object-contain transition duration-300 group-hover:scale-[1.02]"
                            loading="lazy"
                        />
                    </div>
                </button>
            @endforeach
        </div>
    @endif

    <div
        x-show="showModal"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
    >
        <div class="absolute inset-0 bg-black/80" @click="close()"></div>

        <div class="relative z-10 flex h-full w-full items-center justify-center p-4">
            <button
                type="button"
                class="absolute right-6 top-6 rounded-full bg-black/60 px-3 py-1 text-lg text-white transition hover:bg-black"
                @click="close()"
            >
                ✕
            </button>

            <img
                x-bind:src="modalImage"
                x-bind:alt="modalTitle"
                class="max-h-[95vh] max-w-[95vw] object-contain"
            />
        </div>
    </div>
</div>
