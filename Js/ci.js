document.addEventListener('DOMContentLoaded', function() {
    const translations = {
        'fr': {
            'Profil': 'Profil',
            'Image du Cours': 'Image du Cours',
            'Éléments du Cours': 'Éléments du Cours',
            'Prix': 'Prix',
            'DA': 'DA',
            'Auteur': 'Auteur',
            'Catégorie': 'Catégorie',
            'Télécharger Image du reçu de paiement': 'Télécharger Image du reçu de paiement',
            'Valider': 'Valider',
            'Confirmer l\'achat': 'Confirmer l\'achat',
            'Votre achat a été effectué avec succès. Vous pouvez maintenant accéder à votre cours.': 'Votre achat a été effectué avec succès. Vous pouvez maintenant accéder à votre cours.',
            'Vous avez déjà ce cours, en cours de visualisation.': 'Vous avez déjà ce cours, en cours de visualisation.',
            'Erreur de connexion au serveur.': 'Erreur de connexion au serveur.',
            'Veuillez télécharger une image du reçu de paiement.': 'Veuillez télécharger une image du reçu de paiement.',
            'Le téléchargement du fichier a échoué.': 'Le téléchargement du fichier a échoué.',
            'Requête invalide.': 'Requête invalide.',
            'Solde d\'argent virtuel insuffisant pour acheter ce cours.': 'Solde d\'argent virtuel insuffisant pour acheter ce cours.',
            'Accès non autorisé.': 'Accès non autorisé.',
            'Voici nos options de paiement:': 'Voici nos options de paiement:',
            'Par RIP': 'Par RIP',
            'En personne à l\'adresse': 'En personne à l\'adresse',
            'Beauté': 'Beauté',
            'Mode': 'Mode',
            'Technologie': 'Technologie',
            'Alimentation': 'Alimentation',
            'Voyage': 'Voyage',
            'Santé': 'Santé',
            'Sports': 'Sports',
            'Divertissement': 'Divertissement',
            'Maison': 'Maison',
            'Éducation': 'Éducation',
            'Art': 'Art',
            'Musique': 'Musique',
            'Livres & Littérature': 'Livres & Littérature',
            'Automobiles': 'Automobiles',
            'Finance': 'Finance',
            'Animaux de Compagnie': 'Animaux de Compagnie',
            'Plein Air': 'Plein Air',
            'Fitness': 'Fitness',
            'Informatique': 'Informatique'
        },
        'ar': {
            'Profil': 'الملف الشخصي',
            'Image du Cours': 'صورة الدورة',
            'Éléments du Cours': 'عناصر الدورة',
            'Prix': 'السعر',
            'DA': 'دينار جزائري',
            'Auteur': 'المؤلف',
            'Catégorie': 'الفئة',
            'Télécharger Image du reçu de paiement': 'تحميل صورة إيصال الدفع',
            'Valider': 'تأكيد',
            'Confirmer l\'achat': 'تأكيد الشراء',
            'Votre achat a été effectué avec succès. Vous pouvez maintenant accéder à votre cours.': 'تمت عملية الشراء بنجاح. يمكنك الآن الوصول إلى دورتك.',
            'Vous avez déjà ce cours, en cours de visualisation.': 'لديك هذه الدورة بالفعل، جاري العرض.',
            'Erreur de connexion au serveur.': 'خطأ في الاتصال بالخادم.',
            'Veuillez télécharger une image du reçu de paiement.': 'يرجى تحميل صورة إيصال الدفع.',
            'Le téléchargement du fichier a échoué.': 'فشل تحميل الملف.',
            'Requête invalide.': 'طلب غير صالح.',
            'Solde d\'argent virtuel insuffisant pour acheter ce cours.': 'رصيد المال الافتراضي غير كاف لشراء هذه الدورة.',
            'Accès non autorisé.': 'الوصول غير مسموح.',
            'Voici nos options de paiement:': 'فيما يلي خيارات الدفع لدينا:',
            'Par RIP': 'عن طريق RIP',
            'En personne à l\'adresse': 'شخصيًا على العنوان',
            'Beauté': 'الجمال',
            'Mode': 'الموضة',
            'Technologie': 'التكنولوجيا',
            'Alimentation': 'الطعام',
            'Voyage': 'السفر',
            'Santé': 'الصحة',
            'Sports': 'الرياضة',
            'Divertissement': 'الترفيه',
            'Maison': 'المنزل',
            'Éducation': 'التعليم',
            'Art': 'الفن',
            'Musique': 'الموسيقى',
            'Livres & Littérature': 'الكتب والأدب',
            'Automobiles': 'السيارات',
            'Finance': 'المالية',
            'Animaux de Compagnie': 'الحيوانات الأليفة',
            'Plein Air': 'الهواء الطلق',
            'Fitness': 'اللياقة البدنية',
            'Informatique': 'علوم الكمبيوتر'
        },
        'en': {
            'Profil': 'Profile',
            'Image du Cours': 'Course Image',
            'Éléments du Cours': 'Course Elements',
            'Prix': 'Price',
            'DA': 'DA',
            'Auteur': 'Author',
            'Catégorie': 'Category',
            'Télécharger Image du reçu de paiement': 'Upload payment receipt image',
            'Valider': 'Validate',
            'Confirmer l\'achat': 'Confirm purchase',
            'Votre achat a été effectué avec succès. Vous pouvez maintenant accéder à votre cours.': 'Your purchase has been successfully completed. You can now access your course.',
            'Vous avez déjà ce cours, en cours de visualisation.': 'You already have this course, in progress.',
            'Erreur de connexion au serveur.': 'Server connection error.',
            'Veuillez télécharger une image du reçu de paiement.': 'Please upload a payment receipt image.',
            'Le téléchargement du fichier a échoué.': 'File upload failed.',
            'Requête invalide.': 'Invalid request.',
            'Solde d\'argent virtuel insuffisant pour acheter ce cours.': 'Insufficient virtual money balance to buy this course.',
            'Accès non autorisé.': 'Unauthorized access.',
            'Voici nos options de paiement:': 'Here are our payment options:',
            'Par RIP': 'By RIP',
            'En personne à l\'adresse': 'In person at the address',
            'Beauté': 'Beauty',
            'Mode': 'Fashion',
            'Technologie': 'Technology',
            'Alimentation': 'Food',
            'Voyage': 'Travel',
            'Santé': 'Health',
            'Sports': 'Sports',
            'Divertissement': 'Entertainment',
            'Maison': 'Home',
            'Éducation': 'Education',
            'Art': 'Art',
            'Musique': 'Music',
            'Livres & Littérature': 'Books & Literature',
            'Automobiles': 'Automobiles',
            'Finance': 'Finance',
            'Animaux de Compagnie': 'Pets',
            'Plein Air': 'Outdoors',
            'Fitness': 'Fitness',
            'Informatique': 'Computer Science'
        }
    };

    document.querySelectorAll('[data-translate]').forEach(element => {
        element.textContent = translate(element.textContent);
    });

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

    function translatePage(lang) {
        document.querySelectorAll('[data-translate]').forEach(element => {
            const key = element.textContent.trim();
            if (translations[lang] && translations[lang][key]) {
                element.textContent = translations[lang][key];
            }
        });

        document.body.style.direction = lang === 'ar' ? 'rtl' : 'ltr';

        setCookie('language', lang, 30);

        const paymentMessage = document.getElementById('paymentMessage');
        if (paymentMessage) {
            paymentMessage.textContent = translations[lang]['Veuillez télécharger une image du reçu de paiement.'];
        }
    }
    

    function initPage() {
        let lang = getCookie('language');
        if (!lang) {
            lang = 'fr';
            setCookie('language', lang, 30);
        }
        translatePage(lang);
    
        const paymentMessage = document.querySelector('.payment_message');
        if (paymentMessage) {
            paymentMessage.textContent = translations[lang]['Veuillez télécharger une image du reçu de paiement.'];
        }
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

    function validateForm(event) {
        event.preventDefault();
        var fileInput = document.getElementById('ccpImage');
        var paymentMessage = document.querySelector('.payment_message');

        if (!fileInput.files.length) {
            paymentMessage.style.display = 'block';
            paymentMessage.textContent = translations[getCookie('language')]['Veuillez télécharger une image du reçu de paiement.'];
        } else {
            var formData = new FormData();
            formData.append('ccpImage', fileInput.files[0]);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', './1s2qZa/bc_p.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = xhr.responseText.trim();
                        paymentMessage.innerHTML = '<p>' + response + '</p>';
                        paymentMessage.style.display = 'block';

                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                    } else {
                        paymentMessage.innerHTML = '<p>' + translations[getCookie('language')]['Erreur de connexion au serveur.'] + '</p>';
                        paymentMessage.style.display = 'block';
                    }
                }
            };
            xhr.send(formData);
        }
    }

    document.getElementById('ccpForm').addEventListener('submit', validateForm);
});