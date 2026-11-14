document.addEventListener('DOMContentLoaded', function() {
    const translations = {
        'fr': {
            'Aide aux projets': 'Aide aux projets',
            'Ajouter un projet': 'Ajouter un projet',
            'Participer au tirage au sort': 'Participer au tirage au sort',
            'Aucun projet n\'est actuellement disponible pour affichage.': 'Aucun projet n\'est actuellement disponible pour affichage.',
            'Nom du projet:': 'Nom du projet:',
            'Lieu du projet:': 'Lieu du projet:',
            'Idée du projet:': 'Idée du projet:',
            'Coût global du projet:': 'Coût global du projet:',
            'Durée du partenariat:': 'Durée du partenariat:',
            'Nombre de partenaires:': 'Nombre de partenaires:',
            'Montant de participation par personne:': 'Montant de participation par personne:',
            'Taux d\'intérêt du partenaire:': 'Taux d\'intérêt du partenaire:',
            'Audience cible:': 'Audience cible:',
            'Impact technologique:': 'Impact technologique:',
            'Stratégie de marketing:': 'Stratégie de marketing:',
            'Participer': 'Participer',
            'Formulaire de participation': 'Formulaire de participation',
            'Prénom:': 'Prénom:',
            'Nom:': 'Nom:',
            'Numéro de téléphone:': 'Numéro de téléphone:',
            'Email:': 'Email:',
            'Adresse personnelle:': 'Adresse personnelle:',
            'Soumettre': 'Soumettre',
            'Votre participation a été enregistrée avec succès!': 'Votre participation a été enregistrée avec succès!',
            'Une erreur s\'est produite:': 'Une erreur s\'est produite:',
            'Le prénom et le nom ne doivent contenir que des lettres.': 'Le prénom et le nom ne doivent contenir que des lettres.',
            'Le numéro de téléphone doit contenir 10 chiffres.': 'Le numéro de téléphone doit contenir 10 chiffres.',
            'Veuillez entrer une adresse email valide.': 'Veuillez entrer une adresse email valide.',
            'L\'adresse personnelle doit contenir des mots et au moins un chiffre.': 'L\'adresse personnelle doit contenir des mots et au moins un chiffre.',
            'Limitée': 'Limitée',
            'Illimitée': 'Illimitée'
        },
        'ar': {
            'Aide aux projets': 'المساعدة في المشاريع',
            'Ajouter un projet': 'إضافة مشروع',
            'Participer au tirage au sort': 'المشاركة في السحب',
            'Aucun projet n\'est actuellement disponible pour affichage.': 'لا توجد مشاريع متاحة للعرض حاليًا.',
            'Nom du projet:': 'اسم المشروع:',
            'Lieu du projet:': 'موقع المشروع:',
            'Idée du projet:': 'فكرة المشروع:',
            'Coût global du projet:': 'التكلفة الإجمالية للمشروع:',
            'Durée du partenariat:': 'مدة الشراكة:',
            'Nombre de partenaires:': 'عدد الشركاء:',
            'Montant de participation par personne:': 'مبلغ المشاركة لكل شخص:',
            'Taux d\'intérêt du partenaire:': 'معدل فائدة الشريك:',
            'Audience cible:': 'الجمهور المستهدف:',
            'Impact technologique:': 'التأثير التكنولوجي:',
            'Stratégie de marketing:': 'استراتيجية التسويق:',
            'Participer': 'المشاركة',
            'Formulaire de participation': 'نموذج المشاركة',
            'Prénom:': 'الاسم الأول:',
            'Nom:': 'اسم العائلة:',
            'Numéro de téléphone:': 'رقم الهاتف:',
            'Email:': 'البريد الإلكتروني:',
            'Adresse personnelle:': 'العنوان الشخصي:',
            'Soumettre': 'إرسال',
            'Votre participation a été enregistrée avec succès!': 'تم تسجيل مشاركتك بنجاح!',
            'Une erreur s\'est produite:': 'حدث خطأ:',
            'Le prénom et le nom ne doivent contenir que des lettres.': 'يجب أن يحتوي الاسم الأول واسم العائلة على أحرف فقط.',
            'Le numéro de téléphone doit contenir 10 chiffres.': 'يجب أن يحتوي رقم الهاتف على 10 أرقام.',
            'Veuillez entrer une adresse email valide.': 'الرجاء إدخال عنوان بريد إلكتروني صالح.',
            'L\'adresse personnelle doit contenir des mots et au moins un chiffre.': 'يجب أن يحتوي العنوان الشخصي على كلمات ورقم واحد على الأقل.',
            'Limitée': 'محدودة',
            'Illimitée': 'غير محدودة'
        },
        'en': {
            'Aide aux projets': 'Project Assistance',
            'Ajouter un projet': 'Add a Project',
            'Participer au tirage au sort': 'Participate in the Draw',
            'Aucun projet n\'est actuellement disponible pour affichage.': 'No projects are currently available for display.',
            'Nom du projet:': 'Project Name:',
            'Lieu du projet:': 'Project Location:',
            'Idée du projet:': 'Project Idea:',
            'Coût global du projet:': 'Total Project Cost:',
            'Durée du partenariat:': 'Partnership Duration:',
            'Nombre de partenaires:': 'Number of Partners:',
            'Montant de participation par personne:': 'Participation Amount per Person:',
            'Taux d\'intérêt du partenaire:': 'Partner Interest Rate:',
            'Audience cible:': 'Target Audience:',
            'Impact technologique:': 'Technological Impact:',
            'Stratégie de marketing:': 'Marketing Strategy:',
            'Participer': 'Participate',
            'Formulaire de participation': 'Participation Form',
            'Prénom:': 'First Name:',
            'Nom:': 'Last Name:',
            'Numéro de téléphone:': 'Phone Number:',
            'Email:': 'Email:',
            'Adresse personnelle:': 'Personal Address:',
            'Soumettre': 'Submit',
            'Votre participation a été enregistrée avec succès!': 'Your participation has been successfully registered!',
            'Une erreur s\'est produite:': 'An error occurred:',
            'Le prénom et le nom ne doivent contenir que des lettres.': 'First name and last name must contain only letters.',
            'Le numéro de téléphone doit contenir 10 chiffres.': 'The phone number must contain 10 digits.',
            'Veuillez entrer une adresse email valide.': 'Please enter a valid email address.',
            'L\'adresse personnelle doit contenir des mots et au moins un chiffre.': 'The personal address must contain words and at least one number.',
            'Limitée': 'Limited',
            'Illimitée': 'Unlimited'
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

        document.querySelectorAll('.project-details p').forEach(element => {
            const key = element.querySelector('strong').textContent.trim();
            if (translations[lang] && translations[lang][key]) {
                element.querySelector('strong').textContent = translations[lang][key];
            }
        });

        document.querySelectorAll('.partner-info-form label').forEach(element => {
            const key = element.textContent.trim();
            if (translations[lang] && translations[lang][key]) {
                element.textContent = translations[lang][key];
            }
        });

        document.querySelectorAll('button').forEach(button => {
            const key = button.textContent.trim();
            if (translations[lang] && translations[lang][key]) {
                button.textContent = translations[lang][key];
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