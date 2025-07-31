# Skills With Nishant - Digital Course Selling Web App

A fully functional mobile-first digital course selling web application built with PHP, MySQL, HTML, Tailwind CSS, and JavaScript.

## 🚀 Features

### User Panel
- **User Authentication**: Login/Signup with AJAX
- **Course Browsing**: Search, filter, and view courses
- **Course Details**: Detailed course information with pricing
- **Payment Integration**: Razorpay payment gateway
- **Video Learning**: Custom video player with chapter navigation
- **Profile Management**: Edit personal information
- **My Courses**: View purchased courses
- **Help & Support**: FAQ and contact information

### Admin Panel
- **Dashboard**: Statistics and recent activity
- **Course Management**: Add, edit, delete courses
- **Chapter Management**: Organize course content
- **Video Management**: Upload and manage videos
- **Banner Management**: Upload promotional banners
- **User Management**: View user details and purchases
- **Order Management**: Track course purchases
- **Payment Tracking**: Monitor payment status

## 🛠️ Technologies Used

- **Backend**: PHP 8.0+
- **Database**: MySQL 8.0+
- **Frontend**: HTML5, Tailwind CSS, JavaScript
- **Icons**: Font Awesome
- **Payment**: Razorpay Integration
- **Video Player**: Custom HTML5 video player

## 📁 Project Structure

```
├── common/
│   ├── config.php          # Database configuration
│   ├── header.php          # User header component
│   └── bottom.php          # User bottom navigation
├── admin/
│   ├── common/
│   │   ├── header.php      # Admin header
│   │   └── bottom.php      # Admin footer
│   ├── login.php           # Admin login
│   ├── index.php           # Admin dashboard
│   ├── course.php          # Course management
│   ├── banner.php          # Banner management
│   ├── chapter.php         # Chapter management
│   ├── video.php           # Video management
│   ├── users.php           # User management
│   ├── orders.php          # Order management
│   ├── payments.php        # Payment tracking
│   ├── settings.php        # Admin settings
│   └── logout.php          # Admin logout
├── ajax/
│   ├── login.php           # User login handler
│   ├── signup.php          # User signup handler
│   ├── create_order.php    # Payment order creation
│   └── verify_payment.php  # Payment verification
├── uploads/                # File uploads directory
├── index.php               # Homepage
├── login.php               # User login page
├── course.php              # Course listing
├── course_detail.php       # Course details
├── buy.php                 # Purchase page
├── mycourses.php           # User's courses
├── watch.php               # Video learning
├── profile.php             # User profile
├── help.php                # Help & support
├── logout.php              # User logout
├── install.php             # Database installation
└── README.md               # This file
```

## 🚀 Installation

### Prerequisites
- PHP 8.0 or higher
- MySQL 8.0 or higher
- Web server (Apache/Nginx)
- cURL extension enabled

### Setup Instructions

1. **Clone or Download the Project**
   ```bash
   git clone <repository-url>
   cd skills-with-nishant
   ```

2. **Configure Database**
   - Create a MySQL database named `skillzup`
   - Update database credentials in `common/config.php`:
     ```php
     define('DB_HOST', '127.0.0.1');
     define('DB_USER', 'root');
     define('DB_PASS', 'root');
     define('DB_NAME', 'skillzup');
     ```

3. **Install Database**
   - Open your browser and navigate to: `http://localhost/install.php`
   - This will create all necessary tables and sample data

4. **Configure Razorpay (Optional)**
   - Sign up for a Razorpay account
   - Get your API keys from the Razorpay dashboard
   - Update the keys in `ajax/create_order.php` and `ajax/verify_payment.php`:
     ```php
     $razorpay_key_id = 'rzp_test_YOUR_KEY_ID';
     $razorpay_key_secret = 'YOUR_SECRET_KEY';
     ```

5. **Set File Permissions**
   ```bash
   chmod 755 uploads/
   chmod 755 uploads/videos/
   chmod 755 uploads/images/
   ```

6. **Access the Application**
   - User Panel: `http://localhost/index.php`
   - Admin Panel: `http://localhost/admin/login.php`

## 👤 Default Credentials

### Admin Panel
- **Username**: admin
- **Password**: admin123

## 🔧 Configuration

### Database Configuration
Edit `common/config.php` to match your database settings:
```php
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'skillzup');
```

### Razorpay Configuration
For production, replace test keys with live keys in:
- `ajax/create_order.php`
- `ajax/verify_payment.php`

## 📱 Features Overview

### Mobile-First Design
- Responsive layout optimized for mobile devices
- Touch-friendly interface
- Disabled right-click and text selection
- Custom video player with download protection

### Security Features
- Password hashing using PHP's built-in functions
- SQL injection prevention with prepared statements
- XSS protection with proper output escaping
- CSRF protection for forms
- Server-side payment verification

### User Experience
- AJAX-based login/signup
- Real-time search functionality
- Smooth animations and transitions
- Intuitive navigation
- Progress tracking for courses

### Admin Features
- Comprehensive dashboard with statistics
- Bulk operations for content management
- File upload with validation
- Order and payment tracking
- User management capabilities

## 🎯 Key Features

### Course Management
- Create courses with titles, descriptions, and pricing
- Upload course thumbnails
- Organize content into chapters
- Upload video files (MP4 format)
- Set course categories

### Payment System
- Integrated Razorpay payment gateway
- Secure payment processing
- Order tracking and management
- Payment verification

### Video Learning
- Custom video player
- Chapter-based navigation
- Progress tracking
- Download protection

### User Management
- User registration and authentication
- Profile management
- Purchase history
- Course access control

## 🔒 Security Measures

- **Input Validation**: All user inputs are validated and sanitized
- **SQL Injection Prevention**: Prepared statements used throughout
- **XSS Protection**: Output properly escaped
- **Session Security**: Secure session management
- **File Upload Security**: File type and size validation
- **Payment Security**: Server-side payment verification

## 📞 Support

For support and questions:
- Email: support@skillswithnishant.com
- Phone: +91 98765 43210

## 📄 License

This project is created for educational purposes. Please ensure you have proper licenses for any commercial use.

## 🤝 Contributing

Feel free to submit issues and enhancement requests!

---

**Note**: This is a fully functional course selling platform. Make sure to configure your payment gateway properly before going live. 
