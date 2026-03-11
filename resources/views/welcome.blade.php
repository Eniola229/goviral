<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booster — Premium Social Media Growth</title>
    <!-- SEO Meta -->
    <meta name="description" content="Boost your social media presence with {{ config('app.name', 'Booster') }}. We offer premium, fast, and reliable social media growth services for Instagram, Facebook, TikTok, Twitter, and more." />
    <meta name="keyword" content="social media growth, buy followers Nigeria, Instagram growth services, TikTok followers, Facebook likes, Twitter engagement, social media marketing Nigeria" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Syne:wght@400;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Open Graph / Social Sharing -->
    <meta property="og:title" content="{{ config('app.name', 'Booster') }} – Premium Social Media Growth Services" />
    <meta property="og:description" content="Grow your Instagram, TikTok, Facebook, and Twitter accounts with fast, secure, and reliable social media growth services." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ asset('assets/images/B.png') }}" />
    <meta property="og:site_name" content="{{ config('app.name', 'Booster') }}" />

    <!--! BEGIN: Favicon-->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/B.png') }}" />
    <!--! END: Favicon-->
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --navy: #050D1F;
            --navy-2: #0A1628;
            --navy-3: #0F2040;
            --blue: #1A3A6E;
            --accent: #2563EB;
            --electric: #3B82F6;
            --gold: #F59E0B;
            --cream: #F5F0E8;
            --muted: #A0B4CC;
            --white: #FFFFFF;
            --border: rgba(37, 99, 235, 0.2);
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Syne', sans-serif;
            background: var(--navy);
            color: var(--white);
            overflow-x: hidden;
            cursor: none;
        }

        .cursor {
            width: 10px; height: 10px;
            background: var(--electric);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 99999;
            transition: transform 0.1s;
            mix-blend-mode: screen;
        }
        .cursor-ring {
            width: 36px; height: 36px;
            border: 1.5px solid var(--electric);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 99998;
            transition: all 0.15s ease;
            opacity: 0.5;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 512 512' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
            opacity: 0.025;
            pointer-events: none;
            z-index: 9999;
        }

        nav {
            position: fixed; top: 0; left: 0; width: 100%;
            z-index: 1000;
            padding: 1.4rem 3rem;
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid transparent;
            transition: all 0.4s;
        }
        nav.scrolled {
            background: rgba(5, 13, 31, 0.92);
            backdrop-filter: blur(16px);
            border-color: var(--border);
        }

        .logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.9rem;
            letter-spacing: 3px;
            color: var(--white);
            text-decoration: none;
        }
        .logo span { color: var(--electric); }

        .nav-links {
            display: flex; gap: 2.5rem; list-style: none;
        }
        .nav-links a {
            color: var(--muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            transition: color 0.3s;
        }
        .nav-links a:hover { color: var(--white); }

        .nav-btns { display: flex; gap: 1rem; align-items: center; }

        .btn {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 0.7rem 1.8rem;
            border-radius: 4px;
            text-decoration: none;
            cursor: none;
            border: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-ghost {
            color: var(--muted);
            background: transparent;
            border: 1px solid rgba(122, 143, 173, 0.3);
        }
        .btn-ghost:hover { color: var(--white); border-color: var(--white); }
        .btn-solid {
            background: var(--accent);
            color: var(--white);
            box-shadow: 0 0 30px rgba(37, 99, 235, 0.3);
        }
        .btn-solid:hover {
            background: var(--electric);
            box-shadow: 0 0 40px rgba(59, 130, 246, 0.5);
            transform: translateY(-2px);
        }

        /* HERO */
        .hero {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            padding: 9rem 3rem 5rem;
            position: relative;
            overflow: hidden;
            gap: 4rem;
        }

        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(37, 99, 235, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(37, 99, 235, 0.05) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

        .hero-glow {
            position: absolute;
            width: 800px; height: 800px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.18) 0%, transparent 70%);
            top: -200px; right: -200px;
            pointer-events: none;
        }
        .hero-glow-2 {
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.07) 0%, transparent 70%);
            bottom: 0; left: 10%;
            pointer-events: none;
        }

        .hero-left {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .hero-eyebrow {
            font-family: 'DM Mono', monospace;
            font-size: 0.75rem;
            color: var(--electric);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            opacity: 0;
            animation: rise 0.8s ease forwards 0.3s;
        }
        .hero-eyebrow::before {
            content: '';
            display: inline-block;
            width: 40px; height: 1px;
            background: var(--electric);
        }

        .hero h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(5rem, 8vw, 9rem);
            line-height: 0.92;
            letter-spacing: -1px;
            color: var(--white);
            opacity: 0;
            animation: rise 1s ease forwards 0.5s;
        }
        .hero h1 .outline {
            -webkit-text-stroke: 2px rgba(255,255,255,0.35);
            color: transparent;
        }
        .hero h1 .blue { color: var(--electric); }

        .hero-desc {
            color: #B8C9E0;
            font-size: 1.05rem;
            line-height: 1.75;
            font-weight: 400;
            margin-top: 1.8rem;
            max-width: 420px;
            opacity: 0;
            animation: rise 1s ease forwards 0.7s;
        }

        .hero-actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-top: 2.5rem;
            opacity: 0;
            animation: rise 1s ease forwards 0.9s;
        }

        .btn-large {
            padding: 1rem 2.5rem;
            font-size: 0.9rem;
        }

        /* HERO RIGHT */
        .hero-right {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            animation: rise 1s ease forwards 0.6s;
        }

        .social-panel {
            position: relative;
            width: 100%;
            max-width: 420px;
        }

        /* Scrollable cards container */
        .social-cards-scroll {
            max-height: 340px;
            overflow: hidden;
            position: relative;
            border-radius: 14px;
        }

        .social-cards-track {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            animation: scrollCards 22s linear infinite;
        }

        .social-cards-track:hover {
            animation-play-state: paused;
        }

        @keyframes scrollCards {
            0% { transform: translateY(0); }
            100% { transform: translateY(-50%); }
        }

        .soc-card {
            background: rgba(10, 22, 40, 0.9);
            border: 1px solid rgba(37, 99, 235, 0.25);
            border-radius: 14px;
            padding: 0.9rem 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.9rem;
            backdrop-filter: blur(12px);
            flex-shrink: 0;
            transition: border-color 0.3s;
        }
        .soc-card:hover { border-color: var(--electric); }

        .soc-card-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .ig-bg { background: linear-gradient(135deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
        .tt-bg { background: #111; border: 1px solid #333; }
        .yt-bg { background: #FF0000; }
        .tw-bg { background: #1DA1F2; }
        .fb-bg { background: #1877F2; }
        .sp-bg { background: #1DB954; }
        .tg-bg { background: #0088cc; }
        .wa-bg { background: #25D366; }
        .li-bg { background: #0A66C2; }
        .sc-bg { background: #FF6600; }
        .pi-bg { background: #E60023; }
        .tw2-bg { background: #9146FF; }

        .soc-card-info { flex: 1; min-width: 0; }
        .soc-card-name {
            font-size: 0.82rem;
            font-weight: 700;
            color: #E8F0FA;
            margin-bottom: 0.1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .soc-card-handle {
            font-family: 'DM Mono', monospace;
            font-size: 0.62rem;
            color: #7A9ABD;
            letter-spacing: 0.5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .soc-card-right {
            text-align: right;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.2rem;
        }
        .soc-card-count {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.4rem;
            line-height: 1;
            color: var(--white);
        }
        .soc-card-count.green { color: #22D3A5; }
        .soc-card-count.gold { color: #F59E0B; }
        .soc-card-metric {
            font-family: 'DM Mono', monospace;
            font-size: 0.58rem;
            color: #7A9ABD;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .soc-card-country {
            font-family: 'DM Mono', monospace;
            font-size: 0.55rem;
            color: #22D3A5;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .soc-card-country::before {
            content: '▲';
            font-size: 0.45rem;
        }

        /* Fade edges on scroll area */
        .social-cards-scroll::before,
        .social-cards-scroll::after {
            content: '';
            position: absolute;
            left: 0; right: 0;
            height: 50px;
            z-index: 2;
            pointer-events: none;
        }
        .social-cards-scroll::before {
            top: 0;
            background: linear-gradient(to bottom, var(--navy), transparent);
        }
        .social-cards-scroll::after {
            bottom: 0;
            background: linear-gradient(to top, var(--navy), transparent);
        }

        /* Orbit icons */
        .orbit-icons {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            pointer-events: none;
            z-index: -1;
        }
        .orbit-icon {
            position: absolute;
            width: 38px; height: 38px;
            border-radius: 10px;
            background: rgba(10, 22, 40, 0.9);
            border: 1px solid rgba(37, 99, 235, 0.3);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            color: #7A9ABD;
        }
        .orbit-icon:nth-child(1) { top: 2%; left: 40%; animation: orbit1 8s ease-in-out infinite; }
        .orbit-icon:nth-child(2) { top: 20%; right: -8%; animation: orbit2 9s ease-in-out infinite; }
        .orbit-icon:nth-child(3) { top: 55%; right: -10%; animation: orbit3 7s ease-in-out infinite; }
        .orbit-icon:nth-child(4) { bottom: 5%; right: 20%; animation: orbit1 10s ease-in-out infinite 1s; }
        .orbit-icon:nth-child(5) { bottom: 15%; left: -8%; animation: orbit2 8s ease-in-out infinite 2s; }
        .orbit-icon:nth-child(6) { top: 38%; left: -10%; animation: orbit3 9s ease-in-out infinite 0.5s; }

        @keyframes orbit1 {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(8px, -10px) rotate(5deg); }
            66% { transform: translate(-5px, 8px) rotate(-3deg); }
        }
        @keyframes orbit2 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-10px, -8px); }
        }
        @keyframes orbit3 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(6px, 10px) scale(1.05); }
        }

        .live-badge {
            position: absolute;
            top: -14px; right: 0px;
            background: linear-gradient(135deg, #22D3A5, #059669);
            color: white;
            font-family: 'DM Mono', monospace;
            font-size: 0.6rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            box-shadow: 0 0 20px rgba(34, 211, 165, 0.4);
            z-index: 3;
        }
        .live-dot {
            width: 6px; height: 6px;
            background: white;
            border-radius: 50%;
            animation: blink 1.2s ease-in-out infinite;
        }
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        /* LIVE ACTIVITY FEED */
        .activity-feed {
            margin-top: 1rem;
            background: rgba(10,22,40,0.85);
            border: 1px solid rgba(37,99,235,0.25);
            border-radius: 12px;
            overflow: hidden;
            backdrop-filter: blur(12px);
        }
        .activity-feed-header {
            padding: 0.6rem 1rem;
            border-bottom: 1px solid rgba(37,99,235,0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .activity-feed-header span {
            font-family: 'DM Mono', monospace;
            font-size: 0.6rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #7A9ABD;
        }
        .activity-feed-header .green-dot {
            width: 6px; height: 6px;
            background: #22D3A5;
            border-radius: 50%;
            box-shadow: 0 0 6px #22D3A5;
            animation: blink 1.5s ease-in-out infinite;
        }
        .activity-items {
            max-height: 115px;
            overflow: hidden;
            position: relative;
        }
        .activity-track {
            display: flex;
            flex-direction: column;
        }
        .activity-item {
            padding: 0.55rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            border-bottom: 1px solid rgba(37,99,235,0.08);
            animation: slideInActivity 0.4s ease;
        }
        @keyframes slideInActivity {
            from { opacity: 0; transform: translateX(-10px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .activity-icon {
            width: 26px; height: 26px;
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem;
            flex-shrink: 0;
        }
        .activity-text {
            flex: 1;
            font-size: 0.7rem;
            color: #B0C8E0;
            line-height: 1.3;
        }
        .activity-text strong {
            color: #E8F0FA;
            font-weight: 700;
        }
        .activity-text .hl {
            color: #22D3A5;
            font-weight: 700;
        }
        .activity-time {
            font-family: 'DM Mono', monospace;
            font-size: 0.55rem;
            color: #4A6A8A;
            flex-shrink: 0;
        }

        /* Order counter bar */
        .counter-bar {
            margin-top: 0.75rem;
            background: rgba(10,22,40,0.8);
            border: 1px solid rgba(37,99,235,0.25);
            border-radius: 10px;
            padding: 0.8rem 1.1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            backdrop-filter: blur(12px);
        }

        .scroll-hint {
            position: absolute;
            bottom: 2.5rem;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            opacity: 0;
            animation: rise 1s ease forwards 1.2s;
        }
        .scroll-hint span {
            font-family: 'DM Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 2px;
            color: #8aaace;
            text-transform: uppercase;
        }
        .scroll-line {
            width: 1px; height: 50px;
            background: linear-gradient(to bottom, var(--electric), transparent);
            animation: pulse-line 2s ease-in-out infinite;
        }
        @keyframes pulse-line {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }

        /* TICKER */
        .ticker-wrap {
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            background: var(--navy-2);
            overflow: hidden;
            padding: 0.9rem 0;
        }
        .ticker {
            display: flex;
            width: max-content;
            animation: ticker 30s linear infinite;
        }
        .ticker-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0 2rem;
            font-family: 'DM Mono', monospace;
            font-size: 0.75rem;
            letter-spacing: 1px;
            color: var(--muted);
            text-transform: uppercase;
            white-space: nowrap;
        }
        .ticker-item .dot { color: var(--electric); font-size: 0.5rem; }
        @keyframes ticker {
            from { transform: translateX(0); }
            to { transform: translateX(-50%); }
        }

        /* STATS */
        .stats-section {
            padding: 6rem 3rem;
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0;
        }
        .stat-item {
            padding: 3rem;
            border-right: 1px solid var(--border);
            position: relative;
        }
        .stat-item:last-child { border-right: none; }
        .stat-num {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 5rem;
            line-height: 1;
            color: var(--white);
            margin-bottom: 0.5rem;
        }
        .stat-num span { color: var(--electric); }
        .stat-label {
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 600;
        }
        .stat-item::before {
            content: '';
            position: absolute;
            top: 0; left: 3rem; right: 3rem;
            height: 1px;
            background: var(--border);
        }

        /* FEATURES */
        .features {
            padding: 6rem 3rem;
            background: var(--navy-2);
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        .features-inner { max-width: 1400px; margin: 0 auto; }

        .section-tag {
            font-family: 'DM Mono', monospace;
            font-size: 0.7rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--electric);
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .section-tag::before {
            content: '';
            display: inline-block;
            width: 30px; height: 1px;
            background: var(--electric);
        }

        .features-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1px;
            background: var(--border);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }
        .feat-card {
            background: var(--navy-2);
            padding: 3rem;
            transition: background 0.3s;
            position: relative;
            overflow: hidden;
        }
        .feat-card:hover { background: var(--navy-3); }
        .feat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 3px; height: 0;
            background: var(--electric);
            transition: height 0.4s;
        }
        .feat-card:hover::before { height: 100%; }

        .feat-num {
            font-family: 'DM Mono', monospace;
            font-size: 0.7rem;
            color: var(--blue);
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
        }
        .feat-icon {
            font-size: 1.5rem;
            color: var(--electric);
            margin-bottom: 1rem;
        }
        .feat-card h3 {
            font-family: 'Syne', sans-serif;
            font-size: 1.2rem;
            font-weight: 800;
            margin-bottom: 0.8rem;
            color: var(--white);
        }
        .feat-card p {
            color: #9BB5CF;
            font-size: 0.92rem;
            line-height: 1.65;
            font-weight: 400;
        }

        /* PLATFORMS */
        .platforms-section {
            padding: 6rem 3rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        .platforms-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-bottom: 4rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 2rem;
        }
        .platforms-header h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(3rem, 6vw, 5rem);
            line-height: 1;
        }
        .platforms-header p {
            color: #9BB5CF;
            font-size: 0.9rem;
            max-width: 300px;
            text-align: right;
            line-height: 1.6;
        }

        .platforms-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 1px;
            background: var(--border);
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }
        .platform-cell {
            background: var(--navy);
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.6rem;
            transition: all 0.3s;
            text-decoration: none;
        }
        .platform-cell:hover { background: var(--navy-3); }
        .platform-cell i {
            font-size: 1.6rem;
            color: var(--muted);
            transition: color 0.3s;
        }
        .platform-cell:hover i { color: var(--electric); }
        .platform-cell span {
            font-size: 0.65rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 600;
            font-family: 'DM Mono', monospace;
        }

        /* PARTNER */
        .partner-section {
            padding: 6rem 3rem;
            background: var(--navy-2);
            border-top: 1px solid var(--border);
        }
        .partner-inner {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6rem;
            align-items: center;
        }
        .partner-left h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(3rem, 5vw, 4.5rem);
            line-height: 1.0;
            margin: 1rem 0 1.5rem;
        }
        .partner-left p {
            color: #9BB5CF;
            line-height: 1.7;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }
        .partner-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem;
            margin-bottom: 2.5rem;
        }
        .badge {
            font-family: 'DM Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 0.4rem 0.9rem;
            border: 1px solid var(--border);
            border-radius: 3px;
            color: var(--muted);
        }
        .partner-right { position: relative; }
        .partner-card {
            background: var(--navy);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }
        .partner-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(37, 99, 235, 0.08), transparent 60%);
            pointer-events: none;
        }
        .partner-logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3.5rem;
            letter-spacing: 4px;
            color: var(--white);
            margin-bottom: 0.5rem;
        }
        .partner-logo span { color: var(--electric); }
        .partner-card p {
            color: #9BB5CF;
            font-size: 0.85rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .partner-card .btn-solid { width: 100%; text-align: center; }

        /* CTA */
        .cta-section {
            padding: 8rem 3rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 900px; height: 600px;
            background: radial-gradient(ellipse, rgba(37, 99, 235, 0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-section h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(4rem, 10vw, 9rem);
            line-height: 0.9;
            letter-spacing: -1px;
            margin-bottom: 2rem;
        }
        .cta-section h2 .outline {
            -webkit-text-stroke: 1px rgba(255,255,255,0.2);
            color: transparent;
        }
        .cta-section p {
            color: var(--muted);
            font-size: 1rem;
            max-width: 450px;
            margin: 0 auto 3rem;
            line-height: 1.7;
        }

        /* FOOTER */
        footer {
            background: var(--navy-2);
            border-top: 1px solid var(--border);
            padding: 4rem 3rem 2rem;
        }
        .footer-top {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 4rem;
            padding-bottom: 3rem;
            border-bottom: 1px solid var(--border);
        }
        .footer-brand .logo-text {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            letter-spacing: 3px;
            margin-bottom: 1rem;
            display: block;
        }
        .footer-brand .logo-text span { color: var(--electric); }
        .footer-brand p {
            color: #9BB5CF;
            font-size: 0.88rem;
            line-height: 1.65;
            max-width: 280px;
            margin-bottom: 1.5rem;
        }
        .footer-socials { display: flex; gap: 0.6rem; }
        .soc-btn {
            width: 38px; height: 38px;
            border: 1px solid var(--border);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s;
        }
        .soc-btn:hover { border-color: var(--electric); color: var(--electric); }

        .footer-col h4 {
            font-size: 0.7rem;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--white);
            margin-bottom: 1.5rem;
            font-weight: 700;
        }
        .footer-col ul { list-style: none; }
        .footer-col li { margin-bottom: 0.8rem; }
        .footer-col a {
            color: var(--muted);
            text-decoration: none;
            font-size: 0.88rem;
            transition: color 0.3s;
        }
        .footer-col a:hover { color: var(--white); }
        .footer-col .contact-item {
            color: var(--muted);
            font-size: 0.85rem;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            margin-bottom: 0.8rem;
        }
        .footer-col .contact-item i { color: var(--electric); margin-top: 2px; flex-shrink: 0; }

        .footer-bottom {
            max-width: 1400px;
            margin: 2rem auto 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .footer-bottom p {
            color: var(--muted);
            font-size: 0.78rem;
            font-family: 'DM Mono', monospace;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 900px) {
            nav { padding: 1.2rem 1.5rem; }
            .nav-links { display: none; }
            .hero {
                grid-template-columns: 1fr;
                padding: 7rem 1.5rem 4rem;
                gap: 3rem;
            }
            .hero h1 { font-size: clamp(3.5rem, 14vw, 7rem); }
            .hero-right { display: none; }
            .stats-section { grid-template-columns: 1fr; padding: 3rem 1.5rem; }
            .stat-item { border-right: none; border-bottom: 1px solid var(--border); padding: 2rem 1.5rem; }
            .features { padding: 4rem 1.5rem; }
            .features-layout { grid-template-columns: 1fr; }
            .platforms-section { padding: 4rem 1.5rem; }
            .platforms-header { flex-direction: column; align-items: flex-start; gap: 1rem; }
            .platforms-header p { text-align: left; }
            .platforms-grid { grid-template-columns: repeat(4, 1fr); }
            .partner-section { padding: 4rem 1.5rem; }
            .partner-inner { grid-template-columns: 1fr; gap: 3rem; }
            .cta-section { padding: 5rem 1.5rem; }
            footer { padding: 3rem 1.5rem 2rem; }
            .footer-top { grid-template-columns: 1fr 1fr; gap: 2rem; }
            .footer-bottom { flex-direction: column; align-items: flex-start; }
        }

        @media (max-width: 500px) {
            .platforms-grid { grid-template-columns: repeat(3, 1fr); }
            .footer-top { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="cursor" id="cursor"></div>
    <div class="cursor-ring" id="cursorRing"></div>

    <nav id="nav">
        <a href="#" class="logo">Boo<span>ster</span></a>
        <ul class="nav-links">
            <li><a href="#features">Features</a></li>
            <li><a href="#platforms">Platforms</a></li>
            <li><a href="#contact">Contact</a></li>
        </ul>
        <div class="nav-btns">
            <a href="/login" class="btn btn-ghost">Login</a>
            <a href="/register" class="btn btn-solid">Get Started</a>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-glow"></div>
        <div class="hero-glow-2"></div>

        <div class="hero-left">
            <div class="hero-eyebrow">Premium Social Media Growth</div>
            <h1>
                Grow<br>
                <span class="outline">Your</span><br>
                <span class="blue">Audience</span>
            </h1>
            <p class="hero-desc">
                Boost your Instagram, TikTok, YouTube and more.
                Real engagement, instant delivery — built for creators worldwide who mean business.
            </p>
            <div class="hero-actions">
                <a href="/register" class="btn btn-solid btn-large">Start Growing</a>
                <a href="#features" class="btn btn-ghost btn-large">See How</a>
            </div>
        </div>

        <div class="hero-right">
            <div class="social-panel">
                <div class="orbit-icons">
                    <div class="orbit-icon"><i class="fab fa-spotify"></i></div>
                    <div class="orbit-icon"><i class="fab fa-telegram"></i></div>
                    <div class="orbit-icon"><i class="fab fa-linkedin"></i></div>
                    <div class="orbit-icon"><i class="fab fa-snapchat"></i></div>
                    <div class="orbit-icon"><i class="fab fa-discord"></i></div>
                    <div class="orbit-icon"><i class="fab fa-pinterest"></i></div>
                </div>

                <div class="live-badge">
                    <div class="live-dot"></div>
                    Live Boosting
                </div>

                <!-- Scrollable social cards -->
                <div class="social-cards-scroll">
                    <div class="social-cards-track" id="cardsTrack">
                        <!-- JS will populate these -->
                    </div>
                </div>

                <!-- Live activity feed -->
                <div class="activity-feed">
                    <div class="activity-feed-header">
                        <span>Live Activity</span>
                        <div class="green-dot"></div>
                    </div>
                    <div class="activity-items">
                        <div class="activity-track" id="activityTrack">
                            <!-- JS will populate -->
                        </div>
                    </div>
                </div>

                <!-- Counter bar -->
                <div class="counter-bar">
                    <div style="display:flex;align-items:center;gap:0.6rem;">
                        <div style="width:8px;height:8px;background:#22D3A5;border-radius:50%;box-shadow:0 0 8px #22D3A5;"></div>
                        <span style="font-family:'DM Mono',monospace;font-size:0.68rem;letter-spacing:1px;color:#A0BDD8;text-transform:uppercase;">Orders completed today</span>
                    </div>
                    <span id="order-counter" style="font-family:'Bebas Neue',sans-serif;font-size:1.4rem;color:#22D3A5;">2,247</span>
                </div>
            </div>
        </div>

        <div class="scroll-hint">
            <span>Scroll</span>
            <div class="scroll-line"></div>
        </div>
    </section>

    <div class="ticker-wrap">
        <div class="ticker">
            <div class="ticker-item"><span class="dot">●</span> Instagram Followers</div>
            <div class="ticker-item"><span class="dot">●</span> TikTok Likes</div>
            <div class="ticker-item"><span class="dot">●</span> YouTube Views</div>
            <div class="ticker-item"><span class="dot">●</span> Facebook Page Likes</div>
            <div class="ticker-item"><span class="dot">●</span> Twitter Followers</div>
            <div class="ticker-item"><span class="dot">●</span> Spotify Streams</div>
            <div class="ticker-item"><span class="dot">●</span> Telegram Members</div>
            <div class="ticker-item"><span class="dot">●</span> LinkedIn Connections</div>
            <div class="ticker-item"><span class="dot">●</span> SoundCloud Plays</div>
            <div class="ticker-item"><span class="dot">●</span> Discord Members</div>
            <div class="ticker-item"><span class="dot">●</span> Instagram Followers</div>
            <div class="ticker-item"><span class="dot">●</span> TikTok Likes</div>
            <div class="ticker-item"><span class="dot">●</span> YouTube Views</div>
            <div class="ticker-item"><span class="dot">●</span> Facebook Page Likes</div>
            <div class="ticker-item"><span class="dot">●</span> Twitter Followers</div>
            <div class="ticker-item"><span class="dot">●</span> Spotify Streams</div>
            <div class="ticker-item"><span class="dot">●</span> Telegram Members</div>
            <div class="ticker-item"><span class="dot">●</span> LinkedIn Connections</div>
            <div class="ticker-item"><span class="dot">●</span> SoundCloud Plays</div>
            <div class="ticker-item"><span class="dot">●</span> Discord Members</div>
        </div>
    </div>

    <div class="stats-section reveal">
        <div class="stat-item">
            <div class="stat-num">50K<span>+</span></div>
            <div class="stat-label">Happy Customers</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">10M<span>+</span></div>
            <div class="stat-label">Orders Delivered</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">99.9<span>%</span></div>
            <div class="stat-label">Success Rate</div>
        </div>
    </div>

    <section id="features" class="features">
        <div class="features-inner">
            <div class="section-tag reveal">Why We're Different</div>
            <div class="features-layout">
                <div class="feat-card reveal">
                    <div class="feat-num">01</div>
                    <div class="feat-icon"><i class="fas fa-bolt"></i></div>
                    <h3>Instant Delivery</h3>
                    <p>Orders kick off within minutes. Our automated pipeline processes and delivers without you waiting around.</p>
                </div>
                <div class="feat-card reveal">
                    <div class="feat-num">02</div>
                    <div class="feat-icon"><i class="fas fa-shield-alt"></i></div>
                    <h3>Safe & Compliant</h3>
                    <p>We don't touch your credentials or use shady shortcuts. Every method aligns with platform guidelines.</p>
                </div>
                <div class="feat-card reveal">
                    <div class="feat-num">03</div>
                    <div class="feat-icon"><i class="fas fa-users"></i></div>
                    <h3>Real Engagement</h3>
                    <p>No bots. No ghost accounts. Authentic, high-quality engagement that actually moves the needle.</p>
                </div>
                <div class="feat-card reveal">
                    <div class="feat-num">04</div>
                    <div class="feat-icon"><i class="fas fa-headset"></i></div>
                    <h3>Support, Always On</h3>
                    <p>Got a question at 2am? Our team responds fast — day, night, weekends. No ticket queue runaround.</p>
                </div>
                <div class="feat-card reveal">
                    <div class="feat-num">05</div>
                    <div class="feat-icon"><i class="fas fa-tag"></i></div>
                    <h3>Prices That Make Sense</h3>
                    <p>Competitive rates with bulk discounts. Growing your presence shouldn't drain your budget.</p>
                </div>
                <div class="feat-card reveal">
                    <div class="feat-num">06</div>
                    <div class="feat-icon"><i class="fas fa-undo"></i></div>
                    <h3>Refund Guarantee</h3>
                    <p>Something goes wrong? We make it right. Straightforward refund policy, no hoops.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="platforms" class="platforms-section">
        <div class="platforms-header reveal">
            <h2>16 Platforms.<br>One Dashboard.</h2>
            <p>Manage your growth across every major social platform from a single place.</p>
        </div>
        <div class="platforms-grid reveal">
            <div class="platform-cell"><i class="fab fa-instagram"></i><span>Instagram</span></div>
            <div class="platform-cell"><i class="fab fa-tiktok"></i><span>TikTok</span></div>
            <div class="platform-cell"><i class="fab fa-youtube"></i><span>YouTube</span></div>
            <div class="platform-cell"><i class="fab fa-facebook"></i><span>Facebook</span></div>
            <div class="platform-cell"><i class="fab fa-twitter"></i><span>Twitter/X</span></div>
            <div class="platform-cell"><i class="fab fa-spotify"></i><span>Spotify</span></div>
            <div class="platform-cell"><i class="fab fa-telegram"></i><span>Telegram</span></div>
            <div class="platform-cell"><i class="fab fa-soundcloud"></i><span>SoundCloud</span></div>
            <div class="platform-cell"><i class="fab fa-linkedin"></i><span>LinkedIn</span></div>
            <div class="platform-cell"><i class="fab fa-pinterest"></i><span>Pinterest</span></div>
            <div class="platform-cell"><i class="fab fa-snapchat"></i><span>Snapchat</span></div>
            <div class="platform-cell"><i class="fab fa-twitch"></i><span>Twitch</span></div>
            <div class="platform-cell"><i class="fab fa-discord"></i><span>Discord</span></div>
            <div class="platform-cell"><i class="fab fa-reddit"></i><span>Reddit</span></div>
            <div class="platform-cell"><i class="fab fa-whatsapp"></i><span>WhatsApp</span></div>
            <div class="platform-cell"><i class="fas fa-users"></i><span>Clubhouse</span></div>
        </div>
    </section>

    <section class="partner-section">
        <div class="partner-inner">
            <div class="partner-left reveal">
                <div class="section-tag">Partner Services</div>
                <h2>Buy &amp; Sell<br>Verified Accounts</h2>
                <p>Need an established account to jumpstart your presence? Or ready to sell your aged profiles? Accopond is our trusted marketplace partner for safe, verified account transactions.</p>
                <div class="partner-badges">
                    <span class="badge">Verified Accounts</span>
                    <span class="badge">Secure Transactions</span>
                    <span class="badge">Instant Delivery</span>
                    <span class="badge">24/7 Support</span>
                </div>
                <a href="https://www.accopond.com.ng" target="_blank" class="btn btn-solid btn-large">
                    Visit Accopond &nbsp;<i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="partner-right reveal">
                <div class="partner-card">
                    <div class="partner-logo">Acco<span>pond</span></div>
                    <p>Nigeria's trusted marketplace for buying and selling social media accounts. Thousands of verified buyers and sellers, every transaction protected.</p>
                    <a href="https://www.accopond.com.ng" target="_blank" class="btn btn-solid" style="display:block; text-align:center;">
                        Go to Accopond.com.ng
                    </a>
                    <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border); display:flex; gap:0.5rem; align-items:center;">
                        <i class="fas fa-shield-alt" style="color:var(--electric); font-size:0.8rem;"></i>
                        <span style="font-size:0.75rem; color:var(--muted); font-family:'DM Mono',monospace; letter-spacing:1px;">TRUSTED BY THOUSANDS WORLDWIDE</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <h2 class="reveal">
            Go<br>
            <span class="outline">Viral</span><br>
            Today
        </h2>
        <p class="reveal">Join 50,000+ creators and brands growing their presence with Booster.</p>
        <a href="/register" class="btn btn-solid btn-large reveal">Create Free Account</a>
    </section>

    <footer id="contact">
        <div class="footer-top">
            <div class="footer-brand">
                <span class="logo-text">Boo<span>ster</span></span>
                <p>Your trusted partner for social media growth worldwide. Safe, fast, and built for results.</p>
                <div class="footer-socials">
                    <a href="https://whatsapp.com/channel/0029Vb7I1e3JuyAL31rIia0r" target="_blank" class="soc-btn"><i class="fab fa-whatsapp"></i></a>
                    <a href="https://www.tiktok.com/@booster3928" target="_blank" class="soc-btn"><i class="fab fa-tiktok"></i></a>
                    <a href="#" class="soc-btn"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="soc-btn"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h4>Navigate</h4>
                <ul>
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Sign Up</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#platforms">Platforms</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <ul>
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/terms-of-use">Terms of Use</a></li>
                    <li><a href="/refund-policy">Refund Policy</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <div class="contact-item"><i class="fas fa-envelope"></i> info@boosterr.xyz</div>
                <div class="contact-item"><i class="fab fa-whatsapp"></i> 09152880128<br><small style="opacity:.6">(WhatsApp only)</small></div>
                <div class="contact-item"><i class="fas fa-map-marker-alt"></i> Lagos, Nigeria</div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 Booster. All rights reserved.</p>
            <p>Powered by AfricGEM International Company Limited</p>
        </div>
    </footer>

    <script>
    // ═══════════════════════════════════════════════════
    // DYNAMIC SOCIAL CARDS DATA — All fictional names
    // ═══════════════════════════════════════════════════
    const socialCards = [
        // Instagram
        { platform: 'instagram', icon: 'fab fa-instagram', bg: 'ig-bg', name: 'Tolani Adewale', handle: '@tolani.adewale · Instagram', metric: 'Followers', count: '87.4K', countColor: 'green', gained: '+22.1K', from: '🇳🇬 Nigeria' },
        { platform: 'tiktok', icon: 'fab fa-tiktok', bg: 'tt-bg', name: 'Marcus Chen', handle: '@marcusvibes · TikTok', metric: 'Likes', count: '2.3M', countColor: 'green', gained: '+480K', from: '🇺🇸 USA' },
        { platform: 'youtube', icon: 'fab fa-youtube', bg: 'yt-bg', name: 'Amira Hassan', handle: 'AmiraTV · YouTube', metric: 'Subscribers', count: '341K', countColor: 'green', gained: '+18.5K', from: '🇦🇪 UAE' },
        { platform: 'twitter', icon: 'fab fa-twitter', bg: 'tw-bg', name: 'Jake Morrison', handle: '@jakemorr · Twitter/X', metric: 'Followers', count: '112K', countColor: 'green', gained: '+9.2K', from: '🇬🇧 UK' },
        { platform: 'instagram', icon: 'fab fa-instagram', bg: 'ig-bg', name: 'Chidinma Obi', handle: '@chidinma.obi · Instagram', metric: 'Likes', count: '5.8M', countColor: 'gold', gained: '+1.2M', from: '🇳🇬 Nigeria' },
        { platform: 'tiktok', icon: 'fab fa-tiktok', bg: 'tt-bg', name: 'Sofia Reyes', handle: '@sofiareyes · TikTok', metric: 'Followers', count: '1.4M', countColor: 'green', gained: '+55K', from: '🇲🇽 Mexico' },
        { platform: 'facebook', icon: 'fab fa-facebook', bg: 'fb-bg', name: 'David Okonkwo', handle: 'David Okonkwo · Facebook', metric: 'Page Likes', count: '93K', countColor: 'green', gained: '+14K', from: '🇬🇭 Ghana' },
        { platform: 'spotify', icon: 'fab fa-spotify', bg: 'sp-bg', name: 'Luca Ferri', handle: '@lucaferri · Spotify', metric: 'Monthly Streams', count: '820K', countColor: 'green', gained: '+175K', from: '🇮🇹 Italy' },
        { platform: 'instagram', icon: 'fab fa-instagram', bg: 'ig-bg', name: 'Priya Kapoor', handle: '@priya.k · Instagram', metric: 'Followers', count: '210K', countColor: 'green', gained: '+31K', from: '🇮🇳 India' },
        { platform: 'youtube', icon: 'fab fa-youtube', bg: 'yt-bg', name: 'Kwame Asante', handle: 'KwameBuilds · YouTube', metric: 'Views', count: '7.2M', countColor: 'gold', gained: '+980K', from: '🇬🇭 Ghana' },
        { platform: 'telegram', icon: 'fab fa-telegram', bg: 'tg-bg', name: 'Fatima Al-Rashid', handle: 'FatimaChannel · Telegram', metric: 'Members', count: '28.4K', countColor: 'green', gained: '+6.2K', from: '🇸🇦 Saudi Arabia' },
        { platform: 'twitter', icon: 'fab fa-twitter', bg: 'tw-bg', name: 'Ryan Brooks', handle: '@ryanbrooks · Twitter/X', metric: 'Impressions', count: '4.1M', countColor: 'gold', gained: '+850K', from: '🇺🇸 USA' },
        { platform: 'instagram', icon: 'fab fa-instagram', bg: 'ig-bg', name: 'Yemi Daniels', handle: '@yemidaniels · Instagram', metric: 'Followers', count: '58K', countColor: 'green', gained: '+20.3K', from: '🇳🇬 Nigeria' },
        { platform: 'tiktok', icon: 'fab fa-tiktok', bg: 'tt-bg', name: 'Ling Wei', handle: '@lingwei.daily · TikTok', metric: 'Likes', count: '12.7M', countColor: 'gold', gained: '+3.4M', from: '🇨🇳 China' },
        { platform: 'whatsapp', icon: 'fab fa-whatsapp', bg: 'wa-bg', name: "Emeka's Business Group", handle: 'WhatsApp Group', metric: 'Members', count: '3.8K', countColor: 'green', gained: '+3K 🇺🇸 USA', from: '🌍 Worldwide' },
        { platform: 'instagram', icon: 'fab fa-instagram', bg: 'ig-bg', name: 'Isabella Moreno', handle: '@bellamoreno · Instagram', metric: 'Comments', count: '92.3K', countColor: 'gold', gained: '+18K', from: '🇧🇷 Brazil' },
        { platform: 'whatsapp', icon: 'fab fa-whatsapp', bg: 'wa-bg', name: 'Tunde Marketing Hub', handle: 'WhatsApp Group', metric: 'Real Members', count: '5.1K', countColor: 'green', gained: '+2K 🇬🇧 UK', from: '🌍 Multi-Country' },
        { platform: 'youtube', icon: 'fab fa-youtube', bg: 'yt-bg', name: 'Emma Johansson', handle: 'EmmaCreates · YouTube', metric: 'Subscribers', count: '445K', countColor: 'green', gained: '+28K', from: '🇸🇪 Sweden' },
        { platform: 'linkedin', icon: 'fab fa-linkedin', bg: 'li-bg', name: 'Ahmed Yusuf', handle: 'Ahmed Yusuf · LinkedIn', metric: 'Connections', count: '14.2K', countColor: 'green', gained: '+1.8K', from: '🇰🇪 Kenya' },
        { platform: 'instagram', icon: 'fab fa-instagram', bg: 'ig-bg', name: 'Carlos Mendez', handle: '@carlosmendez · Instagram', metric: 'Shares', count: '221K', countColor: 'gold', gained: '+44K', from: '🇦🇷 Argentina' },
    ];

    // Shuffle for each page load
    function shuffle(arr) {
        const a = [...arr];
        for (let i = a.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [a[i], a[j]] = [a[j], a[i]];
        }
        return a;
    }

    function buildCard(card) {
        return `
        <div class="soc-card">
            <div class="soc-card-icon ${card.bg}"><i class="${card.icon}" style="color:#fff"></i></div>
            <div class="soc-card-info">
                <div class="soc-card-name">${card.name}</div>
                <div class="soc-card-handle">${card.handle}</div>
            </div>
            <div class="soc-card-right">
                <div class="soc-card-count ${card.countColor}">${card.count}</div>
                <div class="soc-card-metric">${card.metric}</div>
                <div class="soc-card-country">${card.gained} added</div>
            </div>
        </div>`;
    }

    // Build scrollable cards (doubled for seamless loop)
    const track = document.getElementById('cardsTrack');
    const shuffled = shuffle(socialCards);
    const doubled = [...shuffled, ...shuffled];
    track.innerHTML = doubled.map(buildCard).join('');


    // ═══════════════════════════════════════════════════
    // LIVE ACTIVITY FEED
    // ═══════════════════════════════════════════════════
    const activityEvents = [
        { icon: 'fab fa-instagram', bg: '#E1306C', text: '<strong>Tolani A.</strong> just got <span class="hl">+1,000 followers</span> from 🇳🇬 Nigeria' },
        { icon: 'fab fa-whatsapp', bg: '#25D366', text: '<strong>Emeka B.</strong> received <span class="hl">+500 real members</span> from 🇺🇸 USA' },
        { icon: 'fab fa-tiktok', bg: '#111', text: '<strong>Sofia R.</strong> gained <span class="hl">+5K likes</span> on a video from 🇲🇽 Mexico' },
        { icon: 'fab fa-youtube', bg: '#FF0000', text: '<strong>KwameBuilds</strong> hit <span class="hl">+2K subscribers</span> from 🇬🇧 UK' },
        { icon: 'fab fa-twitter', bg: '#1DA1F2', text: '<strong>Ryan B.</strong> got <span class="hl">+800 followers</span> from 🇺🇸 United States' },
        { icon: 'fab fa-instagram', bg: '#E1306C', text: '<strong>Chidinma O.</strong> received <span class="hl">+3K post likes</span> instantly' },
        { icon: 'fab fa-telegram', bg: '#0088cc', text: '<strong>Fatima A.</strong> added <span class="hl">+300 channel members</span> from 🇦🇪 UAE' },
        { icon: 'fab fa-spotify', bg: '#1DB954', text: '<strong>Luca F.</strong> got <span class="hl">+10K streams</span> from 🇮🇹 Italy' },
        { icon: 'fab fa-whatsapp', bg: '#25D366', text: '<strong>Tunde M.</strong> added <span class="hl">+2K members</span> from 🇬🇧 UK' },
        { icon: 'fab fa-instagram', bg: '#E1306C', text: '<strong>Priya K.</strong> gained <span class="hl">+1.5K followers</span> from 🇮🇳 India' },
        { icon: 'fab fa-tiktok', bg: '#111', text: '<strong>Marcus C.</strong> got <span class="hl">+20K video views</span> from 🇺🇸 USA' },
        { icon: 'fab fa-facebook', bg: '#1877F2', text: '<strong>David O.</strong> received <span class="hl">+700 page likes</span> from 🇬🇭 Ghana' },
        { icon: 'fab fa-instagram', bg: '#E1306C', text: '<strong>Yemi D.</strong> gained <span class="hl">+4K reel views</span> within minutes' },
        { icon: 'fab fa-youtube', bg: '#FF0000', text: '<strong>Emma J.</strong> received <span class="hl">+500 subscribers</span> from 🇸🇪 Sweden' },
        { icon: 'fab fa-whatsapp', bg: '#25D366', text: '<strong>Aisha Group</strong> added <span class="hl">+1K real members</span> from 🇿🇦 South Africa' },
        { icon: 'fab fa-twitter', bg: '#1DA1F2', text: '<strong>Jake M.</strong> got <span class="hl">+300 retweets</span> from 🇬🇧 UK' },
    ];

    let activityIndex = 0;
    const activityTrack = document.getElementById('activityTrack');

    function timeAgo() {
        const opts = ['just now', '1m ago', '2m ago', '3m ago', '5m ago'];
        return opts[Math.floor(Math.random() * opts.length)];
    }

    function addActivity() {
        const event = activityEvents[activityIndex % activityEvents.length];
        activityIndex++;

        const item = document.createElement('div');
        item.className = 'activity-item';
        item.innerHTML = `
            <div class="activity-icon" style="background:${event.bg}20; border:1px solid ${event.bg}40">
                <i class="${event.icon}" style="color:${event.bg}"></i>
            </div>
            <div class="activity-text">${event.text}</div>
            <div class="activity-time">${timeAgo()}</div>
        `;

        activityTrack.insertBefore(item, activityTrack.firstChild);

        // Keep max 4 items visible
        while (activityTrack.children.length > 4) {
            activityTrack.removeChild(activityTrack.lastChild);
        }
    }

    // Init with 3 items, then keep adding
    addActivity(); addActivity(); addActivity();
    setInterval(addActivity, 2600);


    // ═══════════════════════════════════════════════════
    // ORDER COUNTER
    // ═══════════════════════════════════════════════════
    const counterEl = document.getElementById('order-counter');
    let count = 2100 + Math.floor(Math.random() * 400);
    counterEl.textContent = count.toLocaleString();
    setInterval(() => {
        if (Math.random() > 0.35) {
            count += Math.floor(Math.random() * 4) + 1;
            counterEl.textContent = count.toLocaleString();
        }
    }, 2800);


    // ═══════════════════════════════════════════════════
    // CUSTOM CURSOR
    // ═══════════════════════════════════════════════════
    const cursor = document.getElementById('cursor');
    const ring = document.getElementById('cursorRing');
    let mx = 0, my = 0, rx = 0, ry = 0;

    document.addEventListener('mousemove', e => {
        mx = e.clientX; my = e.clientY;
        cursor.style.left = mx - 5 + 'px';
        cursor.style.top = my - 5 + 'px';
    });

    function animRing() {
        rx += (mx - rx) * 0.12;
        ry += (my - ry) * 0.12;
        ring.style.left = rx - 18 + 'px';
        ring.style.top = ry - 18 + 'px';
        requestAnimationFrame(animRing);
    }
    animRing();

    document.querySelectorAll('a, button').forEach(el => {
        el.addEventListener('mouseenter', () => {
            cursor.style.transform = 'scale(2)';
            ring.style.transform = 'scale(1.4)';
            ring.style.opacity = '0.8';
        });
        el.addEventListener('mouseleave', () => {
            cursor.style.transform = 'scale(1)';
            ring.style.transform = 'scale(1)';
            ring.style.opacity = '0.5';
        });
    });

    // NAV SCROLL
    const nav = document.getElementById('nav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 60);
    });

    // REVEAL ON SCROLL
    const reveals = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => entry.target.classList.add('visible'), i * 80);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    reveals.forEach(el => observer.observe(el));

    // SMOOTH SCROLL
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
    </script>
</body>
</html>