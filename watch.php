<?php
require_once 'common/config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

$course_id = $_GET['id'] ?? 0;

if (!$course_id) {
    redirect('mycourses.php');
}

// Get course details
$course_query = "SELECT * FROM courses WHERE id = ?";
$course_stmt = $conn->prepare($course_query);
$course_stmt->bind_param("i", $course_id);
$course_stmt->execute();
$course_result = $course_stmt->get_result();

if ($course_result->num_rows === 0) {
    redirect('mycourses.php');
}

$course = $course_result->fetch_assoc();

// Check if user has purchased this course
$purchase_query = "SELECT * FROM orders WHERE user_id = ? AND course_id = ? AND status = 'completed'";
$purchase_stmt = $conn->prepare($purchase_query);
$purchase_stmt->bind_param("ii", $_SESSION['user_id'], $course_id);
$purchase_stmt->execute();
$purchase_result = $purchase_stmt->get_result();

if ($purchase_result->num_rows === 0) {
    redirect('course_detail.php?id=' . $course_id);
}

// Get chapters and videos
$chapters_query = "SELECT * FROM chapters WHERE course_id = ? ORDER BY id ASC";
$chapters_stmt = $conn->prepare($chapters_query);
$chapters_stmt->bind_param("i", $course_id);
$chapters_stmt->execute();
$chapters_result = $chapters_stmt->get_result();

// Get first video for default playback
$first_video_query = "SELECT v.*, ch.title as chapter_title 
                      FROM videos v 
                      JOIN chapters ch ON v.chapter_id = ch.id 
                      WHERE ch.course_id = ? 
                      ORDER BY ch.id ASC, v.id ASC 
                      LIMIT 1";
$first_video_stmt = $conn->prepare($first_video_query);
$first_video_stmt->bind_param("i", $course_id);
$first_video_stmt->execute();
$first_video_result = $first_video_stmt->get_result();
$first_video = $first_video_result->fetch_assoc();
?>

<?php include 'common/header.php'; ?>

<!-- Course Header -->
<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-800 mb-1"><?php echo htmlspecialchars($course['title']); ?></h1>
            <p class="text-gray-600">Continue your learning journey</p>
        </div>
        <a href="mycourses.php" class="text-blue-600 hover:text-blue-700">
            <i class="fas fa-arrow-left mr-1"></i>
            Back to Courses
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Video Player -->
    <div class="lg:col-span-2">
        <div class="bg-black rounded-lg overflow-hidden mb-4">
            <div id="videoPlayer" class="relative w-full" style="aspect-ratio: 16/9;">
                <?php if ($first_video): ?>
                    <video id="video" controls class="w-full h-full" controlsList="nodownload" oncontextmenu="return false;">
                        <source src="uploads/videos/<?php echo $first_video['filename']; ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php else: ?>
                    <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                        <div class="text-center text-white">
                            <i class="fas fa-video text-4xl mb-2"></i>
                            <p>No videos available</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Video Info -->
        <?php if ($first_video): ?>
            <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                <h3 id="videoTitle" class="text-lg font-semibold text-gray-800 mb-2">
                    <?php echo htmlspecialchars($first_video['title']); ?>
                </h3>
                <p id="chapterTitle" class="text-sm text-gray-600 mb-2">
                    <?php echo htmlspecialchars($first_video['chapter_title']); ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Course Content -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-md p-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Course Content</h3>
            
            <div class="space-y-2 max-h-96 overflow-y-auto">
                <?php 
                $chapters_result->data_seek(0);
                while ($chapter = $chapters_result->fetch_assoc()): 
                ?>
                    <div class="border border-gray-200 rounded-lg">
                        <div class="p-3 bg-gray-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-folder text-blue-600 mr-2"></i>
                                    <h4 class="font-medium text-gray-800"><?php echo htmlspecialchars($chapter['title']); ?></h4>
                                </div>
                                <button class="chapter-toggle text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="chapter-videos hidden">
                            <?php
                            $videos_query = "SELECT * FROM videos WHERE chapter_id = ? ORDER BY id ASC";
                            $videos_stmt = $conn->prepare($videos_query);
                            $videos_stmt->bind_param("i", $chapter['id']);
                            $videos_stmt->execute();
                            $videos_result = $videos_stmt->get_result();
                            ?>
                            
                            <div class="p-3 space-y-2">
                                <?php while ($video = $videos_result->fetch_assoc()): ?>
                                    <div class="video-item cursor-pointer p-2 rounded hover:bg-gray-100 transition-colors" 
                                         data-video-id="<?php echo $video['id']; ?>"
                                         data-video-filename="<?php echo $video['filename']; ?>"
                                         data-video-title="<?php echo htmlspecialchars($video['title']); ?>"
                                         data-chapter-title="<?php echo htmlspecialchars($chapter['title']); ?>">
                                        <div class="flex items-center">
                                            <i class="fas fa-play-circle text-blue-600 mr-2"></i>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-800"><?php echo htmlspecialchars($video['title']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Chapter toggle functionality
document.querySelectorAll('.chapter-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const chapter = this.closest('.border');
        const videos = chapter.querySelector('.chapter-videos');
        const icon = this.querySelector('i');
        
        videos.classList.toggle('hidden');
        icon.classList.toggle('fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
    });
});

// Video selection functionality
document.querySelectorAll('.video-item').forEach(item => {
    item.addEventListener('click', function() {
        const videoId = this.dataset.videoId;
        const videoFilename = this.dataset.videoFilename;
        const videoTitle = this.dataset.videoTitle;
        const chapterTitle = this.dataset.chapterTitle;
        
        // Update video player
        const video = document.getElementById('video');
        const videoTitleElement = document.getElementById('videoTitle');
        const chapterTitleElement = document.getElementById('chapterTitle');
        
        if (video && videoFilename) {
            video.src = 'uploads/videos/' + videoFilename;
            video.load();
            video.play();
        }
        
        if (videoTitleElement) {
            videoTitleElement.textContent = videoTitle;
        }
        
        if (chapterTitleElement) {
            chapterTitleElement.textContent = chapterTitle;
        }
        
        // Update active state
        document.querySelectorAll('.video-item').forEach(item => {
            item.classList.remove('bg-blue-100', 'border-blue-300');
        });
        this.classList.add('bg-blue-100', 'border-blue-300');
    });
});

// Disable right-click on video
document.addEventListener('contextmenu', function(e) {
    if (e.target.tagName === 'VIDEO') {
        e.preventDefault();
    }
});

// Disable video download
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && (e.key === 's' || e.key === 'S')) {
        e.preventDefault();
    }
});
</script>

<style>
/* Custom scrollbar for course content */
.overflow-y-auto::-webkit-scrollbar {
    width: 4px;
}
.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
}
.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 2px;
}
.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>

<?php include 'common/bottom.php'; ?>