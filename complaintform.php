<?php 
session_start();
include("header.php"); 
include("dbconfig.php"); 

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = '';
$userEmail = '';
$userPhone = '';

if ($isLoggedIn) {
    // Fetch user data from session or database
    $userId = $_SESSION['user_id'];
    $userQuery = "SELECT name, email, phone FROM user WHERE id = $userId";
    $userResult = mysqli_query($conn, $userQuery);
    if ($userResult && mysqli_num_rows($userResult) > 0) {
        $userData = mysqli_fetch_assoc($userResult);
        $userName = $userData['name'];
        $userEmail = $userData['email'];
        $userPhone = $userData['phone'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $name = isset($_POST["name"]) ? $_POST["name"] : $userName;
    $email = isset($_POST["email"]) ? $_POST["email"] : $userEmail;
    $phone = isset($_POST["phone"]) ? $_POST["phone"] : $userPhone;
    $age = $_POST["age"];
    $csector = $_POST["csector"];
    $location = $_POST["location"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $status = isset($_POST["status"]) ? $_POST["status"] : 'Pending'; // Default to 'Pending'
    $priority = isset($_POST["priority"]) ? $_POST["priority"] : 'Medium'; // Default to 'Medium'

    // Insert complaint data into the `complain` table
    $query = "INSERT INTO `complain`(`name`, `email`, `phone`, `age_group`, `complaint_sector`, `location`, `title`, `description`, `status`, `priority`) VALUES ('$name', '$email', '$phone', '$age', '$csector', '$location', '$title', '$description', '$status', '$priority');";
    $runq = mysqli_query($conn, $query);

    $successMessage = "";

    if ($runq) {
        $complaintId = mysqli_insert_id($conn); // Get the last inserted complaint ID
        $complaintIdFormatted = 'FB' . str_pad($complaintId, 6, '0', STR_PAD_LEFT);
        $successMessage = "✅ Complaint submitted successfully! Your Complaint ID is: <strong>$complaintIdFormatted</strong>. You will receive updates via email and SMS.";

        $Query2 = "INSERT INTO `complaint_status_summary` (`compid`) VALUES ('$complaintId');";
        mysqli_query($conn, $Query2);
        // Handle multiple file uploads
        
// Handle multiple file uploads
if (isset($_FILES['uploaded_files'])) {
    $uploadDir = 'uploads/';
    $allowed_ext = ["jpg", "jpeg", "gif", "png"];

    // Ensure the uploads directory exists
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            die("Failed to create uploads directory.");
        }
    }

    foreach ($_FILES['uploaded_files']['name'] as $key => $fileName) {
        $error = $_FILES['uploaded_files']['error'][$key];
        $tmp_name = $_FILES['uploaded_files']['tmp_name'][$key];

        if ($error === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed_ext)) {
                $uniqueFileName = uniqid() . "." . $ext;
                $uploadFile = $uploadDir . $uniqueFileName;

                if (move_uploaded_file($tmp_name, $uploadFile)) {
                    // Insert file path into the `photo` table
                    $photoQuery = "INSERT INTO `photo` (`c_id`, `path`) VALUES ('$complaintId', '$uploadFile');";
                    if (!mysqli_query($conn, $photoQuery)) {
                        die("Failed to insert photo path into database.");
                    }
                } else {
                    die("Failed to upload file: $fileName.");
                }
            } else {
                die("Only images are allowed.");
            }
        } else {
            die("Error uploading file: $fileName.");
        }
    }
}
    } else {
        $successMessage = "Failed to insert complaint.";
    }
}

?>

<div id="complaintPage" class="page">
    <div class="container mx-auto px-4 py-12">
        <?php if (!empty($successMessage)): ?>
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-6 rounded-2xl mb-8 shadow-2xl animate-pulse" role="alert">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-4xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold mb-2">Success!</h3>
                        <p class="text-lg"><?php echo $successMessage; ?></p>
                        <p class="mt-3 text-sm opacity-90">
                            <i class="fas fa-info-circle mr-2"></i>Track your complaint status in the "My Complaints" section
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-12 slide-in-bottom">
                <h1 class="text-5xl font-bold text-gradient mb-6" data-translate="file_new_complaint">File New
                    Complaint</h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Use our intelligent complaint form with AI-powered suggestions to ensure your issue is
                    properly documented
                    and routed to the right authorities for quick resolution.
                </p>
            </div>

            <!-- Progress Indicator -->
            <div class="mb-12 fade-in-scale">
                <div class="flex items-center justify-center space-x-4 mb-4">
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold">
                            1</div>
                        <span class="ml-2 font-medium">Personal Info</span>
                    </div>
                    <div class="w-16 h-1 bg-gray-300 rounded"></div>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">
                            2</div>
                        <span class="ml-2 font-medium text-gray-500">Complaint Details</span>
                    </div>
                    <div class="w-16 h-1 bg-gray-300 rounded"></div>
                    <div class="flex items-center">
                        <div
                            class="w-10 h-10 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center font-bold">
                            3</div>
                        <span class="ml-2 font-medium text-gray-500">Review & Submit</span>
                    </div>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 33%"></div>
                </div>
            </div>

            <form id="complaintForm"
                class="bg-white rounded-3xl shadow-2xl p-10 border border-gray-100 slide-in-bottom" method="post"
                action="complaintform.php" enctype="multipart/form-data">
                <!-- Personal Information Section -->
                <div id="step1" class="step-content">
                    <h3 class="text-2xl font-bold mb-8 text-center text-gray-800">Personal Information</h3>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-3"
                                data-translate="full_name">Full Name *</label>
                            <input type="text" id="complainantName" name="name" required
                                value="<?php echo htmlspecialchars($userName); ?>"
                                <?php echo $isLoggedIn ? 'readonly' : ''; ?>
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300 <?php echo $isLoggedIn ? 'bg-gray-100 cursor-not-allowed' : ''; ?>">
                            <?php if ($isLoggedIn): ?>
                            
                            <?php else: ?>
                            <div
                                class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                💡 Enter your full legal name as per ID
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-3"
                                data-translate="email">Email Address *</label>
                            <input type="email" id="complainantEmail" name="email" required
                                value="<?php echo htmlspecialchars($userEmail); ?>"
                                <?php echo $isLoggedIn ? 'readonly' : ''; ?>
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300 <?php echo $isLoggedIn ? 'bg-gray-100 cursor-not-allowed' : ''; ?>">
                            <?php if ($isLoggedIn): ?>
                           
                            <?php else: ?>
                            <div
                                class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                💡 Use active email for updates
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-3"
                                data-translate="phone">Phone Number *</label>
                            <input type="tel" id="complainantPhone" name="phone" required
                                value="<?php echo htmlspecialchars($userPhone); ?>"
                                <?php echo $isLoggedIn ? 'readonly' : ''; ?>
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300 <?php echo $isLoggedIn ? 'bg-gray-100 cursor-not-allowed' : ''; ?>"
                                placeholder="+91 98765 43210">
                            <?php if ($isLoggedIn): ?>
                            
                            <?php else: ?>
                            <div
                                class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                💡 Include country code (+91)
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Age Group</label>
                            <select id="ageGroup"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300" name="age">
                                <option value="">Select Age Group</option>
                                <option value="18-25">18-25 years</option>
                                <option value="26-35">26-35 years</option>
                                <option value="36-50">36-50 years</option>
                                <option value="51-65">51-65 years</option>
                                <option value="65+">65+ years</option>
                            </select>
                            <div
                                class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                💡 Helps prioritize senior citizen complaints
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-8">
                        <button type="button" onclick="nextStep()"
                            class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">
                            Next Step <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Complaint Details Section -->
                <div id="step2" class="step-content hidden">
                    <h3 class="text-2xl font-bold mb-8 text-center text-gray-800">Complaint Details</h3>
                    <div class="grid md:grid-cols-2 gap-8">
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-3"
                                data-translate="sector">Complaint Sector *</label>
                            <select id="complaintSector" required
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300" name="csector">
                                <option value="">Select Sector</option>
                                <option value="college">🎓 Educational Institutions</option>
                                <option value="police">🚔 Law Enforcement</option>
                                <option value="municipal">🏛️ Municipal Corporation</option>
                                <option value="healthcare">🏥 Healthcare Services</option>
                                <option value="transport">🚌 Transportation</option>
                                <option value="electricity">⚡ Electricity Board</option>
                                <option value="water">💧 Water Supply</option>
                                <option value="other">📋 Other Services</option>
                            </select>
                            <div
                                class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                💡 Choose the most relevant sector
                            </div>
                        </div>
                        <!-- <div class="relative">
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Sub-Category</label>
                                    <select id="subCategory"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300">
                                        <option value="">Select Sub-Category</option>
                                    </select>
                                    <div
                                        class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                        💡 Specific category helps faster routing
                                    </div>
                                </div> -->
                        <div class="md:col-span-2 relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-3"
                                data-translate="location">Location Details *</label>
                            <div class="flex space-x-4">
                                <input type="text" id="complaintLocation" name="location" required
                                    class="flex-1 border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300"
                                    placeholder="Enter detailed address">
                                <button type="button" onclick="getCurrentLocation()"
                                    class="bg-green-600 text-white px-6 py-3 rounded-xl hover:bg-green-700 transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-map-marker-alt mr-2"></i>GPS
                                </button>
                            </div>
                            <div
                                class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                💡 Include landmarks for better identification
                            </div>
                        </div>
                        <div class="md:col-span-2 relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-3"
                                data-translate="complaint_title">Complaint Title *</label>
                            <input type="text" id="complaintTitle" name="title" required
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300"
                                placeholder="Brief, descriptive title">
                            <div id="titleSuggestions" class="suggestion-box hidden"></div>
                            <div
                                class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                💡 Be specific and concise
                            </div>
                        </div>
                        <div class="md:col-span-2 relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-3"
                                data-translate="description">Detailed Description *</label>
                            <textarea id="complaintDescription" required rows="6" name="description"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300"
                                placeholder="Describe the issue in detail..."></textarea>
                            <div
                                class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                💡 Include when, where, what happened
                            </div>
                            <div class="mt-2 text-sm text-gray-500">
                                <span id="charCount">0</span>/1000 characters
                            </div>
                        </div>
                        <div class="relative">
                                    <label class="block text-sm font-semibold text-gray-700 mb-3"
                                        data-translate="priority">Priority Level</label>
                                    <select id="complaintPriority"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300" name="priority">
                                        <option value="low">🟢 Low - General issues</option>
                                        <option value="medium" selected>🟡 Medium - Moderate impact</option>
                                        <option value="high">🟠 High - Significant impact</option>
                                        <option value="urgent">🔴 Urgent - Immediate attention needed</option>
                                    </select>
                                    <div
                                        class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                        💡 Choose based on impact and urgency
                                    </div>
                                </div>
                        <div class="relative">
                                    <label class="block text-sm font-semibold text-gray-700 mb-3">Expected Resolution
                                        Time</label>
                                    <select id="expectedTime"
                                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 hover:border-gray-300" name="status">
                                        <option value="">Select timeframe</option>
                                        <option value="immediate">Immediate (within 24 hours)</option>
                                        <option value="week">Within a week</option>
                                        <option value="month">Within a month</option>
                                        <option value="flexible">Flexible timeline</option>
                                    </select>
                                    <div
                                        class="suggestion-tooltip absolute -top-2 right-0 bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs opacity-0 transition-opacity duration-300">
                                        💡 Realistic expectations help planning
                                    </div>
                                </div>
                    </div>
                    <div class="flex justify-between mt-8">
                        <button type="button" onclick="prevStep()"
                            class="bg-gray-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-all duration-300">
                            <i class="fas fa-arrow-left mr-2"></i>Previous
                        </button>
                        <button type="button" onclick="nextStep()"
                            class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">
                            Next Step <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Review & Submit Section -->
                <div id="step3" class="step-content hidden">
                    <h3 class="text-2xl font-bold mb-8 text-center text-gray-800">Review & Submit</h3>
                    <div class="space-y-6">
                        <div class="relative">
                            <label class="block text-sm font-semibold text-gray-700 mb-3"
                                data-translate="attach_photos">Attach Evidence (Optional)</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 transition-colors duration-300">
                                <input type="file" id="complaintPhotos" name="uploaded_files[]" multiple accept="image/jpeg,image/png" class="hidden">
                                <div onclick="document.getElementById('complaintPhotos').click()" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-lg font-medium text-gray-600 mb-2">Click to upload photos</p>
                                    <p class="text-sm text-gray-500">Maximum 5 photos, 5MB each (JPG, PNG)</p>
                                </div>
                            </div>
                            <div id="photoPreview" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4 hidden">
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-6">
                            <h4 class="font-bold text-lg mb-4">Complaint Summary</h4>
                            <div id="complaintSummary" class="space-y-3 text-sm">
                                <!-- Summary will be populated here -->
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                            <div class="flex items-start space-x-3">
                                <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                <div>
                                    <h4 class="font-bold text-blue-800 mb-2">Important Information</h4>
                                    <ul class="text-sm text-blue-700 space-y-1">
                                        <li>• You will receive a unique complaint ID after submission</li>
                                        <li>• SMS and email notifications will be sent for status updates</li>
                                        <li>• You can track progress in the "My Complaints" section</li>
                                        <li>• False complaints may result in legal action</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3">
                            <input type="checkbox" id="termsAccept" required
                                class="w-5 h-5 text-blue-600 rounded focus:ring-blue-500">
                            <label for="termsAccept" class="text-sm text-gray-700">
                                I agree to the <a href="#" class="text-blue-600 hover:underline">Terms &
                                Conditions</a> and confirm that the information provided is accurate.
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button type="button" onclick="prevStep()"
                            class="bg-gray-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-gray-700 transition-all duration-300">
                            <i class="fas fa-arrow-left mr-2"></i>Previous
                        </button>
                        <button type="submit"
                            class="bg-gradient-to-r from-green-600 to-blue-600 text-white px-10 py-3 rounded-xl font-bold text-lg hover:from-green-700 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-paper-plane mr-3"></i>Submit Complaint
                        </button>
                    </div>
                </div>

                
            </form>
        </div>
    </div>
</div>

<!-- Login Modal -->
<?php include("login.php"); ?>


<!-- Signup Modal -->
<?php include("signup.php"); ?>

<script>
// Ensure the form submits properly
const complaintForm = document.getElementById('complaintForm');
complaintForm.addEventListener('submit', function(event) {
    // Check if the form is valid
    if (!complaintForm.checkValidity()) {
        event.preventDefault();
        alert('Please fill out all required fields.');
    }
});

// Photo preview functionality
const photoInput = document.getElementById('complaintPhotos');
const photoPreviewContainer = document.getElementById('photoPreview');

photoInput.addEventListener('change', function(event) {
    const files = event.target.files;
    
    // Clear previous previews
    photoPreviewContainer.innerHTML = '';
    
    if (files.length > 0) {
        photoPreviewContainer.classList.remove('hidden');
        
        // Limit to 5 photos
        const maxFiles = Math.min(files.length, 5);
        
        for (let i = 0; i < maxFiles; i++) {
            const file = files[i];
            
            // Check file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                alert(`${file.name} is too large. Maximum size is 5MB.`);
                continue;
            }
            
            // Check file type
            if (!file.type.match('image/jpeg') && !file.type.match('image/png')) {
                alert(`${file.name} is not a valid image. Only JPG and PNG are allowed.`);
                continue;
            }
            
            // Create preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative group';
                previewDiv.innerHTML = `
                    <img src="${e.target.result}" 
                         alt="Preview ${i + 1}" 
                         class="w-full h-32 object-cover rounded-lg border-2 border-gray-300 hover:border-blue-500 transition-all duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 rounded-lg flex items-center justify-center">
                        <span class="text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            ${file.name}
                        </span>
                    </div>
                    <button type="button" 
                            onclick="removePhoto(${i})" 
                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors duration-300 opacity-0 group-hover:opacity-100">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                `;
                photoPreviewContainer.appendChild(previewDiv);
            };
            reader.readAsDataURL(file);
        }
        
        if (files.length > 5) {
            alert('Maximum 5 photos allowed. Only the first 5 will be uploaded.');
        }
    } else {
        photoPreviewContainer.classList.add('hidden');
    }
});

// Function to remove photo (simplified - resets all)
function removePhoto(index) {
    photoInput.value = '';
    photoPreviewContainer.innerHTML = '';
    photoPreviewContainer.classList.add('hidden');
}
</script>