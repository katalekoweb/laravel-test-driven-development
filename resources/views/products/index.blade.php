<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (request()->user()->is_admin)
                <div class="mb-3">
                    <a href="{{ route('products.create') }}">
                        <x-primary-button>Add new product</x-primary-button>
                    </a>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div>
                    <table class="full">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Price in Eur</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        {{ $product->name }}
                                    </td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->price_eur }}</td>
                                    <td class=" flex items-center space-x-2">
                                        @if (request()->user()->is_admin)
                                        <a class=" underline font-bold" href="{{ route('products.edit', $product->id) }}">Edit</a>

                                        <form action="{{ route('products.destroy', $product->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <x-danger-button onclick="return confirm('Are you sure?')" type="submit">Delete</x-danger-button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">{{ __('No products found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-2 px-6">
                    {{ $products->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
