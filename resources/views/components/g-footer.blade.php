<footer class="footer">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="fs-11 text-muted fw-medium text-uppercase mb-0 copyright">
                    <span>Copyright ©</span>
                    <script>document.write(new Date().getFullYear());</script>
                    <span class="ms-1">Booster. All rights reserved.</span>
                </p>
            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center justify-content-md-end gap-3 mt-3 mt-md-0">
                    <a href="{{ route('faq') }}" class="fs-11 fw-semibold text-uppercase text-muted footer-link">
                        <i class="feather-help-circle me-1" style="font-size: 12px;"></i>
                        FAQ
                    </a>
                    <span class="text-muted">|</span>
                    <a href="{{ route('terms-of-use') }}" class="fs-11 fw-semibold text-uppercase text-muted footer-link">
                        <i class="feather-file-text me-1" style="font-size: 12px;"></i>
                        Terms
                    </a>
                    <span class="text-muted">|</span>
                    <a href="{{ route('refund-policy') }}" class="fs-11 fw-semibold text-uppercase text-muted footer-link">
                        <i class="feather-rotate-ccw me-1" style="font-size: 12px;"></i>
                        Refund Policy
                    </a>
                    @auth
                    <span class="text-muted">|</span>
                    <a href="{{ route('support.index') }}" class="fs-11 fw-semibold text-uppercase text-muted footer-link">
                        <i class="feather-headphones me-1" style="font-size: 12px;"></i>
                        Support
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer {
        padding: 1.5rem 0;
        border-top: 1px solid rgba(0,0,0,0.1);
        background-color: #fff;
        margin-top: auto;
    }
    
    .footer-link {
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }
    
    .footer-link:hover {
        color: var(--bs-primary) !important;
        transform: translateY(-1px);
    }
    
    .footer .copyright {
        line-height: 1.8;
    }
    
    @media (max-width: 767.98px) {
        .footer .row > div:first-child {
            text-align: center;
        }
        
        .footer .d-flex {
            justify-content: center !important;
            flex-wrap: wrap;
        }
    }
</style>


<!-- JS Files -->
    <!--! BEGIN: Vendors JS !-->
    <script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>
    <!-- vendors.min.js {always must need to be top} -->
    <script src="{{ asset('assets/vendors/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/js/circle-progress.min.js') }}"></script>
    <!--! END: Vendors JS !-->
    
    <!--! BEGIN: Apps Init !-->
    <script src="{{ asset('assets/js/common-init.min.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard-init.min.js') }}"></script>
    <!--! END: Apps Init !-->
    
    <!--! BEGIN: Theme Customizer !-->
    <script src="{{ asset('assets/js/theme-customizer-init.min.js') }}"></script>
    <!--! END: Theme Customizer !-->
    
    @stack('scripts')
</body>
</html>