@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div>
        <div class="text-lg bg-accent text-white p-4">
            {{ $title }}
        </div>

        <div class="px-6 py-4">
            <div class="mt-4 text-sm text-gray-600">
                {{ $content }}
            </div>
        </div>
    </div>

    <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-end">
        {{ $footer }}
    </div>
</x-modal>
