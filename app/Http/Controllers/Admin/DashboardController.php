<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard');
    }
    
    public function settings()
    {
        return Inertia::render('Admin/Settings');
    }
    
    public function updateSettings(Request $request)
    {
        // Логика обновления настроек
    }
}