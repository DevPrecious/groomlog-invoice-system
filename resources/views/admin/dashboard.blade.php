<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-4/5 mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (!empty($setting))
                        <div>
                            <form autocomplete="off" action="{{ route('admin.store') }}" method="POST">
                                @csrf
                                @if (session()->has('success'))
                                    <div class="w-full py-2 px-3 bg-green-500 text-white">
                                        {{ session()->get('success') }}
                                    </div>
                                @endif
                                @csrf
                                <div class="grid space-y-1">
                                    <label for="email">Receiver Email</label>
                                    <input type="text" required name="email" class="rounded-lg p-2 outline-none" id="">
                                    <label for="email">Receiver Address</label>
                                    {{-- <input type="text" name="info" required class="rounded-lg p-2 outline-none" id=""> --}}
                                    <textarea name="info" id="" class="rounded-lg p-2 outline-none" cols="30" rows="4"></textarea>
                                    <label for="email">Services</label>
                                    <select name="services-0" id="" class="rounded-lg p-2 outline-none">
                                        <option value="web development">Web Development</option>
                                        <option value="Smart Contract">Smart Contract</option>
                                        <option value="Product Design">Product Design</option>
                                    </select>
                                    <span>Description</span>
                                    <div class="flex space-x-4">
                                        <input type="text" required name="0-service-0" placeholder="Service"
                                            class="w-1/2 rounded-lg p-2 outline-none" id="">
                                        <input type="number" required name="0-rate-0" placeholder="Rate"
                                            class="w-1/2 price rounded-lg p-2 outline-none" id="">
                                    </div>
                                    <span class="text-blue-500 cursor-pointer add_button" data-id="0">Add item</span>
                                    <div class="item_wrapper">
                                        {{-- <div class="flex space-x-4">
                                        <input type="text" name="" placeholder="Service"
                                            class="w-1/2 rounded-lg p-2 outline-none" id="">
                                        <input type="number" name="" placeholder="Rate"
                                            class="w-1/2 rounded-lg p-2 outline-none" id="">
                                    </div> --}}
                                    </div>
                                    <div class="border-b"></div>
                                    <span class="text-blue-500 cursor-pointer add_service">Add
                                        services</span>
                                    <div class="service_wrapper w-full grid">

                                    </div>
                                    <hr>
                                    <div class="grid justify-end">
                                    <span>Total</span>
                                    <div class="border-b"></div>
                                    <div class="flex">
                                        <span>$</span>
                                        <span id="totalvalue">0</span>
                                    </div>
                                    <input type="hidden" name="total" class="total" value="" >
                                </div>
                                    <div>
                                        <button type="submit"
                                            class="bg-blue-500 text-white px-3 py-1 rounded-lg">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="px-3 bg-yellow-600 text-white text-2xl">Please fill out the settings first <br>
                        </div>
                        <div class="pt-4"></div>
                        <a class="bg-blue-500 text-white px-3 py-1 rounded-lg" href="{{ route('admin.settings') }}"
                            class="text-sm">Settings</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        $(document).on("change", ".price", function() {
            var sum = 0;
            $(".price").each(function() {
                sum += +$(this).val();
            });
            $(".total").val(sum);
            $("#totalvalue").text(sum);
        });
        $(document).ready(function() {
            // $('.price').change(function() {
            //     var total = 0;

            //     $('.price').each(function() {
            //         if ($(this).val() != '')
            //             total += parseInt($(this).val());
            //     });

            //     $('#totalvalue').html(total);
            //     $('#ttotal').val(total);
            // });
            var maxField = 10;
            var addButton = $('.add_button');
            var x = 1;
            var item_wrapper = $('.item_wrapper');

            $(addButton).click(function() {
                var id = $(this).attr('data-id');
                itemId = document.querySelectorAll('.item_wrapper > *').length + 1;
                if (x < maxField) {
                    x++;
                    var html = `
                    <div class="pt-3 flex space-x-4">
                        <input type="text" required name="${id}-service-${itemId}" placeholder="Service"
                            class="w-1/2 rounded-lg p-2 outline-none" id="">
                        <input type="number" required name="${id}-rate-${itemId}" placeholder="Rate"
                            class="w-1/2 price rounded-lg p-2 outline-none" id="">
                        <span class="text-red-500 cursor-pointer remove_button">remove</span>
                    </div>`;
                    $(item_wrapper).append(html);
                }
            });

            $(item_wrapper).on('click', '.remove_button', function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            });
            var addService = $('.add_service');
            var service_wrapper = $('.service_wrapper');
            $(addService).click(function() {
                if (x < maxField) {
                    x++;
                    var serviceHtml = `
                    <div class="pt-3 w-full grid top-s" id="top_s" data-id="${x}">
                        <span>Service</span>
                        <select name="services-${x}" id="" class="rounded-lg p-2 outline-none">
                            <option value="web development">Web Development</option>
                            <option value="Smart Contract">Smart Contract</option>
                            <option value="Product Design">Product Design</option>
                        </select>
                        <span>Description</span>
                        <div class="flex space-x-4">
                            <input type="text" required name="${x}-service-0" placeholder="Service"
                                class="w-1/2 rounded-lg p-2 outline-none" id="">
                            <input type="number" required name="${x}-rate-0" placeholder="Rate"
                                class="w-1/2 price rounded-lg p-2 outline-none" id="">
                        </div>
                        <span class="text-red-500 cursor-pointer remove_service">remove</span>
                        <span class="text-blue-500 cursor-pointer" id="sub_add_item" data-id="${x}">Add item</span>
                        <div class="sub_add">
                            
                        </div>
                    </div>
            `;
                    $(service_wrapper).append(serviceHtml);
                }
            });

            $(service_wrapper).on('click', '.remove_service', function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            });

            $("body").on("click", "#sub_add_item", function() {
                var id = $(this).attr('data-id');
                var top_s = $('#top_s').data('id');
                var $divComponent = $(this).closest(".top-s");
                itemId = document.querySelectorAll('.sub_add > *').length + 1;
                if (x < maxField) {
                    x++;
                    var subHtml = `
                    <div class="pt-3 flex space-x-4">
                         <input type="text" required name="${id}-service-${itemId}" placeholder="Service"
                            class="w-1/2 rounded-lg p-2 outline-none" id="">
                        <input type="number" required name="${id}-rate-${itemId}" placeholder="Rate"
                            class="w-1/2 price rounded-lg p-2 outline-none" id="">
                            <span class="text-red-500 cursor-pointer remove_sub">remove</span>
                    </div>
                     `;
                    // $(".sub_add").append(subHtml);
                    $divComponent.append(subHtml);
                }
            });

            $('body').on('click', '.remove_sub', function(e) {
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            });
        });
    </script>



</x-app-layout>
