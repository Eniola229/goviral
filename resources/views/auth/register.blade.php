@include('components.g-header')

<main class="auth-minimal-wrapper">
    <div class="auth-minimal-inner">
        <div class="minimal-card-wrapper">
            <div class="card mb-4 mt-5 mx-4 mx-sm-0 position-relative">
                <div class="wd-50 bg-white p-2 rounded-circle shadow-lg position-absolute translate-middle top-0 start-50">
                    <img src="{{ asset('assets/images/B.png') }}" alt="" class="img-fluid">
                </div>
                <div class="card-body p-sm-5">
                    <h2 class="fs-20 fw-bolder mb-4">Register</h2>
                    <h4 class="fs-13 fw-bold mb-2">Create your account</h4>
                    <p class="fs-12 fw-medium text-muted">Thank you for joining <strong>Viral</strong>let's create your account to get started.</p>
                    
                    <form method="POST" action="{{ route('register') }}" class="w-100 mt-4 pt-2">
                        @csrf
                        
                        <!-- Name -->
                        <div class="mb-4">
                            <input id="name" type="text" class="form-control" name="name" placeholder="Full Name" :value="old('name')" required autofocus autocomplete="name">
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        
                        <!-- Email Address -->
                        <div class="mb-4">
                            <input id="email" type="email" class="form-control" name="email" placeholder="Email Address" :value="old('email')" required autocomplete="username">
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        
                        <!-- Password -->
                        <div class="mb-4">
                            <input id="password" type="password" class="form-control" name="password" placeholder="Password" required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        
                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>
                        
                        <div class="mb-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="termsConditions" required>
                                <label class="custom-control-label c-pointer" for="termsConditions">I agree to the <a href="javascript:void(0);" class="text-primary">Terms & Conditions</a></label>
                            </div>
                        </div>
                        
                        <div class="mt-5">
                            <button type="submit" class="btn btn-lg btn-primary w-100">{{ __('Register') }}</button>
                        </div>
                    </form>
                    
                    <div class="mt-5 text-muted">
                        <span> {{ __('Already registered?') }}</span>
                        <a href="{{ route('login') }}" class="fw-bold">{{ __('Log in') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include('components.g-footer')