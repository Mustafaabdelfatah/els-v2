<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    public function reset(Request $request)
    {
        $request->validate([
            'otp' => 'required',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*?[A-Z])(?=.*?[0-9]).{8,}$/'
            ],
        ], [
            'password.regex' => 'The password must be at least 8 characters long and contain at least one uppercase letter and one number.',
        ]);
        $user = User::where('otp', $request->otp)->first();
        if (!$user || !$user->otp || $user->otp !== $request->otp) {
            return response()->json(['message' => 'Invalid OTP'], 422);
        }
        $user->password = Hash::make($request->password);
        $user->otp = null;
        $user->save();

        return response()->json(['message' => 'Password reset successfully']);
    }

    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json(['status' => trans($response)], 200);
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['email' => trans($response)], 422);
    }
}
