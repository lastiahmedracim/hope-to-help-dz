document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('signup-form');
    const errorMessagesDiv = document.getElementById('error-messages');

    const translations = {
        'fr': {
            "fill_all_fields": "Veuillez remplir tous les champs.",
            "name_3_letters": "Le nom doit contenir au moins 3 lettres.",
            "firstname_3_letters": "Le prénom doit contenir au moins 3 lettres.",
            "wilaya_3_letters": "La wilaya doit contenir au moins 3 lettres.",
            "city_3_letters": "La ville doit contenir au moins 3 lettres.",
            "district_3_letters": "La commune doit contenir au moins 3 lettres.",
            "invalid_email": "L'email n'est pas valide.",
            "invalid_phone": "Le numéro de téléphone doit contenir 10 chiffres positifs.",
            "invalid_password": "Le mot de passe doit contenir au moins une lettre majuscule, un chiffre et être composé d'au moins 8 caractères. Les caractères spéciaux ne sont pas autorisés.",
            "passwords_not_match": "Les mots de passe ne correspondent pas.",
            "invalid_rip": "R.I.P doit contenir 20 chiffres positifs.",
            "invalid_address": "L'adresse doit contenir au moins 3 caractères avec des lettres et des chiffres.",
            "invalid_left_code": "Le code d'invitation Gauche doit contenir uniquement des lettres et des chiffres.",
            "codes_identical": "Les codes d'invitation Droite et Gauche ne peuvent pas être identiques.",
            "invalid_right_code": "Le code d'invitation Droite doit contenir uniquement des lettres et des chiffres.",
            "invalid_invitation_code": "Code d'invitation doit contenir uniquement des lettres et des chiffres.",
            "accept_terms": "Vous devez accepter les termes et conditions.",
            "network_error": "Une erreur s'est produite. Veuillez réessayer plus tard."
        },
        'ar': {
            "fill_all_fields": "يرجى ملء جميع الحقول.",
            "name_3_letters": "يجب أن يحتوي الاسم على 3 أحرف على الأقل.",
            "firstname_3_letters": "يجب أن يحتوي الاسم الأول على 3 أحرف على الأقل.",
            "wilaya_3_letters": "يجب أن تحتوي الولاية على 3 أحرف على الأقل.",
            "city_3_letters": "يجب أن تحتوي المدينة على 3 أحرف على الأقل.",
            "district_3_letters": "يجب أن تحتوي البلدية على 3 أحرف على الأقل.",
            "invalid_email": "البريد الإلكتروني غير صالح.",
            "invalid_phone": "يجب أن يحتوي رقم الهاتف على 10 أرقام موجبة.",
            "invalid_password": "يجب أن تحتوي كلمة المرور على حرف كبير واحد على الأقل، ورقم واحد، وأن تتكون من 8 أحرف على الأقل. الأحرف الخاصة غير مسموح بها.",
            "passwords_not_match": "كلمات المرور غير متطابقة.",
            "invalid_rip": "يجب أن يحتوي R.I.P على 20 رقمًا موجبًا.",
            "invalid_address": "يجب أن يحتوي العنوان على 3 أحرف على الأقل مع أحرف وأرقام.",
            "invalid_left_code": "يجب أن يحتوي رمز الدعوة الأيسر على أحرف وأرقام فقط.",
            "codes_identical": "لا يمكن أن تكون رموز الدعوة اليمنى واليسرى متطابقة.",
            "invalid_right_code": "يجب أن يحتوي رمز الدعوة الأيمن على أحرف وأرقام فقط.",
            "invalid_invitation_code": "يجب أن يحتوي رمز الدعوة على أحرف وأرقام فقط.",
            "accept_terms": "يجب عليك قبول الشروط والأحكام.",
            "network_error": "حدث خطأ. يرجى المحاولة مرة أخرى لاحقًا."
        },
        'en': {
            "fill_all_fields": "Please fill in all fields.",
            "name_3_letters": "The name must contain at least 3 letters.",
            "firstname_3_letters": "The first name must contain at least 3 letters.",
            "wilaya_3_letters": "The wilaya must contain at least 3 letters.",
            "city_3_letters": "The city must contain at least 3 letters.",
            "district_3_letters": "The district must contain at least 3 letters.",
            "invalid_email": "The email is not valid.",
            "invalid_phone": "The phone number must contain 10 positive digits.",
            "invalid_password": "The password must contain at least one uppercase letter, one number, and be at least 8 characters long. Special characters are not allowed.",
            "passwords_not_match": "Passwords do not match.",
            "invalid_rip": "R.I.P must contain 20 positive digits.",
            "invalid_address": "The address must contain at least 3 characters with letters and numbers.",
            "invalid_left_code": "The Left invitation code must contain only letters and numbers.",
            "codes_identical": "The Right and Left invitation codes cannot be identical.",
            "invalid_right_code": "The Right invitation code must contain only letters and numbers.",
            "invalid_invitation_code": "Invitation code must contain only letters and numbers.",
            "accept_terms": "You must accept the terms and conditions.",
            "network_error": "An error occurred. Please try again later."
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

        document.body.style.direction = lang === 'ar' ? 'rtl' : 'ltr';

        setCookie('language', lang, 30);
        currentLang = lang;
    }

    translatePage(currentLang);

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        errorMessagesDiv.innerHTML = '';

        let errorMessages = [];

        const userLastName = document.getElementById('user_last_name').value.trim();
        const userName = document.getElementById('user_name').value.trim();
        const userEmail = document.getElementById('user_email').value.trim();
        const rip = document.getElementById('rip').value.trim();
        const phoneNumber = document.getElementById('phone_number').value.trim();
        const userPassword = document.getElementById('user_password').value.trim();
        const confirmPassword = document.getElementById('confirm_password').value.trim();
        const wilaya = document.getElementById('wilaya').value.trim();
        const city = document.getElementById('city').value.trim();
        const district = document.getElementById('district').value.trim();
        const userAddress = document.getElementById('user_address').value.trim();
        const userLPseudo = document.getElementById('user_l_pseudo').value.trim();
        const userRPseudo = document.getElementById('user_r_pseudo').value.trim();
        const parrainPseudo = document.getElementById('parrain_pseudo').value.trim();
        const termsConditions = document.getElementById('terms_conditions').checked;

        const isAlpha = (str) => /^[A-Za-zÀ-ÿ]+$/.test(str);
        const isAlphanumeric = (str) => /^[A-Za-z0-9]+$/.test(str);
        const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        const isValidRIP = (rip) => /^\d{20}$/.test(rip);
        const isValidPhoneNumber = (phone) => /^\d{10}$/.test(phone);
        const isValidPassword = (password) => /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/.test(password);
        const isValidName = (name) => name && isAlpha(name) && name.length >= 3;
        const isValidAddress = (address) => /[A-Za-z].*\d|\d.*[A-Za-z]/.test(address) && address.length >= 3;

        if (!userLastName || !userName || !userEmail || !phoneNumber || !userPassword || !confirmPassword || !wilaya || !city || !district || !userAddress || !userLPseudo || !userRPseudo || !parrainPseudo || !rip) {
            errorMessages.push(translations[currentLang].fill_all_fields);
        } else {
            if (!isValidName(userLastName)) {
                errorMessages.push(translations[currentLang].name_3_letters);
            } else if (!isValidName(userName)) {
                errorMessages.push(translations[currentLang].firstname_3_letters);
            } else if (!isValidName(wilaya)) {
                errorMessages.push(translations[currentLang].wilaya_3_letters);
            } else if (!isValidName(city)) {
                errorMessages.push(translations[currentLang].city_3_letters);
            } else if (!isValidName(district)) {
                errorMessages.push(translations[currentLang].district_3_letters);
            } else if (!isValidEmail(userEmail)) {
                errorMessages.push(translations[currentLang].invalid_email);
            } else if (!isValidPhoneNumber(phoneNumber) || phoneNumber.startsWith('-')) {
                errorMessages.push(translations[currentLang].invalid_phone);
            } else if (!isValidPassword(userPassword)) {
                errorMessages.push(translations[currentLang].invalid_password);
            } else if (userPassword !== confirmPassword) {
                errorMessages.push(translations[currentLang].passwords_not_match);
            } else if (!isValidRIP(rip) || BigInt(rip) < 0) {
                errorMessages.push(translations[currentLang].invalid_rip);
            } else if (!isValidAddress(userAddress)) {
                errorMessages.push(translations[currentLang].invalid_address);
            } else if (!isAlphanumeric(userLPseudo)) {
                errorMessages.push(translations[currentLang].invalid_left_code);
            } else if (userLPseudo === userRPseudo) {
                errorMessages.push(translations[currentLang].codes_identical);
            } else if (!isAlphanumeric(userRPseudo)) {
                errorMessages.push(translations[currentLang].invalid_right_code);
            } else if (!isAlphanumeric(parrainPseudo)) {
                errorMessages.push(translations[currentLang].invalid_invitation_code);
            } else if (!termsConditions) {
                errorMessages.push(translations[currentLang].accept_terms);
            }
        }

        if (errorMessages.length > 0) {
            displayErrorMessage(errorMessages[0]);
            return;
        }

        grecaptcha.ready(function() {
            grecaptcha.execute('6Ld2gA8qAAAAADdxY7b2fqykkxgs-dUDqd1PaMFU', {action: 'submit'}).then(function(token) {
                const formData = new FormData(form);
                formData.append('recaptcha_token', token);
        
                fetch('../functions/chp.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        displayErrorMessage(data.message);
                    } else {
                        fetch('../functions/usp.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                window.location.href = '../index.php';
                            } else {
                                displayErrorMessage(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            displayErrorMessage(translations[currentLang].network_error);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    displayErrorMessage(translations[currentLang].network_error);
                });
            });
        });
    });

    function displayErrorMessage(message) {
        errorMessagesDiv.innerHTML = `<p>${message}</p>`;
    }

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

    function updatePlaceholders(lang) {
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

        for (const [id, placeholder] of Object.entries(placeholders[lang])) {
            const element = document.getElementById(id);
            if (element) {
                element.placeholder = placeholder;
            }
        }
    }

    updatePlaceholders(currentLang);

    document.getElementById('fr-link').addEventListener('click', () => updatePlaceholders('fr'));
    document.getElementById('ar-link').addEventListener('click', () => updatePlaceholders('ar'));
    document.getElementById('en-link').addEventListener('click', () => updatePlaceholders('en'));
});