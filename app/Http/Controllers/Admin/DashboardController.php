<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'inactive_employees' => Employee::where('status', 'inactive')->count(),
            'total_leads' => 100,
            'total_qualified' => 50,
            'total_products' => 30,
            'total_lost' => 10,
            'total_won' => 10,
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
