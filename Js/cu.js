document.addEventListener('DOMContentLoaded', function() {
    const translations = {
        'fr': {
            'Contactez-nous': 'Contactez-nous',
            'N\'hésitez pas à nous contacter pour toute question, demande de renseignement ou suggestion. Remplissez le formulaire ci-dessous, et nous vous répondrons dès que possible.': 'N\'hésitez pas à nous contacter pour toute question, demande de renseignement ou suggestion. Remplissez le formulaire ci-dessous, et nous vous répondrons dès que possible.',
            'Adresse: EL QODS ALGER CHERAGA': 'Adresse: EL QODS ALGER CHERAGA',
            'Téléphone: 0670123991': 'Téléphone: 0670123991',
            'Email: support@hopetohelp.dz': 'Email: support@hopetohelp.dz',
            'Nom': 'Nom',
            'Email': 'Email',
            'Sujet': 'Sujet',
            'Votre message': 'Votre message',
            'Envoyer': 'Envoyer'
        },
        'ar': {
            'Contactez-nous': 'اتصل بنا',
            'N\'hésitez pas à nous contacter pour toute question, demande de renseignement ou suggestion. Remplissez le formulaire ci-dessous, et nous vous répondrons dès que possible.': 'لا تتردد في الاتصال بنا لأي سؤال أو استفسار أو اقتراح. املأ النموذج أدناه وسنرد عليك في أقرب وقت ممكن.',
            'Adresse: EL QODS ALGER CHERAGA': 'العنوان: القدس الجزائر الشراقة',
            'Téléphone: 0670123991': 'الهاتف: 0670123991',
            'Email: support@hopetohelp.dz': 'البريد الإلكتروني: support@hopetohelp.dz',
            'Nom': 'الاسم',
            'Email': 'البريد الإلكتروني',
            'Sujet': 'الموضوع',
            'Votre message': 'رسالتك',
            'Envoyer': 'إرسال'
        },
        'en': {
            'Contactez-nous': 'Contact Us',
            'N\'hésitez pas à nous contacter pour toute question, demande de renseignement ou suggestion. Remplissez le formulaire ci-dessous, et nous vous répondrons dès que possible.': 'Don\'t hesitate to contact us for any questions, inquiries, or suggestions. Fill out the form below, and we will respond as soon as possible.',
            'Adresse: EL QODS ALGER CHERAGA': 'Address: EL QODS ALGIERS CHERAGA',
            'Téléphone: 0670123991': 'Phone: 0670123991',
            'Email: support@hopetohelp.dz': 'Email: support@hopetohelp.dz',
            'Nom': 'Name',
            'Email': 'Email',
            'Sujet': 'Subject',
            'Votre message': 'Your message',
            'Envoyer': 'Send'
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
            const key = element.textContent.trim();
            if (translations[lang] && translations[lang][key]) {
                element.textContent = translations[lang][key];
            }
        });

        document.getElementById('name').placeholder = translations[lang]['Nom'];
        document.getElementById('email').placeholder = translations[lang]['Email'];
        document.getElementById('subject').placeholder = translations[lang]['Sujet'];
        document.getElementById('message').placeholder = translations[lang]['Votre message'];
        
        document.querySelector('.submit-btn').textContent = translations[lang]['Envoyer'];

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