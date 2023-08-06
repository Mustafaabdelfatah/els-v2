<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\OTPVerificationEmail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;


class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);
        $email = $request->email;
        // Check if the user exists in the database
        $user = User::where('email', $email)->first();
        if ($user) {
            $domain = substr(strrchr($email, "@"), 1);
            // Check if the email domain is "ncms.sa"
            if ($domain !== 'ncms.sa'){
                $otp = random_int(100000, 200000);;
                // Store the verification code in the user's database record
                $user->otp = $otp;
                $user->save();
                // $resetLink = route('password.resetPage', ['email' => $email, 'otp' => $otp]);
                $resetLink = 'http://localhost:3000/reset-password';
                // Send the verification code to the user's email address
               // Send the verification code to the user's email address
                try {
                    Mail::to($user->email)->send(new OTPVerificationEmail($otp, $user->name, $resetLink));
                    return 'yes';
                } catch (\Exception $e) {
                    // Handle any exceptions that occur during email sending
                    return $e->getMessage();
                }
            }else{
                return response()->json(['error' => 'Invalid email domain'], 422);
            }
        }else{
            return response()->json(['error' => 'Invalid user'], 422);
        }
    }

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json(['status' => trans($response)], 200);
    }

    protected function sendResetlinkFailedResponse(Request $request, $response)
    {
        return response()->json(['email' => trans($response)], 422);
    }
}
