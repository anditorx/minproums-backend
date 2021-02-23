<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $users = DB::table('users')->where('roles', 'customer')->get();
        $admins = DB::table('users')->where('roles', 'admin')->get();
        $total_customer = count($users);
        $total_admin = count($admins);
        
        return view('dashboard', [
            'total_customer' => $total_customer,
            'total_admin' => $total_admin,
        ]);
    }
}
