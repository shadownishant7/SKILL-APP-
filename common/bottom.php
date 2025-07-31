        </div>
    </main>

    <!-- Bottom Navigation -->
    <nav class="theme-bg text-white border-t border-blue-600 fixed bottom-0 left-0 right-0 z-50">
        <div class="flex items-center justify-around py-2">
            <a href="index.php" class="flex flex-col items-center p-2 text-white hover:text-gray-200">
                <i class="fas fa-home text-lg mb-1"></i>
                <span class="text-xs">Home</span>
            </a>
            <a href="course.php" class="flex flex-col items-center p-2 text-white hover:text-gray-200">
                <i class="fas fa-book text-lg mb-1"></i>
                <span class="text-xs">Courses</span>
            </a>
            <?php if (isLoggedIn()): ?>
                <a href="mycourses.php" class="flex flex-col items-center p-2 text-white hover:text-gray-200">
                    <i class="fas fa-graduation-cap text-lg mb-1"></i>
                    <span class="text-xs">My Courses</span>
                </a>
            <?php endif; ?>
            <a href="help.php" class="flex flex-col items-center p-2 text-white hover:text-gray-200">
                <i class="fas fa-question-circle text-lg mb-1"></i>
                <span class="text-xs">Help</span>
            </a>
            <a href="profile.php" class="flex flex-col items-center p-2 text-white hover:text-gray-200">
                <i class="fas fa-user text-lg mb-1"></i>
                <span class="text-xs">Profile</span>
            </a>
        </div>
    </nav>

    <script>
        // Sidebar functionality
        const menuBtn = document.getElementById('menuBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const closeSidebar = document.getElementById('closeSidebar');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebarFunc() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        menuBtn.addEventListener('click', openSidebar);
        closeSidebar.addEventListener('click', closeSidebarFunc);
        overlay.addEventListener('click', closeSidebarFunc);

        // Disable right-click
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });

        // Disable text selection
        document.addEventListener('selectstart', function(e) {
            e.preventDefault();
        });

        // Disable pinch-to-zoom
        document.addEventListener('gesturestart', function(e) {
            e.preventDefault();
        });

        document.addEventListener('gesturechange', function(e) {
            e.preventDefault();
        });

        document.addEventListener('gestureend', function(e) {
            e.preventDefault();
        });
    </script>
</body>
</html>