<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Premium Social Media Growth Services</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!--! BEGIN: Favicon-->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/b.png') }}" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #6366F1;
            --primary-dark: #4F46E5;
            --secondary: #EC4899;
            --accent: #F59E0B;
            --dark: #0F172A;
            --dark-lighter: #1E293B;
            --gray: #64748B;
            --light: #F1F5F9;
            --white: #FFFFFF;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--dark);
            color: var(--white);
            overflow-x: hidden;
        }

        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
            font-weight: 900;
            line-height: 1.1;
        }

        /* Animated Background */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, var(--dark) 0%, #1a1f35 50%, var(--dark) 100%);
        }

        .gradient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: float 20s infinite ease-in-out;
        }

        .orb-1 {
            width: 600px;
            height: 600px;
            background: var(--primary);
            top: -200px;
            right: -200px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 500px;
            height: 500px;
            background: var(--secondary);
            bottom: -150px;
            left: -150px;
            animation-delay: 7s;
        }

        .orb-3 {
            width: 400px;
            height: 400px;
            background: var(--accent);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: 14s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(50px, -50px) scale(1.1); }
            66% { transform: translate(-50px, 50px) scale(0.9); }
        }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            animation: slideDown 0.8s ease-out;
        }

        @keyframes slideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .logo {
            font-size: 1.8rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 2rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: var(--white);
            border: 2px solid var(--white);
        }

        .btn-outline:hover {
            background: var(--white);
            color: var(--dark);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8rem 2rem 4rem;
            text-align: center;
            position: relative;
        }

        .hero-content {
            max-width: 900px;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero h1 {
            font-size: clamp(3rem, 8vw, 5.5rem);
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--white), var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        .hero p {
            font-size: 1.3rem;
            color: var(--gray);
            margin-bottom: 2.5rem;
            line-height: 1.6;
            animation: fadeInUp 1s ease-out 0.4s both;
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 5rem;
            animation: fadeInUp 1s ease-out 0.8s both;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--primary);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--gray);
            font-size: 1rem;
        }

        /* Features Section */
        .features {
            padding: 6rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-header h2 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--white), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .section-header p {
            font-size: 1.2rem;
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.03);
            padding: 2.5rem;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.4s;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0;
            transition: opacity 0.4s;
            z-index: -1;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: var(--primary);
        }

        .feature-card:hover::before {
            opacity: 0.1;
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-family: 'DM Sans', sans-serif;
            font-weight: 700;
        }

        .feature-card p {
            color: var(--gray);
            line-height: 1.6;
        }

        /* Social Proof Section */
        .social-proof {
            padding: 6rem 2rem;
            background: rgba(255, 255, 255, 0.02);
        }

        .platforms {
            display: flex;
            justify-content: center;
            gap: 3rem;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 0 auto;
        }

        .platform-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s;
        }

        .platform-item:hover {
            transform: scale(1.1);
        }

        .platform-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .platform-name {
            font-weight: 600;
            color: var(--gray);
        }

        /* CTA Section */
        .cta {
            padding: 8rem 2rem;
            text-align: center;
        }

        .cta-content {
            max-width: 800px;
            margin: 0 auto;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1));
            padding: 4rem;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .cta h2 {
            font-size: clamp(2rem, 4vw, 3.5rem);
            margin-bottom: 1.5rem;
        }

        .cta p {
            font-size: 1.2rem;
            color: var(--gray);
            margin-bottom: 2.5rem;
        }

        /* Footer */
        footer {
            padding: 3rem 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(10px);
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .footer-section h3 {
            font-family: 'DM Sans', sans-serif;
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.8rem;
        }

        .footer-section a {
            color: var(--gray);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: var(--primary);
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-icon {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.2rem;
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-icon:hover {
            background: var(--primary);
            border-color: var(--primary);
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: var(--gray);
        }

        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--white);
            font-size: 1.5rem;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .cta-content {
                padding: 2.5rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="gradient-orb orb-1"></div>
        <div class="gradient-orb orb-2"></div>
        <div class="gradient-orb orb-3"></div>
    </div>

    <!-- Navigation -->
    <nav>
        <div class="logo">{{ config('app.name', 'ViralBoost') }}</div>
        <ul class="nav-links">
            <li><a href="#features">Features</a></li>
            <li><a href="#services">Services</a></li><!-- 
            <li><a href="#pricing">Pricing</a></li> -->
            <li><a href="#contact">Contact</a></li>
        </ul>
        <div class="nav-buttons">
            <a href="{{ route('login') }}" class="btn btn-outline">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
        </div>
        <button class="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Grow Your Social Media Presence Instantly</h1>
            <p>Boost your Instagram, TikTok, YouTube, and more with our premium, authentic engagement services. Real results, real growth, real fast.</p>
            <div class="hero-buttons">
                <a href="{{ route('register') }}" class="btn btn-primary">Start Growing Now</a>
                <a href="#features" class="btn btn-outline">Learn More</a>
            </div>

            <div class="hero-stats">
                <div class="stat-card">
                    <div class="stat-number">50K+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">10M+</div>
                    <div class="stat-label">Orders Delivered</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">Success Rate</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="section-header">
            <h2>Why Choose Us?</h2>
            <p>We provide the best social media marketing services with unbeatable quality and speed</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Instant Delivery</h3>
                <p>Get your orders started within minutes. Our automated system ensures lightning-fast processing and delivery.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>100% Safe & Secure</h3>
                <p>Your account security is our priority. We use only safe, organic methods that comply with platform guidelines.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Real Results</h3>
                <p>No fake accounts or bots. We deliver authentic, high-quality engagement from real, active users.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>24/7 Support</h3>
                <p>Our dedicated support team is always available to help you with any questions or concerns.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <h3>Affordable Pricing</h3>
                <p>Get the best value for your money with our competitive rates and special bulk discounts.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-redo"></i>
                </div>
                <h3>Money-Back Guarantee</h3>
                <p>Not satisfied? We offer a hassle-free refund policy. Your satisfaction is guaranteed.</p>
            </div>
        </div>
    </section>

    <!-- Social Platforms Section -->
    <section id="services" class="social-proof">
        <div class="section-header">
            <h2>Supported Platforms</h2>
            <p>Grow your presence across all major social media platforms</p>
        </div>

        <div class="platforms">
            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-instagram"></i>
                </div>
                <div class="platform-name">Instagram</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-tiktok"></i>
                </div>
                <div class="platform-name">TikTok</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-youtube"></i>
                </div>
                <div class="platform-name">YouTube</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-facebook"></i>
                </div>
                <div class="platform-name">Facebook</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-twitter"></i>
                </div>
                <div class="platform-name">Twitter/X</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-spotify"></i>
                </div>
                <div class="platform-name">Spotify</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-telegram"></i>
                </div>
                <div class="platform-name">Telegram</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-soundcloud"></i>
                </div>
                <div class="platform-name">SoundCloud</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-linkedin"></i>
                </div>
                <div class="platform-name">LinkedIn</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-pinterest"></i>
                </div>
                <div class="platform-name">Pinterest</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-snapchat"></i>
                </div>
                <div class="platform-name">Snapchat</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-twitch"></i>
                </div>
                <div class="platform-name">Twitch</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-discord"></i>
                </div>
                <div class="platform-name">Discord</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-reddit"></i>
                </div>
                <div class="platform-name">Reddit</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <div class="platform-name">WhatsApp</div>
            </div>

            <div class="platform-item">
                <div class="platform-icon">
                    <i class="fab fa-clubhouse"></i>
                </div>
                <div class="platform-name">Clubhouse</div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-content">
            <h2>Ready to Go Viral?</h2>
            <p>Join thousands of satisfied customers who have transformed their social media presence with our services.</p>
            <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 1rem 3rem; font-size: 1.1rem;">Create Free Account</a>
        </div>
    </section>

    <!-- Accopond Promotion Section -->
    <section class="accopond-promo" style="padding: 6rem 2rem; background: linear-gradient(135deg, rgba(236, 72, 153, 0.1), rgba(99, 102, 241, 0.1));">
        <div style="max-width: 1200px; margin: 0 auto; text-align: center;">
            <div style="background: rgba(255, 255, 255, 0.05); padding: 3rem; border-radius: 30px; border: 1px solid rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);">
                <div style="display: inline-block; padding: 0.5rem 1.5rem; background: linear-gradient(135deg, var(--secondary), var(--accent)); border-radius: 50px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 600;">
                    PARTNER SERVICES
                </div>
                <h2 style="font-size: clamp(2rem, 4vw, 3rem); margin-bottom: 1.5rem; font-family: 'Playfair Display', serif; font-weight: 900;">
                    Looking to Buy or Sell Social Media Accounts?
                </h2>
                <p style="font-size: 1.2rem; color: var(--gray); margin-bottom: 2rem; max-width: 700px; margin-left: auto; margin-right: auto; line-height: 1.6;">
                    Need to buy established social media accounts? Or want to sell your aged accounts? Visit <strong style="color: var(--primary);">Accopond</strong> - your trusted marketplace for buying and selling verified social media accounts safely and securely.
                </p>
                <div style="display: flex; gap: 1.5rem; justify-content: center; align-items: center; flex-wrap: wrap; margin-bottom: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-check-circle" style="color: var(--accent); font-size: 1.2rem;"></i>
                        <span style="color: var(--gray);">Verified Accounts</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-check-circle" style="color: var(--accent); font-size: 1.2rem;"></i>
                        <span style="color: var(--gray);">Secure Transactions</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-check-circle" style="color: var(--accent); font-size: 1.2rem;"></i>
                        <span style="color: var(--gray);">Instant Delivery</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-check-circle" style="color: var(--accent); font-size: 1.2rem;"></i>
                        <span style="color: var(--gray);">24/7 Support</span>
                    </div>
                </div>
                <a href="https://www.accopond.com.ng" target="_blank" class="btn btn-primary" style="padding: 1rem 3rem; font-size: 1.1rem; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-external-link-alt"></i>
                    Visit Accopond Now
                </a>
                <p style="margin-top: 1.5rem; color: var(--gray); font-size: 0.9rem;">
                    <i class="fas fa-shield-alt"></i> Trusted by thousands of buyers and sellers worldwide
                </p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h3>{{ config('app.name', 'ViralBoost') }}</h3>
                <p style="color: var(--gray); margin-bottom: 1.5rem;">Your trusted partner for social media growth. Safe, fast, and reliable.</p>
                <div class="social-links">
                	<a href="https://whatsapp.com/channel/0029Vb7I1e3JuyAL31rIia0r" class="social-icon" target="_blank" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="social-icon" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon" target="_blank" title="Twitter/X"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
<!--                     <a href="#" class="social-icon" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="social-icon" target="_blank" title="TikTok"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="social-icon" target="_blank" title="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-icon" target="_blank" title="Telegram"><i class="fab fa-telegram"></i></a> -->
                <!--     
                    <a href="#" class="social-icon" target="_blank" title="Reddit"><i class="fab fa-reddit-alien"></i></a>
                    <a href="#" class="social-icon" target="_blank" title="Discord"><i class="fab fa-discord"></i></a> -->
                </div>
            </div>

            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Sign Up</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#services">Services</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Support</h3>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Contact</h3>
                <ul style="color: var(--gray);">
                    <li><i class="fas fa-envelope"></i> booster@africicl.com.ng</li>
                    <li><i class="fab fa-whatsapp"></i> 09152880128 (WhatsApp only)</li>
                    <li><i class="fas fa-map-marker-alt"></i> Lagos, Nigeria</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'ViralBoost') }}. All rights reserved.</p>
            <p class="mt-2" style="font-size: 0.9rem;">Powered by <strong>AfricGEM International Company Limited</strong></p>
        </div>
    </footer>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Add scroll effect to nav
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 100) {
                nav.style.background = 'rgba(15, 23, 42, 0.95)';
            } else {
                nav.style.background = 'rgba(15, 23, 42, 0.8)';
            }
        });
    </script>
</body>
</html>