<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="max-w-7xl mx-auto">
        <div class="rounded bg-white w-1/2 p-2">
            <div class="flex items-center justify-center">
                <img src="{{ asset('images/logo complete black.png') }}" class="w-26 h-10" alt="">
            </div>
            <div class="pt-3">
                Hello {{$details['email']}} this is a mail sent from Groomlog, we will like to inform you about <br>
                your pending invoice <br>

                please click on this link to check it out <br>
                <div class="pt-3 flex items-center justify-center">
                    <a href="/invoices/{{$details['invoice']}}" class="rounded-lg px-3 py-1 bg-blue-500 text-white">Invoice</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>