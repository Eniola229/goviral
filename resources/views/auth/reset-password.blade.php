@include('components.g-header')

<main class="auth-minimal-wrapper">
    <div class="auth-minimal-inner">
        <div class="minimal-card-wrapper">
            <div class="card mb-4 mt-5 mx-4 mx-sm-0 position-relative">
                <div class="wd-50 bg-white p-2 rounded-circle shadow-lg position-absolute translate-middle top-0 start-50">
                    <img src="{{ asset('assets/images/logo-abbr.png') }}" alt="" class="img-fluid">
                </div>
                <div class="card-body p-sm-5">
                    <h2 class="fs-20 fw-bolder mb-4">Reset Password</h2>
                    <h4 class="fs-13 fw-bold mb-2">Create your new password</h4>
                    <p class="fs-12 fw-medium text-muted">Please enter your email address and choose a new password for your account.</p>
                    
                    <form method="POST" action="{{ route('password.store') }}" class="w-100 mt-4 pt-2">
                        @csrf
                        
                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        
                        <!-- Email Address -->
                        <div class="mb-4">
                            <input id="email" type="email" class="form-control" name="email" placeholder="Email Address" :value="old('email', $request->email)" required autofocus autocomplete="username">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-4">
                            <input id="password" type="password" class="form-control" name="password" placeholder="New Password" required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="Confirm New Password" required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                        
                        <div class="mt-5">
                            <button type="submit" class="btn btn-lg btn-primary w-100">{{ __('Reset Password') }}</button>
                        </div>
                    </form>
                    
                    <div class="mt-5 text-muted text-center">
                        <span> Remember your password?</span>
                        <a href="{{ route('login') }}" class="fw-bold">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('components.g-footer')