@extends('layouts.app')

@section('title', 'Leave Request Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-eye me-2"></i>Leave Request Details</h4>
                    @if($leaveRequest->status === 'pending')
                        <span class="badge bg-warning fs-6">Pending</span>
                    @elseif($leaveRequest->status === 'approved')
                        <span class="badge bg-success fs-6">Approved</span>
                    @else
                        <span class="badge bg-danger fs-6">Rejected</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted">Employee Information</h6>
                        <p><strong>Name:</strong> {{ $leaveRequest->user->name }}</p>
                        <p><strong>Employee ID:</strong> {{ $leaveRequest->user->employee_id }}</p>
                        <p><strong>Department:</strong> {{ $leaveRequest->user->department->name ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted">Leave Information</h6>
                        <p><strong>Leave Type:</strong> {{ $leaveRequest->leaveType->name }}</p>
                        <p><strong>Start Date:</strong> {{ $leaveRequest->start_date->format('M d, Y') }}</p>
                        <p><strong>End Date:</strong> {{ $leaveRequest->end_date->format('M d, Y') }}</p>
                        <p><strong>Total Days:</strong> {{ $leaveRequest->total_days }} days</p>
                    </div>
                </div>

                <hr>

                <div class="mb-4">
                    <h6 class="text-muted">Reason</h6>
                    <p class="bg-light p-3 rounded">{{ $leaveRequest->reason }}</p>
                </div>

                @if($leaveRequest->status !== 'pending')
                    <div class="mb-4">
                        <h6 class="text-muted">Manager Response</h6>
                        <div class="bg-light p-3 rounded">
                            <p><strong>Status:</strong> 
                                @if($leaveRequest->status === 'approved')
                                    <span class="text-success">Approved</span>
                                @else
                                    <span class="text-danger">Rejected</span>
                                @endif
                            </p>
                            <p><strong>Reviewed by:</strong> {{ $leaveRequest->approver->name ?? 'System' }}</p>
                            <p><strong>Review Date:</strong> {{ $leaveRequest->approved_at->format('M d, Y H:i') }}</p>
                            
                            @if($leaveRequest->manager_comment)
                                <p><strong>Comment:</strong></p>
                                <p class="bg-white p-2 rounded border">{{ $leaveRequest->manager_comment }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="mb-4">
                    <h6 class="text-muted">Request Timeline</h6>
                    <p><strong>Submitted:</strong> {{ $leaveRequest->created_at->format('M d, Y H:i') }}</p>
                    @if($leaveRequest->updated_at != $leaveRequest->created_at)
                        <p><strong>Last Updated:</strong> {{ $leaveRequest->updated_at->format('M d, Y H:i') }}</p>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('leave-requests.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                    
                    @can('approve', $leaveRequest)
                        @if($leaveRequest->status === 'pending')
                            <div>
                                <form method="POST" action="{{ route('leave-requests.approve', $leaveRequest) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success me-2" 
                                            onclick="return confirm('Are you sure you want to approve this request?')">
                                        <i class="fas fa-check me-2"></i>Approve
                                    </button>
                                </form>
                                
                                <button type="button" class="btn btn-danger" 
                                        data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times me-2"></i>Reject
                                </button>
                            </div>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

@can('approve', $leaveRequest)
    @if($leaveRequest->status === 'pending')
        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Leave Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('leave-requests.reject', $leaveRequest) }}">
                        @csrf
                        <div class="modal-body">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                You are about to reject this leave request. Please provide a reason.
                            </div>
                            <div class="mb-3">
                                <label for="manager_comment" class="form-label">Reason for Rejection</label>
                                <textarea class="form-control" id="manager_comment" name="manager_comment" 
                                          rows="4" required placeholder="Please provide a detailed reason for rejection..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times me-2"></i>Reject Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endcan
@endsection
