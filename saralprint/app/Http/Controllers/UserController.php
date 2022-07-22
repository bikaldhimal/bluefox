<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Rules\CorporateUser;

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

    // Signup for Customer
    public function create(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'gender' => 'required|in:male,female,others,rather_not_to_say',
            'address' => 'required',
            'mobile_number' => 'required|numeric|regex:/9[6-8]{1}[0-9]{8}/|digits:10|unique:users',
            // 'mobile_number' => 'required|array',
            // 'mobile_number.*' => 'required|numeric|regex:/9[6-8]{1}[0-9]{8}/|digits:10|distinct|unique:users',
            'password' => 'required|min:8|max:20|regex:/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,20})/|confirmed',
            'email' => 'required|email|unique:users',
            'type' => 'in:individual,corporate',
            'panNumber' => 'required_if:type,corporate|digits:9|unique:users',
            'panDocImage' => 'required_if:type,corporate|mimes:jpg,jpeg,png|max:5048|unique:users',
            'profileImage' => 'required_if:type,corporate|mimes:jpg,jpeg,png|max:5048|unique:users',
            'is_admin' => 'boolean'
        ]);

        $user = User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'address' => $request->address,
            'mobile_number' => "977" . $request->mobile_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'panNumber' => $request->panNumber,
            'panDocImage' => $request->panDocImage,
            'profileImage' => $request->profileImage,
            'is_admin' => false,
            'mobile_verified_code' => rand(11111, 99999),
        ]);

        $newPanDocImageName = uniqid() . '.' . $request->image->extension();
        Storage::disk('public')->put("images/pan", $request->image);
        dd($newPanDocImageName);

        $newProfileImageName = uniqid() . '.' . $request->image->extension();
        Storage::disk('public')->put("images/profile", $request->image);
        dd($newProfileImageName);

        // $token = $user->createToken($request->email)->plainTextToken;

        $response = [
            "status" => true,
            "message" => "User Account Created Successfully",
            // "user" => $user,
            // "token" => $token,
        ];

        // Response if user created successfully 
        return response()->json($response, 201);
    }

    // Signup for Admin
    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'gender' => 'required|in:male,female,others,rather_not_to_say',
            'address' => 'required',
            'mobile_number' => 'numeric|regex:/9[6-8]{1}[0-9]{8}/|digits:10',
            // 'mobile_number' => 'required|array',
            // 'mobile_number.*' => 'required|numeric|regex:/9[6-8]{1}[0-9]{8}/|digits:10|distinct|unique:users',
            'password' => 'required|min:8|max:20|regex:/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,20})/|confirmed',
            'email' => 'required|email|unique:users',
            // 'type' => 'required|in:corporate,individual',
            // 'panDocImage' => 'mimes:jpg,jpeg,png|max:5048|unique:users',
            // 'profileImage' => 'required|mimes:jpg,jpeg,png|max:5048|unique:users',
            'is_admin' => 'boolean'
        ]);

        $user = User::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'address' => $request->address,
            'mobile_number' => "977" . $request->mobile_number,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => "admin",
            // 'panNumber' => $request->panNumber,
            // 'panDocImage' => $request->panDocImage,
            'is_admin' => true,
            // 'profileImage' => $newProfileImageName,
            'mobile_verified_code' => rand(11111, 99999),
        ]);

        // $token = $user->createToken($request->email)->plainTextToken;

        $response = [
            "status" => true,
            "message" => "Admin Account Created Successfully",
            // "user" => $user,
            // "token" => $token,
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
            // 'mobile_number' => 'unique:users',
            'gender' => 'in:male,female,others',
            'modile_number' => 'numeric|regex:/9[6-8]{1}[0-9]{8}/|digits:10|unique:users',
            'password' => 'min:8|max:20|regex:/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,20})/|confirmed',
            'type' => 'in:corporate,individual',
            'is_admin' => 'boolean'
        ]);

        $user = User::find($id);
        $user->name = $request->name ? $request->name : $user->name;
        $user->gender = $request->gender ? $request->gender : $user->gender;
        $user->address = $request->address ? $request->address : $user->address;
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
            "message" => "Successfully Updated"
        ];

        return response()->json($successResponse, 201);
    }

    // Update own profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'gender' => 'in:male,female,others',
            'mobile_number' => 'numeric|regex:/9[6-8]{1}[0-9]{8}/|digits:10',
            'password' => 'min:8|max:20|regex:/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,20})/|confirmed',
            'type' => 'in:corporate,individual',
            'is_admin' => 'boolean'
        ]);

        $user->name = $request->name ? $request->name : $user->name;
        $user->gender = $request->gender ? $request->gender : $user->gender;
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

    // Bulk Delete (Delete All User)
    public function deleteAllUser(Request $request)
    {
        DB::table("users")->whereIn('id', $request->id)->delete();
        return response()->json(['success' => "Users deleted successfully"]);
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

        $customer = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        if (!$customer) {
            return response()->json('message', 'Email not found in our database');
        }

        $user = User::where('email', $request->email)->get()->first();

        // generating token
        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        $fname = explode(' ', $user->name);
        $data = ['name' => $fname[0], 'data' => 'Please click the button below to reset your password.', 'token' => $token, 'route' => ('rPassword')];
        $user['to'] = $request->email;
        $send_mail = Mail::send('forgot-password', $data, function ($message) use ($user) {
            $message->from('noreply@saralprint.com', 'saralprint');
            $message->to($user['to']);
            $message->subject('Reset Password');
        });

        if (!$send_mail) {
            return response()->json('message', 'Unable to send mail to ', $request->email);
        }

        $successResponse = ["message" => "Please check your mail for resetting the password to", "email" => $request->email, "token" => $token];
        return response()->json($successResponse, 200);
    }

    //Password Reset Form
    public function resetForm(Request $request, $token)
    {
        return view('reset-form', ["token" => $token]);
    }

    // Resetting Password after accessing token through mail
    public function resetPassword(Request $request, $token)
    {

        $request->validate([
            'password1' => 'required|min:8|max:20|confirmed',
            'password2' => 'required|min:8|max:20',
        ]);

        $check_token = DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->token,
        ])->first();

        if (!$check_token) {
            return back()->withInput()->with('fail', 'Invalid Token');
        } else {
            User::where('email', $request->email)->update([
                'password' => Hash::make($request->password)
            ]);

            DB::table('password_resets')->where([
                'email' => $request->email
            ])->delete();

            $successResponse = ["Success" => "Your password has been changed, you can now login with new password."];

            return response()->json($successResponse, 201);
            // ->with('verifiedEmail', $request->email);

            return redirect()->route('resetSuccess');
        }
    }

    // Change Password
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:8|max:20|regex:/((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{8,20})/|confirmed'
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
