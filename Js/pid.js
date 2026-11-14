document.addEventListener('DOMContentLoaded', function() {
    const translations = {
        'fr': {
            'Participer au tirage au sort': 'Participer au tirage au sort',
            'La possibilité de participer à un tirage au sort organisé par l\'entreprise pour choisir parmi elle un projet à financer intégralement. Obtenir de nombreux privilèges, y compris une carte de fidélité, qui vous donne droit à des rabais si vous achetez auprès d\'entrepreneurs dans différents domaines.': 'La possibilité de participer à un tirage au sort organisé par l\'entreprise pour choisir parmi elle un projet à financer intégralement. Obtenir de nombreux privilèges, y compris une carte de fidélité, qui vous donne droit à des rabais si vous achetez auprès d\'entrepreneurs dans différents domaines.',
            'Accès à des revenus supplémentaires conformément à la politique de l\'entreprise.': 'Accès à des revenus supplémentaires conformément à la politique de l\'entreprise.',
            'Payer 9500 de monnaie virtuelle pour accéder aux tirages': 'Payer 9500 de monnaie virtuelle pour accéder aux tirages',
            'Vous avez déjà participé au tirage au sort. Veuillez attendre le résultat. Merci pour votre participation!': 'Vous avez déjà participé au tirage au sort. Veuillez attendre le résultat. Merci pour votre participation!',
            'Aucun projet n\'est actuellement disponible pour le tirage au sort.': 'Aucun projet n\'est actuellement disponible pour le tirage au sort.',
            'Idée du projet:': 'Idée du projet:',
            'Audience cible:': 'Audience cible:',
            'Impact technologique:': 'Impact technologique:',
            'Stratégie de marketing:': 'Stratégie de marketing:',
            'Participer au tirage avec le projet sélectionné': 'Participer au tirage avec le projet sélectionné',
            'Une erreur s\'est produite. Veuillez réessayer.': 'Une erreur s\'est produite. Veuillez réessayer.'
        },
        'ar': {
            'Participer au tirage au sort': 'المشاركة في السحب',
            'La possibilité de participer à un tirage au sort organisé par l\'entreprise pour choisir parmi elle un projet à financer intégralement. Obtenir de nombreux privilèges, y compris une carte de fidélité, qui vous donne droit à des rabais si vous achetez auprès d\'entrepreneurs dans différents domaines.': 'فرصة المشاركة في سحب تنظمه الشركة لاختيار مشروع لتمويله بالكامل. الحصول على العديد من الامتيازات، بما في ذلك بطاقة ولاء تمنحك خصومات عند الشراء من رواد الأعمال في مختلف المجالات.',
            'Accès à des revenus supplémentaires conformément à la politique de l\'entreprise.': 'الوصول إلى دخل إضافي وفقًا لسياسة الشركة.',
            'Payer 9500 de monnaie virtuelle pour accéder aux tirages': 'دفع 9500 من العملة الافتراضية للوصول إلى السحوبات',
            'Vous avez déjà participé au tirage au sort. Veuillez attendre le résultat. Merci pour votre participation!': 'لقد شاركت بالفعل في السحب. يرجى انتظار النتيجة. شكرًا لمشاركتك!',
            'Aucun projet n\'est actuellement disponible pour le tirage au sort.': 'لا توجد مشاريع متاحة حاليًا للسحب.',
            'Idée du projet:': 'فكرة المشروع:',
            'Audience cible:': 'الجمهور المستهدف:',
            'Impact technologique:': 'التأثير التكنولوجي:',
            'Stratégie de marketing:': 'استراتيجية التسويق:',
            'Participer au tirage avec le projet sélectionné': 'المشاركة في السحب بالمشروع المحدد',
            'Une erreur s\'est produite. Veuillez réessayer.': 'حدث خطأ. يرجى المحاولة مرة أخرى.'
        },
        'en': {
            'Participer au tirage au sort': 'Participate in the Draw',
            'La possibilité de participer à un tirage au sort organisé par l\'entreprise pour choisir parmi elle un projet à financer intégralement. Obtenir de nombreux privilèges, y compris une carte de fidélité, qui vous donne droit à des rabais si vous achetez auprès d\'entrepreneurs dans différents domaines.': 'The opportunity to participate in a draw organized by the company to choose a project to fully finance. Obtain numerous privileges, including a loyalty card that entitles you to discounts when purchasing from entrepreneurs in various fields.',
            'Accès à des revenus supplémentaires conformément à la politique de l\'entreprise.': 'Access to additional income in accordance with company policy.',
            'Payer 9500 de monnaie virtuelle pour accéder aux tirages': 'Pay 9500 virtual currency to access the draws',
            'Vous avez déjà participé au tirage au sort. Veuillez attendre le résultat. Merci pour votre participation!': 'You have already participated in the draw. Please wait for the result. Thank you for your participation!',
            'Aucun projet n\'est actuellement disponible pour le tirage au sort.': 'No projects are currently available for the draw.',
            'Idée du projet:': 'Project Idea:',
            'Audience cible:': 'Target Audience:',
            'Impact technologique:': 'Technological Impact:',
            'Stratégie de marketing:': 'Marketing Strategy:',
            'Participer au tirage avec le projet sélectionné': 'Participate in the draw with the selected project',
            'Une erreur s\'est produite. Veuillez réessayer.': 'An error occurred. Please try again.'
        }
    };

    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }

    function getCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for(let i=0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function translatePage(lang) {
        document.querySelectorAll('[data-translate]').forEach(element => {
            const key = element.getAttribute('data-translate');
            if (translations[lang] && translations[lang][key]) {
                element.textContent = translations[lang][key];
            }
        });

        document.querySelectorAll('h1, h2, h3, p, label, button').forEach(element => {
            const key = element.textContent.trim();
            if (translations[lang] && translations[lang][key]) {
                element.textContent = translations[lang][key];
            }
        });

        document.body.style.direction = lang === 'ar' ? 'rtl' : 'ltr';

        setCookie('language', lang, 30);
    }

    function initPage() {
        let lang = getCookie('language');
        if (!lang) {
            lang = 'fr';
        }
        translatePage(lang);
    }

    function reloadPage() {
        window.location.reload();
    }

    document.getElementById('fr-link').addEventListener('click', (e) => {
        e.preventDefault();
        translatePage('fr');
        setTimeout(reloadPage, 100);
    });

    document.getElementById('ar-link').addEventListener('click', (e) => {
        e.preventDefault();
        translatePage('ar');
        setTimeout(reloadPage, 100);
    });

    document.getElementById('en-link').addEventListener('click', (e) => {
        e.preventDefault();
        translatePage('en');
        setTimeout(reloadPage, 100);
    });

    initPage();
});