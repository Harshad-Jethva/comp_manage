<div id="loginModal" class="modal">
        <div class="modal-content bg-white rounded-3xl shadow-2xl w-full max-w-md">
            <div class="p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-3xl font-bold text-gradient">Login</h2>
                    <button onclick="hideModal('loginModal')" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="loginForm" class="space-y-6" action="upload.php" method="POST">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" required
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" required
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" id="rememberMe"
                                class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                            <label for="rememberMe" class="ml-2 text-sm text-gray-700">Remember me</label>
                        </div>
                        <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
                    </div>
                    <button type="submit" name="login"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300">
                        Login
                    </button>
                    <div class="text-center text-sm text-gray-600">
                        Don't have an account? <a href="#" onclick="hideModal('loginModal'); showModal('signupModal')"
                            class="text-blue-600 hover:underline">Sign up</a>
                    </div>
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <button type="button"
                            class="bg-white border border-gray-300 rounded-xl py-3 hover:bg-gray-50 transition-colors duration-300">
                            <i class="fab fa-google text-red-500"></i>
                        </button>
                        <button type="button"
                            class="bg-white border border-gray-300 rounded-xl py-3 hover:bg-gray-50 transition-colors duration-300">
                            <i class="fab fa-facebook-f text-blue-600"></i>
                        </button>
                        <button type="button"
                            class="bg-white border border-gray-300 rounded-xl py-3 hover:bg-gray-50 transition-colors duration-300">
                            <i class="fab fa-twitter text-blue-400"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>