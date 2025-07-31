<?php
require_once 'common/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

// Get user's purchased courses
$query = "SELECT c.*, o.created_at as purchase_date, o.status as order_status
          FROM courses c 
          JOIN orders o ON c.id = o.course_id 
          WHERE o.user_id = ? AND o.status = 'completed'
          ORDER BY o.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$courses_result = $stmt->get_result();
?>

<?php include 'common/header.php'; ?>

<!-- Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-2">My Courses</h1>
    <p class="text-gray-600">Continue learning from where you left off</p>
</div>

<?php if ($courses_result->num_rows > 0): ?>
    <!-- Course Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($course = $courses_result->fetch_assoc()): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="relative">
                    <img src="<?php echo $course['thumbnail'] ?: 'https://via.placeholder.com/300x200/4F46E5/FFFFFF?text=Course'; ?>" 
                         alt="<?php echo htmlspecialchars($course['title']); ?>"
                         class="w-full h-48 object-cover">
                    <div class="absolute top-2 right-2">
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                            Purchased
                        </span>
                    </div>
                </div>
                
                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">
                        <?php echo htmlspecialchars($course['title']); ?>
                    </h3>
                    
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm text-gray-600">
                            Purchased: <?php echo date('M d, Y', strtotime($course['purchase_date'])); ?>
                        </span>
                        <span class="text-sm font-medium text-green-600">
                            ₹<?php echo $course['price']; ?>
                        </span>
                    </div>
                    
                    <!-- Progress Bar (Placeholder) -->
                    <div class="mb-3">
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-1">
                            <span>Progress</span>
                            <span>0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                    
                    <a href="watch.php?id=<?php echo $course['id']; ?>" 
                       class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg text-center font-medium hover:bg-blue-700 transition duration-200 flex items-center justify-center">
                        <i class="fas fa-play mr-2"></i>
                        Continue Learning
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <!-- Empty State -->
    <div class="text-center py-12">
        <i class="fas fa-graduation-cap text-4xl text-gray-400 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">No courses yet</h3>
        <p class="text-gray-500 mb-6">Start your learning journey by purchasing your first course</p>
        <a href="course.php" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200">
            Browse Courses
        </a>
    </div>
<?php endif; ?>

<!-- Learning Tips -->
<?php if ($courses_result->num_rows > 0): ?>
<div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
    <h3 class="text-lg font-semibold text-blue-800 mb-3">Learning Tips</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex items-start">
            <i class="fas fa-clock text-blue-600 mt-1 mr-3"></i>
            <div>
                <h4 class="font-medium text-blue-800 mb-1">Set a Schedule</h4>
                <p class="text-blue-700 text-sm">Dedicate 30 minutes daily to make steady progress</p>
            </div>
        </div>
        <div class="flex items-start">
            <i class="fas fa-notes-medical text-blue-600 mt-1 mr-3"></i>
            <div>
                <h4 class="font-medium text-blue-800 mb-1">Take Notes</h4>
                <p class="text-blue-700 text-sm">Write down key concepts to reinforce learning</p>
            </div>
        </div>
        <div class="flex items-start">
            <i class="fas fa-users text-blue-600 mt-1 mr-3"></i>
            <div>
                <h4 class="font-medium text-blue-800 mb-1">Practice Regularly</h4>
                <p class="text-blue-700 text-sm">Apply what you learn through hands-on projects</p>
            </div>
        </div>
        <div class="flex items-start">
            <i class="fas fa-question-circle text-blue-600 mt-1 mr-3"></i>
            <div>
                <h4 class="font-medium text-blue-800 mb-1">Ask Questions</h4>
                <p class="text-blue-700 text-sm">Don't hesitate to seek help when needed</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php include 'common/bottom.php'; ?>