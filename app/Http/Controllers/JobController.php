<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendEmail;

class JobController extends Controller
{
    public function enqueue(Request $request)
    {
         $details = ['email' => 'recipient@example.com'];
         SendEmail::dispatchNow($details);
    }
}
