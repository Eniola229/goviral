# 🚀 Booster - Social Media Growth Platform

<div align="center">

![Booster Logo](https://via.placeholder.com/150x150/4F46E5/FFFFFF?text=BOOSTER)

**Amplify Your Social Media Presence**

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://www.mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

[Features](#features) • [Demo](#demo) • [Installation](#installation) • [Documentation](#documentation) • [Support](#support)

</div>

---

## 📋 Table of Contents

- [About](#about)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Screenshots](#screenshots)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [API Integration](#api-integration)
- [Project Structure](#project-structure)
- [Contributing](#contributing)
- [License](#license)
- [Support](#support)

---

## 🎯 About

**Booster** is a comprehensive social media management platform that enables users to enhance their social media presence across multiple platforms. Built with Laravel and modern web technologies, Booster provides a seamless experience for purchasing social media engagement services including followers, likes, views, and more.

The platform integrates with professional SMM panel APIs to deliver real, high-quality engagement while maintaining a user-friendly interface and robust security features.

---

## ✨ Features

### 🔐 User Management
- **Secure Authentication** - JWT-based user authentication system
- **User Dashboard** - Personalized dashboard with real-time statistics
- **Profile Management** - Complete profile customization
- **Activity Logs** - Detailed transaction and order history

### 💰 Wallet System
- **Multi-Payment Integration** - Paystack, Flutterwave, Korapay, and Crypto support
- **Instant Top-ups** - Real-time balance updates
- **Automated Bonuses** - Tiered cashback system (5% - 20%)
- **Transaction History** - Complete financial tracking
- **Auto-Refunds** - Automatic refunds on failed orders

### 📦 Order Management
- **Multi-Platform Support** - Instagram, TikTok, Facebook, YouTube, Twitter, and more
- **Real-time Status Tracking** - Live order status updates
- **Auto-Status Sync** - Automatic synchronization with API provider
- **Bulk Ordering** - Place multiple orders efficiently
- **Refill Guarantee** - Automated refill requests for eligible services
- **Service Categories** - Organized by platform and service type

### 🎨 User Interface
- **Responsive Design** - Mobile-first Bootstrap 5 framework
- **Modern UI/UX** - Clean, intuitive interface
- **Dark Mode Ready** - Eye-friendly theme options
- **Real-time Updates** - AJAX-powered dynamic content
- **Interactive Charts** - Visual data representation

### 🛡️ Security Features
- **CSRF Protection** - Laravel's built-in security
- **XSS Prevention** - Input sanitization and validation
- **Secure Payments** - PCI-compliant payment gateways
- **Encrypted Storage** - Sensitive data encryption
- **Rate Limiting** - API abuse prevention
- **Activity Monitoring** - Comprehensive logging system

### 📊 Analytics & Reporting
- **Order Statistics** - Track completion rates and trends
- **Spending Analytics** - Monitor lifetime spending
- **Performance Metrics** - Dashboard KPIs
- **Export Capabilities** - Download transaction reports

---

## 🛠️ Tech Stack

### Backend
- **Framework:** Laravel 10.x
- **Language:** PHP 8.1+
- **Database:** MySQL 8.0+
- **Cache:** Redis (optional)
- **Queue:** Laravel Queue System

### Frontend
- **CSS Framework:** Bootstrap 5.3
- **JavaScript:** Vanilla JS + jQuery
- **Icons:** Feather Icons
- **Charts:** Chart.js (optional)

### Third-Party Services
- **Payment Gateways:**
  - Paystack
  - Flutterwave
  - Korapay
  - Cryptocurrency Payments
- **SMM Panel:** Ogaviral API
- **Email:** Laravel Mail / SMTP

---

## 📸 Screenshots

### Dashboard
![Dashboard](https://via.placeholder.com/800x400/4F46E5/FFFFFF?text=Dashboard+Screenshot)

### Order Management
![Orders](https://via.placeholder.com/800x400/10B981/FFFFFF?text=Order+Management)

### Wallet & Payments
![Wallet](https://via.placeholder.com/800x400/F59E0B/FFFFFF?text=Wallet+System)

---

## 🚀 Installation

### Prerequisites

Before you begin, ensure you have the following installed:
- PHP >= 8.1
- Composer
- MySQL >= 8.0
- Node.js & NPM (optional, for asset compilation)
- Git

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/booster.git
cd booster
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install NPM dependencies (if using)
npm install
```

### Step 3: Environment Configuration

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Environment Variables

Edit your `.env` file with your configuration:

```env
APP_NAME=Booster
APP_ENV=production
APP_KEY=base64:GENERATED_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourwebsite.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=booster_db
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# Ogaviral API Configuration
OGAVIRAL_API_KEY=your_ogaviral_api_key
OGAVIRAL_API_URL=https://ogaviral.com/api/v2

# Payment Gateway Keys
PAYSTACK_PUBLIC_KEY=your_paystack_public_key
PAYSTACK_SECRET_KEY=your_paystack_secret_key
FLUTTERWAVE_PUBLIC_KEY=your_flutterwave_public_key
FLUTTERWAVE_SECRET_KEY=your_flutterwave_secret_key
KORAPAY_PUBLIC_KEY=your_korapay_public_key
KORAPAY_SECRET_KEY=your_korapay_secret_key

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mail_username
MAIL_PASSWORD=your_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@booster.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 5: Database Setup

```bash
# Run migrations
php artisan migrate

# Seed the database (optional)
php artisan db:seed
```

### Step 6: Storage & Cache

```bash
# Create storage symbolic link
php artisan storage:link

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 7: Set Permissions

```bash
# Set proper permissions for storage and cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 8: Start the Application

**Development:**
```bash
php artisan serve
```
Visit: `http://localhost:8000`

**Production:**
Configure your web server (Apache/Nginx) to point to the `public` directory.

### Step 9: Setup Cron Job (Important)

For automatic order status updates, add this to your crontab:

```bash
# Edit crontab
crontab -e

# Add this line
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## ⚙️ Configuration

### Payment Gateways Setup

#### Paystack
1. Sign up at [Paystack](https://paystack.com)
2. Get your API keys from Settings > API Keys
3. Add to `.env` file

#### Flutterwave
1. Sign up at [Flutterwave](https://flutterwave.com)
2. Get your API keys from Settings > API
3. Add to `.env` file

#### Korapay
1. Sign up at [Korapay](https://korapay.com)
2. Get your API keys from Settings
3. Add to `.env` file

### SMM Panel Integration

1. Sign up for an account at [Ogaviral](https://ogaviral.com)
2. Navigate to API section
3. Generate your API key
4. Add to `.env` as `OGAVIRAL_API_KEY`

### Email Configuration

Configure your SMTP settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

---

## 📚 Usage

### For Users

1. **Registration**
   - Sign up with email and password
   - Verify email (if enabled)
   - Complete profile setup

2. **Adding Funds**
   - Navigate to Wallet section
   - Choose payment method
   - Enter amount and confirm
   - Receive instant credit + bonus

3. **Placing Orders**
   - Browse available services by platform
   - Select desired service
   - Enter target URL and quantity
   - Confirm and place order
   - Track order status in real-time

4. **Order Management**
   - View order history
   - Check order status
   - Request refills (for eligible orders)
   - Download invoices

### For Administrators

1. **Dashboard Access**
   - Access admin panel at `/admin`
   - View platform statistics
   - Monitor user activity

2. **User Management**
   - View all users
   - Manage user balances
   - Handle support tickets

3. **Order Oversight**
   - Monitor all orders
   - Handle refund requests
   - Review failed transactions

---

## 🔌 API Integration

### Ogaviral API Endpoints Used

#### Get Services
```php
POST https://ogaviral.com/api/v2
{
    "key": "YOUR_API_KEY",
    "action": "services"
}
```

#### Place Order
```php
POST https://ogaviral.com/api/v2
{
    "key": "YOUR_API_KEY",
    "action": "add",
    "service": "SERVICE_ID",
    "link": "TARGET_URL",
    "quantity": "QUANTITY"
}
```

#### Check Order Status
```php
POST https://ogaviral.com/api/v2
{
    "key": "YOUR_API_KEY",
    "action": "status",
    "order": "ORDER_ID"
}
```

#### Request Refill
```php
POST https://ogaviral.com/api/v2
{
    "key": "YOUR_API_KEY",
    "action": "refill",
    "order": "ORDER_ID"
}
```

---

## 📁 Project Structure

```
booster/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── UpdateOrderStatuses.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   ├── DashboardController.php
│   │   │   ├── OrderController.php
│   │   │   └── WalletController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Order.php
│   │   ├── Transaction.php
│   │   └── Logged.php
│   ├── Services/
│   │   ├── OgaviralService.php
│   │   └── WalletService.php
│   └── Notifications/
│       └── OrderPlaced.php
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   ├── css/
│   ├── js/
│   └── index.php
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   ├── components/
│   │   ├── dashboard.blade.php
│   │   ├── order/
│   │   │   ├── index.blade.php
│   │   │   └── new.blade.php
│   │   └── wallet/
│   └── lang/
├── routes/
│   ├── web.php
│   └── api.php
├── storage/
├── tests/
├── .env.example
├── composer.json
├── package.json
└── README.md
```

---

## 🤝 Contributing

We welcome contributions to Booster! Here's how you can help:

1. **Fork the Repository**
   ```bash
   git clone https://github.com/yourusername/booster.git
   ```

2. **Create a Feature Branch**
   ```bash
   git checkout -b feature/AmazingFeature
   ```

3. **Commit Your Changes**
   ```bash
   git commit -m 'Add some AmazingFeature'
   ```

4. **Push to the Branch**
   ```bash
   git push origin feature/AmazingFeature
   ```

5. **Open a Pull Request**

### Coding Standards
- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add comments for complex logic
- Update documentation as needed
- Write tests for new features

---

## 🐛 Bug Reports & Feature Requests

Found a bug or have a feature idea? Please create an issue on GitHub:

1. Check existing issues first
2. Use the issue template
3. Provide detailed information
4. Include steps to reproduce (for bugs)

---

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

```
MIT License

Copyright (c) 2025 Booster

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## 📞 Support

Need help? Here's how to get support:

- 📧 **Email:** support@booster.com
- 💬 **Discord:** [Join our community](https://discord.gg/booster)
- 📖 **Documentation:** [docs.booster.com](https://docs.booster.com)
- 🐛 **Issues:** [GitHub Issues](https://github.com/yourusername/booster/issues)

---

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [Bootstrap](https://getbootstrap.com) - UI Framework
- [Feather Icons](https://feathericons.com) - Beautiful icons
- [Ogaviral](https://ogaviral.com) - SMM Panel API
- All our amazing contributors!

---

## 🌟 Star History

[![Star History Chart](https://api.star-history.com/svg?repos=yourusername/booster&type=Date)](https://star-history.com/#yourusername/booster&Date)

---

## 📈 Roadmap

- [x] User authentication system
- [x] Wallet & payment integration
- [x] Order management system
- [x] Auto-status updates
- [ ] Admin dashboard
- [ ] Affiliate system
- [ ] Multi-language support
- [ ] API for developers
- [ ] Mobile app (iOS/Android)
- [ ] Advanced analytics
- [ ] AI-powered recommendations

---

<div align="center">

**Made with ❤️ by the Booster Team**


[⬆ Back to Top](#-booster---social-media-growth-platform)

</div>