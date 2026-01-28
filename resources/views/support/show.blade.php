<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ substr($ticket->id, 0, 8) }} - Support</title>
</head>
<body>
    @include('components.g-header')
    @include('components.nav')

    <main class="nxl-container">
        <div class="nxl-content">
            <div class="page-header">
                <div class="page-header-left d-flex align-items-center">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Ticket #{{ substr($ticket->id, 0, 8) }}</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('support.index') }}">Support</a></li>
                        <li class="breadcrumb-item">View</li>
                    </ul>
                </div>
            </div>

            <div class="main-content">
                <div class="row">
                    
                    <!-- Chat Area (Left) -->
                    <div class="col-lg-8">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">{{ $ticket->subject }}</h5>
                                <span class="badge bg-{{ $ticket->status == 'open' ? 'primary' : 'secondary' }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </div>
                            <div class="card-body">
                                
                                <!-- Messages Loop: Shows all replies dynamically -->
                                <div class="d-flex flex-column gap-4">
                                    @foreach($ticket->messages as $msg)
                                        <div class="d-flex {{ $msg->is_admin ? 'justify-content-start' : 'justify-content-end' }}">
                                            
                                            <div class="d-flex flex-column {{ $msg->is_admin ? 'align-items-start' : 'align-items-end' }}">
                                                
                                                <!-- Sender Name & Time -->
                                                <small class="text-muted mb-1">
                                                    @if($msg->is_admin)
                                                        <strong class="text-primary">Admin Support</strong>
                                                    @else
                                                        <strong>You</strong>
                                                    @endif
                                                    • {{ $msg->created_at->format('H:i') }}
                                                </small>

                                                <!-- Message Bubble -->
                                                <div class="p-3 rounded {{ $msg->is_admin ? 'bg-light text-dark' : 'bg-primary text-white' }}" style="max-width: 70%;">
                                                    {{ $msg->message }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reply Box (Right) -->
                    <div class="col-lg-4">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Reply</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('support.reply', $ticket->id) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Your Message</label>
                                        <textarea name="message" class="form-control" rows="6" required placeholder="Type your reply here..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="feather-send me-2"></i> Send Reply
                                    </button>
                                </form>
                                
                                <div class="alert alert-info mt-3 mb-0">
                                    <small><i class="feather-info me-2"></i> Replies appear automatically in the chat above.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    @include('components.g-footer')

    <!-- Minimal JS to handle link click if needed -->
    <style>
        .hover-bg-light:hover { background-color: #f8f9fa; }
    </style>
</body>
</html>