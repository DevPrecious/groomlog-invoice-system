<?php

namespace App\Http\Controllers;

use App\Mail\CancelMail;
use App\Mail\PaidMail;
use App\Mail\UnPaidMail;
use App\Models\Invoice;
use App\Models\Loggin;
use App\Models\Section;
use App\Models\Settings;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        foreach ($post as $k => $v) {
            $parts = str_getcsv($k, '-');
            $fp = $parts[0];
            if (strstr($fp, 'services')) $services[$parts[1]] = $v;
            if (is_numeric($fp)) {
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
            $inv->total = $request->total;
            $inv->invoice = $invoice;
            $inv->tax = $tax;

            $inv->save();
            $invoice_id[$key] = $inv->id;
        }
        foreach ($service as $key => $value) {
            foreach ($value as $k => $v) {
                if (is_array(array_keys($v)[0])) {
                } else {
                    $dataforsec = [
                        'service' => $v['service'],
                        'rate' => $v['rate'],
                        'invoice_id' => $invoice_id[$key],
                    ];
                }
                DB::table('sections')->insert($dataforsec);
            }
        }

        Status::create(['status' => 0, 'invoice_no' => $inv->invoice, 'active' => 0]);
        Loggin::create(['invoice_id' => $inv->id, 'log_type' => 'created_invoice', 'user_id' => auth()->id()]);

        $details = [

            'title' => 'Groomlog Invoice',

            'email' => $email,
            'invoice' => $inv->invoice

        ];
        Mail::to($email)->send(new \App\Mail\Invoice($details));
        return back()->with('success', 'Invoice Created Successfully');
    }

    public function invoices()
    {
        $invoices = Invoice::latest('invoices.created_at')->join('statuses', 'statuses.invoice_no', '=', 'invoices.invoice')->orderBy('invoices.created_at', 'desc')->paginate(5);
        // $invoices = Invoice::join('statuses', 'statuses.invoice_no', '=', 'invoices.invoice')->orderBy('invoices.created_at', 'desc')->get()->groupBy('invoice');
        // $pgn = Invoice::all()->paginate(5);
        // dd($pgn);
        return view('admin.invoices', compact('invoices'));
    }

    public function invoice($invoicex)
    {
        $setting = Settings::find(1);
        $invoice = Invoice::with('sections')->where('invoice', $invoicex)->get()->groupBy('invoice');
        // $sum = Invoice::withSum('sections', 'rate')->first();
        // dd($sum);
        $invx = Invoice::where('invoice', $invoicex)->first();
        // dd($invx);
        return view('admin.invoice', compact('invoice', 'invx', 'setting'));
    }

    public function response($invoice_no)
    {
        $details = [
            'title' => 'Groomlog Invoice',
        ];
        $inv = Invoice::where('invoice', $invoice_no)->first();
        $invoice = Status::where('invoice_no', $invoice_no)->first();
        if (empty($invoice)) {
            Status::create(['status' => 0, 'active' => 0, 'invoice_no' => $invoice_no]);
        } else {
            if ($invoice->status == 1) {
                Status::where('invoice_no', $invoice_no)->update(['status' => 0, 'invoice_no' => $invoice_no]);
                Mail::to($inv->receiver_email)->send(new UnPaidMail($details));
            } else {
                Mail::to($inv->receiver_email)->send(new PaidMail($details));
                Status::where('invoice_no', $invoice_no)->update(['status' => 1, 'invoice_no' => $invoice_no]);
            }
        }

        return back()->with('success', 'Invoice has been updated');
    }

    public function active($invoice_no)
    {
        $details = [
            'title' => 'Groomlog Invoice',
        ];
        $inv = Invoice::where('invoice', $invoice_no)->first();
        $invoice = Status::where('invoice_no', $invoice_no)->first();
        if (empty($invoice)) {
            Status::create(['status' => 0, 'active' => 1, 'invoice_no' => $invoice_no]);
        } else {
            if ($invoice->active == 1) {
                $details['can'] = 'up';
                Mail::to($inv->receiver_email)->send(new CancelMail($details));
                Status::where('invoice_no', $invoice_no)->update(['active' => 0, 'invoice_no' => $invoice_no]);
            } else {
                $details['can'] = 'can';
                Mail::to($inv->receiver_email)->send(new CancelMail($details));
                Status::where('invoice_no', $invoice_no)->update(['active' => 1, 'invoice_no' => $invoice_no]);
            }
        }

        return back()->with('success', 'Invoice has been updated');
    }


    public function edit($invoice)
    {
        $invoice = Invoice::where('invoice', $invoice)->first();
        $section = Section::where('invoice_id', $invoice->id)->first();
        $sections = Section::where('invoice_id', $invoice->id)->get();
        $section_t = Section::all();
        $invoices = DB::table('invoices')->where('invoice', $invoice->invoice)->get();
        // dd($invoices);
        return view('admin.edit', compact('invoice', 'section', 'sections', 'invoices', 'section_t'));
    }

    public function storeedit(Request $request)
    {
        $invoicee = $request->invoicee;
        $invoicee_id = $request->invoicee_id;

        foreach($invoicee_id as $idd => $kk){
            $deleteSect = Section::where('invoice_id', $kk)->delete();
            $deleteInv = Invoice::where('id', $kk)->delete();
        }

        $email = $request->email;
        $info = $request->info;
        $post = $request->all();
        $services = $service = [];
        foreach ($post as $k => $v) {
            $parts = str_getcsv($k, '-');
            $fp = $parts[0];
            if (strstr($fp, 'services')) $services[$parts[1]] = $v;
            if (is_numeric($fp)) {
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
            $inv->total = $request->total ?? 0;
            $inv->invoice = $invoicee;
            $inv->tax = $tax;

            $inv->save();
            // foreach ($request->invoicee_id as $dd => $vv) {
            //     $getinv = Invoice::where('id', $vv)->get();
            //     foreach($getinv as $gett){
            //         if($gett->delete()){
            //         // Invoice::where('invoice', $invoicee)->update(['receiver_email' => $email, 'receiver_info' => $info, 'render' => $services[$key], 'total' => $request->total, 'invoice' => $invoice, 'tax' => $tax]);
            //         $inv->save();
            //         $invoice_id[$key] = $inv->id;
            //         }
            //         else {
            //             $inv->save();
            //             $invoice_id[$key] = $inv->id;
            //         }
            //     }
            // }
            $invoice_id[$key] = $inv->id;
            // dd($invoice_id[$key] = $inv->id);
        }
        foreach ($service as $key => $value) {
            foreach ($value as $k => $v) {
                if (is_array(array_keys($v)[0])) {
                } else {
                    $dataforsec = [
                        'service' => $v['service'],
                        'rate' => $v['rate'],
                        'invoice_id' => $invoice_id[$key],
                    ];
                }
                // DB::table('sections')->delete($invoice_id[$key]);
                DB::table('sections')->insert($dataforsec);
            }
        }

        // Status::create(['status' => 0, 'invoice_no' => $inv->invoice, 'active' => 0]);
        // Loggin::create(['invoice_id' => $inv->id, 'log_type' => 'created_invoice', 'user_id' => auth()->id()]);

        $details = [

            'title' => 'Groomlog Invoice',

            'email' => $email,
            'invoice' => $inv->invoice

        ];
        // Mail::to($email)->send(new \App\Mail\Invoice($details));
        return redirect()->route('admin.home')->with('success', 'Invoice has been updated');
    }
}
