<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $email = $request->email;
        $info = $request->info;
        $services = $request->services;
        $service = $request->service;
        $tax = 7.5;
        $invoice = rand(0, 100000);
        $rate = $request->rate;
        $total = $request->total;

        for ($i = 0; $i < count($services); $i++) {
            $inv = new Invoice();
            $inv->receiver_email = $email;
            $inv->receiver_info = $info;
            $inv->render = $services[$i];
            $inv->total = $total;
            $inv->invoice = $invoice;
            $inv->tax = $tax;

            $inv->save();
            $invoice_id = $inv->id;

            $dataforsec = [
                'service' => $service[$i],
                'rate' => $rate[$i],
                'invoice_id' => $invoice_id,
            ];
            DB::table('sections')->insert($dataforsec);
        }
        $details = [

            'title' => 'Mail from Groomlog',
    
            'email' => $email,
            'invoice' => $inv->invoice
    
        ];
        \Mail::to($email)->send(new \App\Mail\InvoiceMail($details));
        return back()->with('success', 'Invoice Created Successfully');
    }

    public function invoices()
    {
        $invoices = Invoice::orderBy('created_at', 'desc')->get()->groupBy('invoice');
        // dd($invoices);
        return view('admin.invoices', compact('invoices'));
    }

    public function invoice($invoicex)
    {
        $invoice = Invoice::with('sections')->where('invoice', $invoicex)->get();
        $invx = Invoice::where('invoice', $invoicex)->first();
        // dd($invx);
        return view('admin.invoice', compact('invoice', 'invx'));
    }
}
