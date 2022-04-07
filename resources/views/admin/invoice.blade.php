<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="images/favicon.png" rel="icon" />
    <title>General Invoice - Groomlog</title>
    <meta name="author" content="harnishdesign.net">

    <!-- Web Fonts
======================= -->
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900'
        type='text/css'>

    <!-- Stylesheet
======================= -->
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" type="text/css" href="vendor/font-awesome/css/all.min.css" />
</head>

<body class="bg-gray-300">
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between">
                        <img src="{{ asset('images/logo complete black.png') }}" class="w-26 h-10"  alt="">
                        <span class="text-4xl text-blue-500">Invoice</span>
                    </div>
                    <div class="pt-3">
                        <div class="border-b"></div>
                    </div>
                    <div class="pt-4 flex justify-between">
                        <div class="flex justify-center items-center space-x-1">
                            <span class="text-sm font-bold">Date:</span>
                            <span>{{ \Carbon\Carbon::parse($invx->created_at)->format('d F Y') }}</span>
                        </div>
                        <div class="flex justify-center items-center space-x-1">
                            <span class="text-sm font-bold">Invoice no:</span>
                            <span>{{ $invx->invoice }}</span>
                        </div>
                    </div>
                    <div class="pt-3">
                        <div class="border-b"></div>
                    </div>
                    <div class="pt-6 flex justify-between">
                        <div class="grid">
                            <span class="text-sm font-bold">Invoiced To:</span>
                            <span>{{ $invx->receiver_email }}<br>
                                {{ $invx->receiver_info }} </span>
                        </div>
                        <div class="grid">
                            <div class="flex justify-end">
                                <span class="text-sm font-bold" dir="rtl">Pay To</span> 
                                <span class="text-sm font-bold">:</span> 
                            </div>
                            <span dir="rtl">Groomlog <br>
                               @if(!empty($setting))  {!! str_replace(",", "<br/>", $setting->office_address) !!} @endif <br>
                                hr@groomlog.com
                        </div>
                    </div>
                    <div class="pt-4"></div>
                    <div class="flex justify-between bg-gray-200 rounded-t-lg px-3 py-3">
                        <span class="font-bold text-sm">Service</span>
                        <span class="font-bold text-sm">Description</span>
                        <span class="font-bold text-sm">Amount</span>
                    </div>
                    @foreach ($invoice as $inv => $service)
                    @foreach($service as $inv)
                    <div>
                        <div class="flex justify-between  rounded-b-lg px-3 py-3">
                            <span class="font-thin text-sm">{{ $inv->render }}</span>
                            <div class="grid space-y-1">
                                <?php $rate_total = 0; ?>
                                <?php $sub_total = 0; ?>
                                <?php $total = 0; ?>
                                @foreach($inv->sections as $inv)
                                <div class="flex space-x-1">
                                    <span class="font-thin text-sm">{{ $inv->service }} - </span>
                                    <span class="font-thin text-sm">${{ $inv->rate }}</span>
                                </div>
                                <?php $rate_total += $inv->rate ?>
                                <?php $sub_total += $rate_total ?>
                                <?php $total += $sub_total * ($setting->tax / 100) + $setting->fee + $sub_total ?>
                                @endforeach
                            </div>
                            <div>
                                <span class="font-thin text-sm">${{ $rate_total }}</span>
                            </div>
                        </div>
                        <div class="border-b"></div>
                    </div>
                    @endforeach
                    @endforeach
                    <div class="flex justify-end bg-gray-300 border-t rounded-b-lg px-3 py-3">
                        <div class="grid space-y-3">
                            <div class="grid">
                                <div class="flex justify-center items-center space-x-4">
                                    <span class="font-bold text-sm">Sub Total:</span>
                                    <span>${{ $sub_total }}</span>
                                </div>
                            </div>
                            <div class="border-b"></div>
                            <div class="grid">
                                <div class="flex justify-center items-center space-x-4">
                                    <span class="font-bold text-sm">Tax:</span>
                                    <span>${{ $setting->tax ?? '' }}</span>
                                </div>
                            </div>
                            <div class="border-b"></div>
                            <div class="grid">
                                <div class="flex justify-center items-center space-x-4">
                                    <span class="font-bold text-sm">Commission Fee:</span>
                                    <span>${{ $setting->fee ?? '' }}</span>
                                </div>
                            </div>
                            <div class="border-b"></div>
                            <div class="grid">
                                <div class="flex justify-center items-center space-x-4">
                                    <span class="font-bold text-sm">Total:</span>
                                    <span>${{ $total }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="text-center p-3">
                    <p class="text"><strong>NOTE :</strong> You can pay to any of those above</p>
                    <div class="pt-2"></div>
                    <div class="btn-group btn-group-sm d-print-none"> <a href="javascript:window.print()"
                            class="border  px-3 py-1"><i class="fa fa-print"></i> Print</a></div>
                </footer>
                <div class="pt-6">
                    <div class="grid items-center justify-center">
                        <span class="text-xl text-center">Pay with either</span>
                        <div class="pt-3"></div>
                        <div class="flex space-x-8">
                            <div class="grid">
                                <span class="text-sm font-bold">Bank Transfer</span>
                                <div class="text-sm">
                                    Account name: {{ $setting->account_name ?? '' }} <br>
                                    Bank: {{ $setting->bank ?? '' }} <br>
                                    Account number: {{ $setting->account_number ?? '' }} <br>
                                </div>
                            </div>
                            <div class="grid">
                                <span class="text-sm font-bold">Crypto</span>
                                <div>
                                    Eth: {{ $setting->crypto ?? '' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-6"></div>
            </div>
            
        </div>
    </div>
    <!-- Footer -->
    
    </div>
</body>

</html>
