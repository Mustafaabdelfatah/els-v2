<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class employeeController extends Controller
{
    public function index(Request $request)
    {
        dd($request->all());
        dispatch(new SendMailJob($user->email));
    }
}
