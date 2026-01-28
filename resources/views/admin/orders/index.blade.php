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
        <div class="row mb-4">
            <div class="col-md-4 col-lg-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted mb-2 fs-11">Total Orders</h6>
                        <h3 class="mb-0">{{ number_format($totalOrders) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted mb-2 fs-11">Pending</h6>
                        <h3 class="mb-0 text-warning">{{ number_format($pendingOrders) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted mb-2 fs-11">Processing</h6>
                        <h3 class="mb-0 text-info">{{ number_format($processingOrders) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted mb-2 fs-11">Completed</h6>
                        <h3 class="mb-0 text-success">{{ number_format($completedOrders) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted mb-2 fs-11">Cancelled</h6>
                        <h3 class="mb-0 text-danger">{{ number_format($cancelledOrders) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-2">
                <div class="card">
                    <div class="card-body">
                        <h6 class="text-muted mb-2 fs-11">Total Revenue</h6>
                        <h3 class="mb-0 text-primary">₦{{ number_format($totalRevenue, 0) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">All Orders</h5>
                            <div class="card-header-action">
                                <button type="button" class="btn btn-sm btn-light-brand" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                                    <i class="feather-filter me-2"></i> Filters
                                </button>
                            </div>
                        </div>
                        
                        <!-- Search and Filter Form -->
                        <div class="collapse {{ request()->hasAny(['search', 'status', 'date_from', 'date_to', 'amount_min', 'amount_max']) ? 'show' : '' }}" id="filterCollapse">
                            <div class="card-body border-bottom bg-light">
                                <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label fs-11">Search</label>
                                        <input type="text" 
                                               name="search" 
                                               class="form-control" 
                                               placeholder="Order ID, Service, Customer..." 
                                               value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fs-11">Status</label>
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
                                        <label class="form-label fs-11">From Date</label>
                                        <input type="date" 
                                               name="date_from" 
                                               class="form-control" 
                                               value="{{ request('date_from') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fs-11">To Date</label>
                                        <input type="date" 
                                               name="date_to" 
                                               class="form-control" 
                                               value="{{ request('date_to') }}">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label fs-11">Min Amount</label>
                                        <input type="number" 
                                               name="amount_min" 
                                               class="form-control" 
                                               placeholder="0" 
                                               value="{{ request('amount_min') }}">
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label fs-11">Max Amount</label>
                                        <input type="number" 
                                               name="amount_max" 
                                               class="form-control" 
                                               placeholder="∞" 
                                               value="{{ request('amount_max') }}">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <div class="d-flex gap-1 w-100">
                                            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                                <i class="feather-search"></i>
                                            </button>
                                            <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm">
                                                <i class="feather-x"></i>
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Orders Table -->
                        <div class="card-body custom-card-action p-0">
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
                                        @forelse($orders as $order)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-primary">
                                                        #{{ substr($order->id, 0, 8) }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.customers.show', $order->user_id) }}" class="d-flex align-items-center">
                                                        <div class="avatar-text avatar-sm bg-soft-primary text-primary me-2">
                                                            {{ substr($order->user->name, 0, 2) }}
                                                        </div>
                                                        <span>{{ $order->user->name }}</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $order->service_name }}">
                                                        {{ $order->service_name }}
                                                    </div>
                                                </td>
                                                <td>{{ number_format($order->quantity) }}</td>
                                                <td>₦{{ number_format($order->charge, 2) }}</td>
                                                <td>
                                                    @if($order->status == 'completed')
                                                        <span class="badge bg-soft-success text-success">
                                                            <i class="feather-check-circle me-1"></i> Completed
                                                        </span>
                                                    @elseif($order->status == 'processing')
                                                        <span class="badge bg-soft-info text-info">
                                                            <i class="feather-loader me-1"></i> Processing
                                                        </span>
                                                    @elseif($order->status == 'pending')
                                                        <span class="badge bg-soft-warning text-warning">
                                                            <i class="feather-clock me-1"></i> Pending
                                                        </span>
                                                    @elseif($order->status == 'partial')
                                                        <span class="badge bg-soft-primary text-primary">
                                                            <i class="feather-pie-chart me-1"></i> Partial
                                                        </span>
                                                    @else
                                                        <span class="badge bg-soft-danger text-danger">
                                                            <i class="feather-x-circle me-1"></i> Cancelled
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>{{ $order->created_at->format('M d, Y') }}</div>
                                                    <span class="fs-11 text-muted">{{ $order->created_at->format('H:i') }}</span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                                       class="btn btn-sm btn-light-brand">
                                                        <i class="feather-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5">
                                                    <div class="mb-3">
                                                        <i class="feather-shopping-cart fs-1 text-muted"></i>
                                                    </div>
                                                    <h6 class="text-muted">No orders found</h6>
                                                    @if(request()->hasAny(['search', 'status', 'date_from', 'date_to', 'amount_min', 'amount_max']))
                                                        <p class="text-muted mb-0">Try adjusting your filters</p>
                                                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary mt-3">
                                                            Clear Filters
                                                        </a>
                                                    @endif
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
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted mb-0 fs-12">
                                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ number_format($orders->total()) }} orders
                                    </p>
                                </div>
                                <div>
                                    {{ $orders->links() }}
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

@include('admin.components.footer')