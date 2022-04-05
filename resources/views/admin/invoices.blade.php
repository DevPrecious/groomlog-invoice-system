<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoices Dashboard') }}
        </h2>
    </x-slot>
    <div class="pt-2 flex justify-center items-center">
        <span class="text-3xl font-bold">Invoices</span>
    </div>
    @foreach ($invoices as $invoice => $invoice_list)
        <div class="py-12">
            <div class="w-4/5 mx-auto sm:px-6 lg:px-8">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Service
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Client Email
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Created At
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Total
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice_list as $inv)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                        {{ $inv->render }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $inv->receiver_email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $inv->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4">
                                        ${{ $inv->total }}
                                    </td>
                                    <td class="px-6 space-x-3 flex py-4 text-right">
                                        {{-- <a href="#"
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a> --}}
                                            {{-- <a href="#"
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</a> --}}
                                            <a href="{{ route('admin.invoices.view', $inv->invoice) }}"
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

</x-app-layout>
