<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;

class LeaveRequestPolicy
{
    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        // Admin can view all
        if ($user->isAdmin()) {
            return true;
        }
        
        // Manager can view requests from their department
        if ($user->isManager() && $user->department_id === $leaveRequest->user->department_id) {
            return true;
        }
        
        // Employee can view their own requests
        return $user->id === $leaveRequest->user_id;
    }

    public function approve(User $user, LeaveRequest $leaveRequest): bool
    {
        // Admin can approve all
        if ($user->isAdmin()) {
            return true;
        }
        
        // Manager can approve requests from their department (but not their own)
        if ($user->isManager() && 
            $user->department_id === $leaveRequest->user->department_id &&
            $user->id !== $leaveRequest->user_id) {
            return true;
        }
        
        return false;
    }
}