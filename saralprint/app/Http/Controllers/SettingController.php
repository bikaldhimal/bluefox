<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = Setting::all();

        return response()->json($setting, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([

            'name' => 'required',
            'estd' => 'required',
            'address' => 'required',
            'zip' => 'required',
            'mobile_number' => 'required|regex:/9[6-8]{1}[0-9]{8}/',
            'landline' => 'required',
            'email' => 'required|email',
            'about_us' => 'required',
            'facebook' => 'required',
        ]);

        $setting = Setting::create([
            'name' => $request->name,
            'estd' => $request->estd,
            'address' => $request->address,
            'zip' => $request->zip,
            'mobile_number' => $request->mobile_number,
            'landline' => $request->landline,
            'email' => $request->email,
            'about_us' => $request->about_us,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'instagram' => $request->instagram,
            'linkedIn' => $request->linkedIn,
            'website' => $request->website,
        ]);

        return $setting;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function about(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $id)
    {
        $setting = Setting::find($id);

        if (!$setting) {
            return response()->json(["message" => "User not found"], 404);
        }

        return response()->json($setting, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'email'=>'email',
        //     'name'=>'name',
        //     'estd'=>'estd'
        // ]);



        $us = Setting::find($id);
        $us->name = $request->name ? $request->name : $us->name;
        $us->estd = $request->estd ? $request->estd : $us->estd;
        $us->address = $request->address ? $request->address : $us->address;
        $us->zip = $request->zip ? $request->zip : $us->zip;
        $us->about_us = $request->about_us ? $request->about_us : $us->about_us;



        $us->update();

        $errUs = [
            "status" => false,
            "message" => "Error"
        ];

        if (!$us) {
            return response()->json($errUs, 404);
        }

        $sucessUs = [
            "status" => true,
            "message" => "successfull"
        ];

        return response()->json($sucessUs, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        // $setting = Setting::find($id);
        // if (!$us) {
        //     return response()->json(["message" => "User not found"], 404);
        // }
        // $setting->delete();
        // $sucessUs = ["message" => "User deleted successfully"];
        // return response()->json($sucessUs, 200);
    }
}
