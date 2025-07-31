<?php
require_once 'common/config.php';

// Get filters
$search_query = $_GET['search'] ?? '';
$sort_by = $_GET['sort'] ?? 'latest';

// Build query
$where_conditions = [];
$params = [];
$types = '';

if ($search_query) {
    $where_conditions[] = "(title LIKE ? OR description LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
    $types .= 'ss';
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Sort options
$sort_clause = match($sort_by) {
    'price_low' => 'ORDER BY price ASC',
    'price_high' => 'ORDER BY price DESC',
    'latest' => 'ORDER BY created_at DESC',
    default => 'ORDER BY created_at DESC'
};

$query = "SELECT * FROM courses $where_clause $sort_clause";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$courses_result = $stmt->get_result();
?>

<?php include 'common/header.php'; ?>

<!-- Filters -->
<div class="mb-6 bg-white rounded-lg shadow-md p-4">
    <form method="GET" class="space-y-4">
        <!-- Search -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search_query); ?>" 
                   placeholder="Search courses..." 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Sort -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="latest" <?php echo $sort_by == 'latest' ? 'selected' : ''; ?>>Latest</option>
                    <option value="price_low" <?php echo $sort_by == 'price_low' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_high" <?php echo $sort_by == 'price_high' ? 'selected' : ''; ?>>Price: High to Low</option>
                </select>
            </div>
        </div>
        
        <div class="flex space-x-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Apply Filters
            </button>
            <a href="course.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                Clear
            </a>
        </div>
    </form>
</div>

<!-- Results Count -->
<div class="mb-4">
    <p class="text-gray-600">
        <?php echo $courses_result->num_rows; ?> course<?php echo $courses_result->num_rows != 1 ? 's' : ''; ?> found
        <?php if ($search_query): ?>
            for "<?php echo htmlspecialchars($search_query); ?>"
        <?php endif; ?>
    </p>
</div>

<!-- Course Grid -->
<?php if ($courses_result->num_rows > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($course = $courses_result->fetch_assoc()): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <a href="course_detail.php?id=<?php echo $course['id']; ?>" class="block">
                    <div class="aspect-w-16 aspect-h-9">
                        <img src="<?php echo $course['image'] ?: 'https://via.placeholder.com/300x200/0284C7/FFFFFF?text=Course'; ?>" 
                             alt="<?php echo htmlspecialchars($course['title']); ?>"
                             class="w-full h-48 object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2">
                            <?php echo htmlspecialchars($course['title']); ?>
                        </h3>
                        <p class="text-sm text-gray-700 mb-3 line-clamp-2">
                            <?php echo htmlspecialchars(substr($course['description'], 0, 100)) . (strlen($course['description']) > 100 ? '...' : ''); ?>
                        </p>
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
                </a>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <!-- No Results -->
    <div class="text-center py-12">
        <i class="fas fa-search text-4xl text-gray-400 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">No courses found</h3>
        <p class="text-gray-500 mb-4">
            <?php if ($search_query): ?>
                No courses match your search for "<?php echo htmlspecialchars($search_query); ?>"
            <?php else: ?>
                No courses available at the moment
            <?php endif; ?>
        </p>
        <a href="course.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
            View All Courses
        </a>
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