@props(['title' => null])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mt-3">
    @if($title)
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
        </div>
    @endif
    <div class="p-6 text-gray-900">
        {{ $slot }}
    </div>
</div>