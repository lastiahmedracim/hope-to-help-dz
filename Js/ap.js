document.addEventListener('DOMContentLoaded', function() {
    const translations = {
        'fr': {
            'Développer un ou plusieurs projets pour leur présentation sur la plateforme.': 'Développer un ou plusieurs projets pour leur présentation sur la plateforme.',
            'La possibilité de financement de votre projet par des personnes ou institutions intéressées.': 'La possibilité de financement de votre projet par des personnes ou institutions intéressées.',
            'Obtenir de nombreux privilèges, y compris une carte de fidélité, qui vous donne droit à des rabais si vous achetez auprès d\'entrepreneurs dans différents domaines.': 'Obtenir de nombreux privilèges, y compris une carte de fidélité, qui vous donne droit à des rabais si vous achetez auprès d\'entrepreneurs dans différents domaines.',
            'Accès à des revenus supplémentaires conformément à la politique de l\'entreprise.': 'Accès à des revenus supplémentaires conformément à la politique de l\'entreprise.',
            'Payer 30000 de monnaie virtuelle pour accéder à l\'ajout de projet': 'Payer 30000 de monnaie virtuelle pour accéder à l\'ajout de projet',
            'Vous avez droit à un projet gratuit': 'Vous avez droit à un projet gratuit',
            'Vous pouvez créer un projet gratuitement ou choisir de payer pour un accès illimité.': 'Vous pouvez créer un projet gratuitement ou choisir de payer pour un accès illimité.',
            'Accès illimité aux projets': 'Accès illimité aux projets',
            'Payez pour publier autant de projets que vous le souhaitez.': 'Payez pour publier autant de projets que vous le souhaitez.',
            'Payer 30000 de monnaie virtuelle pour un accès illimité': 'Payer 30000 de monnaie virtuelle pour un accès illimité',
            'Demande d\'aide financière pour votre projet': 'Demande d\'aide financière pour votre projet',
            'Nom du projet (max 140 caractères) :': 'Nom du projet (max 140 caractères) :',
            'Forme juridique du projet :': 'Forme juridique du projet :',
            'Idée du projet (max 210 caractères) :': 'Idée du projet (max 210 caractères) :',
            'Nom du propriétaire du projet :': 'Nom du propriétaire du projet :',
            'Compétences du propriétaire :': 'Compétences du propriétaire :',
            'Noms des partenaires (s\'il y en a) :': 'Noms des partenaires (s\'il y en a) :',
            'Raison du choix de l\'idée du projet :': 'Raison du choix de l\'idée du projet :',
            'Emplacement du projet :': 'Emplacement du projet :',
            'Caractéristiques uniques de votre produit :': 'Caractéristiques uniques de votre produit :',
            'Valeur ajoutée de votre produit (max 140 caractères) :': 'Valeur ajoutée de votre produit (max 140 caractères) :',
            'Public cible de votre produit :': 'Public cible de votre produit :',
            'Impact de la technologie sur votre produit :': 'Impact de la technologie sur votre produit :',
            'Stratégies marketing prévues :': 'Stratégies marketing prévues :',
            'Nombre d\'employés prévus :': 'Nombre d\'employés prévus :',
            'Coût de location :': 'Coût de location :',
            'Coût des équipements :': 'Coût des équipements :',
            'Détails des équipements :': 'Détails des équipements :',
            'Dépenses supplémentaires :': 'Dépenses supplémentaires :',
            'Numéro de carte d\'identité nationale du propriétaire :': 'Numéro de carte d\'identité nationale du propriétaire :',
            'Où afficher le projet :': 'Où afficher le projet :',
            'Liste des projets': 'Liste des projets',
            'Tirage au sort': 'Tirage au sort',
            'Liste des projets Et Tirage au sort': 'Liste des projets Et Tirage au sort',
            'La durée de partenariat :': 'La durée de partenariat :',
            'Limité': 'Limité',
            'Illimité': 'Illimité',
            'Taux d\'intérêt du partenaire (%) :': 'Taux d\'intérêt du partenaire (%) :',
            'Coût total du projet :': 'Coût total du projet :',
            'Nombre de partenaires :': 'Nombre de partenaires :',
            'Montant de participation par personne :': 'Montant de participation par personne :',
            'Soumettre le projet': 'Soumettre le projet'
        },
        'ar': {
            'Développer un ou plusieurs projets pour leur présentation sur la plateforme.': 'تطوير مشروع واحد أو أكثر لعرضه على المنصة.',
            'La possibilité de financement de votre projet par des personnes ou institutions intéressées.': 'إمكانية تمويل مشروعك من قبل أشخاص أو مؤسسات مهتمة.',
            'Obtenir de nombreux privilèges, y compris une carte de fidélité, qui vous donne droit à des rabais si vous achetez auprès d\'entrepreneurs dans différents domaines.': 'الحصول على العديد من الامتيازات، بما في ذلك بطاقة ولاء تمنحك الحق في الحصول على خصومات عند الشراء من رواد الأعمال في مختلف المجالات.',
            'Accès à des revenus supplémentaires conformément à la politique de l\'entreprise.': 'الوصول إلى دخل إضافي وفقًا لسياسة الشركة.',
            'Payer 30000 de monnaie virtuelle pour accéder à l\'ajout de projet': 'دفع 30000 من العملة الافتراضية للوصول إلى إضافة المشروع',
            'Vous avez droit à un projet gratuit': 'لديك الحق في مشروع مجاني',
            'Vous pouvez créer un projet gratuitement ou choisir de payer pour un accès illimité.': 'يمكنك إنشاء مشروع مجانًا أو اختيار الدفع للحصول على وصول غير محدود.',
            'Accès illimité aux projets': 'وصول غير محدود للمشاريع',
            'Payez pour publier autant de projets que vous le souhaitez.': 'ادفع لنشر أكبر عدد ممكن من المشاريع كما تريد.',
            'Payer 30000 de monnaie virtuelle pour un accès illimité': 'دفع 30000 من العملة الافتراضية للحصول على وصول غير محدود',
            'Demande d\'aide financière pour votre projet': 'طلب المساعدة المالية لمشروعك',
            'Nom du projet (max 140 caractères) :': 'اسم المشروع (بحد أقصى 140 حرفًا):',
            'Forme juridique du projet :': 'الشكل القانوني للمشروع:',
            'Idée du projet (max 210 caractères) :': 'فكرة المشروع (بحد أقصى 210 حرفًا):',
            'Nom du propriétaire du projet :': 'اسم مالك المشروع:',
            'Compétences du propriétaire :': 'مهارات المالك:',
            'Noms des partenaires (s\'il y en a) :': 'أسماء الشركاء (إن وجدوا):',
            'Raison du choix de l\'idée du projet :': 'سبب اختيار فكرة المشروع:',
            'Emplacement du projet :': 'موقع المشروع:',
            'Caractéristiques uniques de votre produit :': 'الميزات الفريدة لمنتجك:',
            'Valeur ajoutée de votre produit (max 140 caractères) :': 'القيمة المضافة لمنتجك (بحد أقصى 140 حرفًا):',
            'Public cible de votre produit :': 'الجمهور المستهدف لمنتجك:',
            'Impact de la technologie sur votre produit :': 'تأثير التكنولوجيا على منتجك:',
            'Stratégies marketing prévues :': 'استراتيجيات التسويق المخطط لها:',
            'Nombre d\'employés prévus :': 'عدد الموظفين المتوقع:',
            'Coût de location :': 'تكلفة الإيجار:',
            'Coût des équipements :': 'تكلفة المعدات:',
            'Détails des équipements :': 'تفاصيل المعدات:',
            'Dépenses supplémentaires :': 'النفقات الإضافية:',
            'Numéro de carte d\'identité nationale du propriétaire :': 'رقم بطاقة الهوية الوطنية للمالك:',
            'Où afficher le projet :': 'أين يتم عرض المشروع:',
            'Liste des projets': 'قائمة المشاريع',
            'Tirage au sort': 'السحب العشوائي',
            'Liste des projets Et Tirage au sort': 'قائمة المشاريع والسحب العشوائي',
            'La durée de partenariat :': 'مدة الشراكة:',
            'Limité': 'محدودة',
            'Illimité': 'غير محدودة',
            'Taux d\'intérêt du partenaire (%) :': 'معدل الفائدة للشريك (%):',
            'Coût total du projet :': 'التكلفة الإجمالية للمشروع:',
            'Nombre de partenaires :': 'عدد الشركاء:',
            'Montant de participation par personne :': 'مبلغ المشاركة لكل شخص:',
            'Soumettre le projet': 'تقديم المشروع'
        },
        'en': {
            'Développer un ou plusieurs projets pour leur présentation sur la plateforme.': 'Develop one or more projects for presentation on the platform.',
            'La possibilité de financement de votre projet par des personnes ou institutions intéressées.': 'The possibility of financing your project by interested individuals or institutions.',
            'Obtenir de nombreux privilèges, y compris une carte de fidélité, qui vous donne droit à des rabais si vous achetez auprès d\'entrepreneurs dans différents domaines.': 'Obtain numerous privileges, including a loyalty card that entitles you to discounts when purchasing from entrepreneurs in various fields.',
            'Accès à des revenus supplémentaires conformément à la politique de l\'entreprise.': 'Access to additional income in accordance with company policy.',
            'Payer 30000 de monnaie virtuelle pour accéder à l\'ajout de projet': 'Pay 30000 virtual currency to access project addition',
            'Vous avez droit à un projet gratuit': 'You are entitled to one free project',
            'Vous pouvez créer un projet gratuitement ou choisir de payer pour un accès illimité.': 'You can create a project for free or choose to pay for unlimited access.',
            'Accès illimité aux projets': 'Unlimited access to projects',
            'Payez pour publier autant de projets que vous le souhaitez.': 'Pay to publish as many projects as you want.',
            'Payer 30000 de monnaie virtuelle pour un accès illimité': 'Pay 30000 virtual currency for unlimited access',
            'Demande d\'aide financière pour votre projet': 'Request for financial assistance for your project',
            'Nom du projet (max 140 caractères) :': 'Project name (max 140 characters):',
            'Forme juridique du projet :': 'Legal form of the project:',
            'Idée du projet (max 210 caractères) :': 'Project idea (max 210 characters):',
            'Nom du propriétaire du projet :': 'Project owner\'s name:',
            'Compétences du propriétaire :': 'Owner\'s skills:',
            'Noms des partenaires (s\'il y en a) :': 'Names of partners (if any):',
            'Raison du choix de l\'idée du projet :': 'Reason for choosing the project idea:',
            'Emplacement du projet :': 'Project location:',
            'Caractéristiques uniques de votre produit :': 'Unique features of your product:',
            'Valeur ajoutée de votre produit (max 140 caractères) :': 'Added value of your product (max 140 characters):',
            'Public cible de votre produit :': 'Target audience for your product:',
            'Impact de la technologie sur votre produit :': 'Impact of technology on your product:',
            'Stratégies marketing prévues :': 'Planned marketing strategies:',
            'Nombre d\'employés prévus :': 'Planned number of employees:',
            'Coût de location :': 'Rental cost:',
            'Coût des équipements :': 'Equipment cost:',
            'Détails des équipements :': 'Equipment details:',
            'Dépenses supplémentaires :': 'Additional expenses:',
            'Numéro de carte d\'identité nationale du propriétaire :': 'Owner\'s national ID card number:',
            'Où afficher le projet :': 'Where to display the project:',
            'Liste des projets': 'Project list',
            'Tirage au sort': 'Random draw',
            'Liste des projets Et Tirage au sort': 'Project list and Random draw',
            'La durée de partenariat :': 'Partnership duration:',
            'Limité': 'Limited',
            'Illimité': 'Unlimited',
            'Taux d\'intérêt du partenaire (%) :': 'Partner interest rate (%):',
            'Coût total du projet :': 'Total project cost:',
            'Nombre de partenaires :': 'Number of partners:',
            'Montant de participation par personne :': 'Participation amount per person:',
            'Soumettre le projet': 'Submit project'
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

        document.querySelectorAll('label').forEach(label => {
            const key = label.textContent.trim();
            if (translations[lang] && translations[lang][key]) {
                label.textContent = translations[lang][key];
            }
        });

        document.querySelectorAll('input[type="submit"], button[type="submit"]').forEach(button => {
            const key = button.value || button.textContent.trim();
            if (translations[lang] && translations[lang][key]) {
                if (button.type === 'submit') {
                    button.value = translations[lang][key];
                } else {
                    button.textContent = translations[lang][key];
                }
            }
        });

        document.querySelectorAll('option').forEach(option => {
            const key = option.textContent.trim();
            if (translations[lang] && translations[lang][key]) {
                option.textContent = translations[lang][key];
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