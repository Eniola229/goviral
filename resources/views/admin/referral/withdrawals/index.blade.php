@extends('admin.layouts.app')

@section('content')
<div class="page-header">
    <div class="page-header-left d-flex align-items-center">
        <div class="page-header-title">
            <h5 class="m-b-10">Referral Withdrawals</h5>
        </div>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Referral Withdrawals</li>
        </ul>
    </div>
</div>

@if(session('alert'))
    <div class="alert alert-{{ session('alert.type') }} alert-dismissible fade show">
        <i class="feather-{{ session('alert.type') == 'success' ? 'check-circle' : 'alert-circle' }} me-2"></i>
        {{ session('alert.message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="fw-bold mb-1">{{ $stats['pending'] }}</h3>
                        <p class="text-muted mb-0">Pending</p>
                    </div>
                    <div class="avatar-text avatar-lg bg-warning-subtle">
                        <i class="feather-clock text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="fw-bold mb-1">{{ $stats['approved'] }}</h3>
                        <p class="text-muted mb-0">Approved</p>
                    </div>
                    <div class="avatar-text avatar-lg bg-info-subtle">
                        <i class="feather-check text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="fw-bold mb-1">{{ $stats['success'] }}</h3>
                        <p class="text-muted mb-0">Completed</p>
                    </div>
                    <div class="avatar-text avatar-lg bg-success-subtle">
                        <i class="feather-check-circle text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="fw-bold mb-1">{{ $stats['failed'] }}</h3>
                        <p class="text-muted mb-0">Failed/Rejected</p>
                    </div>
                    <div class="avatar-text avatar-lg bg-danger-subtle">
                        <i class="feather-x-circle text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $status == 'pending' ? 'active' : '' }}" href="?status=pending">
                    Pending ({{ $stats['pending'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status == 'approved' ? 'active' : '' }}" href="?status=approved">
                    Approved ({{ $stats['approved'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status == 'success' ? 'active' : '' }}" href="?status=success">
                    Completed ({{ $stats['success'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status == 'failed' ? 'active' : '' }}" href="?status=failed">
                    Failed ({{ $stats['failed'] }})
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $status == 'all' ? 'active' : '' }}" href="?status=all">
                    All
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr class="border-b">
                        <th>Reference</th>
                        <th>User</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Bank Details</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $withdrawal)
                        <tr>
                            <td>
                                <code>{{ $withdrawal->reference }}</code>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-bold">{{ $withdrawal->referral->user->name }}</div>
                                    <small class="text-muted">{{ $withdrawal->referral->user->email }}</small>
                                </div>
                            </td>
                            <td>
                                @if(str_contains($withdrawal->description, 'bank'))
                                    <span class="badge bg-soft-primary text-primary">
                                        <i class="feather-briefcase me-1"></i> Bank Transfer
                                    </span>
                                @else
                                    <span class="badge bg-soft-info text-info">
                                        <i class="feather-credit-card me-1"></i> Wallet
                                    </span>
                                @endif
                            </td>
                            <td class="fw-bold">₦{{ number_format($withdrawal->amount, 2) }}</td>
                            <td>
                                @if(isset($withdrawal->metadata['bank_name']))
                                    <div class="small">
                                        <div><strong>{{ $withdrawal->metadata['bank_name'] }}</strong></div>
                                        <div>{{ $withdrawal->metadata['account_number'] }}</div>
                                        <div>{{ $withdrawal->metadata['account_name'] }}</div>
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($withdrawal->status == 'success')
                                    <span class="badge bg-soft-success text-success">
                                        <i class="feather-check-circle me-1"></i> Success
                                    </span>
                                @elseif($withdrawal->status == 'pending')
                                    <span class="badge bg-soft-warning text-warning">
                                        <i class="feather-clock me-1"></i> Pending
                                    </span>
                                @elseif($withdrawal->status == 'approved')
                                    <span class="badge bg-soft-info text-info">
                                        <i class="feather-check me-1"></i> Approved
                                    </span>
                                @else
                                    <span class="badge bg-soft-danger text-danger">
                                        <i class="feather-x-circle me-1"></i> {{ ucfirst($withdrawal->status) }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $withdrawal->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                @if($withdrawal->status == 'pending')
                                    @if(Auth::guard('admin')->user()->isSuperAdmin() || Auth::guard('admin')->user()->isAccountant())
                                        <div class="btn-group">
                                            @if(str_contains($withdrawal->description, 'wallet'))
                                                <form action="{{ route('admin.referral.withdrawals.approve-wallet', $withdrawal->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Approve this wallet withdrawal?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Approve to Wallet">
                                                        <i class="feather-check"></i> Approve
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.referral.withdrawals.approve-bank', $withdrawal->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Initiate bank transfer for this withdrawal?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Process Bank Transfer">
                                                        <i class="feather-send"></i> Process
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger ms-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#rejectModal{{ $withdrawal->id }}"
                                                    title="Reject Withdrawal">
                                                <i class="feather-x"></i> Reject
                                            </button>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $withdrawal->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.referral.withdrawals.reject', $withdrawal->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Withdrawal</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Rejecting withdrawal for <strong>{{ $withdrawal->referral->user->name }}</strong></p>
                                                            <p>Amount: <strong>₦{{ number_format($withdrawal->amount, 2) }}</strong></p>
                                                            
                                                            <div class="mb-3">
                                                                <label class="form-label">Reason for Rejection *</label>
                                                                <textarea name="reason" 
                                                                          class="form-control" 
                                                                          rows="3" 
                                                                          placeholder="Enter reason for rejection" 
                                                                          required></textarea>
                                                            </div>

                                                            <div class="alert alert-warning">
                                                                <i class="feather-info me-2"></i>
                                                                The amount will be refunded to the user's referral balance.
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Reject Withdrawal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge bg-soft-warning text-warning">
                                            <i class="feather-lock me-1"></i> No Permission
                                        </span>
                                    @endif
                                @else
                                    <a href="{{ route('admin.referral.withdrawals.show', $withdrawal->id) }}" class="btn btn-sm btn-info">
                                        <i class="feather-eye"></i> View
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No withdrawal requests found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($withdrawals->hasPages())
        <div class="card-footer">
            {{ $withdrawals->appends(['status' => $status])->links() }}
        </div>
    @endif
</div>
@endsection