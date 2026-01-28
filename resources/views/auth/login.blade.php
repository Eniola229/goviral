@include('components.g-header')

<main class="auth-minimal-wrapper">
    <div class="auth-minimal-inner">
        <div class="minimal-card-wrapper">
            <div class="card mb-4 mt-5 mx-4 mx-sm-0 position-relative">
                <div class="wd-50 bg-white p-2 rounded-circle shadow-lg position-absolute translate-middle top-0 start-50">
                    <img src="{{ asset('assets/images/logo-abbr.png') }}" alt="" class="img-fluid">
                </div>
                <div class="card-body p-sm-5">
                    <h2 class="fs-20 fw-bolder mb-4">Login</h2>
                    <h4 class="fs-13 fw-bold mb-2">Login to your account</h4>
                    
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    
                    <form method="POST" action="{{ route('login') }}" class="w-100 mt-4 pt-2">
                        @csrf
                        
                        <!-- Email Address -->
                        <div class="mb-4">
                            <input id="email" type="email" class="form-control" name="email" placeholder="Email or Username" :value="old('email')" required autofocus autocomplete="username">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-3">
                            <input id="password" type="password" class="form-control" name="password" placeholder="Password" required autocomplete="current-password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <!-- Remember Me -->
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="remember_me" name="remember">
                                    <label class="custom-control-label c-pointer" for="remember_me">{{ __('Remember me') }}</label>
                                </div>
                            </div>
                            <div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="fs-11 text-primary">{{ __('Forgot your password?') }}</a>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-5">
                            <button type="submit" class="btn btn-lg btn-primary w-100">{{ __('Log in') }}</button>
                        </div>
                    </form>
                    
                    <div class="mt-5 text-muted">
                        <span> Don't have an account?</span>
                        <a href="{{ route('register') }}" class="fw-bold">Create an Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('components.g-footer')