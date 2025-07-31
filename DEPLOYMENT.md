# Deployment Guide - Skills With Nishant

## 🚀 Quick Setup Guide

### 1. Server Requirements
- **PHP**: 8.0 or higher
- **MySQL**: 8.0 or higher
- **Web Server**: Apache/Nginx
- **Extensions**: cURL, mysqli, fileinfo

### 2. Installation Steps

#### Step 1: Upload Files
```bash
# Clone or download the repository
git clone <your-repository-url>
cd skills-with-nishant

# Upload to your web server
# Or use FTP/SFTP to upload all files
```

#### Step 2: Database Setup
1. Create a MySQL database named `skillzup`
2. Update database credentials in `common/config.php`:
   ```php
   define('DB_HOST', 'your_host');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'skillzup');
   ```

#### Step 3: Install Database
1. Open your browser
2. Navigate to: `https://yourdomain.com/install.php`
3. This will create all tables and sample data

#### Step 4: Set Permissions
```bash
chmod 755 uploads/
chmod 755 uploads/videos/
chmod 755 uploads/images/
```

#### Step 5: Configure Payment (Optional)
1. Sign up for Razorpay account
2. Get your API keys from dashboard
3. Update keys in:
   - `ajax/create_order.php`
   - `ajax/verify_payment.php`

### 3. Access Your Application

#### User Panel
- **URL**: `https://yourdomain.com/index.php`
- **Features**: Browse courses, make purchases, watch videos

#### Admin Panel
- **URL**: `https://yourdomain.com/admin/login.php`
- **Username**: `admin`
- **Password**: `admin123`

### 4. Security Checklist

- [ ] Change default admin password
- [ ] Configure HTTPS
- [ ] Set up proper file permissions
- [ ] Configure Razorpay live keys
- [ ] Set up backup system
- [ ] Configure error logging

### 5. Production Optimizations

#### Performance
- Enable PHP OPcache
- Configure MySQL query cache
- Use CDN for static assets
- Enable Gzip compression

#### Security
- Use HTTPS only
- Set secure headers
- Regular security updates
- Database backup automation

### 6. Troubleshooting

#### Common Issues
1. **Database Connection Error**
   - Check database credentials
   - Ensure MySQL service is running

2. **Upload Errors**
   - Check file permissions
   - Verify upload directory exists

3. **Payment Issues**
   - Verify Razorpay configuration
   - Check server logs for errors

4. **Video Playback Issues**
   - Ensure MP4 format
   - Check file permissions
   - Verify video file integrity

### 7. Maintenance

#### Regular Tasks
- Monitor error logs
- Backup database weekly
- Update PHP and MySQL
- Review security settings

#### Backup Commands
```bash
# Database backup
mysqldump -u username -p skillzup > backup.sql

# File backup
tar -czf uploads_backup.tar.gz uploads/
```

### 8. Support

For technical support:
- Email: support@skillswithnishant.com
- Documentation: Check README.md
- Issues: Create GitHub issue

---

**Note**: Always test in a staging environment before deploying to production.