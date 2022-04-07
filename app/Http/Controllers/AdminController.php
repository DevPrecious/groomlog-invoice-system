<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use App\Models\Settings;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $setting = Settings::find(1);
        return view('admin.dashboard', compact('setting'));
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
            $inv->total = 80;
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
        \Mail::to($email)->send(new \App\Mail\Invoice($details));
        return back()->with('success', 'Invoice Created Successfully');
    }

    public function invoices()
    {
        $invoices = Invoice::orderBy('invoices.created_at', 'desc')->join('statuses', 'statuses.invoice_no', '=', 'invoices.invoice')->get()->groupBy('invoice');
        if(empty($invoices)){
        $invoices = Invoice::orderBy('invoices.created_at', 'desc')->get()->groupBy('invoice');

        }
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

    public function response($invoice_no){
        $invoice = Status::where('invoice_no', $invoice_no)->first();
        if(empty($invoice)){
            Status::create(['status' => 1, 'active' => 0, 'invoice_no' => $invoice_no]);
        }else{
            if($invoice->status == 1){
                Status::where('invoice_no', $invoice_no)->update(['status' => 0, 'invoice_no' => $invoice_no]);
            }else{
                Status::where('invoice_no', $invoice_no)->update(['status' => 1, 'invoice_no' => $invoice_no]);
            }
        }

        return back()->with('success', 'Invoice has been updated');
    }

    public function active($invoice_no){
        $invoice = Status::where('invoice_no', $invoice_no)->first();
        if(empty($invoice)){
            Status::create(['status' => 0, 'active' => 1, 'invoice_no' => $invoice_no]);
        }else{
            if($invoice->active == 1){
                Status::where('invoice_no', $invoice_no)->update(['active' => 0, 'invoice_no' => $invoice_no]);
            }else{
                Status::where('invoice_no', $invoice_no)->update(['active' => 1, 'invoice_no' => $invoice_no]);
            }
        }

        return back()->with('success', 'Invoice has been updated');
    }
}
