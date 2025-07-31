<?php
require_once 'common/config.php';

// Get banners
$banners_query = "SELECT * FROM banners ORDER BY sort_order ASC, id DESC";
$banners_result = $conn->query($banners_query);

// Get latest courses
$courses_query = "SELECT c.*, cat.name as category_name 
                 FROM courses c 
                 LEFT JOIN categories cat ON c.category_id = cat.id 
                 ORDER BY c.created_at DESC 
                 LIMIT 10";
$courses_result = $conn->query($courses_query);
?>

<?php include 'common/header.php'; ?>

<!-- Search Bar -->
<div class="mb-6">
    <div class="relative">
        <input type="text" id="searchInput" placeholder="Search for courses..." 
               class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
    </div>
</div>

<!-- Banner Slider -->
<?php if ($banners_result->num_rows > 0): ?>
<div class="mb-8">
    <div id="bannerSlider" class="relative overflow-hidden rounded-lg">
        <div class="flex transition-transform duration-500 ease-in-out">
            <?php while ($banner = $banners_result->fetch_assoc()): ?>
                <div class="w-full flex-shrink-0">
                    <img src="<?php echo $banner['image']; ?>" alt="Banner" 
                         class="w-full h-48 object-cover">
                </div>
            <?php endwhile; ?>
        </div>
        
        <!-- Navigation Dots -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <?php 
            $banners_result->data_seek(0);
            $banner_count = $banners_result->num_rows;
            for ($i = 0; $i < $banner_count; $i++): 
            ?>
                <button class="w-2 h-2 bg-white bg-opacity-50 rounded-full banner-dot" data-index="<?php echo $i; ?>"></button>
            <?php endfor; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Latest Courses -->
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-800">Latest Courses</h2>
        <a href="course.php" class="text-blue-600 hover:text-blue-700 text-sm">View All</a>
    </div>
    
    <div class="overflow-x-auto">
        <div class="flex space-x-4 pb-4" style="scrollbar-width: none; -ms-overflow-style: none;">
            <?php while ($course = $courses_result->fetch_assoc()): ?>
                <div class="w-64 flex-shrink-0">
                    <a href="course_detail.php?id=<?php echo $course['id']; ?>" class="block">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="aspect-w-16 aspect-h-9">
                                <img src="<?php echo $course['thumbnail'] ?: 'https://via.placeholder.com/300x200/4F46E5/FFFFFF?text=Course'; ?>" 
                                     alt="<?php echo htmlspecialchars($course['title']); ?>"
                                     class="w-full h-32 object-cover">
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">
                                    <?php echo htmlspecialchars($course['title']); ?>
                                </h3>
                                <p class="text-sm text-gray-600 mb-2"><?php echo htmlspecialchars($course['category_name']); ?></p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-lg font-bold text-blue-600">₹<?php echo $course['price']; ?></span>
                                        <span class="text-sm text-gray-500 line-through">₹<?php echo $course['mrp']; ?></span>
                                    </div>
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">
                                        <?php 
                                        $discount = round((($course['mrp'] - $course['price']) / $course['mrp']) * 100);
                                        echo $discount . '% OFF';
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</div>

<!-- Categories -->
<div class="mb-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4">Categories</h2>
    <div class="grid grid-cols-2 gap-4">
        <?php
        $categories_query = "SELECT * FROM categories ORDER BY name";
        $categories_result = $conn->query($categories_query);
        while ($category = $categories_result->fetch_assoc()):
        ?>
            <a href="course.php?category=<?php echo $category['id']; ?>" 
               class="bg-white p-4 rounded-lg shadow-md text-center hover:shadow-lg transition-shadow">
                <i class="fas fa-book text-2xl text-blue-600 mb-2"></i>
                <h3 class="font-medium text-gray-800"><?php echo htmlspecialchars($category['name']); ?></h3>
            </a>
        <?php endwhile; ?>
    </div>
</div>

<!-- Welcome Message for Non-logged Users -->
<?php if (!isLoggedIn()): ?>
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
    <div class="flex items-center">
        <i class="fas fa-info-circle text-blue-600 text-xl mr-3"></i>
        <div>
            <h3 class="font-semibold text-blue-800 mb-1">Welcome to Skills With Nishant!</h3>
            <p class="text-blue-700 text-sm">Login to access your courses and track your progress.</p>
        </div>
    </div>
    <div class="mt-3">
        <a href="login.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition duration-200">
            Login Now
        </a>
    </div>
</div>
<?php endif; ?>

<script>
// Banner slider functionality
const bannerSlider = document.getElementById('bannerSlider');
const bannerDots = document.querySelectorAll('.banner-dot');
let currentSlide = 0;
const totalSlides = bannerDots.length;

function showSlide(index) {
    if (bannerSlider) {
        const slideContainer = bannerSlider.querySelector('.flex');
        slideContainer.style.transform = `translateX(-${index * 100}%)`;
        
        // Update dots
        bannerDots.forEach((dot, i) => {
            dot.classList.toggle('bg-opacity-100', i === index);
            dot.classList.toggle('bg-opacity-50', i !== index);
        });
        
        currentSlide = index;
    }
}

// Auto-slide every 5 seconds
if (totalSlides > 1) {
    setInterval(() => {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }, 5000);
}

// Dot navigation
bannerDots.forEach((dot, index) => {
    dot.addEventListener('click', () => showSlide(index));
});

// Search functionality
const searchInput = document.getElementById('searchInput');
searchInput.addEventListener('input', function() {
    const query = this.value.trim();
    if (query.length > 2) {
        // You can implement AJAX search here
        // For now, redirect to course page with search parameter
        setTimeout(() => {
            if (this.value.trim() === query) {
                window.location.href = `course.php?search=${encodeURIComponent(query)}`;
            }
        }, 1000);
    }
});
</script>

<?php include 'common/bottom.php'; ?>