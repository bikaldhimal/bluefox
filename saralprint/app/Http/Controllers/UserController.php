<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $users = User::all();

        return response()->json($users, 200);
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
            'address' => 'required',
            'mobile_number' => 'required|numeric|regex:/9[6-8]{1}[0-9]{8}/|digits:10|unique:users',
            'password' => 'required|min:8|max:16|confirmed',
            'email' => 'required|email|unique:users',
            'type' => 'required|in:corporate,individual',
            'is_admin' => 'required|boolean'
        ]);

        $user = User::create([
            'name' => $request->name,
            'address' => $request->address,
            'mobile_number' => "977" . $request->mobile_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'panNumber' => $request->panNumber,
            'is_admin' => $request->is_admin,
            'mobile_verified_code' => rand(11111, 99999),
        ]);

        // generating token
        $token = $user->createToken($request->email)->plainTextToken;
        // response message
        $response = [
            "status" => true,
            "message" => "User Created Successfully",
            "user" => $user,
            "token" => $token,
        ];

        // Response if user created successfully 
        return response()->json($response, 201);
    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(["message" => "Invalid username and password provided"], 404);
        }

        $token = $user->createToken($request->email)->plainTextToken;

        $response = [
            "status" => true,
            "user" => $user,
            "token" => $token,
        ];
        return response()->json($response, 200);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }

        return response()->json($user, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'mobile_number' => 'unique:users',
            'password' => 'min:8|max:16|confirmed',
            'type' => 'in:corporate,individual',
            'is_admin' => 'boolean'
        ]);

        $user = User::find($id);
        $user->name = $request->name ? $request->name : $user->name;
        $user->mobile_number = $request->mobile_number ? $request->mobile_number : $user->mobile_number;
        $user->address = $request->address ? $request->address : $user->address;
        $user->password = $request->password ? $request->Hash::make($request->password) : $user->password;
        $user->type = $request->type ? $request->type : $user->type;
        $user->is_admin = $request->is_admin ? $request->is_admin : $user->is_admin;
        $user->update();


        $errResponse = [
            "status" => false,
            "message" => "Update error"
        ];

        if (!$user) {
            return response()->json($errResponse, 404);
        }

        $successResponse = [
            "status" => true,
            "message" => "Successfully Updated"
        ];

        return response()->json($successResponse, 201);
    }

    // Update own profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'mobile_number' => 'unique:users',
            'password' => 'min:8|max:16|confirmed',
            'type' => 'in:corporate,individual',
            'is_admin' => 'boolean'
        ]);

        $user->name = $request->name ? $request->name : $user->name;
        $user->mobile_number = $request->mobile_number ? $request->mobile_number : $user->mobile_number;
        $user->password = $request->password ? $request->Hash::make($request->password) : $user->password;
        $user->type = $request->type ? $request->type : $user->type;
        $user->is_admin = $request->is_admin ? $request->is_admin : $user->is_admin;
        $user->update();

        $errResponse = [
            "status" => false,
            "message" => "Update error"
        ];

        if (!$user) {
            return response()->json($errResponse, 404);
        }

        $successResponse = [
            "status" => true,
            "message" => "Profile Updated Successfully"
        ];

        return response()->json($successResponse, 201);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["message" => "User not found"], 404);
        }
        $user->delete();
        $successResponse = ["message" => "User deleted successfully"];
        return response()->json($successResponse, 200);
    }

    public function profile(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    // Delete own profile
    public function profileDelete(Request $request)
    {
        $successResponse = ["message" => "Profile deleted successfully"];
        $user = $request->user();
        $user->delete();
        return response()->json($successResponse, 200);
    }

    // Forget Password
    // Send Password Reset Link in mail with token
    public function sendResetLink(Request $request)
    {

        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->get()->first();


        // generating token
        $token = Str::random(64);

        $pass = DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);


        $action_link = ['token' => $token, 'email' => $request->email];
        $body = "Hello $user->name, You can reset your password through the link provided below of email " . $request->email;

        Mail::send('email-forgot', ['action_link' => $action_link, 'body' => $body], function ($message) use ($request) {
            $message->from('noreply@saralprint.com', 'saralprint');
            $message->to($request->email)->subject('Reset Password');
        });

        return response()->json('success', 'Password reset link has been sent to you email')->with([
            'token' => $token,
            'email' => $request->email
        ]);
    }

    // Resetting Password after accessing token through mail
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users|email',
            'password' => 'required|min:8|max:16|confirmed',
        ]);

        $check_token = DB::table('password_resets')->where([
            'email' => $request->email,
            'toekn' => $request->token,
        ])->first();

        if (!$check_token) {
            return back()->withInput()->with('fail', 'Invalid token');
        } else {
            User::where('email', $request->email)->update([
                'password' => Hash::make($request->password)
            ]);

            DB::table('password_resets')->where([
                'email' => $request->email
            ])->delete();

            $successResponse = ["Success" => "Your password has been changed, now you can login with new password."];

            return response()->json($successResponse, 201)->with('verifiedEmail', $request->email);
        }
    }

    // Change Password
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:8|max:16|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validaiton fails',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        if (Hash::check($request->old_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            return response()->json([
                'message' => 'Password is successfully updated.',
            ], 201);
        } else {
            return response()->json([
                'message' => 'Old password does not matched!',
            ], 400);
        }
    }
}
