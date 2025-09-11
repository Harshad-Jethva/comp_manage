console.log("Main JS loaded");
// Language translations
const translations = {
    en: {
        file_complaint: "File Complaint",
        track_complaints: "Track Complaints",
        features_title: "Why Choose FIXBOT?",
        ai_assistance: "AI-Powered Assistance",
        ai_description: "Our advanced AI chatbot provides intelligent suggestions, guides you through the complaint process, and offers personalized recommendations based on your specific situation and location.",
        real_time: "Real-time Tracking",
        tracking_description: "Monitor your complaint journey with live updates, detailed progress reports, and transparent communication from relevant authorities throughout the resolution process.",
        multilingual: "Multilingual Support",
        language_description: "Access FIXBOT in your preferred language with comprehensive support for English, Hindi, and Gujarati, ensuring no language barrier prevents you from seeking justice.",
        sectors_title: "Comprehensive Sector Coverage",
        college: "Educational Institutions",
        police: "Law Enforcement",
        municipal: "Municipal Services",
        healthcare: "Healthcare Services",
        file_new_complaint: "File New Complaint",
        full_name: "Full Name",
        email: "Email Address",
        phone: "Phone Number",
        sector: "Complaint Sector",
        location: "Location Details",
        complaint_title: "Complaint Title",
        description: "Detailed Description",
        priority: "Priority Level",
        attach_photos: "Attach Evidence",
        complaint_history: "Complaint History & Tracking",
        about_us: "About FIXBOT",
        our_mission: "Our Mission",
        mission_text: "FIXBOT is revolutionizing complaint management by providing an AI-powered platform that connects citizens with relevant authorities across multiple sectors. Our mission is to make complaint filing and resolution more efficient, transparent, and accessible to everyone, regardless of their technical expertise or language preferences.",
        key_features: "Advanced Features & Capabilities",
        ai_powered: "Advanced AI Processing",
        ai_feature_desc: "Our sophisticated AI engine analyzes complaints using natural language processing, automatically categorizes issues, and provides intelligent routing to appropriate authorities.",
        multi_sector: "Comprehensive Integration",
        sector_feature_desc: "Seamlessly integrated with multiple government departments and public service sectors, ensuring your complaints reach the right desk without bureaucratic delays.",
        our_team: "Meet Our Team",
        contact_us: "Get in Touch",
        get_in_touch: "Send us a Message",
        name: "Full Name",
        message: "Message",
        our_office: "Our Office",
        email_us: "Email Us",
        call_us: "Call Us",
        working_hours: "Working Hours",
        connect_with_us: "Connect With Us"
    },
    hi: {
        file_complaint: "शिकायत दर्ज करें",
        track_complaints: "शिकायत ट्रैक करें",
        features_title: "FIXBOT क्यों चुनें?",
        ai_assistance: "AI-संचालित सहायता",
        ai_description: "हमारा उन्नत AI चैटबॉट बुद्धिमान सुझाव प्रदान करता है, आपको शिकायत प्रक्रिया के माध्यम से मार्गदर्शन करता है, और आपकी विशिष्ट स्थिति और स्थान के आधार पर व्यक्तिगत सिफारिशें प्रदान करता है।",
        real_time: "रीयल-टाइम ट्रैकिंग",
        tracking_description: "समाधान प्रक्रिया के दौरान प्रासंगिक अधिकारियों से लाइव अपडेट, विस्तृत प्रगति रिपोर्ट और पारदर्शी संचार के साथ अपनी शिकायत यात्रा की निगरानी करें।",
        multilingual: "बहुभाषी समर्थन",
        language_description: "अंग्रेजी, हिंदी और गुजराती के लिए व्यापक समर्थन के साथ अपनी पसंदीदा भाषा में FIXBOT तक पहुंचें, यह सुनिश्चित करते हुए कि कोई भाषा बाधा आपको न्याय की तलाश से नहीं रोकती है।",
        sectors_title: "व्यापक क्षेत्र कवरेज",
        college: "शैक्षणिक संस्थान",
        police: "कानून प्रवर्तन",
        municipal: "नगरपालिका सेवाएं",
        healthcare: "स्वास्थ्य सेवाएं",
        file_new_complaint: "नई शिकायत दर्ज करें",
        full_name: "पूरा नाम",
        email: "ईमेल पता",
        phone: "फोन नंबर",
        sector: "शिकायत क्षेत्र",
        location: "स्थान विवरण",
        complaint_title: "शिकायत शीर्षक",
        description: "विस्तृत विवरण",
        priority: "प्राथमिकता स्तर",
        attach_photos: "साक्ष्य संलग्न करें",
        complaint_history: "शिकायत इतिहास और ट्रैकिंग",
        about_us: "FIXBOT के बारे में",
        our_mission: "हमारा मिशन",
        mission_text: "FIXBOT एक AI-संचालित प्लेटफॉर्म प्रदान करके शिकायत प्रबंधन में क्रांति ला रहा है जो कई क्षेत्रों में नागरिकों को प्रासंगिक अधिकारियों से जोड़ता है। हमारा मिशन शिकायत दर्ज करने और समाधान को अधिक कुशल, पारदर्शी और सभी के लिए सुलभ बनाना है, चाहे उनकी तकनीकी विशेषज्ञता या भाषा प्राथमिकताएं कुछ भी हों।",
        key_features: "उन्नत सुविधाएं और क्षमताएं",
        ai_powered: "उन्नत AI प्रसंस्करण",
        ai_feature_desc: "हमारा परिष्कृत AI इंजन प्राकृतिक भाषा प्रसंस्करण का उपयोग करके शिकायतों का विश्लेषण करता है, मुद्दों को स्वचालित रूप से वर्गीकृत करता है, और उचित अधिकारियों को बुद्धिमान रूटिंग प्रदान करता है।",
        multi_sector: "व्यापक एकीकरण",
        sector_feature_desc: "कई सरकारी विभागों और सार्वजनिक सेवा क्षेत्रों के साथ निर्बाध रूप से एकीकृत, यह सुनिश्चित करता है कि आपकी शिकायतें बिना किसी नौकरशाही देरी के सही डेस्क तक पहुंचें।",
        our_team: "हमारी टीम से मिलें",
        contact_us: "संपर्क करें",
        get_in_touch: "हमें एक संदेश भेजें",
        name: "पूरा नाम",
        message: "संदेश",
        our_office: "हमारा कार्यालय",
        email_us: "हमें ईमेल करें",
        call_us: "हमें कॉल करें",
        working_hours: "कार्य घंटे",
        connect_with_us: "हमसे जुड़ें"
    },
    gu: {
        file_complaint: "ફરિયાદ દાખલ કરો",
        track_complaints: "ફરિયાદો ટ્રૅક કરો",
        features_title: "FIXBOT શા માટે પસંદ કરો?",
        ai_assistance: "AI-સક્ષમ સહાય",
        ai_description: "અમારી અદ્યતન AI ચેટબોટ બુદ્ધિશાળી સૂચનો પ્રદાન કરે છે, તમને ફરિયાદ પ્રક્રિયા દ્વારા માર્ગદર્શન આપે છે, અને તમારી ચોક્કસ પરિસ્થિતિ અને સ્થાનના આધારે વ્યક્તિગત ભલામણો પ્રદાન કરે છે.",
        real_time: "રીઅલ-ટાઇમ ટ્રૅકિંગ",
        tracking_description: "રિઝોલ્યુશન પ્રક્રિયા દરમિયાન સંબંધિત અધિકારીઓ તરફથી લાઇવ અપડેટ્સ, વિગતવાર પ્રગતિ અહેવાલો અને પારદર્શક સંચાર સાથે તમારી ફરિયાદ યાત્રાને મોનિટર કરો.",
        multilingual: "બહુભાષી સપોર્ટ",
        language_description: "અંગ્રેજી, હિન્દી અને ગુજરાતી માટે વ્યાપક સપોર્ટ સાથે તમારી પસંદગીની ભાષામાં FIXBOT ઍક્સેસ કરો, ખાતરી કરો કે કોઈ ભાષા અવરોધ તમને ન્યાય માટે પ્રયાસ કરતા અટકાવે નહીં.",
        sectors_title: "વ્યાપક સેક્ટર કવરેજ",
        college: "શૈક્ષણિક સંસ્થાઓ",
        police: "કાયદા અમલીકરણ",
        municipal: "મ્યુનિસિપલ સેવાઓ",
        healthcare: "આરોગ્ય સેવાઓ",
        file_new_complaint: "નવી ફરિયાદ દાખલ કરો",
        full_name: "પૂરું નામ",
        email: "ઇમેઇલ સરનામું",
        phone: "ફોન નંબર",
        sector: "ફરિયાદ સેક્ટર",
        location: "સ્થાન વિગતો",
        complaint_title: "ફરિયાદ શીર્ષક",
        description: "વિગતવાર વર્ણન",
        priority: "પ્રાથમિકતા સ્તર",
        attach_photos: "સાક્ષ્ય જોડો",
        complaint_history: "ફરિયાદ ઇતિહાસ અને ટ્રૅકિંગ",
        about_us: "FIXBOT વિશે",
        our_mission: "અમારું મિશન",
        mission_text: "FIXBOT એક AI-સક્ષમ પ્લેટફોર્મ પ્રદાન કરીને ફરિયાદ મેનેજમેન્ટમાં ક્રાંતિ લાવી રહ્યું છે જે બહુવિધ સેક્ટરોમાં નાગરિકોને સંબંધિત અધિકારીઓ સાથે જોડે છે. અમારું મિશન ફરિયાદ ફાઇલિંગ અને રિઝોલ્યુશનને વધુ કાર્યક્ષમ, પારદર્શક અને દરેક માટે સુલભ બનાવવાનું છે, ભલે તેમની તકનીકી નિપુણતા અથવા ભાષા પસંદગીઓ ગમે તે હોય.",
        key_features: "અદ્યતન સુવિધાઓ અને ક્ષમતાઓ",
        ai_powered: "અદ્યતન AI પ્રોસેસિંગ",
        ai_feature_desc: "અમારી અદ્યતન AI એન્જિન કુદરતી ભાષા પ્રોસેસિંગનો ઉપયોગ કરીને ફરિયાદોનું વિશ્લેષણ કરે છે, મુદ્દાઓને આપમેળે વર્ગીકૃત કરે છે, અને યોગ્ય અધિકારીઓને બુદ્ધિશાળી રૂટિંગ પ્રદાન કરે છે.",
        multi_sector: "વ્યાપક સંકલન",
        sector_feature_desc: "બહુવિધ સરકારી વિભાગો અને જનસેવા ક્ષેત્રો સાથે સીમલેસ રીતે સંકલિત, ખાતરી કરે છે કે તમારી ફરિયાદો કોઈ નોકરશાહી વિલંબ વિના યોગ્ય ડેસ્ક પર પહોંચે.",
        our_team: "અમારી ટીમ સાથે મળો",
        contact_us: "અમારો સંપર્ક કરો",
        get_in_touch: "અમને સંદેશ મોકલો",
        name: "પૂરું નામ",
        message: "સંદેશ",
        our_office: "અમારું ઓફિસ",
        email_us: "અમને ઇમેઇલ કરો",
        call_us: "અમને કોલ કરો",
        working_hours: "કામ કરવાના સમય",
        connect_with_us: "અમારી સાથે જોડાઓ"
    }
};

// Current language
let currentLanguage = 'en';

// DOM elements
const languageSelector = document.getElementById('languageSelector');
const elementsToTranslate = document.querySelectorAll('[data-translate]');

// Initialize the page
document.addEventListener('DOMContentLoaded', function () {
    // Simulate loading screen
    setTimeout(() => {
        document.getElementById('loadingScreen').style.display = 'none';
        showPage('home');
    }, 2000);

    // Initialize scroll reveal animations
    initScrollReveal();

    // Initialize counters
    initCounters();

    // Set up language selector
    languageSelector.addEventListener('change', function () {
        currentLanguage = this.value;
        translatePage();
    });

    // Set up mobile menu toggle
    document.getElementById('mobileMenuBtn').addEventListener('click', function () {
        const mobileMenu = document.getElementById('mobileMenu');
        mobileMenu.classList.toggle('hidden');
        this.innerHTML = mobileMenu.classList.contains('hidden') ?
            '<i class="fas fa-bars text-xl"></i>' :
            '<i class="fas fa-times text-xl"></i>';
    });

    // Set up chatbot toggle
    document.getElementById('chatbotToggle').addEventListener('click', function () {
        const chatWindow = document.getElementById('chatbotWindow');
        chatWindow.classList.toggle('active');
    });

    document.getElementById('chatbotClose').addEventListener('click', function () {
        document.getElementById('chatbotWindow').classList.remove('active');
    });

    // Set up complaint form
    setupComplaintForm();

    // Load sample complaints for history page
    loadSampleComplaints();

    // Set up tooltips
    setupTooltips();

    console.log('Chatbot script loaded');

    const chatbotToggle = document.getElementById('chatbotToggle');
    const chatbotClose = document.getElementById('chatbotClose');
    const chatWindow = document.getElementById('chatbotWindow');

    if (chatbotToggle && chatbotClose && chatWindow) {
        console.log('Chatbot elements found');

        chatbotToggle.addEventListener('click', function () {
            console.log('Chatbot toggle clicked');
            chatWindow.classList.toggle('active');
        });

        chatbotClose.addEventListener('click', function () {
            console.log('Chatbot close clicked');
            chatWindow.classList.remove('active');
        });
    } else {
        console.error('Chatbot elements not found in the DOM');
    }
});

// Initialize scroll reveal animations
function initScrollReveal() {
    const scrollReveals = document.querySelectorAll('.scroll-reveal');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
            }
        });
    }, {
        threshold: 0.1
    });

    scrollReveals.forEach(element => {
        observer.observe(element);
    });
}

// Initialize counters
function initCounters() {
    const counters = document.querySelectorAll('.counter');
    const speed = 200;

    counters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        const count = +counter.innerText;
        const increment = target / speed;

        if (count < target) {
            counter.innerText = Math.ceil(count + increment);
            setTimeout(initCounters, 1);
        } else {
            counter.innerText = target;
        }
    });
}

// Translate the page
function translatePage() {
    elementsToTranslate.forEach(element => {
        const key = element.getAttribute('data-translate');
        if (translations[currentLanguage] && translations[currentLanguage][key]) {
            element.textContent = translations[currentLanguage][key];
        }
    });
}

// Show a specific page
function showPage(pageId) {
    document.querySelectorAll('.page').forEach(page => {
        page.classList.add('hidden');
    });
    document.getElementById(pageId + 'Page').classList.remove('hidden');
    window.scrollTo(0, 0);

    // Special initialization for certain pages
    if (pageId === 'history') {
        filterComplaints();
    }
}

// Show modal
function showModal(modalId) {
    document.getElementById(modalId).classList.add('active');
}

// Hide modal
function hideModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// Set up complaint form functionality
function setupComplaintForm() {
    const form = document.getElementById('complaintForm');
    const complaintTitle = document.getElementById('complaintTitle');
    const complaintDescription = document.getElementById('complaintDescription');
    const complaintSector = document.getElementById('complaintSector');
    const subCategory = document.getElementById('subCategory');
    const complaintPhotos = document.getElementById('complaintPhotos');
    const photoPreview = document.getElementById('photoPreview');
    const charCount = document.getElementById('charCount');

    // Title suggestions
    complaintTitle.addEventListener('input', function () {
        const title = this.value.toLowerCase();
        const suggestions = document.getElementById('titleSuggestions');

        if (title.length > 2) {
            const sampleTitles = [
                "Road potholes near Gandhi Nagar",
                "Water leakage on Main Street",
                "Garbage not collected for 3 days",
                "Street light not working",
                "Unauthorized construction",
                "Noise pollution from factory",
                "Corrupt official demanding bribe",
                "College fees excessive"
            ];

            const matches = sampleTitles.filter(t => t.toLowerCase().includes(title));

            if (matches.length > 0) {
                suggestions.innerHTML = '';
                matches.forEach(match => {
                    const div = document.createElement('div');
                    div.className = 'suggestion-item';
                    div.textContent = match;
                    div.addEventListener('click', function () {
                        complaintTitle.value = match;
                        suggestions.classList.add('hidden');
                    });
                    suggestions.appendChild(div);
                });
                suggestions.classList.remove('hidden');
            } else {
                suggestions.classList.add('hidden');
            }
        } else {
            suggestions.classList.add('hidden');
        }
    });

    // Hide suggestions when clicking elsewhere
    document.addEventListener('click', function (e) {
        if (e.target !== complaintTitle) {
            document.getElementById('titleSuggestions').classList.add('hidden');
        }
    });

    // Character count for description
    complaintDescription.addEventListener('input', function () {
        charCount.textContent = this.value.length;
    });

    // Sector subcategories
    complaintSector.addEventListener('change', function () {
        subCategory.innerHTML = '<option value="">Select Sub-Category</option>';

        const subCategories = {
            college: [
                "Admission issues",
                "Fee structure",
                "Faculty behavior",
                "Infrastructure problems",
                "Examination concerns",
                "Scholarship delays"
            ],
            police: [
                "Bribery/corruption",
                "Slow response",
                "Harassment",
                "False case",
                "Traffic violations",
                "Missing person"
            ],
            municipal: [
                "Garbage collection",
                "Road maintenance",
                "Street lighting",
                "Water supply",
                "Drainage issues",
                "Public park maintenance"
            ],
            healthcare: [
                "Hospital cleanliness",
                "Doctor availability",
                "Medicine stock",
                "Ambulance service",
                "Waiting time",
                "Staff behavior"
            ],
            transport: [
                "Bus frequency",
                "Overcrowding",
                "Driver behavior",
                "Bus condition",
                "Route changes",
                "Ticket issues"
            ],
            electricity: [
                "Power outage",
                "Billing issues",
                "Meter problems",
                "Safety hazards",
                "Transformer failure",
                "New connection"
            ],
            water: [
                "Water quality",
                "Supply timing",
                "Leakage",
                "Billing issues",
                "New connection",
                "Tanker service"
            ],
            other: [
                "Environmental issues",
                "Consumer rights",
                "Telecom services",
                "Banking services",
                "Postal services",
                "Other complaints"
            ]
        };

        const selected = subCategories[this.value] || [];
        selected.forEach(item => {
            const option = document.createElement('option');
            option.value = item.toLowerCase().replace(/\s+/g, '-');
            option.textContent = item;
            subCategory.appendChild(option);
        });
    });

    // Photo upload preview
    complaintPhotos.addEventListener('change', function () {
        photoPreview.innerHTML = '';

        if (this.files.length > 0) {
            photoPreview.classList.remove('hidden');

            for (let i = 0; i < Math.min(this.files.length, 5); i++) {
                const file = this.files[i];
                const reader = new FileReader();

                reader.onload = function (e) {
                    const div = document.createElement('div');
                    div.className = 'relative';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-24 object-cover rounded-lg';

                    const btn = document.createElement('button');
                    btn.className = 'absolute -top-2 -right-2 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center';
                    btn.innerHTML = '<i class="fas fa-times text-xs"></i>';
                    btn.addEventListener('click', function () {
                        div.remove();
                        if (photoPreview.children.length === 0) {
                            photoPreview.classList.add('hidden');
                        }
                    });

                    div.appendChild(img);
                    div.appendChild(btn);
                    photoPreview.appendChild(div);
                };

                reader.readAsDataURL(file);
            }
        } else {
            photoPreview.classList.add('hidden');
        }
    });

    // Form submission
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        showModal('successModal');
    });
}

// Next step in complaint form
function nextStep() {
    const currentStep = document.querySelector('.step-content:not(.hidden)');
    const nextStep = currentStep.nextElementSibling;

    if (nextStep) {
        currentStep.classList.add('hidden');
        nextStep.classList.remove('hidden');

        // Update progress bar
        const progressFill = document.querySelector('.progress-fill');
        if (nextStep.id === 'step2') {
            progressFill.style.width = '66%';
        } else if (nextStep.id === 'step3') {
            progressFill.style.width = '100%';
            updateComplaintSummary();
        }
    }
}

// Previous step in complaint form
function prevStep() {
    const currentStep = document.querySelector('.step-content:not(.hidden)');
    const prevStep = currentStep.previousElementSibling;

    if (prevStep) {
        currentStep.classList.add('hidden');
        prevStep.classList.remove('hidden');

        // Update progress bar
        const progressFill = document.querySelector('.progress-fill');
        if (prevStep.id === 'step1') {
            progressFill.style.width = '33%';
        } else if (prevStep.id === 'step2') {
            progressFill.style.width = '66%';
        }
    }
}

// Update complaint summary
function updateComplaintSummary() {
    const summary = document.getElementById('complaintSummary');
    summary.innerHTML = '';

    const fields = [
        { label: 'Name', id: 'complainantName' },
        { label: 'Email', id: 'complainantEmail' },
        { label: 'Phone', id: 'complainantPhone' },
        { label: 'Age Group', id: 'ageGroup' },
        { label: 'Sector', id: 'complaintSector' },
        { label: 'Sub-Category', id: 'subCategory' },
        { label: 'Location', id: 'complaintLocation' },
        { label: 'Title', id: 'complaintTitle' },
        { label: 'Description', id: 'complaintDescription' },
        { label: 'Priority', id: 'complaintPriority' },
        { label: 'Expected Resolution', id: 'expectedTime' }
    ];

    fields.forEach(field => {
        const element = document.getElementById(field.id);
        if (element && element.value) {
            const div = document.createElement('div');
            div.className = 'flex justify-between';

            const label = document.createElement('span');
            label.className = 'font-medium';
            label.textContent = field.label + ':';

            const value = document.createElement('span');
            value.textContent = element.value;

            div.appendChild(label);
            div.appendChild(value);
            summary.appendChild(div);
        }
    });

    // Add photo count
    const photoCount = document.getElementById('complaintPhotos').files.length;
    if (photoCount > 0) {
        const div = document.createElement('div');
        div.className = 'flex justify-between';

        const label = document.createElement('span');
        label.className = 'font-medium';
        label.textContent = 'Photos Attached:';

        const value = document.createElement('span');
        value.textContent = photoCount;

        div.appendChild(label);
        div.appendChild(value);
        summary.appendChild(div);
    }
}

// Get current location
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            position => {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                // Use a geocoding service to get address (this is a mock implementation)
                const address = `Near ${Math.round(Math.random() * 100)} Street, Based on your current location (${latitude.toFixed(6)}, ${longitude.toFixed(6)})`;
                document.getElementById('complaintLocation').value = address;

                showNotification('success', 'Location detected successfully!');
            },
            error => {
                showNotification('error', 'Unable to retrieve your location. Please enter manually.');
            }
        );
    } else {
        showNotification('error', 'Geolocation is not supported by your browser.');
    }
}

// Load sample complaints for history page
function loadSampleComplaints() {
    const complaints = [
        {
            id: 'FIXBOT-2024-12345',
            title: 'Potholes on Main Road causing accidents',
            sector: 'municipal',
            status: 'in-progress',
            priority: 'high',
            date: '2024-05-15',
            description: 'Large potholes near Gandhi Chowk causing multiple accidents daily. Need urgent repair.',
            updates: [
                { date: '2024-05-15', status: 'Received', message: 'Complaint registered and assigned to Municipal Engineer' },
                { date: '2024-05-16', status: 'In Progress', message: 'Site inspection scheduled for tomorrow' }
            ]
        },
        {
            id: 'FIXBOT-2024-12346',
            title: 'Street light not working for 2 weeks',
            sector: 'municipal',
            status: 'pending',
            priority: 'medium',
            date: '2024-05-10',
            description: 'Street light pole number 45 in Sector 5 not working since May 1st.',
            updates: [
                { date: '2024-05-10', status: 'Received', message: 'Complaint registered and assigned to Electrical Department' }
            ]
        },
        {
            id: 'FIXBOT-2024-12347',
            title: 'College demanding excessive fees',
            sector: 'college',
            status: 'resolved',
            priority: 'urgent',
            date: '2024-04-25',
            description: 'ABC College demanding additional fees not mentioned in prospectus.',
            updates: [
                { date: '2024-04-25', status: 'Received', message: 'Complaint registered and forwarded to Education Department' },
                { date: '2024-04-28', status: 'In Progress', message: 'Notice issued to college for explanation' },
                { date: '2024-05-05', status: 'Resolved', message: 'College directed to refund excess fees' }
            ]
        },
        {
            id: 'FIXBOT-2024-12348',
            title: 'Water leakage near my house',
            sector: 'water',
            status: 'closed',
            priority: 'low',
            date: '2024-04-15',
            description: 'Continuous water leakage from main pipeline near house no. 123, causing water wastage.',
            updates: [
                { date: '2024-04-15', status: 'Received', message: 'Complaint registered and assigned to Water Department' },
                { date: '2024-04-16', status: 'In Progress', message: 'Technician visited site and identified issue' },
                { date: '2024-04-17', status: 'Resolved', message: 'Pipeline repaired and leakage fixed' },
                { date: '2024-04-20', status: 'Closed', message: 'Complaint resolved to satisfaction' }
            ]
        }
    ];

    localStorage.setItem('complaints', JSON.stringify(complaints));
}

// Filter complaints in history page
function filterComplaints() {
    const statusFilter = document.getElementById('statusFilter').value;
    const sectorFilter = document.getElementById('sectorFilter').value;
    const priorityFilter = document.getElementById('priorityFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    const searchInput = document.getElementById('searchInput').value;

    const complaintsList = document.getElementById('complaintsList');
    complaintsList.innerHTML = '';

    fetch('complainhistory.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'filterComplaints',
            status: statusFilter,
            sector: sectorFilter,
            priority: priorityFilter,
            date: dateFilter,
            search: searchInput
        })
    })
    .then(response => response.json())
    .then(complaints => {
        if (complaints.length === 0) {
            complaintsList.innerHTML = `
                <div class="bg-white rounded-3xl shadow-xl p-8 text-center">
                    <i class="fas fa-inbox text-5xl text-gray-400 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">No complaints found</h3>
                    <p class="text-gray-600 mb-6">Try adjusting your filters or file a new complaint.</p>
                </div>
            `;
            return;
        }

        complaints.forEach(complaint => {
            const complaintElement = document.createElement('div');
            complaintElement.className = 'bg-white rounded-3xl shadow-xl overflow-hidden hover-lift';
            complaintElement.innerHTML = `
                <div class="md:flex">
                    <div class="p-6 md:p-8 flex-1">
                        <h3 class="text-xl font-bold mb-2">${complaint.title}</h3>
                        <p class="text-gray-600 mb-4">${complaint.description}</p>
                        <img src="uploads/${complaint.photo}" alt="Complaint Photo" class="w-full h-48 object-cover rounded-lg mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium">${complaint.id}</span>
                            <button onclick="viewComplaintDetails('${complaint.id}')" class="text-blue-600 hover:text-blue-800 font-medium">
                                View Details <i class="fas fa-chevron-right ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            complaintsList.appendChild(complaintElement);
        });
    })
    .catch(error => {
        console.error('Error fetching complaints:', error);
    });
}

// View complaint details (mock implementation)
function viewComplaintDetails(id) {
    const complaints = JSON.parse(localStorage.getItem('complaints')) || [];
    const complaint = complaints.find(c => c.id === id);

    if (complaint) {
        const statusColors = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'in-progress': 'bg-blue-100 text-blue-800',
            'resolved': 'bg-green-100 text-green-800',
            'closed': 'bg-gray-100 text-gray-800'
        };

        const priorityIcons = {
            'low': '🟢',
            'medium': '🟡',
            'high': '🟠',
            'urgent': '🔴'
        };

        const sectorIcons = {
            'college': '🎓',
            'police': '🚔',
            'municipal': '🏛️',
            'healthcare': '🏥',
            'transport': '🚌',
            'electricity': '⚡',
            'water': '💧',
            'other': '📋'
        };

        const modalContent = `
                    <div class="p-8">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h2 class="text-2xl font-bold">${complaint.title}</h2>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="text-sm font-medium ${statusColors[complaint.status]} px-3 py-1 rounded-full">
                                        ${complaint.status.replace('-', ' ')}
                                    </span>
                                    <span class="text-sm">${priorityIcons[complaint.priority]} ${complaint.priority}</span>
                                    <span class="text-sm">${sectorIcons[complaint.sector]} ${complaint.sector}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Filed on</div>
                                <div class="font-medium">${complaint.date}</div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 rounded-xl p-6 mb-6">
                            <h3 class="font-bold mb-3">Complaint Details</h3>
                            <p class="text-gray-700">${complaint.description}</p>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="font-bold mb-4">Status Updates</h3>
                            <div class="space-y-4">
                                ${complaint.updates.map(update => `
                                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                                        <div class="flex justify-between mb-1">
                                            <span class="font-medium">${update.status}</span>
                                            <span class="text-sm text-gray-500">${update.date}</span>
                                        </div>
                                        <p class="text-gray-700">${update.message}</p>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button onclick="hideModal('complaintDetailsModal')" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-xl font-medium hover:bg-gray-300 transition-colors duration-300">
                                Close
                            </button>
                            <button class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-2 rounded-xl font-medium hover:from-blue-700 hover:to-purple-700 transition-all duration-300">
                                <i class="fas fa-print mr-2"></i>Print
                            </button>
                        </div>
                    </div>
                `;

        // Create a modal if it doesn't exist
        if (!document.getElementById('complaintDetailsModal')) {
            const modal = document.createElement('div');
            modal.id = 'complaintDetailsModal';
            modal.className = 'modal';
            modal.innerHTML = `
                        <div class="modal-content bg-white rounded-3xl shadow-2xl w-full max-w-2xl">
                            ${modalContent}
                        </div>
                    `;
            document.body.appendChild(modal);
        } else {
            document.getElementById('complaintDetailsModal').querySelector('.modal-content').innerHTML = modalContent;
        }

        showModal('complaintDetailsModal');
    }
}

// Show notification
function showNotification(type, message) {
    const notification = document.getElementById('notification');
    const icon = document.getElementById('notificationIcon');
    const text = document.getElementById('notificationText');

    // Set notification content
    text.textContent = message;

    // Set notification style based on type
    notification.className = 'notification';
    if (type === 'success') {
        notification.classList.add('bg-green-500');
        icon.className = 'fas fa-check-circle text-xl';
    } else if (type === 'error') {
        notification.classList.add('bg-red-500');
        icon.className = 'fas fa-exclamation-circle text-xl';
    } else if (type === 'warning') {
        notification.classList.add('bg-yellow-500');
        icon.className = 'fas fa-exclamation-triangle text-xl';
    } else {
        notification.classList.add('bg-blue-500');
        icon.className = 'fas fa-info-circle text-xl';
    }

    // Show notification
    notification.classList.add('show');

    // Hide after 5 seconds
    setTimeout(() => {
        notification.classList.remove('show');
    }, 5000);
}

// Set up tooltips
function setupTooltips() {
    const tooltips = document.querySelectorAll('.suggestion-tooltip');

    tooltips.forEach(tooltip => {
        const input = tooltip.parentElement.querySelector('input, select, textarea');

        if (input) {
            input.addEventListener('focus', () => {
                tooltip.style.opacity = '1';
            });

            input.addEventListener('blur', () => {
                tooltip.style.opacity = '0';
            });
        }
    });
}