<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('store.settings') }}" method="POST" class="grid space-y-2">
                        @if(session()->has('success'))
                                <div class="w-full py-2 px-3 bg-green-500 text-white">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                        @csrf
                        <label for="">Account name</label>
                        <input type="text" name="account_name" value="{{ $setting->account_name ?? '' }}" class="rounded" id="">
                        @error('account_name')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror
                        <label for="">Bank name</label>
                        <input type="text" name="bank" value="{{ $setting->bank ?? '' }}" class="rounded" id="">
                        @error('bank')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror
                        <label for="">Account number</label>
                        <input type="text" name="account_number" value="{{ $setting->account_number ?? '' }}" class="rounded" id="">
                        @error('account_number')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror
                        <label for="">Crypto</label>
                        <input type="text" name="crypto" value="{{ $setting->crypto ?? '' }}" class="rounded" id="">
                        @error('crypto')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror
                        <label for="">Office address</label>
                        <textarea name="office_address" id="" cols="30" rows="5">{{ $setting->office_address ?? '' }}</textarea>
                        @error('office_address')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror
                        <label for="">Tax</label>
                        <input type="number" name="tax" class="rounded" value="{{ $setting->tax ?? '' }}" id="">
                        @error('tax')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror
                        <label for="">Comission Fee</label>
                        <input type="number" name="fee" class="rounded" value="{{ $setting->fee ?? '' }}" id="">
                        @error('fee')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror
                        <div class="pt-2"></div>
                        <div class="flex">
                            <button type="submit" class="rounded px-3 py-1 bg-blue-500 text-white">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
