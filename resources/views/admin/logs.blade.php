<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Login activities') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs ">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Time
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Activity
                            </th>
                    </thead>
                    <tbody>
                        @foreach ($activities as $activity)
                        <tr class="bg-white border-b ">
                            <th scope="row" class="px-6 py-4 font-medium  whitespace-nowrap">
                                {{ $activity->user->name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ \Carbon\Carbon::parse($activity->logged_date)->diffForHumans() }}
                            </td>
                            @if ($activity->log_type == 'login')
                            <td class="px-6 py-4">
                                <span class="bg-green-500 rounded-lg text-white px-3 py-2">Logged in</span>
                            </td>
                            @elseif($activity->log_type == 'created_invoice')
                            <td class="px-6 py-4">
                                <span class="bg-blue-500 rounded-lg text-white px-3 py-2">Created invoice : {{ $activity->invoice_id }}</span>
                            </td>
                            @elseif($activity->log_type == 'logout')
                            <td class="px-6 py-4">
                                <span class="bg-red-500 rounded-lg text-white px-3 py-2">Logged out</span>
                            </td>
                            @endif       
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pt-2"></div>
            {{$activities->links('pagination::tailwind')}}
        </div>
    </div>
</x-app-layout>
