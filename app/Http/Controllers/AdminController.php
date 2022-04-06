<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use App\Models\Settings;
use Carbon\Carbon;
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
        $email = $request->email;
        $info = $request->info;
        $post = $request->all();
        $services = $service = [];
        foreach($post as $k=>$v){
            $parts = str_getcsv($k, '-');
            $fp = $parts[0];
            if(strstr($fp, 'services')) $services[$parts[1]] = $v;
            if(is_numeric($fp)) {
                $service[$fp] = $service[$fp] ?? [];
                $service[$fp][$parts[2]] = $service[$fp][$parts[2]] ?? [];
                $service[$fp][$parts[2]][$parts[1]] = $service[$fp][$parts[2]][$parts[1]] ?? [];
                $service[$fp][$parts[2]][$parts[1]] = $v;
            }
        }
        $tax = 7.5;
        $invoice = rand(0, 100000);
        $rate = $request->rate;
        $total = $request->total;

        $invoice_id = [];
        foreach ($services as $key => $value) {
            $inv = new Invoice();
            $inv->receiver_email = $email;
            $inv->receiver_info = $info;
            $inv->render = $services[$key];
            $inv->total = $total;
            $inv->invoice = $invoice;
            $inv->tax = $tax;

            $inv->save();
            $invoice_id[$key] = $inv->id;
            
        }
     foreach($service as $key => $value){
         foreach($value as $k=>$v){
             if(is_array(array_keys($v)[0])){
                
            }
            else {
                $dataforsec = [
                    'service' => $v['service'],
                    'rate' => $v['rate'],
                    'invoice_id' => $invoice_id[$key],
                ];
            }
            DB::table('sections')->insert($dataforsec);
         }
        }
    

        $details = [

            'title' => 'Groomlog Invoice',

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
        $setting = Settings::find(1);
        $invoice = Invoice::with('sections')->where('invoice', $invoicex)->get()->groupBy('invoice');
        $invx = Invoice::where('invoice', $invoicex)->first();
        // dd($invx);
        return view('admin.invoice', compact('invoice', 'invx', 'setting'));
    }
}
