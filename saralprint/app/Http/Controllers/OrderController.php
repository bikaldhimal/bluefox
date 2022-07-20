<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\models\User;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders, 200);
        // return $orders;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $validation)


    {
        $validation->validate([
            'user_id' => 'required',
            'status' => 'required|in:pending,on-work,delivered',
            'quantity' => 'required',
            'discount' => 'required',
            'total' => 'required',
            'payment' => 'required|in:COD,connectIPS,esewa,khalti'
        ]);

        $user = User::find($validation->user_id);
        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }

        $order = Order::create([
            'user_id' => $validation->user_id,
            'status' => $validation->status,
            'quantity' => $validation->quantity,
            'discount' => $validation->discount,
            'total' => $validation->total,
            'payment' => $validation->payment

        ]);
        return $order;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $request-> validate([

        // ]);

        // $ordr = Order::where('status', $request->status)->first();
        // return $ordr;  

        $order = Order::find($id);
        // $stat=Order::check($request->status);
        // return $stat;
        $order->quantity = $request->quantity ?  $request->quantity : $order->quantity;
        $order->discount = $request->discount ?  $request->discount : $order->discount;
        $order->total = $request->total ?  $request->total : $order->total;
        $order->update();

        $errResponse = [
            "status" => false,
            "message" => "Update error"
        ];

        if (!$order) {
            return response()->json($errResponse, 404);
        }

        $successResponse = [
            "status" => true,
            "message" => "Success"
        ];

        return response()->json($successResponse, 201);
    }
}

/**
 * Remove the specified resource from storage.
 *
 * @param  \App\Models\Order  $order
 * @return \Illuminate\Http\Response
 */
