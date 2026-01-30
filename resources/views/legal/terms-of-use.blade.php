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
                                                <i class="feather-file-text" style="font-size: 3rem;"></i>
                                            </div>
                                            <h1 class="display-4 fw-bold text-black mb-3">
                                                Terms of Use
                                            </h1>
                                            <p class="lead text-black mb-1">
                                                Please read these terms carefully before using our services
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
                                        
                                        <!-- Quick Navigation -->
                                        <div class="card border-0 shadow-lg mb-4">
                                            <div class="card-body p-4">
                                                <h6 class="fw-bold mb-3">Quick Navigation</h6>
                                                <div class="row g-2">
                                                    <div class="col-md-4 col-sm-6">
                                                        <a href="#section-1" class="btn btn-outline-primary btn-sm w-100 text-start">
                                                            <i class="feather-user me-2"></i>Account Registration
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6">
                                                        <a href="#section-2" class="btn btn-outline-primary btn-sm w-100 text-start">
                                                            <i class="feather-package me-2"></i>Services Description
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6">
                                                        <a href="#section-3" class="btn btn-outline-primary btn-sm w-100 text-start">
                                                            <i class="feather-credit-card me-2"></i>Payment & Billing
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6">
                                                        <a href="#section-4" class="btn btn-outline-primary btn-sm w-100 text-start">
                                                            <i class="feather-check-circle me-2"></i>User Responsibilities
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6">
                                                        <a href="#section-5" class="btn btn-outline-primary btn-sm w-100 text-start">
                                                            <i class="feather-alert-triangle me-2"></i>Prohibited Activities
                                                        </a>
                                                    </div>
                                                    <div class="col-md-4 col-sm-6">
                                                        <a href="#section-6" class="btn btn-outline-primary btn-sm w-100 text-start">
                                                            <i class="feather-rotate-ccw me-2"></i>Refunds & Cancellations
                                                        </a>
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
                                                                <i class="feather-file-text text-primary fs-4"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h4 class="fw-bold mb-2">Agreement to Terms</h4>
                                                            <p class="text-muted mb-0">
                                                                Welcome to Booster! By accessing and using our social media growth services, 
                                                                you agree to be bound by these Terms of Use. Please read them carefully.
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="alert alert-info border-0 bg-info bg-opacity-10">
                                                        <div class="d-flex align-items-start gap-2">
                                                            <i class="feather-clock text-info"></i>
                                                            <div>
                                                                <strong class="text-info">24/7 Support Available:</strong>
                                                                <p class="mb-0 text-dark mt-1">Questions about our terms? Our support team is available around the clock to help clarify any points.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Account Registration -->
                                                <div class="mb-5" id="section-1">
                                                    <div class="border-start border-primary border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">1. Account Registration</h5>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="card bg-light border-0 h-100">
                                                                <div class="card-body">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-user text-primary mt-1"></i>
                                                                        <div>
                                                                            <strong class="d-block mb-2">1.1 Eligibility</strong>
                                                                            <p class="text-muted small mb-0">You must be at least 18 years old to use our services.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card bg-light border-0 h-100">
                                                                <div class="card-body">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-lock text-primary mt-1"></i>
                                                                        <div>
                                                                            <strong class="d-block mb-2">1.2 Account Security</strong>
                                                                            <p class="text-muted small mb-0">You are responsible for maintaining the confidentiality of your account credentials.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card bg-light border-0 h-100">
                                                                <div class="card-body">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-info text-primary mt-1"></i>
                                                                        <div>
                                                                            <strong class="d-block mb-2">1.3 Accurate Information</strong>
                                                                            <p class="text-muted small mb-0">You must provide accurate and complete information during registration.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card bg-light border-0 h-100">
                                                                <div class="card-body">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-users text-primary mt-1"></i>
                                                                        <div>
                                                                            <strong class="d-block mb-2">1.4 One Account Per User</strong>
                                                                            <p class="text-muted small mb-0">Each user is permitted one account. Multiple accounts may be suspended.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Services Description -->
                                                <div class="mb-5" id="section-2">
                                                    <div class="border-start border-success border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">2. Services Description</h5>
                                                    </div>
                                                    <div class="card border border-success border-opacity-25 bg-success bg-opacity-5 mb-3">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-start gap-3">
                                                                <i class="feather-check-circle text-success fs-4"></i>
                                                                <div>
                                                                    <strong class="d-block mb-2">2.1 What We Offer</strong>
                                                                    <p class="text-muted mb-0">Booster provides social media growth services including followers, likes, views, comments, and other engagement metrics across various platforms.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <div class="card bg-light border-0 h-100">
                                                                <div class="card-body">
                                                                    <strong class="d-block mb-2">2.2 No Guarantees</strong>
                                                                    <p class="text-muted small mb-0">While we strive for quality, we cannot guarantee specific results or permanent retention of delivered services.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card bg-light border-0 h-100">
                                                                <div class="card-body">
                                                                    <strong class="d-block mb-2">2.3 Platform Changes</strong>
                                                                    <p class="text-muted small mb-0">Services may be affected by changes to social media platform policies or algorithms.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card bg-light border-0 h-100">
                                                                <div class="card-body">
                                                                    <strong class="d-block mb-2">2.4 Service Availability</strong>
                                                                    <p class="text-muted small mb-0">We reserve the right to modify, suspend, or discontinue any service at any time.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Payment and Billing -->
                                                <div class="mb-5" id="section-3">
                                                    <div class="border-start border-warning border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">3. Payment and Billing</h5>
                                                    </div>
                                                    <div class="alert alert-warning border-0 bg-warning bg-opacity-10 mb-3">
                                                        <div class="d-flex align-items-start gap-2">
                                                            <i class="feather-alert-circle text-warning"></i>
                                                            <div>
                                                                <strong class="text-warning">Important:</strong>
                                                                <p class="mb-0 text-dark mt-1">Wallet deposits are non-refundable and can only be used to place orders on our platform. There is no withdrawal option to bank accounts.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="card bg-light border-0">
                                                                <div class="card-body">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-wallet text-warning mt-1"></i>
                                                                        <div>
                                                                            <strong class="d-block mb-2">3.1 Wallet System</strong>
                                                                            <p class="text-muted small mb-0">All purchases are made using credits in your Booster wallet.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card bg-light border-0">
                                                                <div class="card-body">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-dollar-sign text-warning mt-1"></i>
                                                                        <div>
                                                                            <strong class="d-block mb-2">3.2 Deposits</strong>
                                                                            <p class="text-muted small mb-0">Wallet deposits are final and can only be used for placing orders on Booster.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card bg-light border-0">
                                                                <div class="card-body">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-tag text-warning mt-1"></i>
                                                                        <div>
                                                                            <strong class="d-block mb-2">3.3 Pricing</strong>
                                                                            <p class="text-muted small mb-0">All prices are displayed in Nigerian Naira (₦) and are subject to change without notice.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card bg-light border-0">
                                                                <div class="card-body">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-zap text-warning mt-1"></i>
                                                                        <div>
                                                                            <strong class="d-block mb-2">3.4 Order Deduction</strong>
                                                                            <p class="text-muted small mb-0">Funds are deducted from your wallet immediately when an order is placed.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="card bg-danger bg-opacity-10 border border-danger border-opacity-25">
                                                                <div class="card-body">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-x-circle text-danger mt-1"></i>
                                                                        <div>
                                                                            <strong class="text-danger d-block mb-2">3.5 No Chargebacks</strong>
                                                                            <p class="text-dark small mb-0">Initiating chargebacks may result in account suspension and legal action.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- User Responsibilities -->
                                                <div class="mb-5" id="section-4">
                                                    <div class="border-start border-info border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">4. User Responsibilities</h5>
                                                    </div>
                                                    <div class="list-group list-group-flush">
                                                        <div class="list-group-item px-0">
                                                            <div class="d-flex gap-3">
                                                                <div class="badge bg-info text-white" style="height: fit-content;">4.1</div>
                                                                <div>
                                                                    <strong class="d-block mb-1">Accurate Information</strong>
                                                                    <p class="text-muted mb-0 small">You must provide correct social media profile links when placing orders.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item px-0">
                                                            <div class="d-flex gap-3">
                                                                <div class="badge bg-info text-white" style="height: fit-content;">4.2</div>
                                                                <div>
                                                                    <strong class="d-block mb-1">Account Access</strong>
                                                                    <p class="text-muted mb-0 small">Ensure your social media accounts are public and accessible during service delivery.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item px-0">
                                                            <div class="d-flex gap-3">
                                                                <div class="badge bg-info text-white" style="height: fit-content;">4.3</div>
                                                                <div>
                                                                    <strong class="d-block mb-1">Compliance</strong>
                                                                    <p class="text-muted mb-0 small">You are responsible for ensuring your use of our services complies with social media platform terms.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item px-0">
                                                            <div class="d-flex gap-3">
                                                                <div class="badge bg-info text-white" style="height: fit-content;">4.4</div>
                                                                <div>
                                                                    <strong class="d-block mb-1">No Illegal Use</strong>
                                                                    <p class="text-muted mb-0 small">Our services must not be used for illegal purposes or to violate third-party rights.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-group-item px-0 border-bottom">
                                                            <div class="d-flex gap-3">
                                                                <div class="badge bg-info text-white" style="height: fit-content;">4.5</div>
                                                                <div>
                                                                    <strong class="d-block mb-1">Account Risk</strong>
                                                                    <p class="text-muted mb-0 small">You acknowledge that using social media growth services may violate platform policies and could result in account restrictions.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Prohibited Activities -->
                                                <div class="mb-5" id="section-5">
                                                    <div class="border-start border-danger border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">5. Prohibited Activities</h5>
                                                    </div>
                                                    <div class="card border border-danger border-opacity-25 bg-danger bg-opacity-5">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-start gap-3 mb-3">
                                                                <i class="feather-alert-octagon text-danger fs-3"></i>
                                                                <div>
                                                                    <h6 class="text-white fw-bold mb-2">You may NOT:</h6>
                                                                </div>
                                                            </div>
                                                            <div class="row g-2">
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-x text-white small mt-1"></i>
                                                                        <span class="small" style="color: white;">Resell our services without authorization</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-x text-white small mt-1"></i>
                                                                        <span class="small" style="color: white;">Use automated systems or bots to access our platform</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-x text-white small mt-1"></i>
                                                                        <span class="small" style="color: white;">Attempt to hack, reverse engineer, or exploit our systems</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-x text-white small mt-1"></i>
                                                                        <span class="small" style="color: white;">Create multiple accounts to abuse promotions or refunds</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-x text-white small mt-1"></i>
                                                                        <span class="small" style="color: white;">Share your account credentials with others</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-x text-white small mt-1"></i>
                                                                        <span class="small" style="color: white;">Place orders for competitors' accounts without permission</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-x text-white small mt-1"></i>
                                                                        <span class="small" style="color: white;">Engage in fraudulent payment activities</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Refunds and Cancellations -->
                                                <div class="mb-5" id="section-6">
                                                    <div class="border-start border-primary border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">6. Refunds and Cancellations</h5>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <div class="card bg-light border-0 h-100">
                                                                <div class="card-body text-center">
                                                                    <i class="feather-rotate-ccw text-primary fs-2 mb-3"></i>
                                                                    <strong class="d-block mb-2">6.1 Refund Policy</strong>
                                                                    <p class="text-muted small mb-0">Refunds are subject to our Refund Policy. Please review it carefully before placing orders.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card bg-light border-0 h-100">
                                                                <div class="card-body text-center">
                                                                    <i class="feather-x-circle text-warning fs-2 mb-3"></i>
                                                                    <strong class="d-block mb-2">6.2 Order Cancellation</strong>
                                                                    <p class="text-muted small mb-0">Orders cannot be cancelled once they enter "Processing" status.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card bg-light border-0 h-100">
                                                                <div class="card-body text-center">
                                                                    <i class="feather-refresh-cw text-success fs-2 mb-3"></i>
                                                                    <strong class="d-block mb-2">6.3 Automatic Refunds</strong>
                                                                    <p class="text-muted small mb-0">System failures or provider cancellations trigger automatic refunds to your wallet.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Intellectual Property -->
                                                <div class="mb-5">
                                                    <div class="border-start border-secondary border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">7. Intellectual Property</h5>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="card bg-light border-0">
                                                                <div class="card-body">
                                                                    <strong class="d-block mb-2">7.1 Our Content</strong>
                                                                    <p class="text-muted small mb-0">All content on Booster, including logos, text, graphics, and software, is our property.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card bg-light border-0">
                                                                <div class="card-body">
                                                                    <strong class="d-block mb-2">7.2 Usage Restrictions</strong>
                                                                    <p class="text-muted small mb-0">You may not copy, reproduce, or distribute our content without written permission.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Disclaimer of Warranties -->
                                                <div class="mb-5">
                                                    <div class="border-start border-warning border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">8. Disclaimer of Warranties</h5>
                                                    </div>
                                                    <div class="card bg-warning bg-opacity-10 border-0">
                                                        <div class="card-body">
                                                            <div class="row g-3">
                                                                <div class="col-md-4">
                                                                    <div class="text-center">
                                                                        <i class="feather-info text-warning fs-3 mb-2"></i>
                                                                        <strong class="d-block mb-1 small">8.1 "As Is" Services</strong>
                                                                        <p class="text-muted small mb-0">Our services are provided "as is" without warranties of any kind.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="text-center">
                                                                        <i class="feather-alert-triangle text-warning fs-3 mb-2"></i>
                                                                        <strong class="d-block mb-1 small">8.2 No Guarantees</strong>
                                                                        <p class="text-muted small mb-0">We do not guarantee uninterrupted, error-free, or secure service.</p>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="text-center">
                                                                        <i class="feather-link text-warning fs-3 mb-2"></i>
                                                                        <strong class="d-block mb-1 small">8.3 Third-Party Services</strong>
                                                                        <p class="text-muted small mb-0">We are not responsible for failures caused by third-party platforms or providers.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Limitation of Liability -->
                                                <div class="mb-5">
                                                    <div class="border-start border-danger border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">9. Limitation of Liability</h5>
                                                    </div>
                                                    <div class="list-group list-group-flush">
                                                        <div class="list-group-item px-0 bg-transparent">
                                                            <strong>9.1 Maximum Liability:</strong>
                                                            <p class="text-muted mb-0 small">Our liability is limited to the amount you paid for the specific service in question.</p>
                                                        </div>
                                                        <div class="list-group-item px-0 bg-transparent">
                                                            <strong>9.2 No Indirect Damages:</strong>
                                                            <p class="text-muted mb-0 small">We are not liable for indirect, incidental, or consequential damages.</p>
                                                        </div>
                                                        <div class="list-group-item px-0 bg-transparent border-bottom">
                                                            <strong>9.3 Account Suspension:</strong>
                                                            <p class="text-muted mb-0 small">We are not liable for consequences resulting from social media platform account suspensions.</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Account Termination -->
                                                <div class="mb-5">
                                                    <div class="border-start border-dark border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">10. Account Termination</h5>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <div class="card border border-dark border-opacity-25 h-100">
                                                                <div class="card-body text-center">
                                                                    <i class="feather-slash fs-2 mb-3" style="color: #212529;"></i>
                                                                    <strong class="d-block mb-2">10.1 Our Right</strong>
                                                                    <p class="text-muted small mb-0">We reserve the right to suspend or terminate accounts that violate these terms.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card border border-dark border-opacity-25 h-100">
                                                                <div class="card-body text-center">
                                                                    <i class="feather-x-circle fs-2 mb-3" style="color: #212529;"></i>
                                                                    <strong class="d-block mb-2">10.2 No Refund on Termination</strong>
                                                                    <p class="text-muted small mb-0">Terminated accounts forfeit remaining wallet balance except in cases of our error.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="card border border-dark border-opacity-25 h-100">
                                                                <div class="card-body text-center">
                                                                    <i class="feather-log-out fs-2 mb-3" style="color: #212529;"></i>
                                                                    <strong class="d-block mb-2">10.3 User Termination</strong>
                                                                    <p class="text-muted small mb-0">You may close your account at any time by contacting support.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Privacy -->
                                                <div class="mb-5">
                                                    <div class="border-start border-success border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">11. Privacy and Data Protection</h5>
                                                    </div>
                                                    <div class="card bg-success bg-opacity-10 border-0">
                                                        <div class="card-body">
                                                            <div class="row g-3">
                                                                <div class="col-md-4">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-database text-success mt-1"></i>
                                                                        <div>
                                                                            <strong class="d-block mb-1 small">11.1 Data Collection</strong>
                                                                            <p class="text-muted small mb-0">We collect and process personal data as described in our Privacy Policy.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-shield text-success mt-1"></i>
                                                                        <div>
                                                                            <strong class="d-block mb-1 small">11.2 Security</strong>
                                                                            <p class="text-muted small mb-0">We implement reasonable security measures to protect your data.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="d-flex align-items-start gap-2">
                                                                        <i class="feather-lock text-success mt-1"></i>
                                                                        <div>
                                                                            <strong class="d-block mb-1 small">11.3 No Sale</strong>
                                                                            <p class="text-muted small mb-0">We do not sell your personal information to third parties.</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Changes to Terms -->
                                                <div class="mb-5">
                                                    <div class="border-start border-info border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">12. Changes to Terms</h5>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="card bg-info bg-opacity-10 border-0">
                                                                <div class="card-body">
                                                                    <strong class="d-block mb-2">12.1 Modification Rights</strong>
                                                                    <p class="text-muted small mb-0">We may update these terms at any time without prior notice.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card bg-info bg-opacity-10 border-0">
                                                                <div class="card-body">
                                                                    <strong class="d-block mb-2">12.2 Continued Use</strong>
                                                                    <p class="text-muted small mb-0">Continued use of our services after changes constitutes acceptance of new terms.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Governing Law -->
                                                <div class="mb-5">
                                                    <div class="border-start border-primary border-4 ps-3 mb-4">
                                                        <h5 class="fw-bold mb-1">13. Governing Law</h5>
                                                    </div>
                                                    <div class="alert alert-primary border-0 bg-primary bg-opacity-10">
                                                        <div class="d-flex align-items-start gap-3">
                                                            <i class="feather-map-pin text-primary fs-4"></i>
                                                            <p class="mb-0 text-dark">These Terms of Use are governed by the laws of the <strong>Federal Republic of Nigeria</strong>.</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Footer Note -->
                                                <div class="pt-4 border-top">
                                                    <div class="alert alert-light border mb-0">
                                                        <div class="d-flex align-items-start gap-2">
                                                            <i class="feather-info text-primary"></i>
                                                            <p class="mb-0 small">
                                                                By using Booster services, you acknowledge that you have read, understood, 
                                                                and agree to be bound by these Terms of Use. 
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
                                                <h4 class="text-black fw-bold mb-3">Questions About Our Terms?</h4>
                                                <p class="mb-4 opacity-90" style="max-width: 500px; margin-left: auto; margin-right: auto;">
                                                    Our 24/7 support team is always available to answer your questions 
                                                    and provide clarification on any aspect of our Terms of Use.
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
    
    /* Card hover effects */
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    /* Quick nav buttons */
    .btn-outline-primary:hover {
        transform: translateY(-2px);
    }
    
    /* List group styling */
    .list-group-item {
        transition: background-color 0.2s ease;
    }
    
    .list-group-item:hover {
        background-color: rgba(0,0,0,0.02);
    }
</style>

@include('components.g-footer')