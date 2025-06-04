@extends('layouts.app')

@section('title', 'Leave Requests')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-list me-2"></i>Leave Requests</h2>
            <a href="{{ route('leave-requests.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>New Request
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if($requests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                        <th>Employee</th>
                                    @endif
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                    <tr>
                                        @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                            <td>
                                                <div>
                                                    <strong>{{ $request->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $request->user->employee_id }}</small>
                                                </div>
                                            </td>
                                        @endif
                                        <td>{{ $request->leaveType->name }}</td>
                                        <td>{{ $request->start_date->format('M d, Y') }}</td>
                                        <td>{{ $request->end_date->format('M d, Y') }}</td>
                                        <td>{{ $request->total_days }}</td>
                                        <td>
                                            @if($request->status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($request->status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('leave-requests.show', $request) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @can('approve', $request)
                                                    @if($request->status === 'pending')
                                                        <form method="POST" action="{{ route('leave-requests.approve', $request) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" 
                                                                    onclick="return confirm('Approve this request?')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        
                                                        <button type="button" class="btn btn-sm btn-danger" 
                                                                data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>

                                    @can('approve', $request)
                                        @if($request->status === 'pending')
                                            <!-- Reject Modal -->
                                            <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Leave Request</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="POST" action="{{ route('leave-requests.reject', $request) }}">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="manager_comment" class="form-label">Reason for Rejection</label>
                                                                    <textarea class="form-control" id="manager_comment" name="manager_comment" 
                                                                              rows="3" required placeholder="Please provide a reason..."></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-danger">Reject Request</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endcan
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $requests->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No leave requests found</h5>
                        <p class="text-muted">Start by submitting your first leave request.</p>
                        <a href="{{ route('leave-requests.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Submit Leave Request
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection