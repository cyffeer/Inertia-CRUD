<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $activities = Activity::with('user', 'post')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return Inertia::render('Dashboard', [
            'activities' => $activities,
            'authUser' => Auth::user(),
        ]);
    }
}