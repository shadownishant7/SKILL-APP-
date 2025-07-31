<?php 
require_once 'common/config.php';
$settings = getSettings();
$support_email = $settings['support_email'] ?? 'support@skillswithnishant.com';
$support_phone = $settings['support_phone'] ?? '+91 98765 43210';
?>

<?php include 'common/header.php'; ?>

<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Help & Support</h1>
        <p class="text-gray-600">Get help with your learning journey</p>
    </div>

    <!-- Contact Information -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact Us</h3>
        <div class="space-y-4">
            <div class="flex items-center">
                <i class="fas fa-envelope text-blue-600 mr-3 text-xl"></i>
                <div>
                    <p class="font-medium text-gray-800">Email Support</p>
                    <p class="text-gray-600"><?php echo htmlspecialchars($support_email); ?></p>
                </div>
            </div>
            <div class="flex items-center">
                <i class="fas fa-phone text-blue-600 mr-3 text-xl"></i>
                <div>
                    <p class="font-medium text-gray-800">Phone Support</p>
                    <p class="text-gray-600"><?php echo htmlspecialchars($support_phone); ?></p>
                </div>
            </div>
            <div class="flex items-center">
                <i class="fas fa-clock text-blue-600 mr-3 text-xl"></i>
                <div>
                    <p class="font-medium text-gray-800">Support Hours</p>
                    <p class="text-gray-600">Monday - Friday: 9:00 AM - 6:00 PM IST</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Frequently Asked Questions</h3>
        <div class="space-y-4">
            <div class="border border-gray-200 rounded-lg">
                <button class="faq-toggle w-full p-4 text-left flex items-center justify-between hover:bg-gray-50">
                    <span class="font-medium text-gray-800">How do I purchase a course?</span>
                    <i class="fas fa-chevron-down text-gray-500"></i>
                </button>
                <div class="faq-content hidden p-4 border-t border-gray-200">
                    <p class="text-gray-700">Browse our course catalog, select a course you're interested in, and click "Buy Now". You'll be redirected to our secure payment gateway powered by Razorpay to complete your purchase.</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg">
                <button class="faq-toggle w-full p-4 text-left flex items-center justify-between hover:bg-gray-50">
                    <span class="font-medium text-gray-800">Can I access my courses offline?</span>
                    <i class="fas fa-chevron-down text-gray-500"></i>
                </button>
                <div class="faq-content hidden p-4 border-t border-gray-200">
                    <p class="text-gray-700">Currently, our courses are available online only. However, you can download course materials and resources for offline reference.</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg">
                <button class="faq-toggle w-full p-4 text-left flex items-center justify-between hover:bg-gray-50">
                    <span class="font-medium text-gray-800">What payment methods do you accept?</span>
                    <i class="fas fa-chevron-down text-gray-500"></i>
                </button>
                <div class="faq-content hidden p-4 border-t border-gray-200">
                    <p class="text-gray-700">We accept all major credit cards, debit cards, net banking, UPI, and digital wallets through our secure payment partner Razorpay.</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg">
                <button class="faq-toggle w-full p-4 text-left flex items-center justify-between hover:bg-gray-50">
                    <span class="font-medium text-gray-800">Do you offer refunds?</span>
                    <i class="fas fa-chevron-down text-gray-500"></i>
                </button>
                <div class="faq-content hidden p-4 border-t border-gray-200">
                    <p class="text-gray-700">We offer a 7-day money-back guarantee. If you're not satisfied with your course, contact our support team within 7 days of purchase for a full refund.</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg">
                <button class="faq-toggle w-full p-4 text-left flex items-center justify-between hover:bg-gray-50">
                    <span class="font-medium text-gray-800">How long do I have access to my courses?</span>
                    <i class="fas fa-chevron-down text-gray-500"></i>
                </button>
                <div class="faq-content hidden p-4 border-t border-gray-200">
                    <p class="text-gray-700">You have lifetime access to all courses you purchase. You can watch the videos as many times as you want, at your own pace.</p>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg">
                <button class="faq-toggle w-full p-4 text-left flex items-center justify-between hover:bg-gray-50">
                    <span class="font-medium text-gray-800">Can I get a certificate after completing a course?</span>
                    <i class="fas fa-chevron-down text-gray-500"></i>
                </button>
                <div class="faq-content hidden p-4 border-t border-gray-200">
                    <p class="text-gray-700">Yes! Upon completion of a course, you'll receive a certificate of completion that you can download and share on your professional profiles.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Troubleshooting -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Troubleshooting</h3>
        <div class="space-y-4">
            <div class="flex items-start">
                <i class="fas fa-video text-blue-600 mr-3 mt-1"></i>
                <div>
                    <h4 class="font-medium text-gray-800 mb-1">Videos not playing?</h4>
                    <p class="text-gray-700 text-sm">Make sure you have a stable internet connection and try refreshing the page. If the issue persists, contact our support team.</p>
                </div>
            </div>
            <div class="flex items-start">
                <i class="fas fa-lock text-blue-600 mr-3 mt-1"></i>
                <div>
                    <h4 class="font-medium text-gray-800 mb-1">Can't access purchased courses?</h4>
                    <p class="text-gray-700 text-sm">Ensure you're logged in with the same account used for purchase. If you're still having issues, contact our support team.</p>
                </div>
            </div>
            <div class="flex items-start">
                <i class="fas fa-credit-card text-blue-600 mr-3 mt-1"></i>
                <div>
                    <h4 class="font-medium text-gray-800 mb-1">Payment issues?</h4>
                    <p class="text-gray-700 text-sm">If your payment was deducted but you haven't received access, please contact our support team with your payment details.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Send us a Message</h3>
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Select a subject</option>
                    <option value="technical">Technical Issue</option>
                    <option value="payment">Payment Issue</option>
                    <option value="course">Course Content</option>
                    <option value="general">General Inquiry</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                <textarea rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Send Message
            </button>
        </form>
    </div>
</div>

<script>
// FAQ toggle functionality
document.querySelectorAll('.faq-toggle').forEach(button => {
    button.addEventListener('click', function() {
        const content = this.nextElementSibling;
        const icon = this.querySelector('i');
        
        content.classList.toggle('hidden');
        icon.classList.toggle('fa-chevron-down');
        icon.classList.toggle('fa-chevron-up');
    });
});
</script>

<?php include 'common/bottom.php'; ?>