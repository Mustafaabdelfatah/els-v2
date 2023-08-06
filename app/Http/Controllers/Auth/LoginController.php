<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        try {
            $credentials = [
                'email' => $request->email,
                'password' => $request->password
            ];

//            return $this->attemptLogin($credentials);

            if ($this->attemptLogin($credentials)) {
                return $this->sendLoginResponse($request);
            }
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 422);
        }

//        $user = User::withTrashed()->where('email',$request->email)->first();
//        if($user->deleted_at != null)
//        {
//
//        }


        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function credentials($request)
    {
        return [
            'uid' => $request->{$this->username()},
            'password' => $request->get('password'),
        ];
    }

    public function attemptLogin($request)
    {
        // attempt to issue a token to user based on the login credentials
        // $token = $this->guard()->attempt($this->credentials($request));

        // ldap
        // $token = Auth::attempt($this->credentials($request));

        $request = collect($request);
        $localUser = User::where('email', $request->get('email'))->first();
        if($localUser && $request->get('password') == $localUser->password)
            Auth::login($localUser);
        else if(!auth()->attempt(['mail' => $request->get('email'), 'password' => $request->get('password')]) &&
            !auth()->attempt(['distinguishedName' => $request->get('email'), 'password' => $request->get('password')]) &&
            !auth()->attempt(['userPrincipalName' => $request->get('email'), 'password' => $request->get('password')]) &&
            !auth()->attempt(['cn' => $request->get('email'), 'password' => $request->get('password')]) &&
            !auth()->attempt(['samaccountname' => $request->get('email'), 'password' => $request->get('password')]) &&
            !auth()->attempt(['uid' => $request->get('email'), 'password' => $request->get('password')])){
            Auth::login($localUser);
        }

//        if (!auth()->attempt(['mail' => $request->get('email'), 'password' => $request->get('password')]) &&
//            !auth()->attempt(['distinguishedName' => $request->get('email'), 'password' => $request->get('password')]) &&
//            !auth()->attempt(['userPrincipalName' => $request->get('email'), 'password' => $request->get('password')]) &&
//            !auth()->attempt(['cn' => $request->get('email'), 'password' => $request->get('password')]) &&
//            !auth()->attempt(['samaccountname' => $request->get('email'), 'password' => $request->get('password')]) &&
//            !auth()->attempt(['uid' => $request->get('email'), 'password' => $request->get('password')])) {
//            // try to log to local users
//            $localUser = User::where('email', $request->get('email'))->first();
//
//            if (!$localUser || $request->get('password') == $localUser->password) {
//                return false;
//            }
//
//            Auth::login($localUser);
//        }

        auth()->user()->refresh();
        if (!isset(auth()->user()->organization_id)) {
            auth()->user()->organization_id = 1;
            auth()->user()->assignRole('Employee');
            auth()->user()->save();
        }

        if (!auth()->user()->active)
            return false;

        $user = $this->guard()->user();
        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail())
            return false;

        return true;
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        $user = auth()->user();
        $role = $user->type;

        $tokenData = $user->createToken('__token__', [$role]);
        $token = $tokenData->token;

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        if ($token->save()) {
            return [
                'token' => $tokenData->accessToken,
                'token_type' => 'Bearer',
                'token_scope' => $token->scopes[0],
                'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
                'status_code' => 200
            ];
        }
    }

    protected function sendFailedLoginResponse()
    {
        $user = $this->guard()->user();

        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return response()->json(["errors" => [
                $this->username() => __('You need to verify your email address')
            ]], 422);
        }

        if (auth()->check() && !$user->active) {
            return response()->json(["errors" => [
                $this->username() => __('Your account is locked,please contact admin')
            ]], 422);
        }

        throw ValidationException::withMessages([
            $this->username() => "Authentication failed"
        ]);
    }

    public function logout()
    {
        auth()->user()->token()->revoke();
        return response()->json(['message' => 'Logged out successfully!']);
    }
}
