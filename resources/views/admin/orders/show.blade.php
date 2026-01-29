@include('components.g-header')
@include('admin.components.nav')
@include('admin.components.header')

<main class="nxl-container">
    <div class="nxl-content">
        
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Order Details</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
                    <li class="breadcrumb-item">#{{ substr($order->id, 0, 8) }}</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="d-flex gap-2">
                    <!-- Manual Status Check Button -->
                    @if($order->api_order_id && in_array($order->status, ['pending', 'processing']))
                        <form method="POST" action="{{ route('admin.orders.check-status', $order->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-info">
                                <i class="feather-refresh-cw me-2"></i> Check Status
                            </button>
                        </form>
                    @endif
                    
                    <a href="{{ route('admin.customers.show', $order->user_id) }}" class="btn btn-sm btn-light-brand">
                        <i class="feather-user me-2"></i> View Customer
                    </a>
                </div>
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

        <!-- Main Content -->
        <div class="main-content">
            <div class="row">
                
                <!-- Order Info Card -->
                <div class="col-xxl-4 col-xl-6">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Order Information</h5>
                            <div>
                                @if($order->status == 'completed')
                                    <span class="badge bg-success">completed</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-info">processing</span>
                                @elseif($order->status == 'pending')
                                    <span class="badge bg-warning">pending</span>
                                @elseif($order->status == 'refunded')
                                    <span class="badge bg-primary">refunded</span>
                                @else
                                    <span class="badge bg-danger">cancelled</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fs-12 text-muted">Order ID:</span>
                                    <code class="fs-11">{{ $order->id }}</code>
                                </div>
                            </div>

                            @if($order->api_order_id)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fs-12 text-muted">API Order ID:</span>
                                    <code class="fs-11">{{ $order->api_order_id }}</code>
                                </div>
                            </div>
                            @endif

                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fs-12 text-muted">Service:</span>
                                    <span class="fs-12 fw-bold text-end" style="max-width: 60%;">{{ $order->service_name }}</span>
                                </div>
                            </div>

                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fs-12 text-muted">Link/URL:</span>
                                </div>
                                <a href="{{ $order->link }}" target="_blank" class="fs-11 text-primary text-break">
                                    {{ $order->link }}
                                </a>
                            </div>

                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fs-12 text-muted">Quantity:</span>
                                    <span class="fs-12 fw-bold">{{ number_format($order->quantity) }}</span>
                                </div>
                            </div>

                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fs-12 text-muted">Charge:</span>
                                    <span class="fs-12 fw-bold text-success">₦{{ number_format($order->charge, 2) }}</span>
                                </div>
                            </div>

                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fs-12 text-muted">Created:</span>
                                    <span class="fs-12 fw-bold">{{ $order->created_at->format('M d, Y H:i') }}</span>
                                </div>
                            </div>

                            <div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fs-12 text-muted">Last Updated:</span>
                                    <span class="fs-12 fw-bold">{{ $order->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info Card -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="card-title">Customer Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="avatar-text avatar-lg bg-soft-primary text-primary me-3">
                                    {{ substr($order->user->name, 0, 2) }}
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $order->user->name }}</h6>
                                    <p class="fs-12 text-muted mb-0">{{ $order->user->email }}</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <span class="fs-12 text-muted">Customer Balance:</span>
                                <span class="fs-12 fw-bold text-success">₦{{ number_format($customerBalance, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="fs-12 text-muted">Member Since:</span>
                                <span class="fs-12 fw-bold">{{ $order->user->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions & Logs -->
                <div class="col-xxl-8 col-xl-6">
                    
                    <!-- Admin Actions (Super Admin & Accountant Only) -->
                    @if(auth('admin')->user()->canEditOrders())
                    <div class="card mb-3">
                        <div class="card-header bg-soft-warning">
                            <h5 class="card-title text-warning">
                                <i class="feather-shield me-2"></i> Admin Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Update Status -->
                                <div class="col-md-6">
                                    <form method="POST" action="{{ route('admin.orders.update-status', $order->id) }}">
                                        @csrf
                                        <label class="form-label fw-bold">Update Order Status</label>
                                        <div class="input-group">
                                            <select name="status" class="form-select" required>
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>pending</option>
                                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>processing</option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>completed</option>
                                                <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>refunded</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>cancelled</option>
                                            </select>
                                            <button type="submit" class="btn btn-warning">Update</button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Refund Order -->
                                @if(!in_array($order->status, ['completed', 'cancelled', 'refunded']))
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Refund Order</label>
                                    <form method="POST" action="{{ route('admin.orders.refund', $order->id) }}" onsubmit="return confirm('Are you sure you want to refund this order? ₦{{ number_format($order->charge, 2) }} will be credited to customer wallet.')">
                                        @csrf
                                        <button type="submit" class="btn btn-info w-100">
                                            <i class="feather-rotate-ccw me-2"></i> Refund
                                        </button>
                                    </form>
                                </div>
                                @endif

                                <!-- Delete Order -->
                                @if(auth('admin')->user()->canDeleteOrders() && in_array($order->status, ['cancelled', 'completed', 'refunded']))
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Delete Order</label>
                                    <form method="POST" action="{{ route('admin.orders.destroy', $order->id) }}" onsubmit="return confirm('Are you sure you want to DELETE this order? This action cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">
                                            <i class="feather-trash-2 me-2"></i> Delete
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- API Response -->
                    @if($order->api_response)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">API Response</h5>
                        </div>
                        <div class="card-body">
                            <pre class="bg-light p-3 rounded" style="max-height: 300px; overflow-y: auto;"><code>{{ json_encode(json_decode($order->api_response), JSON_PRETTY_PRINT) }}</code></pre>
                        </div>
                    </div>
                    @endif

                    <!-- Order Logs -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Order Activity Logs</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="border-b">
                                            <th>Type</th>
                                            <th>Method</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($logs as $log)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-soft-primary text-primary">
                                                        {{ ucfirst($log->type) }}
                                                    </span>
                                                </td>
                                                <td><code class="fs-11">{{ $log->method }}</code></td>
                                                <td>{{ Str::limit($log->description, 60) }}</td>
                                                <td>
                                                    @if($log->status == 'completed')
                                                        <span class="badge bg-soft-success text-success">Success</span>
                                                    @elseif($log->status == 'failed')
                                                        <span class="badge bg-soft-danger text-danger">Failed</span>
                                                    @else
                                                        <span class="badge bg-soft-warning text-warning">{{ ucfirst($log->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $log->created_at->format('M d, H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-3 text-muted">No activity logs</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Logs Pagination -->
                        @if($logs->hasPages())
                        <div class="card-footer">
                            {{ $logs->links() }}
                        </div>
                        @endif
                    </div>

                </div>

            </div>
        </div>

    </div>
</main>

@include('admin.components.footer')