<?php
// Database configuration
$host = '127.0.0.1';
$user = 'root';
$pass = 'root';

try {
    // Create connection
    $conn = new mysqli($host, $user, $pass);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS skillzup";
    if ($conn->query($sql) === TRUE) {
        echo "Database 'skillzup' created successfully<br>";
    } else {
        echo "Error creating database: " . $conn->error . "<br>";
    }
    
    // Select database
    $conn->select_db('skillzup');
    
    // Create users table
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'users' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Create admin table
    $sql = "CREATE TABLE IF NOT EXISTS admin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'admin' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Create banners table
    $sql = "CREATE TABLE IF NOT EXISTS banners (
        id INT AUTO_INCREMENT PRIMARY KEY,
        image VARCHAR(255) NOT NULL,
        link VARCHAR(255)
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'banners' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Create courses table
    $sql = "CREATE TABLE IF NOT EXISTS courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        mrp DECIMAL(10,2) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        description TEXT,
        image VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'courses' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Create chapters table
    $sql = "CREATE TABLE IF NOT EXISTS chapters (
        id INT AUTO_INCREMENT PRIMARY KEY,
        course_id INT NOT NULL,
        title VARCHAR(200) NOT NULL,
        FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'chapters' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Create videos table
    $sql = "CREATE TABLE IF NOT EXISTS videos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        chapter_id INT NOT NULL,
        title VARCHAR(200) NOT NULL,
        filename VARCHAR(255) NOT NULL,
        FOREIGN KEY (chapter_id) REFERENCES chapters(id) ON DELETE CASCADE
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'videos' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Create orders table
    $sql = "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        course_id INT NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
        razorpay_order_id VARCHAR(100),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (course_id) REFERENCES courses(id)
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'orders' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Create settings table
    $sql = "CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        app_name VARCHAR(100) NOT NULL,
        razorpay_key VARCHAR(100),
        razorpay_secret VARCHAR(100),
        support_email VARCHAR(100),
        support_phone VARCHAR(15)
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'settings' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Insert default admin
    $admin_username = 'admin';
    $admin_password = password_hash('123456', PASSWORD_DEFAULT);
    
    $sql = "INSERT IGNORE INTO admin (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $admin_username, $admin_password);
    
    if ($stmt->execute()) {
        echo "Default admin created (username: admin, password: 123456)<br>";
    } else {
        echo "Error creating admin: " . $stmt->error . "<br>";
    }
    
    // Insert default settings
    $app_name = 'Skills With Nishant';
    $razorpay_key = 'rzp_test_YOUR_KEY_ID';
    $razorpay_secret = 'YOUR_SECRET_KEY';
    $support_email = 'support@skillswithnishant.com';
    $support_phone = '+91 98765 43210';
    
    $sql = "INSERT IGNORE INTO settings (app_name, razorpay_key, razorpay_secret, support_email, support_phone) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $app_name, $razorpay_key, $razorpay_secret, $support_email, $support_phone);
    
    if ($stmt->execute()) {
        echo "Default settings created<br>";
    } else {
        echo "Error creating settings: " . $stmt->error . "<br>";
    }
    
    // Create upload directories
    $directories = [
        'uploads',
        'uploads/banners',
        'uploads/courses',
        'uploads/videos'
    ];
    
    foreach ($directories as $dir) {
        if (!file_exists($dir)) {
            if (mkdir($dir, 0755, true)) {
                echo "Directory '$dir' created successfully<br>";
            } else {
                echo "Error creating directory '$dir'<br>";
            }
        } else {
            echo "Directory '$dir' already exists<br>";
        }
    }
    
    echo "<br><strong>Installation completed successfully!</strong><br>";
    echo "You can now <a href='login.php'>login to admin panel</a><br>";
    echo "Admin credentials: admin / 123456";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>