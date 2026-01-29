@include('components.g-header')
<main class="auth-minimal-wrapper">
    <div class="auth-minimal-inner">
        <div class="minimal-card-wrapper">
            <div class="card mb-4 mt-5 mx-4 mx-sm-0 position-relative">
                <div class="wd-50 bg-white p-2 rounded-circle shadow-lg position-absolute translate-middle top-0 start-50">
                    <img src="{{ asset('assets/images/B.png') }}" alt="" class="img-fluid">
                </div>
                <div class="card-body p-sm-5">
                    <h2 class="fs-20 fw-bolder mb-4">Reset Password</h2>
                    <h4 class="fs-13 fw-bold mb-2">You requested a password reset</h4>
                    
                    <div class="mt-4 pt-2">
                        <p class="mb-3">Hello,</p>
                        
                        <p class="mb-3">You are receiving this email because we received a password reset request for your account.</p>
                        
                        <div class="mt-4">
                            <a href="{{ $url }}" class="btn btn-lg btn-primary w-100">
                                Reset Password
                            </a>
                        </div>
                        
                        <div class="alert alert-light border mt-4">
                            <p class="fs-12 mb-2"><strong>This password reset link will expire in {{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes.</strong></p>
                        </div>
                        
                        <div class="mt-4 text-muted">
                            <p class="fs-12 mb-2">If you did not request a password reset, no further action is required.</p>
                            <p class="fs-11 mb-0">If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>
                            <p class="fs-11 text-break"><a href="{{ $url }}">{{ $url }}</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@include('components.g-footer')