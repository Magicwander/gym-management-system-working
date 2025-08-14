<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Redirect to role-specific dashboards
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'trainer':
                return redirect()->route('trainer.dashboard');
            case 'member':
                return redirect()->route('customer.dashboard');
            default:
                abort(403, 'Unauthorized access.');
        }
    }
}
