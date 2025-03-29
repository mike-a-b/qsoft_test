<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('List of orders:') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @if(!session('success'))
                    {{ "HEADER" }}
                @endif
                @if(session('success'))
                    <div class="alert alert-danger">
                        {{ session('success') }}
                    </div>
                @endif
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="table table-auto">
                    <thead class="p-6 text-gray-900">
                        <tr>
                            <th>
                                Id заявки
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                email
                            </th>
                            <th>
                                Status
                            </th>
                            <th>
                                message
                            </th>
                            <th>
                                comment
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr class="p-6 text-gray-900">
                            <td>
                                <div class="mr-4">
                                    <div class="block mt-1">
                                        {{ $order->id  }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mr-4">
                                    <div class="block mt-1">
                                        {{ $order->name  }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mr-4">
                                    <div class="block mt-1">
                                        {{ $order->email  }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mr-4">
                                    <div class="block mt-1">
                                        {{ $order->status  }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mr-4">
                                    <div class="block mt-1">
                                        {{ $order->message  }}
                                    </div>
                                </div>
                            </td>
                            <form action="{{ route('orders.answer', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <td>
                                    <div class="mr-4">
                                        <div class="block mt-1">
                                            <x-input-label for="comment" :value="__('Comment')" />
                                            <input type="text" name="comment" required/>
                                            <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="mr-4">
                                        <div class="block mt-1">
                                            <x-primary-button class="ms-4">
                                                {{ __('Добавить комментарий') }}
                                            </x-primary-button>
                                        </div>
                                    </div>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
