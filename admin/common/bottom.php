        </div>
    </main>

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
    </script>
</body>
</html>