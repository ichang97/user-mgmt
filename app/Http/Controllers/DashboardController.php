<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $totalUsers = User::query()->count();
        $activeUsers = User::query()->where('status', 'active')->count();
        $inactiveUsers = User::query()->where('status', 'inactive')->count();

        return view('dashboard', compact('totalUsers', 'activeUsers', 'inactiveUsers'));
    }
}
