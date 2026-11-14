document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('enter-otp-form');
    const otpMessagesDiv = document.getElementById('otp-messages');

    const translations = {
        'fr': {
            'fill_all_fields': "Veuillez remplir tous les champs.",
            'invalid_email': "Veuillez entrer une adresse email valide.",
            'invalid_otp': "Le code OTP doit être composé de 6 chiffres.",
            'invalid_password': "Le mot de passe doit contenir au moins 10 caractères, une majuscule, un chiffre et ne doit contenir que des lettres et des chiffres.",
            'passwords_not_match': "Les mots de passe ne correspondent pas.",
            'error_occurred': "Une erreur s'est produite. Veuillez réessayer."
        },
        'ar': {
            'fill_all_fields': "يرجى ملء جميع الحقول.",
            'invalid_email': "يرجى إدخال عنوان بريد إلكتروني صالح.",
            'invalid_otp': "يجب أن يتكون رمز OTP من 6 أرقام.",
            'invalid_password': "يجب أن تحتوي كلمة المرور على 10 أحرف على الأقل، وحرف كبير واحد، ورقم واحد، ويجب أن تحتوي على أحرف وأرقام فقط.",
            'passwords_not_match': "كلمات المرور غير متطابقة.",
            'error_occurred': "حدث خطأ. يرجى المحاولة مرة أخرى."
        },
        'en': {
            'fill_all_fields': "Please fill in all fields.",
            'invalid_email': "Please enter a valid email address.",
            'invalid_otp': "The OTP code must be 6 digits.",
            'invalid_password': "The password must contain at least 10 characters, one uppercase letter, one number, and must only contain letters and numbers.",
            'passwords_not_match': "The passwords do not match.",
            'error_occurred': "An error occurred. Please try again."
        }
    };

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

            otpMessagesDiv.innerHTML = '';

            const userEmail = document.getElementById('user_email').value.trim();
            const userRPseudo = document.getElementById('user_r_pseudo').value.trim();
            const otp = document.getElementById('otp').value.trim();
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            const isAlphanumeric = (str) => /^[A-Za-z0-9]+$/.test(str);

            if (!userEmail || !userRPseudo || !otp || !newPassword || !confirmPassword) {
                displayMessage(translations[currentLang].fill_all_fields, 'error');
                return;
            }

            if (!isValidEmail(userEmail)) {
                displayMessage(translations[currentLang].invalid_email, 'error');
                return;
            }

            if (otp.length !== 6 || !/^\d+$/.test(otp)) {
                displayMessage(translations[currentLang].invalid_otp, 'error');
                return;
            }

            if (newPassword.length < 10 || !/[A-Z]/.test(newPassword) || !/\d/.test(newPassword) || !isAlphanumeric(newPassword)) {
                displayMessage(translations[currentLang].invalid_password, 'error');
                return;
            }

            if (newPassword !== confirmPassword) {
                displayMessage(translations[currentLang].passwords_not_match, 'error');
                return;
            }

            const formData = new FormData(form);
            fetch('r_p.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    displayMessage(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = 'signin.php';
                    }, 2000);
                } else {
                    displayMessage(data.messages[0], 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                displayMessage(translations[currentLang].error_occurred, 'error');
            });
        });
    }

    function displayMessage(message, type) {
        otpMessagesDiv.innerHTML = `<p class="${type}">${message}</p>`;
    }
});