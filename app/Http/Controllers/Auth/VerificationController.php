<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Repositories\Contracts\IUser;
use App\Repositories\Eloquent\UsersRepository;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    protected $users;

    /**
     * Create a new controller instance.
     *
     * @param User $user
     */
    public function __construct(UsersRepository $user)
    {
        $this->users = $user;
        // $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request, User $user)
    {
        // Check if the url is valid
        if (! URL::hasValidSignature($request)) {
            return response()->json(["errors"=>[
                "message" => "Invalid verification link"
            ]], 422);
        }


        // Check if user has already verified account

        if ($user->hasVerifiedEmail()) {
            return response()->json(["errors"=>[
                "message" => "Email address already verified"
            ]], 422);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));
        return response()->json([
                "message" => "Your email verified successfully"
            ], 200);
    }
    public function resend(Request $request)
    {
        // Check if the url is valid
        $this->validate($request, [
            'email' =>['email','required']
        ]);
        $user = $this->users->findWhereFirst('email', $request->email);
        if (!$user) {
            return response()->json(["errors"=>[
                "email" => "No user could be found with this email address"
            ]], 422);
        }

        // Check if user has already verified account

        if ($user->hasVerifiedEmail()) {
            return response()->json(["errors"=>[
                "message" => "Email address already verified"
            ]], 422);
        }

        $user->sendEmailVerificationNotification();
        return response()->json([
                "status" => "Your verification link send successfully"
            ], 200);
    }
}
