const translations = {
    'fr': {
        'Accueil': 'Accueil',
        'Formation Professionnelle': 'Formation Professionnelle',
        'Aide aux projets': 'Aide aux projets',
        'Investissement': 'Investissement',
        'S\'Inscrire': 'S\'Inscrire',
        'Se Connecter': 'Se Connecter',
        'Paramètres': 'Paramètres',
        'Profil': 'Profil',
        'Réseaux': 'Réseaux',
        "Acheter l'argent virtuel": "Acheter l'argent virtuel",
        'Déconnexion': 'Déconnexion',
        'Mes Courses': 'Mes Courses',
        'À propos d\'Hope to Help Dz': 'À propos d\'Hope to Help Dz',
        'Contactez-nous': 'Contactez-nous',
        'Conditions': 'Conditions',
        'Inscrivez-vous à notre newsletter': 'Inscrivez-vous à notre newsletter',
        'Entrez votre adresse e-mail': 'Entrez votre adresse e-mail',
        'S\'abonner': 'S\'abonner',
        'Tous droits réservés.': 'Tous droits réservés.',
        'Bientôt disponible': 'Bientôt disponible',
        'Restez fort, restez positif et n\'abandonnez jamais !': 'Restez fort, restez positif et n\'abandonnez jamais !',
        'Afficher les filtres': 'Afficher les filtres',
        'Masquer les filtres': 'Masquer les filtres',
        days: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
        months: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]
    },
    'ar': {
        'Accueil': 'الرئيسية',
        'Formation Professionnelle': 'التكوين المهني',
        'Aide aux projets': 'دعم المشاريع',
        'Investissement': 'الاستثمار',
        'S\'Inscrire': 'التسجيل',
        'Se Connecter': 'تسجيل الدخول',
        'Paramètres': 'الإعدادات',
        'Profil': 'الملف الشخصي',
        'Réseaux': 'الشبكات',
        "Acheter l'argent virtuel": "شراء النقود الافتراضية",
        'Déconnexion': 'تسجيل الخروج',
        'Mes Courses': 'دوراتي',
        'À propos d\'Hope to Help Dz': 'عن Hope to Help Dz',
        'Contactez-nous': 'اتصل بنا',
        'Conditions': 'الشروط',
        'Inscrivez-vous à notre newsletter': 'اشترك في نشرتنا الإخبارية',
        'Entrez votre adresse e-mail': 'أدخل عنوان بريدك الإلكتروني',
        'S\'abonner': 'اشتراك',
        'Tous droits réservés.': 'جميع الحقوق محفوظة.',
        'Bientôt disponible': 'قريباً',
        'Restez fort, restez positif et n\'abandonnez jamais !': 'ابق قوياً، ابق إيجابياً ولا تستسلم أبداً!',
        'Afficher les filtres': 'إظهار الفلاتر',
        'Masquer les filtres': 'إخفاء الفلاتر',
        days: ["الأحد", "الاثنين", "الثلاثاء", "الأربعاء", "الخميس", "الجمعة", "السبت"],
        months: ["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"]
    },
    'en': {
        'Accueil': 'Home',
        'Formation Professionnelle': 'Professional Training',
        'Aide aux projets': 'projects assistance',
        'Investissement': 'Investment',
        'S\'Inscrire': 'Sign Up',
        'Se Connecter': 'Log In',
        'Paramètres': 'Settings',
        'Profil': 'Profile',
        'Réseaux': 'Networks',
        "Acheter l'argent virtuel": "Buy Virtual Money",
        'Déconnexion': 'Log Out',
        'Mes Courses': 'My Courses',
        'À propos d\'Hope to Help Dz': 'About Hope to Help Dz',
        'Contactez-nous': 'Contact Us',
        'Conditions': 'Terms',
        'Inscrivez-vous à notre newsletter': 'Subscribe to our newsletter',
        'Entrez votre adresse e-mail': 'Enter your email address',
        'S\'abonner': 'Subscribe',
        'Tous droits réservés.': 'All rights reserved.',
        'Bientôt disponible': 'Coming soon',
        'Restez fort, restez positif et n\'abandonnez jamais !': 'Stay strong, stay positive, and never give up!',
        'Afficher les filtres': 'Show filters',
        'Masquer les filtres': 'Hide filters',
        days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
    }
};

let currentLanguage = 'fr';

function updateTime() {
    var currentTime = new Date();
    var hours = currentTime.getHours();
    var minutes = currentTime.getMinutes();
    var seconds = currentTime.getSeconds();
    var dayOfWeek = getDayOfWeek(currentTime.getDay());
    var day = currentTime.getDate();
    var month = getMonthName(currentTime.getMonth());
    var year = currentTime.getFullYear();

    minutes = (minutes < 10 ? "0" : "") + minutes;
    seconds = (seconds < 10 ? "0" : "") + seconds;

    var timeString = hours + ':' + minutes + ':' + seconds;
    var dateString = dayOfWeek + ' ' + day + ' ' + month + ' ' + year;

    var dateTimeString = timeString + ' ' + dateString;

    document.querySelector(".date-time").innerHTML = dateTimeString;

    setTimeout(updateTime, 1000);
}

function getDayOfWeek(dayIndex) {
    return translations[currentLanguage].days[dayIndex];
}

function getMonthName(monthIndex) {
    return translations[currentLanguage].months[monthIndex];
}

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function translatePage(lang) {
    currentLanguage = lang;
    setCookie('language', lang, 30);

    document.querySelectorAll('[data-translate]').forEach(element => {
        const key = element.getAttribute('data-translate');
        if (translations[lang] && translations[lang][key]) {
            if (element.tagName === 'INPUT') {
                if (element.type === 'text') {
                    element.placeholder = translations[lang][key];
                } else if (element.type === 'submit') {
                    element.value = translations[lang][key];
                }
            } else {
                element.textContent = translations[lang][key];
            }
        }
    });

    document.documentElement.lang = lang;
    document.querySelector('html').setAttribute('lang', lang);
    
    document.body.style.direction = lang === 'ar' ? 'rtl' : 'ltr';

    updateTime();
}

function toggleComingSoonText(item) {
    const originalText = item.getAttribute('data-translate');
    const comingSoonText = translations[currentLanguage]['Bientôt disponible'];

    if (item.textContent === translations[currentLanguage][originalText]) {
        item.textContent = comingSoonText;
    }

    setTimeout(function() {
        if (item.textContent === comingSoonText) {
            item.textContent = translations[currentLanguage][originalText];
        }
    }, 2000);
}

$(document).ready(function(){
    $('.change-text').click(function(event){
        event.preventDefault();
        toggleComingSoonText(this);
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const dropdownContainers = document.querySelectorAll(".dropdown, .custom-dropdown");
    const settingsDropdowns = document.querySelectorAll(".dropdown-content, .custom-dropdown-content");

    function isInsideDropdown(element) {
        return Array.from(dropdownContainers).some(container => container.contains(element));
    }

    dropdownContainers.forEach(container => {
        container.addEventListener("click", function (event) {
            const isSettingsBtn = event.target.closest("#settingsBtn, .custom-dropbtn");
            const dropdownContent = this.querySelector(".dropdown-content, .custom-dropdown-content");
            
            if (isSettingsBtn) {
                event.preventDefault();
                const isVisible = window.getComputedStyle(dropdownContent).display !== "none";
                dropdownContent.style.display = isVisible ? "none" : "block";
            } else {
                dropdownContent.style.display = "none";
            }
        });
    });

    document.addEventListener("click", function (event) {
        if (!isInsideDropdown(event.target)) {
            settingsDropdowns.forEach(dropdown => {
                dropdown.style.display = "none";
            });
        }
    });
});

function toggleMenu() {
    const menu = document.querySelector('.menu');
    const authLinks = document.querySelector('.auth-links');
    menu.classList.toggle('active');
    authLinks.classList.toggle('active');
}

$(document).ready(function() {
    $('#showFilterBtn').click(function() {
        $('body').toggleClass('show-filter');

        var buttonText = $(this).text();
        var showFiltersText = translations[currentLanguage]['Afficher les filtres'];
        var hideFiltersText = translations[currentLanguage]['Masquer les filtres'];
        
        if ($('body').hasClass('show-filter')) {
            $(this).text(hideFiltersText);
        } else {
            $(this).text(showFiltersText);
        }
    });
});

document.getElementById('fr-link').addEventListener('click', (e) => {
    e.preventDefault();
    translatePage('fr');
});

document.getElementById('ar-link').addEventListener('click', (e) => {
    e.preventDefault();
    translatePage('ar');
});

document.getElementById('en-link').addEventListener('click', (e) => {
    e.preventDefault();
    translatePage('en');
});

document.addEventListener('DOMContentLoaded', () => {
    const savedLanguage = getCookie('language');
    if (savedLanguage && translations[savedLanguage]) {
        translatePage(savedLanguage);
    } else {
        translatePage('fr');
    }
    updateTime();
});

document.addEventListener("DOMContentLoaded", function () {
    const slides = document.querySelectorAll('.slide');
    const indicatorsContainer = document.querySelector('.slider-indicators');

    slides.forEach((slide, index) => {
        const indicator = document.createElement('div');
        indicator.classList.add('indicator');
        indicator.setAttribute('data-index', index);
        indicatorsContainer.appendChild(indicator);
    });

    const indicators = document.querySelectorAll('.indicator');

    let currentSlide = 0;

    function showSlide(index) {
        const translateValue = -index * 100 + '%';
        document.querySelector('.slider-wrapper').style.transform = 'translateX(' + translateValue + ')';
        
        indicators.forEach((indicator, i) => {
            indicator.classList.toggle('active', i === index);
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    }

    showSlide(currentSlide);

    setInterval(nextSlide, 5000);

    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', function () {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });
});