<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Invoices Dashboard') }}
        </h2>
    </x-slot>
    <div class="pt-2 flex justify-center items-center">
        <span class="text-3xl font-bold underline">Invoices</span>
    </div>
        <div class="py-12">
            <div class="w-4/5 mx-auto sm:px-6 lg:px-8">
                @if (session()->has('success'))
                    <div class="w-full py-2 px-3 bg-green-500 text-white">
                        {{ session()->get('success') }}
                    </div>
                    <div class="pt-3"></div>
                @endif
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs uppercase ">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    SN
                                </th>
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
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoices as $inv)
                                <tr class="bg-white border-b">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900  whitespace-nowrap">
                                        #{{ $inv->invoice }}
                                    </th>
                                    <th scope="row" class="px-6 py-4">
                                        {{ $inv->render }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $inv->receiver_email }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $inv->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($inv->active == 0)
                                            @if ($inv->status == 1)
                                                Paid
                                            @else
                                                <span class="text-red-500">Unpaid</span>
                                            @endif
                                        @else
                                            <span class="text-red-500">Cancelled</span>
                                        @endif
                                    </td>
                                    <td class="px-6 space-x-3 flex py-4 text-right">
                                        {{-- <a href="#"
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a> --}}
                                        {{-- <a href="#"
                                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Delete</a> --}}
                                        <a href="{{ route('admin.invoices.view', $inv->invoice) }}"
                                            class="font-medium py-1 px-3 rounded-lg hover:no-underline hover:bg-orange-400 bg-orange-500 text-white dark:text-white">View</a>
                                        @if ($inv->active == 0)
                                            @if ($inv->status == 1)
                                                <a href="{{ route('admin.invoices.res', $inv->invoice) }}"
                                                    class="font-medium py-1 px-3 rounded-lg hover:no-underline hover:bg-blue-400 bg-blue-500 text-white dark:text-white">Unpaid</a>
                                            @else
                                                <a href="{{ route('admin.invoices.res', $inv->invoice) }}"
                                                    class="font-medium py-1 px-3 rounded-lg hover:no-underline hover:bg-green-400 bg-green-500 text-white dark:text-white">Paid</a>
                                            @endif
                                        @else
                                            <span class="text-red-500">Cancelled</span>
                                        @endif
                                        @if ($inv->active == 0)
                                            <a href="{{ route('admin.invoices.cancel', $inv->invoice) }}"
                                                class="font-medium py-1 px-3 rounded-lg hover:no-underline hover:bg-red-400 bg-red-500 text-white dark:text-white">Cancel</a>
                                        @else
                                            <a href="{{ route('admin.invoices.cancel', $inv->invoice) }}"
                                                class="font-medium py-1 px-3 rounded-lg hover:no-underline hover:bg-red-400 bg-red-500 text-white dark:text-white">Restore</a>
                                        @endif
                                        {{-- <a href="{{ route('admin.invoices.edit', $inv->invoice) }}"
                                            class="font-medium py-1 px-3 rounded-lg bg-blue-500 text-white dark:text-white hover:underline">Edit</a> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pt-2"></div>
                {{$invoices->links('pagination::tailwind')}}
            </div>
        </div>

</x-app-layout>
