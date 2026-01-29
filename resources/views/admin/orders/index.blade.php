@include('components.g-header')
@include('admin.components.nav')
@include('admin.components.header')

<main class="nxl-container">
    <div class="nxl-content">
        
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Orders Management</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item">Orders</li>
                </ul>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="feather-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="feather-alert-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4 mt-4">
            <div class="col-xxl-2 col-md-4 col-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-bold mb-1">Total Orders</div>
                                <div class="fs-4 fw-bold text-dark">{{ number_format($totalOrders) }}</div>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-primary text-primary">
                                <i class="feather-shopping-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-2 col-md-4 col-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-bold mb-1">Pending</div>
                                <div class="fs-4 fw-bold text-warning">{{ number_format($pendingOrders) }}</div>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-warning text-warning">
                                <i class="feather-clock"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-2 col-md-4 col-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-bold mb-1">Processing</div>
                                <div class="fs-4 fw-bold text-info">{{ number_format($processingOrders) }}</div>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-info text-info">
                                <i class="feather-loader"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-2 col-md-4 col-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-bold mb-1">Completed</div>
                                <div class="fs-4 fw-bold text-success">{{ number_format($completedOrders) }}</div>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-success text-success">
                                <i class="feather-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-2 col-md-4 col-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-bold mb-1">Cancelled</div>
                                <div class="fs-4 fw-bold text-danger">{{ number_format($cancelledOrders) }}</div>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-danger text-danger">
                                <i class="feather-x-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xxl-2 col-md-4 col-6">
                <div class="card stretch stretch-full">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-bold mb-1">Revenue</div>
                                <div class="fs-4 fw-bold text-primary">₦{{ number_format($totalRevenue, 0) }}</div>
                            </div>
                            <div class="avatar-text avatar-lg bg-soft-primary text-primary">
                                <i class="feather-dollar-sign"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">All Orders</h5>
            </div>

            <!-- Filters -->
            <div class="card-body border-bottom">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search orders..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_from" class="form-control" placeholder="From" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_to" class="form-control" placeholder="To" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="feather-filter me-2"></i> Filter
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                <i class="feather-x me-2"></i> Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="border-b">
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Quantity</th>
                                <th>Charge</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $orderItem)
                                <tr>
                                    <td>
                                        <code class="fs-11">#{{ substr($orderItem->id, 0, 8) }}</code>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.customers.show', $orderItem->user_id) }}" class="text-dark">
                                            {{ $orderItem->user->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $orderItem->service_name }}">
                                            {{ $orderItem->service_name }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($orderItem->quantity) }}</td>
                                    <td class="text-success fw-bold">₦{{ number_format($orderItem->charge, 2) }}</td>
                                    <td>
                                        @if($orderItem->status == 'completed')
                                            <span class="badge bg-soft-success text-success">Completed</span>
                                        @elseif($orderItem->status == 'processing')
                                            <span class="badge bg-soft-info text-info">Processing</span>
                                        @elseif($orderItem->status == 'pending')
                                            <span class="badge bg-soft-warning text-warning">Pending</span>
                                        @elseif($orderItem->status == 'partial')
                                            <span class="badge bg-soft-primary text-primary">Partial</span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>{{ $orderItem->created_at->format('M d, Y') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.orders.show', $orderItem->id) }}" class="btn btn-sm btn-light-brand">
                                            <i class="feather-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="feather-inbox fs-3 d-block mb-2"></i>
                                        No orders found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
            <div class="card-footer">
                {{ $orders->links() }}
            </div>
            @endif
        </div>

    </div>
</main>

@include('admin.components.footer')