@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>
            <div class="text-muted">
                Welcome back, <strong>{{ auth()->user()->name }}</strong> 
                <span class="badge bg-info">{{ ucfirst(auth()->user()->role) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['total_requests'] }}</h4>
                        <p class="mb-0">Total Requests</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['pending_requests'] }}</h4>
                        <p class="mb-0">Pending</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['approved_requests'] }}</h4>
                        <p class="mb-0">Approved</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4>{{ $stats['rejected_requests'] }}</h4>
                        <p class="mb-0">Rejected</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('leave-requests.create') }}" class="btn btn-success btn-lg w-100 mb-2">
                            <i class="fas fa-plus me-2"></i>Submit New Leave Request
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('leave-requests.index') }}" class="btn btn-primary btn-lg w-100 mb-2">
                            <i class="fas fa-list me-2"></i>View All Requests
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Requests -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Leave Requests</h5>
            </div>
            <div class="card-body">
                @if($recentRequests->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    @if(auth()->user()->isAdmin())
                                        <th>Employee</th>
                                    @endif
                                    <th>Leave Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentRequests as $request)
                                    <tr>
                                        @if(auth()->user()->isAdmin())
                                            <td>{{ $request->user->name }}</td>
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
                                        <td>
                                            <a href="{{ route('leave-requests.show', $request) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No leave requests found.</p>
                        <a href="{{ route('leave-requests.create') }}" class="btn btn-primary">
                            Submit Your First Request
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection