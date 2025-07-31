<?php
require_once 'common/config.php';

$course_id = $_GET['id'] ?? 0;

if (!$course_id) {
    redirect('course.php');
}

// Get course details
$query = "SELECT c.*, cat.name as category_name 
          FROM courses c 
          LEFT JOIN categories cat ON c.category_id = cat.id 
          WHERE c.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    redirect('course.php');
}

$course = $result->fetch_assoc();

// Check if user has purchased this course
$is_purchased = false;
if (isLoggedIn()) {
    $purchase_query = "SELECT * FROM orders WHERE user_id = ? AND course_id = ? AND status = 'completed'";
    $purchase_stmt = $conn->prepare($purchase_query);
    $purchase_stmt->bind_param("ii", $_SESSION['user_id'], $course_id);
    $purchase_stmt->execute();
    $purchase_result = $purchase_stmt->get_result();
    $is_purchased = $purchase_result->num_rows > 0;
}

// Get course chapters count
$chapters_query = "SELECT COUNT(*) as chapter_count FROM chapters WHERE course_id = ?";
$chapters_stmt = $conn->prepare($chapters_query);
$chapters_stmt->bind_param("i", $course_id);
$chapters_stmt->execute();
$chapters_result = $chapters_stmt->get_result();
$chapter_count = $chapters_result->fetch_assoc()['chapter_count'];

// Get total videos count
$videos_query = "SELECT COUNT(*) as video_count FROM videos v 
                 JOIN chapters ch ON v.chapter_id = ch.id 
                 WHERE ch.course_id = ?";
$videos_stmt = $conn->prepare($videos_query);
$videos_stmt->bind_param("i", $course_id);
$videos_stmt->execute();
$videos_result = $videos_stmt->get_result();
$video_count = $videos_result->fetch_assoc()['video_count'];
?>

<?php include 'common/header.php'; ?>

<!-- Course Header -->
<div class="mb-6">
    <div class="relative">
        <img src="<?php echo $course['thumbnail'] ?: 'https://via.placeholder.com/800x400/4F46E5/FFFFFF?text=Course'; ?>" 
             alt="<?php echo htmlspecialchars($course['title']); ?>"
             class="w-full h-64 object-cover rounded-lg">
        <div class="absolute inset-0 bg-black bg-opacity-40 rounded-lg"></div>
        <div class="absolute bottom-4 left-4 right-4">
            <h1 class="text-2xl font-bold text-white mb-2"><?php echo htmlspecialchars($course['title']); ?></h1>
            <p class="text-white text-sm opacity-90"><?php echo htmlspecialchars($course['category_name']); ?></p>
        </div>
    </div>
</div>

<!-- Course Info -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600"><?php echo $chapter_count; ?></div>
                <div class="text-sm text-gray-600">Chapters</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600"><?php echo $video_count; ?></div>
                <div class="text-sm text-gray-600">Videos</div>
            </div>
        </div>
        
        <div class="text-right">
            <div class="flex items-center space-x-2 mb-1">
                <span class="text-3xl font-bold text-blue-600">₹<?php echo $course['price']; ?></span>
                <span class="text-lg text-gray-500 line-through">₹<?php echo $course['mrp']; ?></span>
            </div>
            <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded">
                <?php 
                $discount = round((($course['mrp'] - $course['price']) / $course['mrp']) * 100);
                echo $discount . '% OFF';
                ?>
            </span>
        </div>
    </div>
    
    <!-- Description -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Description</h3>
        <p class="text-gray-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
    </div>
    
    <!-- Course Features -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-play-circle text-blue-600 mr-3"></i>
            <span class="text-gray-700">HD Video Lectures</span>
        </div>
        <div class="flex items-center">
            <i class="fas fa-download text-blue-600 mr-3"></i>
            <span class="text-gray-700">Downloadable Resources</span>
        </div>
        <div class="flex items-center">
            <i class="fas fa-certificate text-blue-600 mr-3"></i>
            <span class="text-gray-700">Certificate of Completion</span>
        </div>
        <div class="flex items-center">
            <i class="fas fa-clock text-blue-600 mr-3"></i>
            <span class="text-gray-700">Lifetime Access</span>
        </div>
    </div>
</div>

<!-- Sticky Bottom Button -->
<div class="fixed bottom-20 left-0 right-0 bg-white border-t border-gray-200 p-4 z-40">
    <div class="container mx-auto px-4">
        <?php if ($is_purchased): ?>
            <a href="watch.php?id=<?php echo $course_id; ?>" 
               class="w-full bg-green-600 text-white py-3 px-6 rounded-lg text-center font-semibold hover:bg-green-700 transition duration-200 flex items-center justify-center">
                <i class="fas fa-play mr-2"></i>
                Start Learning
            </a>
        <?php else: ?>
            <a href="buy.php?id=<?php echo $course_id; ?>" 
               class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg text-center font-semibold hover:bg-blue-700 transition duration-200 flex items-center justify-center">
                <i class="fas fa-shopping-cart mr-2"></i>
                Buy Now - ₹<?php echo $course['price']; ?>
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Course Content Preview -->
<?php if ($chapter_count > 0): ?>
<div class="bg-white rounded-lg shadow-md p-6 mb-20">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Course Content</h3>
    
    <?php
    $chapters_query = "SELECT * FROM chapters WHERE course_id = ? ORDER BY sort_order ASC, id ASC";
    $chapters_stmt = $conn->prepare($chapters_query);
    $chapters_stmt->bind_param("i", $course_id);
    $chapters_stmt->execute();
    $chapters_result = $chapters_stmt->get_result();
    ?>
    
    <div class="space-y-3">
        <?php while ($chapter = $chapters_result->fetch_assoc()): ?>
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-folder text-blue-600 mr-3"></i>
                        <h4 class="font-medium text-gray-800"><?php echo htmlspecialchars($chapter['title']); ?></h4>
                    </div>
                    <span class="text-sm text-gray-500">
                        <?php
                        $video_count_query = "SELECT COUNT(*) as count FROM videos WHERE chapter_id = ?";
                        $video_count_stmt = $conn->prepare($video_count_query);
                        $video_count_stmt->bind_param("i", $chapter['id']);
                        $video_count_stmt->execute();
                        $video_count_result = $video_count_stmt->get_result();
                        $chapter_video_count = $video_count_result->fetch_assoc()['count'];
                        echo $chapter_video_count . ' video' . ($chapter_video_count != 1 ? 's' : '');
                        ?>
                    </span>
                </div>
                
                <?php if ($is_purchased): ?>
                    <?php
                    $videos_query = "SELECT * FROM videos WHERE chapter_id = ? ORDER BY sort_order ASC, id ASC LIMIT 3";
                    $videos_stmt = $conn->prepare($videos_query);
                    $videos_stmt->bind_param("i", $chapter['id']);
                    $videos_stmt->execute();
                    $videos_result = $videos_stmt->get_result();
                    ?>
                    <div class="mt-3 space-y-2">
                        <?php while ($video = $videos_result->fetch_assoc()): ?>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-play-circle text-gray-400 mr-2"></i>
                                <span><?php echo htmlspecialchars($video['title']); ?></span>
                                <?php if ($video['duration']): ?>
                                    <span class="ml-auto text-xs text-gray-500"><?php echo $video['duration']; ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                        <?php if ($chapter_video_count > 3): ?>
                            <div class="text-sm text-gray-500 italic">
                                +<?php echo $chapter_video_count - 3; ?> more videos
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<?php endif; ?>

<?php include 'common/bottom.php'; ?>