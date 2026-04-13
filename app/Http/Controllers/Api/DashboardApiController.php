<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;

class DashboardApiController extends Controller
{
    /**
     * GET /api/dashboard/stats
     * Header: Authorization: Bearer {token}
     *
     * Response:
     * {
     *   "success": true,
     *   "stats": {
     *     "total_employees": 25,
     *     "active_employees": 20,
     *     "inactive_employees": 5,
     *     "total_leads": 100,
     *     "total_qualified": 50,
     *     "total_products": 30,
     *     "total_lost": 10,
     *     "total_won": 10
     *   }
     * }
     */
    public function stats()
    {
        return response()->json([
            'success' => true,
            'stats' => [
                'total_employees' => Employee::count(),
                'active_employees' => Employee::where('status', 'active')->count(),
                'inactive_employees' => Employee::where('status', 'inactive')->count(),
                'total_leads' => 100,
                'total_qualified' => 50,
                'total_products' => 30,
                'total_lost' => 10,
                'total_won' => 10,
            ],
        ]);
    }
}
