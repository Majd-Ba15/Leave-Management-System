<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $stats = [
            'total_requests' => 0,
            'pending_requests' => 0,
            'approved_requests' => 0,
            'rejected_requests' => 0,
        ];

        if ($user->isAdmin()) {
            $stats = [
                'total_requests' => LeaveRequest::count(),
                'pending_requests' => LeaveRequest::where('status', 'pending')->count(),
                'approved_requests' => LeaveRequest::where('status', 'approved')->count(),
                'rejected_requests' => LeaveRequest::where('status', 'rejected')->count(),
            ];
            
            $recentRequests = LeaveRequest::with(['user', 'leaveType'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } else {
            $stats = [
                'total_requests' => $user->leaveRequests()->count(),
                'pending_requests' => $user->leaveRequests()->where('status', 'pending')->count(),
                'approved_requests' => $user->leaveRequests()->where('status', 'approved')->count(),
                'rejected_requests' => $user->leaveRequests()->where('status', 'rejected')->count(),
            ];
            
            $recentRequests = $user->leaveRequests()
                ->with('leaveType')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        return view('dashboard', compact('stats', 'recentRequests'));
    }
}