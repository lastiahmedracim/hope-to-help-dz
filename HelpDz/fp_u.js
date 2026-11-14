document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('forgot-password-form');
    const errorMessagesDiv = document.getElementById('error-messages');

    const translations = {
        'fr': {
            'fill_all_fields': "Veuillez remplir tous les champs.",
            'invalid_email': "Veuillez entrer une adresse email valide.",
            'error_occurred': "Une erreur s'est produite. Veuillez réessayer.",
            'email': "Email",
            'right_pseudo': "Pseudonyme droit",
            'submit': "Envoyer le code de réinitialisation"
        },
        'ar': {
            'fill_all_fields': "يرجى ملء جميع الحقول.",
            'invalid_email': "يرجى إدخال عنوان بريد إلكتروني صالح.",
            'error_occurred': "حدث خطأ. يرجى المحاولة مرة أخرى.",
            'email': "البريد الإلكتروني",
            'right_pseudo': "الاسم المستعار الأيمن",
            'submit': "إرسال رمز إعادة التعيين"
        },
        'en': {
            'fill_all_fields': "Please fill in all fields.",
            'invalid_email': "Please enter a valid email address.",
            'error_occurred': "An error occurred. Please try again.",
            'email': "Email",
            'right_pseudo': "Right Pseudonym",
            'submit': "Send Reset Code"
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

    let currentLang = getCookie('language') || 'fr';

    function createStyleElement() {
        const style = document.createElement('style');
        document.head.appendChild(style);
        return style.sheet;
    }

    const dynamicSheet = createStyleElement();

    function updateRTLStyles(isRTL) {
        while(dynamicSheet.cssRules.length > 0) {
            dynamicSheet.deleteRule(0);
        }

        if (isRTL) {
            dynamicSheet.insertRule('body { direction: rtl; text-align: right; }', 0);
            dynamicSheet.insertRule('.input-group label { float: right; }', 0);
            dynamicSheet.insertRule('.user-option { text-align: left; }', 0);
        }
    }

    function translatePage(lang) {
        document.querySelectorAll('[data-translate]').forEach(element => {
            const key = element.getAttribute('data-translate');
            if (translations[lang] && translations[lang][key]) {
                if (element.tagName === 'INPUT' && element.getAttribute('type') === 'submit') {
                    element.value = translations[lang][key];
                } else {
                    element.textContent = translations[lang][key];
                }
            }
        });

        document.documentElement.lang = lang;
        document.documentElement.dir = lang === 'ar' ? 'rtl' : 'ltr';
        
        updateRTLStyles(lang === 'ar');

        setCookie('language', lang, 30);
        currentLang = lang;
        updatePlaceholders(lang);
    }

    function updatePlaceholders(lang) {
        const placeholders = translations[lang];
        for (const [id, placeholder] of Object.entries(placeholders)) {
            const element = document.getElementById(id);
            if (element) {
                element.placeholder = placeholder;
            }
        }
    }

    translatePage(currentLang);

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            errorMessagesDiv.innerHTML = '';

            const userEmail = document.getElementById('user_email').value.trim();
            const userRPseudo = document.getElementById('user_r_pseudo').value.trim();

            const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

            if (!userEmail || !userRPseudo) {
                showError(translations[currentLang].fill_all_fields);
                return;
            }

            if (!isValidEmail(userEmail)) {
                showError(translations[currentLang].invalid_email);
                return;
            }

            fetch('send_otp.php', {
                method: 'POST',
                body: new URLSearchParams(new FormData(form)),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(data.message);
                    setTimeout(() => {
                        window.location.href = 'enter_otp.php';
                    }, 2000);
                } else {
                    showError(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError(translations[currentLang].error_occurred);
            });
        });
    }

    function showError(message) {
        const errorElement = document.createElement('p');
        errorElement.textContent = message;
        errorElement.style.color = 'red';
        errorMessagesDiv.appendChild(errorElement);
    }

    function showSuccess(message) {
        const successElement = document.createElement('p');
        successElement.textContent = message;
        successElement.style.color = 'green';
        errorMessagesDiv.appendChild(successElement);
    }
});