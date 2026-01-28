    @include('components.g-header')
    @include('components.nav')

    <main class="nxl-container">
        <div class="nxl-content">
            
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Order History</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item">Orders</li>
                    </ul>
                </div>
                <div class="page-header-right ms-auto">
                    <a href="{{ route('order.create') }}" class="btn btn-primary">
                        <i class="feather-plus me-2"></i> New Order
                    </a>
                </div>
            </div>

            <div class="main-content">
                <div class="row">
                    <div class="col-12">
                        <div class="card stretch stretch-full">
                            <div class="card-body custom-card-action p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Service</th>
                                                <th>Link</th>
                                                <th>Quantity</th>
                                                <th>Charge</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($orders as $order)
                                                <tr>
                                                    <td>#{{ substr($order->id, 0, 8) }}...</td>
                                                    <td>{{ $order->service_name }}</td>
                                                    <td>
                                                        <a href="{{ $order->link }}" target="_blank" class="text-truncate d-block" style="max-width: 200px;">
                                                            {{ Str::limit($order->link, 30) }}
                                                        </a>
                                                    </td>
                                                    <td>{{ number_format($order->quantity) }}</td>
                                                    <td>₦{{ number_format($order->charge, 2) }}</td>
                                                    <td>
                                                        @if($order->status == 'completed')
                                                            <span class="badge bg-soft-success text-success">Completed</span>
                                                        @elseif($order->status == 'processing')
                                                            <span class="badge bg-soft-warning text-warning">Processing</span>
                                                        @elseif($order->status == 'pending')
                                                            <span class="badge bg-soft-primary text-primary">Pending</span>
                                                        @elseif($order->status == 'cancelled')
                                                            <span class="badge bg-soft-danger text-danger">Cancelled (Refunded)</span>
                                                        @else
                                                            <span class="badge bg-soft-secondary text-secondary">{{ ucfirst($order->status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $order->created_at->diffForHumans() }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-5">
                                                        <div class="text-muted mb-3">No orders found.</div>
                                                        <a href="{{ route('order.create') }}" class="btn btn-primary btn-sm">Place Your First Order</a>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Pagination -->
                            <div class="card-footer">
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('components.g-footer')

</body>
</html>