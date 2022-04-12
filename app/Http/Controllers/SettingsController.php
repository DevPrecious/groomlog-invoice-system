<?php

namespace App\Http\Controllers;

use App\Models\Loggin;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $setting = Settings::find(1);
        return view('admin.settings', compact('setting'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string',
            'bank' => 'required|string',
            'account_number' => 'required|string',
            'crypto' => 'required|string',
            'office_address' => 'required|string',
            'tax' => 'required',
            'fee' => 'required',
        ]);

        
        $setting = Settings::find(1);
        if($setting){
            Settings::where('id', 1)->update($request->except('_token'));
        }else{
            Settings::create($request->except('_token'));
        }

        return back()->with('success', 'Settings has been saved');
    }

    public function activities()
    {
        $activities = Loggin::paginate(5);
        return view('admin.logs', compact('activities'));
    }
}
