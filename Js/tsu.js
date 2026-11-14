document.addEventListener('DOMContentLoaded', function () {
    const translations = {
        'fr': {
            "signup_title": "S'inscrire",
            "signup_form": "Formulaire d'inscription",
            "last_name": "Nom :",
            "first_name": "Prénom :",
            "email": "Email :",
            "phone_number": "Numéro de téléphone :",
            "password": "Mot de passe :",
            "confirm_password": "Confirmer le mot de passe :",
            "wilaya": "Wilaya :",
            "city": "Ville :",
            "district": "La commune :",
            "address": "L'adresse :",
            "rip": "Releve d'identite postal(RIP) :",
            "left_invitation_code": "Code d'Invitation Gauche :",
            "right_invitation_code": "Code d'Invitation Droite :",
            "invitation_code": "Saisir le Code d'invitation :",
            "accept_terms": "J'accepte les termes et conditions",
            "signup_button": "S'inscrire",
            "already_registered": "Déjà inscrit ?",
            "login_here": "Connectez-vous ici"
        },
        'ar': {
            "signup_title": "التسجيل",
            "signup_form": "نموذج التسجيل",
            "last_name": "اللقب:",
            "first_name": "الاسم:",
            "email": "البريد الإلكتروني:",
            "phone_number": "رقم الهاتف:",
            "password": "كلمة المرور:",
            "confirm_password": "تأكيد كلمة المرور:",
            "wilaya": "الولاية:",
            "city": "المدينة:",
            "district": "البلدية:",
            "address": "العنوان:",
            "rip": "رقم التعريف البريدي (RIP):",
            "left_invitation_code": "رمز الدعوة الأيسر:",
            "right_invitation_code": "رمز الدعوة الأيمن:",
            "invitation_code": "أدخل رمز الدعوة:",
            "accept_terms": "أوافق على الشروط والأحكام",
            "signup_button": "التسجيل",
            "already_registered": "هل لديك حساب بالفعل؟",
            "login_here": "سجل الدخول هنا"
        },
        'en': {
            "signup_title": "Sign Up",
            "signup_form": "Registration Form",
            "last_name": "Last Name:",
            "first_name": "First Name:",
            "email": "Email:",
            "phone_number": "Phone Number:",
            "password": "Password:",
            "confirm_password": "Confirm Password:",
            "wilaya": "Wilaya:",
            "city": "City:",
            "district": "District:",
            "address": "Address:",
            "rip": "Postal Identity Statement (RIP):",
            "left_invitation_code": "Left Invitation Code:",
            "right_invitation_code": "Right Invitation Code:",
            "invitation_code": "Enter Invitation Code:",
            "accept_terms": "I accept the terms and conditions",
            "signup_button": "Sign Up",
            "already_registered": "Already registered?",
            "login_here": "Login here"
        }
    };

    const placeholders = {
        'fr': {
            'user_last_name': 'Votre nom',
            'user_name': 'Votre prénom',
            'user_email': 'Votre Email',
            'phone_number': '+213',
            'user_password': 'Mot de passe',
            'confirm_password': 'Confirmer le mot de passe',
            'wilaya': 'Votre wilaya',
            'city': 'Votre ville',
            'district': 'Votre commune',
            'user_address': 'Votre adresse',
            'rip': 'RIP',
            'user_l_pseudo': "Code d'invitation Gauche",
            'user_r_pseudo': "Code d'invitation Droite",
            'parrain_pseudo': "Saisir le Code d'invitation"
        },
        'ar': {
            'user_last_name': 'اسمك الأخير',
            'user_name': 'اسمك الأول',
            'user_email': 'بريدك الإلكتروني',
            'phone_number': '+213',
            'user_password': 'كلمة المرور',
            'confirm_password': 'تأكيد كلمة المرور',
            'wilaya': 'ولايتك',
            'city': 'مدينتك',
            'district': 'بلديتك',
            'user_address': 'عنوانك',
            'rip': 'رقم الحساب البريدي',
            'user_l_pseudo': "رمز الدعوة الأيسر",
            'user_r_pseudo': "رمز الدعوة الأيمن",
            'parrain_pseudo': "أدخل رمز الدعوة"
        },
        'en': {
            'user_last_name': 'Your last name',
            'user_name': 'Your first name',
            'user_email': 'Your Email',
            'phone_number': '+213',
            'user_password': 'Password',
            'confirm_password': 'Confirm password',
            'wilaya': 'Your wilaya',
            'city': 'Your city',
            'district': 'Your district',
            'user_address': 'Your address',
            'rip': 'RIP',
            'user_l_pseudo': "Left Invitation Code",
            'user_r_pseudo': "Right Invitation Code",
            'parrain_pseudo': "Enter Invitation Code"
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

        updatePlaceholders(lang);

        document.body.style.direction = lang === 'ar' ? 'rtl' : 'ltr';
        document.documentElement.lang = lang;
    }

    function updatePlaceholders(lang) {
        for (const [id, placeholder] of Object.entries(placeholders[lang])) {
            const element = document.getElementById(id);
            if (element) {
                element.placeholder = placeholder;
            }
        }
    }

    let currentLang = getCookie('language') || 'fr';
    translatePage(currentLang);

    const errorMessagesDiv = document.getElementById('error-messages');
    const form = document.getElementById('signup-form');

    form.addEventListener('submit', function (e) {
        e.preventDefault();
    });

    function displayErrorMessage(message) {
        errorMessagesDiv.innerHTML = `<p>${message}</p>`;
    }
});