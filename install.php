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
        email VARCHAR(100) UNIQUE NOT NULL,
        phone VARCHAR(15),
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'users' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Create admin table
    $sql = "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'admins' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Create categories table
    $sql = "CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'categories' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Create courses table
    $sql = "CREATE TABLE IF NOT EXISTS courses (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        thumbnail VARCHAR(255),
        mrp DECIMAL(10,2) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        category_id INT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (category_id) REFERENCES categories(id)
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
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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
        video_path VARCHAR(255) NOT NULL,
        duration VARCHAR(10),
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (chapter_id) REFERENCES chapters(id) ON DELETE CASCADE
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'videos' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Create banners table
    $sql = "CREATE TABLE IF NOT EXISTS banners (
        id INT AUTO_INCREMENT PRIMARY KEY,
        image VARCHAR(255) NOT NULL,
        link VARCHAR(255),
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'banners' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Create orders table
    $sql = "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        course_id INT NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        razorpay_order_id VARCHAR(100),
        razorpay_payment_id VARCHAR(100),
        status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (course_id) REFERENCES courses(id)
    )";
    
    if ($conn->query($sql) === TRUE) {
        echo "Table 'orders' created successfully<br>";
    } else {
        echo "Error creating table: " . $conn->error . "<br>";
    }
    
    // Insert default admin
    $admin_username = 'admin';
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    
    $sql = "INSERT IGNORE INTO admins (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $admin_username, $admin_password);
    
    if ($stmt->execute()) {
        echo "Default admin created (username: admin, password: admin123)<br>";
    } else {
        echo "Error creating admin: " . $stmt->error . "<br>";
    }
    
    // Insert sample categories
    $categories = ['Web Development', 'Mobile Development', 'Data Science', 'Design', 'Marketing'];
    foreach ($categories as $category) {
        $sql = "INSERT IGNORE INTO categories (name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $category);
        $stmt->execute();
    }
    echo "Sample categories created<br>";
    
    // Insert sample courses
    $sample_courses = [
        ['Complete Web Development Course', 'Learn HTML, CSS, JavaScript, PHP, and MySQL from scratch', 999, 499, 1],
        ['Mobile App Development', 'Build iOS and Android apps with React Native', 1299, 699, 2],
        ['Data Science Fundamentals', 'Master Python, Pandas, and Machine Learning', 1499, 799, 3],
        ['UI/UX Design Masterclass', 'Create beautiful and functional designs', 899, 449, 4],
        ['Digital Marketing Course', 'Learn SEO, Social Media, and Content Marketing', 799, 399, 5]
    ];
    
    foreach ($sample_courses as $course) {
        $sql = "INSERT IGNORE INTO courses (title, description, mrp, price, category_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssddd", $course[0], $course[1], $course[2], $course[3], $course[4]);
        $stmt->execute();
    }
    echo "Sample courses created<br>";
    
    echo "<br><strong>Installation completed successfully!</strong><br>";
    echo "You can now <a href='index.php'>visit the homepage</a> or <a href='admin/login.php'>login to admin panel</a><br>";
    echo "Admin credentials: admin / admin123";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>