<?php

namespace App\Http\Controllers\API;

use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    use PasswordValidationRules;

    public function login(Request $request)
    {
        try {
            // input validation
            $request->validate([
                'email' => 'email|required',
                'password' => 'required',
            ]);

            // credentials login
            $credentials = request([
                'email',
                'password',
            ]);


            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized',
                ], 'Authentication Failed', 500);
            }

            $user = User::where('email', $request->email)->first();

            
            if ($user->is_logged == 'not') {
                $user->is_logged = 'in';
                $user->update();
            }
            
            
            // send error, if hash password not match
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid credentials');
            }
            
            // login if success
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token'  => $tokenResult,
                'token_type'    => 'Bearer',
                'user'          => $user, 
            ],'Authenticated');


        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function register(Request $request)
    {
        
        try {
             // input validation
            $request->validate([
                'name' => ['required','string','max:255'],
                'email' => ['required','string','email', 'max:255','unique:users'],
                'password' => $this->passwordRules(),
            ]);

            User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'address'       => $request->address,
                'gender'        => $request->gender,
                'phone_number'  => $request->phone_number,
                'city'          => $request->city,
                'password'      => Hash::make($request->password),
                'is_logged'     => 'in',
                'roles'         => 'customer'
            ]);

            $user = User::where('email', $request->email)->first();
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            
            return ResponseFormatter::success([
                'access_token'  => $tokenResult,
                'token_type'    => 'Bearer',
                'user'          => $user, 
            ],'Authenticated');

        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function logout(Request $request, $id)
    {   
        $user = User::where('id', $id)->first();
        // dd($user);
        if ($user === null) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
            ], 'Authentication Failed', 500);
        }
        
        $user->is_logged = 'not';
        $user->update();

        $token = $request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success($token, 'Token revoked');
    }

    // get data user login
    public function fetch(Request $request)
    {
        return ResponseFormatter::success($request->user(), 'Successfully get user'); 
    }

    public function updateProfile(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        $user->update($data);

        return ResponseFormatter::success($user, 'Profile updated');
    }

    public function updatePhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:2048'
        ]);

        if ($validator->fails()) 
        {
            return ResponseFormatter::error([
                'error' => $validator->errors()
            ], 'Update photo failed', 401);
        }

        if($request->file('file'))
        {
            $file = $request->file->store('assets/user','public');

            // save photo url to db
            $user = Auth::user();
            $user->profile_photo_path = $file;
            $user->update();

            return ResponseFormatter::success([$file], 'File successfully uploaded');
        }
    }
}
