<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->id ? 'Edit product' : 'New product' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form class="w-full space-y-3 bg-white shadow-md p-5 "
                action="{{ $product->id ? route('products.update', $product->id) : route('products.store') }}"
                method="post">
                @csrf
                @if ($product?->id)
                    @method('put')
                @endif

                <div class="flex flex-col space-y-4">
                    <div class="mb-3">
                        <x-input-label>Name</x-input-label>
                        <x-text-input class="w-full" name="name" id="name"
                            value="{{ old('name', $product?->name) }}" />

                        @error('name')
                            <div class="text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <x-input-label>Price</x-input-label>
                        <x-text-input class="w-full" name="price" id="price"
                            value="{{ old('price', $product?->price) }}" />

                        @error('price')
                            <div class="text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <x-primary-button>Save</x-primary-button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>
