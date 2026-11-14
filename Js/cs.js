document.addEventListener('DOMContentLoaded', function() {
    const translations = {
        'fr': {
            'Mes courses': 'Mes courses',
            'Vous n\'avez pas de cours en cours de visionnage.': 'Vous n\'avez pas de cours en cours de visionnage.',
            'Regarder': 'Regarder',
            'DA': 'DA'
        },
        'ar': {
            'Mes courses': 'دوراتي',
            'Vous n\'avez pas de cours en cours de visionnage.': 'ليس لديك دورات قيد المشاهدة.',
            'Regarder': 'مشاهدة',
            'DA': 'دج'
        },
        'en': {
            'Mes courses': 'My Courses',
            'Vous n\'avez pas de cours en cours de visionnage.': 'You don\'t have any courses in progress.',
            'Regarder': 'Watch',
            'DA': 'DA'
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

        // Translate the "Regarder" buttons
        document.querySelectorAll('.watch-button').forEach(button => {
            button.textContent = translations[lang]['Regarder'];
        });

        // Translate the currency
        document.querySelectorAll('.course-price').forEach(price => {
            price.textContent = price.textContent.replace('DA', translations[lang]['DA']);
        });

        document.body.style.direction = lang === 'ar' ? 'rtl' : 'ltr';

        setCookie('language', lang, 30);
    }

    function initPage() {
        let lang = getCookie('language');
        if (!lang) {
            lang = 'fr';
            setCookie('language', lang, 30);
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