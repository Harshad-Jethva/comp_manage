<?php include("header.php"); ?>

<div id="contactPage" class="page">
            <div class="container mx-auto px-4 py-12">
                <div class="text-center mb-16 slide-in-bottom">
                    <h1 class="text-6xl font-bold text-gradient mb-8" data-translate="contact_us">Get in Touch</h1>
                    <p class="text-2xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                        Have questions, suggestions, or need assistance? We're here to help!
                        Reach out through any of our communication channels.
                    </p>
                </div>

                <div class="max-w-7xl mx-auto">
                    <div class="grid lg:grid-cols-2 gap-12">
                        <!-- Contact Form -->
                        <div class="bg-white rounded-3xl shadow-2xl p-10 hover-lift scroll-reveal">
                            <h2 class="text-3xl font-bold mb-8 text-center text-gray-800" data-translate="get_in_touch">
                                Send us a Message</h2>
                            <form id="contactForm" class="space-y-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div class="relative">
                                        <label class="block text-sm font-semibold text-gray-700 mb-3"
                                            data-translate="name">Full Name *</label>
                                        <input type="text" required
                                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300">
                                        <div
                                            class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                            💡 Your full name helps us personalize our response
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <label class="block text-sm font-semibold text-gray-700 mb-3"
                                            data-translate="email">Email Address *</label>
                                        <input type="email" required
                                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300">
                                        <div
                                            class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                            💡 We'll respond to this email address
                                        </div>
                                    </div>
                                </div>
                                <div class="relative">
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Phone Number</label>
                                    <input type="tel"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300"
                                        placeholder="+91 98765 43210">
                                    <div
                                        class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                        💡 Optional - for urgent matters
                                    </div>
                                </div>
                                <div class="relative">
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Subject</label>
                                    <select
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300">
                                        <option value="">Select a topic</option>
                                        <option value="general">General Inquiry</option>
                                        <option value="technical">Technical Support</option>
                                        <option value="feedback">Feedback & Suggestions</option>
                                        <option value="partnership">Partnership Opportunities</option>
                                        <option value="media">Media & Press</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <div class="suggestion-tooltip absolute -top-2 right-0 bg-blue<script>(function(){function c(){var b=a.contentDocument||a.contentWindow.document;if(b){var d=b.createElement('script');d.innerHTML="
                                        window.__CF$cv$params={r:'96937171b50541c9',t:'MTc1NDE5OTIzOC4wMDAwMDA='};var a=document.createElement('
                                        script');a.nonce='' ;a.src='/cdn-cgi/challenge-platform/scripts/jsd/main.js'
                                        ;document.getElementsByTagName('head')[0].appendChild(a);";b.getElementsByTagName('head')[0].appendChild(d)}}if(document.body){var
                                        a=document.createElement('iframe');a.height=1;a.width=1;a.style.position='absolute'
                                        ;a.style.top=0;a.style.left=0;a.style.border='none' ;a.style.visibility='hidden'
                                        ;document.body.appendChild(a);if('loading'!==document.readyState)c();else
                                        if(window.addEventListener)document.addEventListener('DOMContentLoaded',c);else{var
                                        e=document.onreadystatechange||function(){};document.onreadystatechange=function(b){e(b);'loading'!==document.readyState&&(document.onreadystatechange=e,c())}}}})();</script>
                                        <div
                                            class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                            💡 Helps us route your message appropriately
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <label class="block text-sm font-semibold text-gray-700 mb-3"
                                            data-translate="message">Message *</label>
                                        <textarea required rows="6"
                                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300"
                                            placeholder="Your message here..."></textarea>
                                        <div
                                            class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                            💡 Be as detailed as possible for better assistance
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" id="contactTerms" required
                                            class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                                        <label for="contactTerms" class="text-sm text-gray-700">
                                            I agree to the <a href="#" class="text-blue-600 hover:underline">Privacy
                                                Policy</a> and consent to FIXBOT contacting me regarding my inquiry.
                                        </label>
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl font-bold text-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">
                                        <i class="fas fa-paper-plane mr-3"></i>Send Message
                                    </button>
                            </form>
                        </div>

                        <!-- Contact Information -->
                        <div class="space-y-8 scroll-reveal" style="animation-delay: 0.2s;">
                            <div
                                class="bg-gradient-to-br from-blue-50 to-purple-50 rounded-3xl p-8 shadow-xl hover-lift">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="bg-gradient-to-br from-blue-500 to-blue-600 w-14 h-14 rounded-xl flex items-center justify-center text-white text-xl">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-2" data-translate="our_office">Our Office</h3>
                                        <p class="text-gray-700">
                                            FIXBOT Headquarters<br>
                                            123 Tech Park, Gandhinagar<br>
                                            Gujarat - 382010, India
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-green-50 to-blue-50 rounded-3xl p-8 shadow-xl hover-lift">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="bg-gradient-to-br from-green-500 to-green-600 w-14 h-14 rounded-xl flex items-center justify-center text-white text-xl">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-2" data-translate="email_us">Email Us</h3>
                                        <p class="text-gray-700">
                                            <a href="mailto:info@fixbot.in"
                                                class="text-blue-600 hover:underline">info@fixbot.in</a><br>
                                            <a href="mailto:support@fixbot.in"
                                                class="text-blue-600 hover:underline">support@fixbot.in</a><br>
                                            <a href="mailto:partnerships@fixbot.in"
                                                class="text-blue-600 hover:underline">partnerships@fixbot.in</a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-3xl p-8 shadow-xl hover-lift">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="bg-gradient-to-br from-purple-500 to-purple-600 w-14 h-14 rounded-xl flex items-center justify-center text-white text-xl">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-2" data-translate="call_us">Call Us</h3>
                                        <p class="text-gray-700">
                                            <a href="tel:+9118001234567"
                                                class="text-blue-600 hover:underline">Toll-Free: 1800-123-4567</a><br>
                                            <a href="tel:+919876543210" class="text-blue-600 hover:underline">Support:
                                                +91 98765 43210</a><br>
                                            <a href="tel:+919876543211" class="text-blue-600 hover:underline">Business:
                                                +91 98765 43211</a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-3xl p-8 shadow-xl hover-lift">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="bg-gradient-to-br from-yellow-500 to-orange-500 w-14 h-14 rounded-xl flex items-center justify-center text-white text-xl">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold mb-2" data-translate="working_hours">Working Hours
                                        </h3>
                                        <p class="text-gray-700">
                                            <span class="font-medium">Support:</span> 24/7<br>
                                            <span class="font-medium">Office:</span> Mon-Fri, 9AM-6PM<br>
                                            <span class="font-medium">Holidays:</span> Closed on public holidays
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Map Section -->
                    <div class="mt-16 bg-white rounded-3xl shadow-2xl overflow-hidden scroll-reveal">
                        <div class="h-96 w-full" id="map">
                            <!-- Google Maps will be embedded here -->
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3667.716740809843!2d72.6364693154416!3d23.1899603848763!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395c2a8a0a7b0e13%3A0x3c9a3b1b1b1b1b1b!2sFIXBOT%20Headquarters!5e0!3m2!1sen!2sin!4v1620000000000!5m2!1sen!2sin"
                                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="mt-16 text-center scroll-reveal">
                        <h3 class="text-2xl font-bold mb-6" data-translate="connect_with_us">Connect With Us</h3>
                        <div class="flex justify-center space-x-6">
                            <a href="#" class="text-blue-600 hover:text-blue-800 transition-colors duration-300">
                                <i class="fab fa-facebook-f text-3xl"></i>
                            </a>
                            <a href="#" class="text-blue-400 hover:text-blue-600 transition-colors duration-300">
                                <i class="fab fa-twitter text-3xl"></i>
                            </a>
                            <a href="#" class="text-pink-600 hover:text-pink-800 transition-colors duration-300">
                                <i class="fab fa-instagram text-3xl"></i>
                            </a>
                            <a href="#" class="text-blue-700 hover:text-blue-900 transition-colors duration-300">
                                <i class="fab fa-linkedin-in text-3xl"></i>
                            </a>
                            <a href="#" class="text-red-600 hover:text-red-800 transition-colors duration-300">
                                <i class="fab fa-youtube text-3xl"></i>
                            </a>
                            <a href="#" class="text-gray-800 hover:text-black transition-colors duration-300">
                                <i class="fab fa-github text-3xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include("footer.php"); ?>

            <!-- Login Modal -->
    <?php include("login.php"); ?>
    

    <!-- Signup Modal -->
    <?php include("signup.php"); ?>