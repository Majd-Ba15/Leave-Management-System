<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            $requests = LeaveRequest::with(['user', 'leaveType', 'approver'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } elseif ($user->isManager()) {
            $requests = LeaveRequest::with(['user', 'leaveType', 'approver'])
                ->whereHas('user', function($query) use ($user) {
                    $query->where('department_id', $user->department_id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        } else {
            $requests = $user->leaveRequests()
                ->with(['leaveType', 'approver'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('leave-requests.index', compact('requests'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::where('is_active', true)->get();
        return view('leave-requests.create', compact('leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $totalDays = $startDate->diffInDays($endDate) + 1;

        LeaveRequest::create([
            'user_id' => auth()->id(),
            'leave_type_id' => $validated['leave_type_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_days' => $totalDays,
            'reason' => $validated['reason'],
        ]);

        return redirect()->route('leave-requests.index')
            ->with('success', 'Leave request submitted successfully!');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);
        
        $leaveRequest->load(['user', 'leaveType', 'approver']);
        return view('leave-requests.show', compact('leaveRequest'));
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $this->authorize('approve', $leaveRequest);

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Leave request approved successfully!');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('approve', $leaveRequest);

        $request->validate([
            'manager_comment' => 'required|string|max:500',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'manager_comment' => $request->manager_comment,
        ]);

        return redirect()->back()->with('success', 'Leave request rejected.');
    }
}