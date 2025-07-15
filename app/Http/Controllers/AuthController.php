<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'failed',
                'error' => $validate->errors(),
            ], 422); // رمز الاستجابة 422 للفشل في التحقق
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // تشفير كلمة المرور
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $user,
        ], 201); // رمز الاستجابة 201 للنجاح في إنشاء مورد جديد
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'failed',
                'error' => $validate->errors(),
            ], 422); // رمز الاستجابة 422 للفشل في التحقق
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $access_token = bin2hex(random_bytes(32));
                $user->access_token = $access_token;
                $user->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'you are logged in',
                    'access_token' => $access_token, // تم تصحيح الخطأ الإملائي هنا
                ], 200);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'password is wrong',
                ], 401); // رمز الاستجابة 401 للوصول غير المصرح به
            }
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'email is wrong',
            ], 404); // رمز الاستجابة 404 لم يتم العثور على المورد
        }
    }
}