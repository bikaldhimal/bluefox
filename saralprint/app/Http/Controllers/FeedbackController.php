<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedbacks = Feedback::all();
        return response()->json($feedbacks, 201);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $validation)
    {
        $validation->validate([
            'feedback_desc' => 'required',
            'user_id' => 'required',
            'order_id' => 'required',
            'feedback_desc' => 'required|min:1|max:500]'
        ]);


        $user = User::find($validation->user_id);
        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }


        $order = Order::find($validation->order_id);
        if (!$order) {
            return response()->json(["message" => "Order not found"], 404);
        }

        $feedback = Feedback::create([
            'user_id' => $validation->user_id,
            'order_id' => $validation->order_id,
            'title' => $validation->feedback_title,
            'description' => $validation->feedback_desc,
        ]);

        return $feedback;
        return response()->json(["message" => "Feedback sent successfully"]);
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
     * @param  \App\Models\feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function edit(feedback $feedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $validation, $id)
    {

        $feedback = Feedback::find($id);
        $feedback->feedback_desc = $validation ? $validation->name : $feedback->feedback_desc;

        $feedback->update();
        $errMessage = [
            "status" => false,
            "message" => "Update error"
        ];
        if (!$feedback) {
            return response()->json($errMessage, 404);
        }

        $successMessage = [
            "status" => true,
            "message" => "Updated Successfully"
        ];

        return response()->json($successMessage, 201);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $feedback = Feedback::find($id);
        if (!$feedback) {
            return response()->json(["message" => "Feedback not found"], 404);
        }
        $feedback->delete();
        $successResponse = ["message" => "Feedback deleted successfully"];
        return response()->json($successResponse, 200);
    }
}
