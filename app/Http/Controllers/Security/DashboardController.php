<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\Resident;

class DashboardController extends Controller
{
    public function index()
    {
        $active_emergency = Incident::where('status', 'active')
            ->where('type', 'emergency')
            ->with('resident')
            ->first();

        $recent_incidents = Incident::with('resident')
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'today_incidents' => Incident::whereDate('created_at', now())->count(),
            'resolved_today' => Incident::whereDate('resolved_at', now())->count(),
            'active_alerts' => Incident::where('status', 'active')->count(),
        ];

        return view('security.dashboard', compact('active_emergency', 'recent_incidents', 'stats'));
    }
}
