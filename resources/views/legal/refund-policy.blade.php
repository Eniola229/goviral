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
                                    <div class="col-xxl-8 col-xl-10">
                                        <a href="{{ route('welcome') }}" class="btn btn-light btn-sm mb-4 shadow-sm">
                                            <i class="feather-arrow-left me-2"></i>
                                            Back to Home
                                        </a>
                                        <div class="text-center">
                                            <div class="mb-3">
                                                <i class="feather-rotate-ccw" style="font-size: 3rem;"></i>
                                            </div>
                                            <h1 class="display-4 fw-bold text-black mb-3">
                                                Refund Policy
                                            </h1>
                                            <p class="lead text-black mb-1">
                                                Understanding when and how refunds are processed
                                            </p>
                                            <p class="text-black-50 small mb-0">
                                                Last Updated: 20 Jan 2026
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="note-body" style="margin-top: -3rem;">
                            <div class="container-fluid">
                                <div class="row justify-content-center">
                                    <div class="col-xxl-8 col-xl-10">
                                        
                                        <!-- Quick Summary Card -->
                                        <div class="card border-0 shadow-lg mb-4">
                                            <div class="card-body p-4">
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <div class="text-center">
                                                            <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                                                <i class="feather-zap text-success fs-3"></i>
                                                            </div>
                                                            <h6 class="fw-bold mb-1">Instant Refunds</h6>
                                                            <p class="text-muted small mb-0">Automatic refunds processed immediately</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="text-center">
                                                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                                                <i class="feather-clock text-primary fs-3"></i>
                                                            </div>
                                                            <h6 class="fw-bold mb-1">24-48 Hours</h6>
                                                            <p class="text-muted small mb-0">Manual refund processing time</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="text-center">
                                                            <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                                                <i class="feather-headphones text-info fs-3"></i>
                                                            </div>
                                                            <h6 class="fw-bold mb-1">24/7 Support</h6>
                                                            <p class="text-muted small mb-0">Always here to help</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Main Content Card -->
                                        <div class="card border-0 shadow-sm mb-4">
                                            <div class="card-body p-md-5 p-4">
                                                
                                                <!-- Introduction -->
                                                <div class="mb-5">
                                                    <div class="d-flex align-items-start gap-3 mb-4">
                                                        <div class="flex-shrink-0">
                                                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                                                <i class="feather-shield text-primary fs-4"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h4 class="fw-bold mb-2">Our Commitment to You</h4>
                                                            <p class="text-muted mb-0">
                                                                At Booster, we strive to provide the best social media growth services. 
                                                                This refund policy outlines the circumstances under which refunds may be issued. 
                                                                Please read this policy carefully before placing an order.
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="alert alert-info border-0 bg-info bg-opacity-10">
                                                        <div class="d-flex align-items-start gap-2">
                                                            <i class="feather-clock text-info"></i>
                                                            <div>
                                                                <strong class="text-info">24/7 Support Available:</strong>
                                                                <p class="mb-0 text-dark mt-1">Have questions about refunds? Our support team is available around the clock to assist you.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Automatic Refunds -->
                                                <div class="mb-5">
                                                    <div class="border-start border-success border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">
                                                            <i class="feather-check-circle text-success me-2"></i>
                                                            Automatic Refunds
                                                        </h5>
                                                    </div>
                                                    <p class="text-muted mb-4">
                                                        We automatically process refunds in the following situations:
                                                    </p>
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <div class="card bg-success bg-opacity-5 border border-success border-opacity-25 h-100">
                                                                <div class="card-body">
                                                                    <div class="d-flex align-items-start gap-2 mb-3">
                                                                        <i class="feather-x-circle text-success"></i>
                                                                        <h6 class="fw-semibold mb-0">Order Cancellation</h6>
                                                                    </div>
                                                                    <p class="text-muted small mb-0">
                                                                        If our service provider cancels your order for any reason, 
                                                                        the full amount will be automatically refunded to your wallet within minutes.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card bg-success bg-opacity-5 border border-success border-opacity-25 h-100">
                                                                <div class="card-body">
                                                                    <div class="d-flex align-items-start gap-2 mb-3">
                                                                        <i class="feather-alert-triangle text-success"></i>
                                                                        <h6 class="fw-semibold mb-0">Service Failure</h6>
                                                                    </div>
                                                                    <p class="text-muted small mb-0">
                                                                        If our system fails to process your order and deducts funds from your wallet, 
                                                                        an automatic refund will be initiated immediately.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card bg-success bg-opacity-5 border border-success border-opacity-25 h-100">
                                                                <div class="card-body">
                                                                    <div class="d-flex align-items-start gap-2 mb-3">
                                                                        <i class="feather-cpu text-success"></i>
                                                                        <h6 class="fw-semibold mb-0">API Errors</h6>
                                                                    </div>
                                                                    <p class="text-muted small mb-0">
                                                                        In cases where the order cannot be placed due to technical errors, 
                                                                        funds are refunded automatically to your wallet.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Manual Refund Requests -->
                                                <div class="mb-5">
                                                    <div class="border-start border-primary border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">
                                                            <i class="feather-message-circle text-primary me-2"></i>
                                                            Manual Refund Requests
                                                        </h5>
                                                    </div>
                                                    <p class="text-muted mb-4">
                                                        You may request a refund by contacting our 24/7 support team in the following cases:
                                                    </p>
                                                    <div class="accordion" id="manualRefundAccordion">
                                                        
                                                        <div class="accordion-item border rounded mb-3">
                                                            <h2 class="accordion-header">
                                                                <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#incomplete">
                                                                    <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                    Incomplete Order Delivery
                                                                </button>
                                                            </h2>
                                                            <div id="incomplete" class="accordion-collapse collapse show" data-bs-parent="#manualRefundAccordion">
                                                                <div class="accordion-body pt-0">
                                                                    <p class="text-muted mb-0">
                                                                        If your order shows as "Partial" or does not deliver the promised quantity, 
                                                                        we will investigate and issue a partial or full refund based on what was delivered.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="accordion-item border rounded mb-3">
                                                            <h2 class="accordion-header">
                                                                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#quality">
                                                                    <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                    Quality Issues
                                                                </button>
                                                            </h2>
                                                            <div id="quality" class="accordion-collapse collapse" data-bs-parent="#manualRefundAccordion">
                                                                <div class="accordion-body pt-0">
                                                                    <p class="text-muted mb-0">
                                                                        If the delivered followers, likes, or other services are of significantly lower quality 
                                                                        than promised (e.g., fake accounts, bots), please contact support with evidence.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="accordion-item border rounded mb-3">
                                                            <h2 class="accordion-header">
                                                                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#stuck">
                                                                    <i class="feather-chevron-right me-2 accordion-icon"></i>
                                                                    Order Stuck in Processing
                                                                </button>
                                                            </h2>
                                                            <div id="stuck" class="accordion-collapse collapse" data-bs-parent="#manualRefundAccordion">
                                                                <div class="accordion-body pt-0">
                                                                    <p class="text-muted mb-0">
                                                                        If your order has been "Processing" for more than 48 hours without progress, 
                                                                        contact our support team for investigation and possible refund.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <!-- Non-Refundable Situations -->
                                                <div class="mb-5">
                                                    <div class="border-start border-danger border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">
                                                            <i class="feather-x-circle text-danger me-2"></i>
                                                            Non-Refundable Situations
                                                        </h5>
                                                    </div>
                                                    <p class="text-muted mb-4">
                                                        Refunds will NOT be issued in the following cases:
                                                    </p>
                                                    <div class="card border border-danger border-opacity-25 bg-danger bg-opacity-5">
                                                        <div class="card-body">
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-x text-white mt-1"></i>
                                                                        <div style="color: white;">
                                                                            <strong class="d-block mb-1">Completed Orders</strong>
                                                                            <p class=" small mb-0">Orders marked as "Completed" are not eligible for refunds.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-x text-white mt-1"></i>
                                                                        <div style="color: white;">
                                                                            <strong class="d-block mb-1">Change of Mind</strong>
                                                                            <p class=" small mb-0">Once an order is placed and being processed, refunds cannot be issued due to change of mind.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-x text-white mt-1"></i>
                                                                        <div style="color: white;">
                                                                            <strong class="d-block mb-1">Incorrect Link Provided</strong>
                                                                            <p class=" small mb-0">If you provide an incorrect profile link or URL, refunds will not be issued.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-x text-white mt-1"></i>
                                                                        <div style="color: white;">
                                                                            <strong class="d-block mb-1">Account Issues</strong>
                                                                            <p class=" small mb-0">If your social media account is private, deleted, or violates platform terms, resulting in service failure.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-x text-white mt-1"></i>
                                                                        <div style="color: white;">
                                                                            <strong class="d-block mb-1">Natural Drop-offs</strong>
                                                                            <p class=" small mb-0">Social media platforms may remove followers/likes naturally. This is beyond our control and not refundable.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-x text-white mt-1"></i>
                                                                        <div style="color: white;">
                                                                            <strong class="d-block mb-1">Excessive Refund Requests</strong>
                                                                            <p class=" small mb-0">Accounts with a pattern of excessive refund requests may be subject to review and potential suspension.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Refund Process -->
                                                <div class="mb-5">
                                                    <div class="border-start border-info border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">
                                                            <i class="feather-refresh-cw text-info me-2"></i>
                                                            Refund Process & Timeline
                                                        </h5>
                                                    </div>
                                                    <div class="row g-3 mb-4">
                                                        <div class="col-md-6">
                                                            <div class="card bg-success bg-opacity-10 border border-success border-opacity-25 h-100">
                                                                <div class="card-body text-center p-4">
                                                                    <i class="feather-zap text-success fs-1 mb-3"></i>
                                                                    <h6 class="fw-bold mb-2">Automatic Refunds</h6>
                                                                    <p class="text-muted mb-3">Processed instantly to your wallet</p>
                                                                    <span class="badge bg-success px-3 py-2">Immediate</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card bg-warning bg-opacity-10 border border-warning border-opacity-25 h-100">
                                                                <div class="card-body text-center p-4">
                                                                    <i class="feather-clock text-warning fs-1 mb-3"></i>
                                                                    <h6 class="fw-bold mb-2">Manual Refunds</h6>
                                                                    <p class="text-muted mb-3">After support team investigation</p>
                                                                    <span class="badge bg-warning px-3 py-2">24-48 Hours</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="alert alert-info border-0 bg-info bg-opacity-10 mb-0">
                                                        <div class="d-flex align-items-start gap-2">
                                                            <i class="feather-info text-info"></i>
                                                            <div>
                                                                <strong class="text-info">Important:</strong>
                                                                <p class="mb-0 text-dark mt-1">All refunds are credited to your Booster wallet and can be used for future orders. Wallet funds cannot be withdrawn to bank accounts.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- How to Request Refund -->
                                                <div class="mb-5">
                                                    <div class="border-start border-primary border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">
                                                            <i class="feather-life-buoy text-primary me-2"></i>
                                                            How to Request a Refund
                                                        </h5>
                                                    </div>
                                                    <div class="card bg-light border-0">
                                                        <div class="card-body p-4">
                                                            <div class="steps">
                                                                <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                                                    <div class="flex-shrink-0">
                                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                            1
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <strong>Log in to your Booster account</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                                                    <div class="flex-shrink-0">
                                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                            2
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <strong>Navigate to Support section</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                                                    <div class="flex-shrink-0">
                                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                            3
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <strong>Create a new support ticket</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                                                    <div class="flex-shrink-0">
                                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                            4
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <strong>Select category: "Refund Request"</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                                                    <div class="flex-shrink-0">
                                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                            5
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <strong>Provide your order ID and reason for refund</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                                                                    <div class="flex-shrink-0">
                                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                            6
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <strong>Include any relevant screenshots or evidence</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex gap-3">
                                                                    <div class="flex-shrink-0">
                                                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                                            <i class="feather-check"></i>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <strong>Our 24/7 support team will review and respond within 24 hours</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Refill Policy -->
                                                <div class="mb-5">
                                                    <div class="border-start border-success border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">
                                                            <i class="feather-repeat text-success me-2"></i>
                                                            Refill Policy
                                                        </h5>
                                                    </div>
                                                    <p class="text-muted mb-3">
                                                        Some services come with a refill guarantee. If followers/likes drop within the guarantee period:
                                                    </p>
                                                    <div class="card bg-success bg-opacity-10 border-0">
                                                        <div class="card-body">
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-check text-success mt-1"></i>
                                                                        <span>Use the "Request Refill" button on your order</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-check text-success mt-1"></i>
                                                                        <span>We will automatically refill the dropped quantity</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-check text-success mt-1"></i>
                                                                        <span>Refills are free and unlimited during the guarantee period</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-check text-success mt-1"></i>
                                                                        <span>Check individual service descriptions for refill guarantee duration</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Footer Note -->
                                                <div class="pt-4 border-top">
                                                    <div class="alert alert-light border mb-0">
                                                        <div class="d-flex align-items-start gap-2">
                                                            <i class="feather-info text-primary"></i>
                                                            <p class="mb-0 small">
                                                                This refund policy may be updated from time to time. Continued use of our services 
                                                                constitutes acceptance of any changes. 
                                                                <strong>Last updated: 20 Jan 2026</strong>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <!-- Contact Support CTA -->
                                        <div class="card bg-gradient-primary text-white border-0 shadow-lg overflow-hidden">
                                            <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
                                                <div class="position-absolute" style="top: -20%; right: -10%; width: 400px; height: 400px; background: white; border-radius: 50%;"></div>
                                            </div>
                                            <div class="card-body text-center p-5 position-relative">
                                                <i class="feather-headphones fs-1 mb-3 d-block"></i>
                                                <h4 class="text-black fw-bold mb-3">24/7 Customer Support</h4>
                                                <p class="mb-4 opacity-90" style="max-width: 500px; margin-left: auto; margin-right: auto; color: black;">
                                                    Our support team is always available to help you with refund requests, 
                                                    order issues, or any questions you may have.
                                                </p>
                                                <div class="d-flex justify-content-center gap-3 flex-wrap">
                                                    @auth
                                                        <a href="{{ route('support.index') }}" class="btn btn-light btn-lg shadow">
                                                            <i class="feather-message-circle me-2"></i>
                                                            Contact Support
                                                        </a>
                                                    @else
                                                        <a href="{{ route('login') }}" class="btn btn-light btn-lg shadow">
                                                            <i class="feather-log-in me-2"></i>
                                                            Login to Contact Support
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
    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }
    
    /* Gradient background */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    /* Accordion styling */
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
    
    /* Card hover effects */
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
</style>

@include('components.g-footer')