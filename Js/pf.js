document.addEventListener('DOMContentLoaded', function() {
    const translations = {
        'fr': {
            'Informations du Profil': 'Informations du Profil',
            'Informations Supplémentaires': 'Informations Supplémentaires',
            'Email': 'Email',
            'Argent Virtuel': 'Argent Virtuel',
            'Profit Non Payé': 'Profit Non Payé',
            'Code d\'invitation Gauche': 'Code d\'invitation Gauche',
            'Code d\'invitation Droite': 'Code d\'invitation Droite',    
            'Releve d\'identite postal(RIP) :': 'Releve d\'identite postal(RIP) :',
            'Demande change RIP': 'Demande change RIP',
            'Nouveau RIP': 'Nouveau RIP',
            'Envoyer la demande': 'Envoyer la demande',
            'Récupérer le profit': 'Récupérer le profit',
            'Sélectionner Descendant': 'Sélectionner Descendant',
            'Montant du Profit': 'Montant du Profit',
            'Envoyer Profit': 'Envoyer Profit',
            'Récupérer le Profit en Argent Virtuel': 'Récupérer le Profit en Argent Virtuel',
            'Prénom': 'Prénom',
            'Nom': 'Nom',
            'Wilaya': 'Wilaya',
            'Ville': 'Ville',
            'La commune': 'La commune',
            'Adresse': 'Adresse',
            'Numéro de Téléphone': 'Numéro de Téléphone',
            'Mettre à Jour': 'Mettre à Jour'
        },
        'ar': {
            'Informations du Profil': 'معلومات الملف الشخصي',
            'Informations Supplémentaires': 'معلومات إضافية',
            'Email': 'البريد الإلكتروني',
            'Argent Virtuel': 'المال الافتراضي',
            'Profit Non Payé': 'الربح غير المدفوع',
            'Code d\'invitation Gauche': 'رمز الدعوة اليسار',
            'Code d\'invitation Droite': 'رمز الدعوة اليمين',
            'Releve d\'identite postal(RIP) :': 'رقم التعريف البريدي',
            'Demande change RIP': 'طلب تغيير رقم التعريف البريدي',
            'Nouveau RIP': 'رقم التعريف البريدي جديد',
            'Envoyer la demande': 'إرسال الطلب',
            'Récupérer le profit': 'استرداد الربح',
            'Sélectionner Descendant': 'اختيار الصديق',
            'Montant du Profit': 'مبلغ الربح',
            'Envoyer Profit': 'إرسال الربح',
            'Récupérer le Profit en Argent Virtuel': 'استرداد الربح كمال افتراضي',
            'Prénom': 'الاسم',
            'Nom': 'اسم العائلة',
            'Wilaya': 'الولاية',
            'Ville': 'المدينة',
            'La commune': 'المنطقة',
            'Adresse': 'العنوان',
            'Numéro de Téléphone': 'رقم الهاتف',
            'Mettre à Jour': 'تحديث'
        },
        'en': {
            'Informations du Profil': 'Profile Information',
            'Informations Supplémentaires': 'Additional Information',
            'Email': 'Email',
            'Argent Virtuel': 'Virtual Money',
            'Profit Non Payé': 'Unpaid Profit',
            'Code d\'invitation Gauche': 'Left Invitation Code',
            'Code d\'invitation Droite': 'Right Invitation Code',
            'Releve d\'identite postal(RIP) :': 'Postal identity number',
            'Demande change RIP': 'Request Change Postal identity number',
            'Nouveau RIP': 'New Postal identity number',
            'Envoyer la demande': 'Send Request',
            'Récupérer le profit': 'Claim Profit',
            'Sélectionner Descendant': 'Select Descendant',
            'Montant du Profit': 'Profit Amount',
            'Envoyer Profit': 'Send Profit',
            'Récupérer le Profit en Argent Virtuel': 'Recover Profit as Virtual Money',
            'Prénom': 'First Name',
            'Nom': 'Last Name',
            'Wilaya': 'Wilaya',
            'Ville': 'City',
            'La commune': 'Town',
            'Adresse': 'Address',
            'Numéro de Téléphone': 'Phone Number',
            'Mettre à Jour': 'Update'
        }
    };

    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function translatePlaceholders(lang) {
        const inputs = document.querySelectorAll('input[placeholder]');
        inputs.forEach(input => {
            const key = input.placeholder;
            if (translations[lang] && translations[lang][key]) {
                input.placeholder = translations[lang][key];
            }
        });
    }

    function translatePage(lang) {
        document.querySelectorAll('[data-translate]').forEach(element => {
            const key = element.getAttribute('data-translate');
            if (translations[lang] && translations[lang][key]) {
                if (element.tagName === 'INPUT' && element.type === 'text') {
                    element.placeholder = translations[lang][key];
                } else {
                    element.textContent = translations[lang][key];
                }
            }
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

    document.getElementById('fr-link').addEventListener('click', (e) => {
        e.preventDefault();
        setCookie('language', 'fr', 30);
        window.location.reload();
    });

    document.getElementById('ar-link').addEventListener('click', (e) => {
        e.preventDefault();
        setCookie('language', 'ar', 30);
        window.location.reload();
    });

    document.getElementById('en-link').addEventListener('click', (e) => {
        e.preventDefault();
        setCookie('language', 'en', 30);
        window.location.reload();
    });

    initPage();

});