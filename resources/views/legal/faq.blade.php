@include('components.g-header')

<main class="nxl-container apps-container apps-notes">
    <div class="nxl-content without-header nxl-full-content">
        <div class="main-content">
            <div class="content-area">
                <div class="content-area-body">
                    <div class="note-wrapper">
                        
                        <!-- Hero Header -->
                        <div class="note-header bg-gradient-primary text-white position-relative overflow-hidden" style="padding: 4rem 0;">
                            <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
                                <div class="position-absolute" style="top: -10%; right: -5%; width: 300px; height: 300px; background: white; border-radius: 50%;"></div>
                                <div class="position-absolute" style="bottom: -15%; left: -5%; width: 400px; height: 400px; background: white; border-radius: 50%;"></div>
                            </div>
                            <div class="container-fluid position-relative">
                                <div class="row justify-content-center">
                                    <div class="col-xxl-8 col-xl-10 text-center">
                                        <a href="{{ route('welcome') }}" class="btn btn-light btn-sm mb-4 shadow-sm">
                                            <i class="feather-arrow-left me-2"></i>
                                            Back to Home
                                        </a>
                                        <h1 class="display-4 fw-bold text-black mb-3">
                                            Frequently Asked Questions
                                        </h1>
                                        <p class="lead text-black-50 mb-0 mx-auto" style="max-width: 600px;">
                                            Find quick answers to common questions about Booster. 
                                            Can't find what you're looking for? Our 24/7 support team is here to help!
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="note-body" style="margin-top: -3rem;">
                            <div class="container-fluid">
                                <div class="row justify-content-center">
                                    <div class="col-xxl-8 col-xl-10">
                                        
                                        <!-- Quick Support Card -->
                                        <div class="card border-0 shadow-lg mb-5 overflow-hidden">
                                            <div class="row g-0">
                                                <div class="col-md-8">
                                                    <div class="card-body p-4">
                                                        <div class="d-flex align-items-start gap-3">
                                                            <div class="flex-shrink-0">
                                                                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                                                    <i class="feather-headphones text-primary fs-3"></i>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h4 class="fw-bold mb-2">Need Help? We're Available 24/7</h4>
                                                                <p class="text-muted mb-3">
                                                                    Our dedicated support team is ready to assist you with any questions, 
                                                                    issues, or concerns you may have.
                                                                </p>
                                                                @auth
                                                                    <a href="{{ route('support.index') }}" class="btn btn-primary">
                                                                        <i class="feather-message-circle me-2"></i>
                                                                        Contact Support Now
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('login') }}" class="btn btn-primary">
                                                                        <i class="feather-log-in me-2"></i>
                                                                        Login to Get Support
                                                                    </a>
                                                                @endauth
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 bg-primary bg-opacity-5 d-none d-md-flex align-items-center justify-content-center">
                                                    <div class="text-center p-3">
                                                        <i class="feather-clock fs-1 text-primary mb-2"></i>
                                                        <div class="fw-bold " style="color: white;">Average Response</div>
                                                        <div class="h4 mb-0 " style="color: white;">1-2 Hours</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Category Tabs -->
                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-body p-0">
                                                <ul class="nav nav-tabs border-0" id="faqTabs" role="tablist" style="padding: 1.5rem 1.5rem 0;">
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active px-4 py-3" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                                                            <i class="feather-help-circle me-2"></i>
                                                            General
                                                        </button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link px-4 py-3" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
                                                            <i class="feather-shopping-cart me-2"></i>
                                                            Orders
                                                        </button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link px-4 py-3" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">
                                                            <i class="feather-credit-card me-2"></i>
                                                            Payment
                                                        </button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link px-4 py-3" id="quality-tab" data-bs-toggle="tab" data-bs-target="#quality" type="button" role="tab">
                                                            <i class="feather-shield me-2"></i>
                                                            Quality
                                                        </button>
                                                    </li>
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link px-4 py-3" id="support-tab" data-bs-toggle="tab" data-bs-target="#support" type="button" role="tab">
                                                            <i class="feather-life-buoy me-2"></i>
                                                            Support
                                                        </button>
                                                    </li>
                                                </ul>

                                                <div class="tab-content p-4" id="faqTabContent">
                                                    
                                                    <!-- General Questions Tab -->
                                                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                                                        <div class="accordion accordion-flush" id="generalAccordion">
                                                            
                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#general1">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        What is Booster?
                                                                    </button>
                                                                </h2>
                                                                <div id="general1" class="accordion-collapse collapse show" data-bs-parent="#generalAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <p class="mb-3">
                                                                            Booster is a leading social media growth platform that helps individuals, businesses, 
                                                                            and influencers grow their social media presence across multiple platforms including 
                                                                            Instagram, TikTok, Twitter/X, YouTube, Facebook, and more.
                                                                        </p>
                                                                        <p class="mb-0 text-muted">
                                                                            We provide services such as followers, likes, views, comments, shares, and other 
                                                                            engagement metrics to help boost your social media visibility and credibility.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#general2">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        Is Booster safe to use?
                                                                    </button>
                                                                </h2>
                                                                <div id="general2" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <div class="alert alert-success border-0 mb-3">
                                                                            <i class="feather-check-circle me-2"></i>
                                                                            <strong>Yes!</strong> We use industry-standard security measures to protect your account and payment information.
                                                                        </div>
                                                                        <p class="mb-2">
                                                                            We never ask for your social media passwords, and all transactions are encrypted.
                                                                        </p>
                                                                        <div class="alert alert-warning border-0 mb-0">
                                                                            <i class="feather-alert-triangle me-2"></i>
                                                                            Please note that using any social media growth service may violate platform terms of service. 
                                                                            We recommend reviewing your platform's policies before using our services.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#general3">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        What platforms do you support?
                                                                    </button>
                                                                </h2>
                                                                <div id="general3" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <p class="mb-3 fw-semibold">We currently support the following social media platforms:</p>
                                                                        <div class="row g-3">
                                                                            <div class="col-sm-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <span>Instagram</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <span>TikTok</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <span>Twitter/X</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <span>Facebook</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <span>YouTube</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <span>Telegram</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <span>Spotify</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <span>LinkedIn</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <span>Discord</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <span>Twitch</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <span>Snapchat</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-plus text-primary"></i>
                                                                                    <span class="fw-semibold">Many more!</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#general4">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        Do I need to provide my password?
                                                                    </button>
                                                                </h2>
                                                                <div id="general4" class="accordion-collapse collapse" data-bs-parent="#generalAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <div class="alert alert-danger border-0">
                                                                            <div class="d-flex align-items-start gap-3">
                                                                                <i class="feather-shield fs-3"></i>
                                                                                <div>
                                                                                    <h6 class="mb-2"><strong>No! Absolutely not!</strong></h6>
                                                                                    <p class="mb-0">
                                                                                        We NEVER ask for your social media passwords. All we need is the public URL/link 
                                                                                        to your profile or post. Be wary of any service that asks for your password - 
                                                                                        it's a red flag for scams.
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <!-- Orders & Delivery Tab -->
                                                    <div class="tab-pane fade" id="orders" role="tabpanel">
                                                        <div class="accordion accordion-flush" id="ordersAccordion">
                                                            
                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#orders1">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        How long does delivery take?
                                                                    </button>
                                                                </h2>
                                                                <div id="orders1" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <p class="mb-3">Delivery times vary by service type:</p>
                                                                        <div class="row g-3 mb-3">
                                                                            <div class="col-sm-6">
                                                                                <div class="card bg-success bg-opacity-10 border-success border-opacity-25">
                                                                                    <div class="card-body p-3">
                                                                                        <div class="d-flex align-items-center gap-2 mb-2">
                                                                                            <i class="feather-zap text-success"></i>
                                                                                            <strong>Instant Services</strong>
                                                                                        </div>
                                                                                        <small class="text-muted">Start within minutes</small>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="card bg-primary bg-opacity-10 border-primary border-opacity-25">
                                                                                    <div class="card-body p-3">
                                                                                        <div class="d-flex align-items-center gap-2 mb-2">
                                                                                            <i class="feather-fast-forward text-primary"></i>
                                                                                            <strong>Fast Services</strong>
                                                                                        </div>
                                                                                        <small class="text-muted">Complete within 1-6 hours</small>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="card bg-info bg-opacity-10 border-info border-opacity-25">
                                                                                    <div class="card-body p-3">
                                                                                        <div class="d-flex align-items-center gap-2 mb-2">
                                                                                            <i class="feather-clock text-info"></i>
                                                                                            <strong>Standard Services</strong>
                                                                                        </div>
                                                                                        <small class="text-muted">Complete within 24-48 hours</small>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <div class="card bg-warning bg-opacity-10 border-warning border-opacity-25">
                                                                                    <div class="card-body p-3">
                                                                                        <div class="d-flex align-items-center gap-2 mb-2">
                                                                                            <i class="feather-trending-up text-warning"></i>
                                                                                            <strong>Gradual Services</strong>
                                                                                        </div>
                                                                                        <small class="text-muted">Over several days (natural)</small>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <p class="mb-0 text-muted small">
                                                                            <i class="feather-info me-1"></i>
                                                                            Check individual service descriptions for specific delivery timeframes. 
                                                                            You can track your order status in real-time from your dashboard.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#orders2">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        How do I place an order?
                                                                    </button>
                                                                </h2>
                                                                <div id="orders2" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <p class="mb-3 fw-semibold">Placing an order is simple and quick:</p>
                                                                        <div class="steps">
                                                                            <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                                                                <div class="flex-shrink-0">
                                                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                                        1
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex-grow-1">
                                                                                    <strong>Create an account and log in</strong>
                                                                                    <p class="text-muted small mb-0">Sign up in seconds with your email</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                                                                <div class="flex-shrink-0">
                                                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                                        2
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex-grow-1">
                                                                                    <strong>Add funds to your wallet</strong>
                                                                                    <p class="text-muted small mb-0">Multiple payment options available</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                                                                <div class="flex-shrink-0">
                                                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                                        3
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex-grow-1">
                                                                                    <strong>Go to "New Order" section</strong>
                                                                                    <p class="text-muted small mb-0">Browse available services</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                                                                <div class="flex-shrink-0">
                                                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                                        4
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex-grow-1">
                                                                                    <strong>Select your platform and service</strong>
                                                                                    <p class="text-muted small mb-0">Choose what you need</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                                                                <div class="flex-shrink-0">
                                                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                                        5
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex-grow-1">
                                                                                    <strong>Enter your profile/post link</strong>
                                                                                    <p class="text-muted small mb-0">Just paste the public URL</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                                                                <div class="flex-shrink-0">
                                                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                                        6
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex-grow-1">
                                                                                    <strong>Choose quantity</strong>
                                                                                    <p class="text-muted small mb-0">Select how many you need</p>
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex gap-3">
                                                                                <div class="flex-shrink-0">
                                                                                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                                        <i class="feather-check"></i>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="flex-grow-1">
                                                                                    <strong>Click "Submit Order"</strong>
                                                                                    <p class="text-muted small mb-0">Done! Track your order in real-time</p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#orders3">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        Can I cancel my order?
                                                                    </button>
                                                                </h2>
                                                                <div id="orders3" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <p class="mb-3">
                                                                            Orders can only be cancelled if they are still in <span class="badge bg-warning text-dark">Pending</span> status. 
                                                                            Once an order moves to <span class="badge bg-info">Processing</span>, it cannot be cancelled as it has 
                                                                            already been submitted to our service provider.
                                                                        </p>
                                                                        <div class="alert alert-info border-0">
                                                                            <i class="feather-info me-2"></i>
                                                                            If you need to cancel, please contact our 24/7 support immediately. 
                                                                            Automatic refunds are issued if the provider cancels your order.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#orders4">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        What if my order doesn't complete?
                                                                    </button>
                                                                </h2>
                                                                <div id="orders4" class="accordion-collapse collapse" data-bs-parent="#ordersAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <p class="mb-3">
                                                                            If your order shows as <span class="badge bg-warning text-dark">Partial</span> or doesn't deliver the full quantity, 
                                                                            you have two options:
                                                                        </p>
                                                                        <div class="row g-3 mb-3">
                                                                            <div class="col-md-6">
                                                                                <div class="card bg-primary bg-opacity-10 border-primary h-100">
                                                                                    <div class="card-body">
                                                                                        <div class="d-flex align-items-start gap-2 mb-2">
                                                                                            <i class="feather-refresh-cw text-primary"></i>
                                                                                            <strong style="color: white;">Request a Refill</strong>
                                                                                        </div>
                                                                                        <p class="small mb-0 " style="color: white;">
                                                                                            Use the "Request Refill" button on your order (if available)
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="card bg-success bg-opacity-10 border-success h-100">
                                                                                    <div class="card-body">
                                                                                        <div class="d-flex align-items-start gap-2 mb-2">
                                                                                            <i class="feather-dollar-sign text-success"></i>
                                                                                            <strong style="color: white;">Request a Refund</strong>
                                                                                        </div>
                                                                                        <p class="small mb-0" style="color: white;">
                                                                                            Contact our 24/7 support team with your order details
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <p class="mb-0 text-muted small">
                                                                            We will investigate and either complete your order or issue a partial/full refund 
                                                                            based on what was delivered.
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <!-- Payment & Wallet Tab -->
                                                    <div class="tab-pane fade" id="payment" role="tabpanel">
                                                        <div class="accordion accordion-flush" id="paymentAccordion">
                                                            
                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#payment1">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        What payment methods do you accept?
                                                                    </button>
                                                                </h2>
                                                                <div id="payment1" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <p class="mb-3 fw-semibold">We accept multiple payment methods for your convenience:</p>
                                                                        <div class="row g-3">
                                                                            <div class="col-md-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-send text-primary"></i>
                                                                                    <span>KoraPay</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-zap text-primary"></i>
                                                                                    <span>Paystack</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="d-flex align-items-center gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-zap text-primary"></i>
                                                                                    <span>Flutterwave</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#payment2">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        How does the wallet system work?
                                                                    </button>
                                                                </h2>
                                                                <div id="payment2" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <p class="mb-3">Our wallet system makes ordering quick and easy:</p>
                                                                        <div class="card bg-light border-0 mb-3">
                                                                            <div class="card-body">
                                                                                <div class="d-flex gap-3 mb-3">
                                                                                    <div class="flex-shrink-0">
                                                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                                            <i class="feather-plus"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex-grow-1">
                                                                                        <strong>Deposit funds</strong>
                                                                                        <p class="text-muted small mb-0">Add money to your Booster wallet</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="d-flex gap-3 mb-3">
                                                                                    <div class="flex-shrink-0">
                                                                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                                            <i class="feather-shopping-cart"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex-grow-1">
                                                                                        <strong>Place orders instantly</strong>
                                                                                        <p class="text-muted small mb-0">Use wallet balance for fast checkout</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="d-flex gap-3 mb-3">
                                                                                    <div class="flex-shrink-0">
                                                                                        <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                                            <i class="feather-list"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex-grow-1">
                                                                                        <strong>Track transactions</strong>
                                                                                        <p class="text-muted small mb-0">View complete wallet history</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="d-flex gap-3 mb-3">
                                                                                    <div class="flex-shrink-0">
                                                                                        <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                                            <i class="feather-refresh-cw"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="flex-grow-1">
                                                                                        <strong>Get refunds</strong>
                                                                                        <p class="text-muted small mb-0">Refunds credited back to wallet</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#payment3">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        Is there a minimum deposit amount?
                                                                    </button>
                                                                </h2>
                                                                <div id="payment3" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <div class="alert alert-info border-0">
                                                                            <i class="feather-info me-2"></i>
                                                                            Yes, the minimum deposit amount varies by payment method but typically starts 
                                                                            from <strong>₦100</strong>. The exact amount will be displayed when you select your payment method 
                                                                            on the deposit page.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#payment4">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        Can I get a refund to my bank account?
                                                                    </button>
                                                                </h2>
                                                                <div id="payment4" class="accordion-collapse collapse" data-bs-parent="#paymentAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <p class="mb-3">
                                                                            All refunds are processed to your Booster wallet. You can then:
                                                                        </p>
                                                                        <div class="row g-3">
                                                                            <div class="col-md-6">
                                                                                <div class="card bg-primary bg-opacity-10 border-primary h-100">
                                                                                    <div class="card-body text-center">
                                                                                        <i class="feather-shopping-cart fs-2 text-primary mb-2"></i>
                                                                                        <p class="mb-0"><strong style="color: white;">Use for future orders</strong></p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <!-- Quality & Security Tab -->
                                                    <div class="tab-pane fade" id="quality" role="tabpanel">
                                                        <div class="accordion accordion-flush" id="qualityAccordion">
                                                            
                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#quality1">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        Are the followers/likes real?
                                                                    </button>
                                                                </h2>
                                                                <div id="quality1" class="accordion-collapse collapse" data-bs-parent="#qualityAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <p class="mb-3">We offer different quality tiers depending on your needs:</p>
                                                                        <div class="row g-3 mb-3">
                                                                            <div class="col-12">
                                                                                <div class="card border-success">
                                                                                    <div class="card-body">
                                                                                        <div class="d-flex align-items-start gap-3">
                                                                                            <div class="badge bg-success">Premium</div>
                                                                                            <div>
                                                                                                <strong>Premium Quality</strong>
                                                                                                <p class="text-muted small mb-0">Active accounts that may engage with content</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <div class="card border-primary">
                                                                                    <div class="card-body">
                                                                                        <div class="d-flex align-items-start gap-3">
                                                                                            <div class="badge bg-primary">High</div>
                                                                                            <div>
                                                                                                <strong>High Quality</strong>
                                                                                                <p class="text-muted small mb-0">Real-looking accounts with profile pictures and posts</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <div class="card border-secondary">
                                                                                    <div class="card-body">
                                                                                        <div class="d-flex align-items-start gap-3">
                                                                                            <div class="badge bg-secondary">Standard</div>
                                                                                            <div>
                                                                                                <strong>Standard Quality</strong>
                                                                                                <p class="text-muted small mb-0">Basic accounts for initial boost</p>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="alert alert-info border-0 mb-0">
                                                                            <i class="feather-trending-up me-2"></i>
                                                                            Higher quality services cost more but provide better retention and appearance.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#quality2">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        Will my account get banned?
                                                                    </button>
                                                                </h2>
                                                                <div id="quality2" class="accordion-collapse collapse" data-bs-parent="#qualityAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <div class="alert alert-warning border-0 mb-3">
                                                                            <i class="feather-alert-triangle me-2"></i>
                                                                            While we use safe delivery methods, using any third-party growth service 
                                                                            may violate social media platform terms of service. We cannot guarantee that 
                                                                            your account won't be affected.
                                                                        </div>
                                                                        <p class="mb-2 fw-semibold">To minimize risk:</p>
                                                                        <div class="row g-2">
                                                                            <div class="col-md-6">
                                                                                <div class="d-flex align-items-start gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <small>Choose gradual delivery services</small>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="d-flex align-items-start gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <small>Don't order excessively large quantities</small>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="d-flex align-items-start gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <small>Maintain natural account activity</small>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="d-flex align-items-start gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-success"></i>
                                                                                    <small>Use high-quality services</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#quality3">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        What is a refill guarantee?
                                                                    </button>
                                                                </h2>
                                                                <div id="quality3" class="accordion-collapse collapse" data-bs-parent="#qualityAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <p class="mb-3">
                                                                            Some services come with a refill guarantee (e.g., 30 days, 60 days, or lifetime). 
                                                                            If followers/likes drop during the guarantee period, you can request a free refill.
                                                                        </p>
                                                                        <div class="card bg-success bg-opacity-10 border-success">
                                                                            <div class="card-body">
                                                                                <div class="d-flex align-items-start gap-3">
                                                                                    <i class="feather-shield text-success fs-3"></i>
                                                                                    <div>
                                                                                        <strong class="text-success" style="color: white;">How it works:</strong>
                                                                                        <p class="mb-0 mt-2 small" style="color: white;">
                                                                                            Simply click the "Request Refill" button on your order, and we'll automatically 
                                                                                            replace the dropped quantity at no extra cost during the guarantee period.
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <!-- Support Tab -->
                                                    <div class="tab-pane fade" id="support" role="tabpanel">
                                                        <div class="accordion accordion-flush" id="supportAccordion">
                                                            
                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#support1">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        How do I contact support?
                                                                    </button>
                                                                </h2>
                                                                <div id="support1" class="accordion-collapse collapse" data-bs-parent="#supportAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <div class="alert alert-primary border-0 mb-3">
                                                                            <i class="feather-headphones me-2"></i>
                                                                            <strong>Our support team is available 24/7!</strong>
                                                                        </div>
                                                                        <p class="mb-2 fw-semibold">To contact us:</p>
                                                                        <div class="steps">
                                                                            <div class="d-flex gap-3 mb-2 pb-2 border-bottom">
                                                                                <span class="badge bg-primary">1</span>
                                                                                <span>Log in to your account</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 mb-2 pb-2 border-bottom">
                                                                                <span class="badge bg-primary">2</span>
                                                                                <span>Go to the "Support" section</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3 mb-2 pb-2 border-bottom">
                                                                                <span class="badge bg-primary">3</span>
                                                                                <span>Create a new support ticket</span>
                                                                            </div>
                                                                            <div class="d-flex gap-3">
                                                                                <span class="badge bg-success">✓</span>
                                                                                <span>Our team typically responds within 1-2 hours</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#support2">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        How fast does support respond?
                                                                    </button>
                                                                </h2>
                                                                <div id="support2" class="accordion-collapse collapse" data-bs-parent="#supportAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <div class="row g-3">
                                                                            <div class="col-md-6">
                                                                                <div class="card bg-success bg-opacity-10 border-success h-100">
                                                                                    <div class="card-body text-center">
                                                                                        <i class="feather-zap fs-1 text-success mb-2"></i>
                                                                                        <h5 class="mb-1">Typical Response</h5>
                                                                                        <p class="h4 text-success mb-0">1-2 Hours</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="card bg-warning bg-opacity-10 border-warning h-100">
                                                                                    <div class="card-body text-center">
                                                                                        <i class="feather-clock fs-1 text-warning mb-2"></i>
                                                                                        <h5 class="mb-1">Peak Times</h5>
                                                                                        <p class="h4 text-warning mb-0">Up to 6 Hours</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="alert alert-info border-0 mt-3 mb-0">
                                                                            <i class="feather-alert-circle me-2"></i>
                                                                            Urgent issues are prioritized and handled as quickly as possible.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="accordion-item border rounded mb-3">
                                                                <h2 class="accordion-header">
                                                                    <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#support3">
                                                                        <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                        Can I get a demo or trial?
                                                                    </button>
                                                                </h2>
                                                                <div id="support3" class="accordion-collapse collapse" data-bs-parent="#supportAccordion">
                                                                    <div class="accordion-body pt-0">
                                                                        <p class="mb-3">We don't offer free trials, but you can:</p>
                                                                        <div class="row g-2">
                                                                            <div class="col-md-6">
                                                                                <div class="d-flex align-items-start gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-primary"></i>
                                                                                    <small>Start with small test orders</small>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="d-flex align-items-start gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-primary"></i>
                                                                                    <small>Check our order history</small>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="d-flex align-items-start gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-primary"></i>
                                                                                    <small>Contact 24/7 support for help</small>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-6">
                                                                                <div class="d-flex align-items-start gap-2 p-3 bg-light rounded">
                                                                                    <i class="feather-check text-primary"></i>
                                                                                    <small>Read detailed service descriptions</small>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- Bottom CTA -->
                                        <div class="card bg-gradient-primary text-white border-0 shadow-lg overflow-hidden">
                                            <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
                                                <div class="position-absolute" style="top: -20%; right: -10%; width: 400px; height: 400px; background: white; border-radius: 50%;"></div>
                                            </div>
                                            <div class="card-body text-center p-5 position-relative">
                                                <i class="feather-message-circle fs-1 mb-3 d-block"></i>
                                                <h3 class="text-black fw-bold mb-3">Still Have Questions?</h3>
                                                <p class="mb-4 opacity-90" style="max-width: 500px; margin-left: auto; margin-right: auto; color: black;">
                                                    Don't hesitate to reach out! Our friendly support team is standing by 24/7 
                                                    to answer all your questions and help you get started.
                                                </p>
                                                <div class="d-flex justify-content-center gap-3 flex-wrap">
                                                    @auth
                                                        <a href="{{ route('support.index') }}" class="btn btn-light btn-lg shadow">
                                                            <i class="feather-headphones me-2"></i>
                                                            Contact 24/7 Support
                                                        </a>
                                                        <a href="{{ route('order.create') }}" class="btn btn-outline-light btn-lg">
                                                            <i class="feather-zap me-2"></i>
                                                            Place Your First Order
                                                        </a>
                                                    @else
                                                        <a href="{{ route('register') }}" class="btn btn-light btn-lg shadow">
                                                            <i class="feather-user-plus me-2"></i>
                                                            Create Free Account
                                                        </a>
                                                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                                                            <i class="feather-log-in me-2"></i>
                                                            Login
                                                        </a>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* Custom styles for better accordion animation */
    .accordion-button {
        transition: all 0.3s ease;
    }
    
    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        box-shadow: none;
    }
    
    .accordion-icon {
        transition: transform 0.3s ease;
    }
    
    .accordion-button:not(.collapsed) .accordion-icon {
        transform: rotate(90deg);
    }
    
    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0,0,0,.125);
    }
    
    /* Tab styling */
    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        transition: all 0.3s ease;
    }
    
    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: #0d6efd;
    }
    
    .nav-tabs .nav-link.active {
        border-bottom-color: #0d6efd;
        color: #0d6efd;
        background: transparent;
        font-weight: 600;
    }
    
    /* Smooth gradient backgrounds */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    /* Card hover effects */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .accordion-item:hover {
        box-shadow: 0 0.125rem 0.5rem rgba(0,0,0,0.075);
    }
</style>

@include('components.g-footer')