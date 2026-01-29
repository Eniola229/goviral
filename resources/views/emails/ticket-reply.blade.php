@include('components.g-header')
<main class="auth-minimal-wrapper">
    <div class="auth-minimal-inner">
        <div class="minimal-card-wrapper">
            <div class="card mb-4 mt-5 mx-4 mx-sm-0 position-relative">
                <div class="wd-50 bg-white p-2 rounded-circle shadow-lg position-absolute translate-middle top-0 start-50">
                    <img src="{{ asset('assets/images/B.png') }}" alt="" class="img-fluid">
                </div>
                <div class="card-body p-sm-5">
                    <h2 class="fs-20 fw-bolder mb-4">Support Ticket Reply</h2>
                    <h4 class="fs-13 fw-bold mb-2">You have a new reply to your support ticket</h4>
                    
                    <div class="mt-4 pt-2">
                        <p class="mb-3">Hello <strong>{{ $ticket->user->name }}</strong>,</p>
                        
                        <p class="mb-3">Our support team has responded to your ticket.</p>
                        
                        <div class="alert alert-light border mb-4">
                            <div class="mb-2">
                                <strong>Ticket #:</strong> {{ $ticket->id }}
                            </div>
                            <div class="mb-2">
                                <strong>Subject:</strong> {{ $ticket->subject }}
                            </div>
                            <div>
                                <strong>Status:</strong> 
                                <span class="badge 
                                    @if($ticket->status === 'open') bg-warning
                                    @elseif($ticket->status === 'in_progress') bg-info
                                    @elseif($ticket->status === 'closed') bg-success
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h5 class="fs-14 fw-bold mb-3">Admin Reply:</h5>
                                <p class="mb-0" style="white-space: pre-wrap;">{{ $message->message }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('user.support.show', $ticket->id) }}" class="btn btn-lg btn-primary w-100">
                                View Full Ticket & Reply
                            </a>
                        </div>
                        
                        <div class="mt-4 text-muted">
                            <p class="fs-12 mb-0">If you have any questions, please reply to this ticket or contact our support team.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@include('components.g-footer')