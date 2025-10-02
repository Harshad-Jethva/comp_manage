<div class="chatbot">
        <div id="chatbotToggle"
            class="chat-bubble bg-gradient-to-br from-blue-600 to-purple-600 text-white w-16 h-16 rounded-full flex items-center justify-center shadow-2xl cursor-pointer hover:from-blue-700 hover:to-purple-700 transition-all duration-300">
            <i class="fas fa-comment-dots text-2xl"></i>
        </div>
        <div id="chatbotWindow" class="chat-window" style="display: none;">
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