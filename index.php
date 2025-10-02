
<?php include("header.php"); ?>
    <!-- Main Content -->
    <main>
        <!-- Home Page -->
        <div id="homePage" class="page">
            <!-- Hero Section -->
            <section class="hero-gradient text-white py-32 relative overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full morphing-shape"></div>
                    <div class="absolute bottom-10 right-10 w-96 h-96 bg-white rounded-full morphing-shape"
                        style="animation-delay: -4s;"></div>
                </div>

                <div class="container mx-auto px-4 text-center relative z-10">
                    <div class="max-w-4xl mx-auto">
                        <h1 class="text-6xl md:text-7xl font-bold mb-8 slide-in-left">
                            Smart Complaint Management
                            <span class="block text-5xl md:text-6xl mt-4 typewriter">with AI Power</span>
                        </h1>
                        <p
                            class="text-xl md:text-2xl mb-12 max-w-3xl mx-auto leading-relaxed slide-in-right opacity-90">
                            Experience the future of complaint management with our AI-powered platform. File complaints
                            across multiple sectors, get intelligent assistance, and track resolution in real-time with
                            unprecedented transparency and efficiency.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-6 justify-center items-center slide-in-bottom">
                            <button onclick="showPage('complaint')"
                                class="group bg-white text-blue-600 px-10 py-4 rounded-2xl font-bold text-lg hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-3xl">
                                <i class="fas fa-plus mr-3 group-hover:rotate-90 transition-transform duration-300"></i>
                                <span data-translate="file_complaint">File Complaint</span>
                                <i
                                    class="fas fa-arrow-right ml-3 group-hover:translate-x-2 transition-transform duration-300"></i>
                            </button>
                            <button onclick="showPage('history')"
                                class="group border-3 border-white text-white px-10 py-4 rounded-2xl font-bold text-lg hover:bg-white hover:text-blue-600 transition-all duration-300 transform hover:scale-105 shadow-2xl">
                                <i
                                    class="fas fa-search mr-3 group-hover:scale-110 transition-transform duration-300"></i>
                                <span data-translate="track_complaints">Track Complaints</span>
                            </button>
                        </div>

                        <!-- Stats Section -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-20 fade-in-scale">
                            <div class="text-center">
                                <div class="text-4xl font-bold mb-2 counter" data-target="50000">0</div>
                                <div class="text-lg opacity-90">Complaints Resolved</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold mb-2 counter" data-target="98">0</div>
                                <div class="text-lg opacity-90">% Success Rate</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold mb-2 counter" data-target="24">0</div>
                                <div class="text-lg opacity-90">Hour Response</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold mb-2 counter" data-target="8">0</div>
                                <div class="text-lg opacity-90">Sectors Covered</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="py-24 bg-gradient-to-br from-gray-50 to-white relative">
                <div class="container mx-auto px-4">
                    <div class="text-center mb-20 scroll-reveal">
                        <h2 class="text-5xl font-bold text-gray-800 mb-6" data-translate="features_title">Why Choose
                            FIXBOT?</h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                            Discover the revolutionary features that make FIXBOT the most advanced complaint management
                            system.
                            Our AI-powered platform transforms how citizens interact with government services.
                        </p>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-12">
                        <div
                            class="group hover-lift card-3d bg-white rounded-3xl p-8 shadow-xl border border-gray-100 scroll-reveal">
                            <div
                                class="bg-gradient-to-br from-blue-500 to-blue-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-robot text-white text-3xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 text-center" data-translate="ai_assistance">AI-Powered
                                Assistance</h3>
                            <p class="text-gray-600 text-center leading-relaxed mb-6" data-translate="ai_description">
                                Our advanced AI chatbot provides intelligent suggestions, guides you through the
                                complaint process,
                                and offers personalized recommendations based on your specific situation and location.
                            </p>
                            <ul class="space-y-3 text-sm text-gray-600">
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>Natural
                                    language processing</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>24/7
                                    intelligent support</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>Contextual
                                    recommendations</li>
                                <li class="flex items-center"><i
                                        class="fas fa-check text-green-500 mr-3"></i>Multi-language understanding</li>
                            </ul>
                        </div>

                        <div class="group hover-lift card-3d bg-white rounded-3xl p-8 shadow-xl border border-gray-100 scroll-reveal"
                            style="animation-delay: 0.2s;">
                            <div
                                class="bg-gradient-to-br from-green-500 to-green-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-clock text-white text-3xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 text-center" data-translate="real_time">Real-time
                                Tracking</h3>
                            <p class="text-gray-600 text-center leading-relaxed mb-6"
                                data-translate="tracking_description">
                                Monitor your complaint journey with live updates, detailed progress reports, and
                                transparent
                                communication from relevant authorities throughout the resolution process.
                            </p>
                            <ul class="space-y-3 text-sm text-gray-600">
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>Live
                                    status updates</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>SMS &
                                    email notifications</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>Progress
                                    timeline view</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>Authority
                                    communication log</li>
                            </ul>
                        </div>

                        <div class="group hover-lift card-3d bg-white rounded-3xl p-8 shadow-xl border border-gray-100 scroll-reveal"
                            style="animation-delay: 0.4s;">
                            <div
                                class="bg-gradient-to-br from-purple-500 to-purple-600 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-language text-white text-3xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold mb-4 text-center" data-translate="multilingual">Multilingual
                                Support</h3>
                            <p class="text-gray-600 text-center leading-relaxed mb-6"
                                data-translate="language_description">
                                Access FIXBOT in your preferred language with comprehensive support for English, Hindi,
                                and Gujarati,
                                ensuring no language barrier prevents you from seeking justice.
                            </p>
                            <ul class="space-y-3 text-sm text-gray-600">
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>3 major
                                    languages supported</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>Real-time
                                    translation</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>Cultural
                                    context awareness</li>
                                <li class="flex items-center"><i class="fas fa-check text-green-500 mr-3"></i>Voice
                                    input support</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sectors Section -->
            <section class="py-24 bg-gradient-to-br from-blue-50 to-purple-50">
                <div class="container mx-auto px-4">
                    <div class="text-center mb-20 scroll-reveal">
                        <h2 class="text-5xl font-bold text-gray-800 mb-6" data-translate="sectors_title">Comprehensive
                            Sector Coverage</h2>
                        <p class="text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                            FIXBOT covers all major government and public service sectors, ensuring your complaints
                            reach the right authorities
                            quickly and efficiently. Our intelligent routing system automatically directs your complaint
                            to the appropriate department.
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div
                            class="group bg-white p-8 rounded-3xl shadow-xl text-center hover-lift border border-gray-100 scroll-reveal">
                            <div
                                class="bg-gradient-to-br from-blue-500 to-blue-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:rotate-12 transition-transform duration-300">
                                <i class="fas fa-graduation-cap text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3" data-translate="college">Educational Institutions</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Academic issues, infrastructure problems,
                                administrative concerns, and student welfare matters.</p>
                        </div>

                        <div class="group bg-white p-8 rounded-3xl shadow-xl text-center hover-lift border border-gray-100 scroll-reveal"
                            style="animation-delay: 0.1s;">
                            <div
                                class="bg-gradient-to-br from-red-500 to-red-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:rotate-12 transition-transform duration-300">
                                <i class="fas fa-shield-alt text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3" data-translate="police">Law Enforcement</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Public safety issues, traffic violations,
                                criminal activities, and police misconduct reports.</p>
                        </div>

                        <div class="group bg-white p-8 rounded-3xl shadow-xl text-center hover-lift border border-gray-100 scroll-reveal"
                            style="animation-delay: 0.2s;">
                            <div
                                class="bg-gradient-to-br from-green-500 to-green-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:rotate-12 transition-transform duration-300">
                                <i class="fas fa-city text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3" data-translate="municipal">Municipal Services</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Road maintenance, waste management, water
                                supply, street lighting, and urban planning issues.</p>
                        </div>

                        <div class="group bg-white p-8 rounded-3xl shadow-xl text-center hover-lift border border-gray-100 scroll-reveal"
                            style="animation-delay: 0.3s;">
                            <div
                                class="bg-gradient-to-br from-purple-500 to-purple-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:rotate-12 transition-transform duration-300">
                                <i class="fas fa-hospital text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3" data-translate="healthcare">Healthcare Services</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Medical facility issues, healthcare
                                accessibility, treatment quality, and public health concerns.</p>
                        </div>

                        <div class="group bg-white p-8 rounded-3xl shadow-xl text-center hover-lift border border-gray-100 scroll-reveal"
                            style="animation-delay: 0.4s;">
                            <div
                                class="bg-gradient-to-br from-yellow-500 to-orange-500 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:rotate-12 transition-transform duration-300">
                                <i class="fas fa-bus text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Transportation</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Public transport issues, road conditions,
                                traffic management, and transportation accessibility.</p>
                        </div>

                        <div class="group bg-white p-8 rounded-3xl shadow-xl text-center hover-lift border border-gray-100 scroll-reveal"
                            style="animation-delay: 0.5s;">
                            <div
                                class="bg-gradient-to-br from-indigo-500 to-indigo-600 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:rotate-12 transition-transform duration-300">
                                <i class="fas fa-bolt text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Electricity Board</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Power outages, billing issues, electrical
                                safety concerns, and infrastructure maintenance.</p>
                        </div>

                        <div class="group bg-white p-8 rounded-3xl shadow-xl text-center hover-lift border border-gray-100 scroll-reveal"
                            style="animation-delay: 0.6s;">
                            <div
                                class="bg-gradient-to-br from-cyan-500 to-blue-500 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:rotate-12 transition-transform duration-300">
                                <i class="fas fa-tint text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Water Supply</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Water quality issues, supply disruptions,
                                pipeline problems, and sanitation concerns.</p>
                        </div>

                        <div class="group bg-white p-8 rounded-3xl shadow-xl text-center hover-lift border border-gray-100 scroll-reveal"
                            style="animation-delay: 0.7s;">
                            <div
                                class="bg-gradient-to-br from-pink-500 to-rose-500 w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:rotate-12 transition-transform duration-300">
                                <i class="fas fa-ellipsis-h text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-3">Other Services</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">Environmental issues, consumer rights,
                                telecommunications, and any other public service concerns.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How It Works Section -->
            <section class="py-24 bg-white">
                <div class="container mx-auto px-4">
                    <div class="text-center mb-20 scroll-reveal">
                        <h2 class="text-5xl font-bold text-gray-800 mb-6">How FIXBOT Works</h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                            Our streamlined process ensures your complaints are handled efficiently and transparently
                            from submission to resolution.
                        </p>
                    </div>

                    <div class="relative">
                        <!-- Timeline Line -->
                        <div
                            class="absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-blue-500 to-purple-500 rounded-full hidden lg:block">
                        </div>

                        <div class="space-y-16">
                            <!-- Step 1 -->
                            <div class="flex flex-col lg:flex-row items-center scroll-reveal">
                                <div class="lg:w-1/2 lg:pr-12 mb-8 lg:mb-0">
                                    <div
                                        class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-8 rounded-3xl shadow-2xl">
                                        <div class="flex items-center mb-4">
                                            <div
                                                class="bg-white text-blue-600 w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl mr-4">
                                                1</div>
                                            <h3 class="text-2xl font-bold">Submit Your Complaint</h3>
                                        </div>
                                        <p class="text-blue-100 leading-relaxed">
                                            Use our intelligent form with AI-powered suggestions to describe your issue.
                                            Upload photos, specify location, and select the appropriate sector for
                                            faster processing.
                                        </p>
                                    </div>
                                </div>
                                <div class="lg:w-1/2 lg:pl-12">
                                    <div class="bg-gray-50 p-8 rounded-3xl">
                                        <i class="fas fa-edit text-6xl text-blue-500 mb-4"></i>
                                        <h4 class="text-xl font-bold mb-3">Smart Form Assistance</h4>
                                        <ul class="space-y-2 text-gray-600">
                                            <li>• AI-powered field suggestions</li>
                                            <li>• Auto-location detection</li>
                                            <li>• Photo evidence upload</li>
                                            <li>• Priority level selection</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex flex-col lg:flex-row-reverse items-center scroll-reveal">
                                <div class="lg:w-1/2 lg:pl-12 mb-8 lg:mb-0">
                                    <div
                                        class="bg-gradient-to-br from-green-500 to-green-600 text-white p-8 rounded-3xl shadow-2xl">
                                        <div class="flex items-center mb-4">
                                            <div
                                                class="bg-white text-green-600 w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl mr-4">
                                                2</div>
                                            <h3 class="text-2xl font-bold">AI Processing & Routing</h3>
                                        </div>
                                        <p class="text-green-100 leading-relaxed">
                                            Our AI system analyzes your complaint, categorizes it accurately, and routes
                                            it to the
                                            most appropriate authority based on location, sector, and urgency level.
                                        </p>
                                    </div>
                                </div>
                                <div class="lg:w-1/2 lg:pr-12">
                                    <div class="bg-gray-50 p-8 rounded-3xl">
                                        <i class="fas fa-cogs text-6xl text-green-500 mb-4"></i>
                                        <h4 class="text-xl font-bold mb-3">Intelligent Analysis</h4>
                                        <ul class="space-y-2 text-gray-600">
                                            <li>• Automatic categorization</li>
                                            <li>• Priority assessment</li>
                                            <li>• Authority matching</li>
                                            <li>• Duplicate detection</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex flex-col lg:flex-row items-center scroll-reveal">
                                <div class="lg:w-1/2 lg:pr-12 mb-8 lg:mb-0">
                                    <div
                                        class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-8 rounded-3xl shadow-2xl">
                                        <div class="flex items-center mb-4">
                                            <div
                                                class="bg-white text-purple-600 w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl mr-4">
                                                3</div>
                                            <h3 class="text-2xl font-bold">Real-time Tracking</h3>
                                        </div>
                                        <p class="text-purple-100 leading-relaxed">
                                            Monitor your complaint's progress with live updates, receive notifications
                                            at each stage,
                                            and communicate directly with handling authorities through our platform.
                                        </p>
                                    </div>
                                </div>
                                <div class="lg:w-1/2 lg:pl-12">
                                    <div class="bg-gray-50 p-8 rounded-3xl">
                                        <i class="fas fa-chart-line text-6xl text-purple-500 mb-4"></i>
                                        <h4 class="text-xl font-bold mb-3">Complete Transparency</h4>
                                        <ul class="space-y-2 text-gray-600">
                                            <li>• Live status updates</li>
                                            <li>• Progress notifications</li>
                                            <li>• Authority communication</li>
                                            <li>• Resolution timeline</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section class="py-24 bg-gradient-to-br from-gray-50 to-blue-50">
                <div class="container mx-auto px-4">
                    <div class="text-center mb-20 scroll-reveal">
                        <h2 class="text-5xl font-bold text-gray-800 mb-6">What Citizens Say</h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                            Real stories from real people who have experienced the power of FIXBOT in resolving their
                            complaints efficiently.
                        </p>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-8">
                        <div class="bg-white p-8 rounded-3xl shadow-xl hover-lift scroll-reveal">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4">
                                    RS
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg">Rajesh Sharma</h4>
                                    <p class="text-gray-600">Ahmedabad Resident</p>
                                </div>
                            </div>
                            <div class="text-yellow-400 mb-4">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="text-gray-700 leading-relaxed">
                                "FIXBOT transformed my experience with municipal complaints. The AI assistant guided me
                                perfectly,
                                and my street lighting issue was resolved within 48 hours. Incredible efficiency!"
                            </p>
                        </div>

                        <div class="bg-white p-8 rounded-3xl shadow-xl hover-lift scroll-reveal"
                            style="animation-delay: 0.2s;">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-green-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4">
                                    PM
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg">Priya Mehta</h4>
                                    <p class="text-gray-600">College Student</p>
                                </div>
                            </div>
                            <div class="text-yellow-400 mb-4">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="text-gray-700 leading-relaxed">
                                "The multilingual support made it so easy for my grandmother to file a complaint in
                                Gujarati.
                                The real-time tracking kept us informed throughout the process. Excellent service!"
                            </p>
                        </div>

                        <div class="bg-white p-8 rounded-3xl shadow-xl hover-lift scroll-reveal"
                            style="animation-delay: 0.4s;">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4">
                                    AK
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg">Amit Kumar</h4>
                                    <p class="text-gray-600">Business Owner</p>
                                </div>
                            </div>
                            <div class="text-yellow-400 mb-4">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="text-gray-700 leading-relaxed">
                                "As a business owner, I deal with multiple civic issues. FIXBOT's sector-wise routing
                                and
                                WhatsApp integration made communication seamless. Highly recommended!"
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Complaint Form Page -->
        

        <!-- Complaint History Page -->
        

        <!-- About Page -->
       

        <!-- Team Page -->
       

        <!-- Contact Page -->
        
    </main>

    <!-- Footer -->
    <?php include("footer.php"); ?>

    <!-- Chatbot Widget -->
    <!-- <?php include("chatbot.php"); ?> -->

    <div class="chatbot">
        <div id="chatbotToggle"
            class="chat-bubble bg-gradient-to-br from-blue-600 to-purple-600 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-2xl cursor-pointer hover:from-blue-700 hover:to-purple-700 transition-all duration-300">
            <i class="fas fa-comment-dots text-2xl"></i>
        </div>
        <!-- <div id="chatbotWindow" class="chat-window" style="display: none;"> -->
            <div id="chatbotWindow" class="chat-window">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-4 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                        <i class="fas fa-robot text-blue-600"></i>
                    </div>
                    <h3 class="font-bold">FIXBOT Assistant</h3>
                </div>
                <button id="chatbotClose" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="chatMessages" class="flex-1 p-4 overflow-y-auto">
                <div class="chat-message bot mb-4">
                    <div class="bg-gray-100 text-gray-800 rounded-2xl p-4 max-w-xs md:max-w-md">
                        <p>Hello! I'm FIXBOT, your AI assistant. How can I help you today?</p>
                    </div>
                    <div class="text-xs text-gray-500 mt-1">Just now</div>
                </div>
            </div>
            <div class="border-t border-gray-200 p-4">
                <div class="flex">
                    <input type="text" id="chatInput" placeholder="Type your message..."
                        class="flex-1 border-2 border-gray-200 rounded-l-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button id="chatSend"
                        class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-r-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <?php include("login.php"); ?>
    

    <!-- Signup Modal -->
    <?php include("signup.php"); ?>

    <!-- Complaint Submitted Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content bg-white rounded-3xl shadow-2xl w-full max-w-md">
            <div class="p-8 text-center">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check-circle text-green-500 text-4xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Complaint Submitted!</h2>
                <p class="text-gray-600 mb-6">
                    Your complaint has been successfully submitted. Your complaint ID is:
                    <span class="font-bold text-blue-600">FIXBOT-2024-12345</span>
                </p>
                <p class="text-gray-600 mb-8">
                    You'll receive confirmation via email and SMS. Track progress in the "My Complaints" section.
                </p>
                <div class="flex space-x-4 justify-center">
                    <button onclick="hideModal('successModal'); showPage('home')"
                        class="bg-gray-200 text-gray-800 px-6 py-3 rounded-xl font-medium hover:bg-gray-300 transition-colors duration-300">
                        Back to Home
                    </button>
                    <button onclick="hideModal('successModal'); showPage('history')"
                        class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-300">
                        Track Complaint
                    </button>
                </div>
            </div>
        </div>
    </div>

    
</body>

</html>