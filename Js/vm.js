const vmTranslations = {
    'fr': {
        "Acheter l'argent virtuel": "Acheter l'argent virtuel",
        "Informations bancaires": "Informations bancaires",
        "Banque": "Banque",
        "Nom & Raison Sociale": "Nom & Raison Sociale",
        "Adresse": "Adresse",
        "IBAN (R.I.B DU CLIENT)": "IBAN (R.I.B DU CLIENT)",
        "Instructions": "Instructions",
        "Effectuez un virement vers le compte bancaire ci-dessus.": "Effectuez un virement vers le compte bancaire ci-dessus.",
        "Prenez une capture d'écran ou une photo du reçu de paiement.": "Prenez une capture d'écran ou une photo du reçu de paiement.",
        "Cliquez sur le bouton ci-dessous pour télécharger votre preuve de paiement.": "Cliquez sur le bouton ci-dessous pour télécharger votre preuve de paiement.",
        "Télécharger le reçu de paiement": "Télécharger le reçu de paiement",
        "Envoyer": "Envoyer",
        "Utilisateur introuvable.": "Utilisateur introuvable.",
        "Votre image de paiement a été téléchargée avec succès. Cela sera vérifié bientôt.": "Votre image de paiement a été téléchargée avec succès. Cela sera vérifié bientôt.",
        "Échec de l'enregistrement de l'image de paiement.": "Échec de l'enregistrement de l'image de paiement.",
        "Le téléchargement du fichier a échoué.": "Le téléchargement du fichier a échoué.",
        "Requête invalide.": "Requête invalide.",
        "Vous avez déjà envoyé une demande de paiement qui est en attente de vérification.": "Vous avez déjà envoyé une demande de paiement qui est en attente de vérification."
    },
    'ar': {
        "Acheter l'argent virtuel": "شراء النقود الافتراضية",
        "Informations bancaires": "معلومات مصرفية",
        "Banque": "البنك",
        "Nom & Raison Sociale": "الاسم والسبب الاجتماعي",
        "Adresse": "العنوان",
        "IBAN (R.I.B DU CLIENT)": "رقم الحساب المصرفي الدولي (IBAN)",
        "Instructions": "تعليمات",
        "Effectuez un virement vers le compte bancaire ci-dessus.": "قم بتحويل الأموال إلى الحساب المصرفي المذكور أعلاه.",
        "Prenez une capture d'écran ou une photo du reçu de paiement.": "التقط لقطة شاشة أو صورة لإيصال الدفع.",
        "Cliquez sur le bouton ci-dessous pour télécharger votre preuve de paiement.": "انقر على الزر أدناه لتحميل إثبات الدفع الخاص بك.",
        "Télécharger le reçu de paiement": "تحميل إيصال الدفع",
        "Envoyer": "إرسال",
        "Utilisateur introuvable.": "المستخدم غير موجود.",
        "Votre image de paiement a été téléchargée avec succès. Cela sera vérifié bientôt.": "تم تحميل صورة الدفع الخاصة بك بنجاح. سيتم التحقق منها قريبًا.",
        "Échec de l'enregistrement de l'image de paiement.": "فشل تسجيل صورة الدفع.",
        "Le téléchargement du fichier a échoué.": "فشل تحميل الملف.",
        "Requête invalide.": "طلب غير صالح.",
        "Vous avez déjà envoyé une demande de paiement qui est en attente de vérification.": "لقد أرسلت بالفعل طلب دفع في انتظار التحقق."
    },
    'en': {
        "Acheter l'argent virtuel": "Buy Virtual Money",
        "Informations bancaires": "Bank Information",
        "Banque": "Bank",
        "Nom & Raison Sociale": "Name & Company Name",
        "Adresse": "Address",
        "IBAN (R.I.B DU CLIENT)": "IBAN (Customer Account Number)",
        "Instructions": "Instructions",
        "Effectuez un virement vers le compte bancaire ci-dessus.": "Make a transfer to the bank account above.",
        "Prenez une capture d'écran ou une photo du reçu de paiement.": "Take a screenshot or photo of the payment receipt.",
        "Cliquez sur le bouton ci-dessous pour télécharger votre preuve de paiement.": "Click the button below to upload your proof of payment.",
        "Télécharger le reçu de paiement": "Upload Payment Receipt",
        "Envoyer": "Send",
        "Utilisateur introuvable.": "User not found.",
        "Votre image de paiement a été téléchargée avec succès. Cela sera vérifié bientôt.": "Your payment image has been uploaded successfully. It will be verified soon.",
        "Échec de l'enregistrement de l'image de paiement.": "Failed to record the payment image.",
        "Le téléchargement du fichier a échoué.": "File upload failed.",
        "Requête invalide.": "Invalid request.",
        "Vous avez déjà envoyé une demande de paiement qui est en attente de vérification.": "You have already sent a payment request that is pending verification."
    }
};

function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function translateVirtualMoney(lang) {
    currentLanguage = lang;
    setCookie('language', lang, 30);

    document.querySelectorAll('[data-translate]').forEach(element => {
        const key = element.getAttribute('data-translate');
        if (vmTranslations[lang] && vmTranslations[lang][key]) {
            if (element.tagName === 'INPUT') {
                if (element.type === 'text' || element.type === 'file') {
                    element.placeholder = vmTranslations[lang][key];
                } else if (element.type === 'submit' || element.type === 'button') {
                    element.value = vmTranslations[lang][key];
                }
            } else {
                element.textContent = vmTranslations[lang][key];
            }
        }
    });

    document.documentElement.lang = lang;
    document.querySelector('html').setAttribute('lang', lang);
    
    document.body.style.direction = lang === 'ar' ? 'rtl' : 'ltr';
}

document.addEventListener('DOMContentLoaded', () => {
    const savedLanguage = getCookie('language');
    if (savedLanguage && vmTranslations[savedLanguage]) {
        translateVirtualMoney(savedLanguage);
    } else {
        translateVirtualMoney('fr');
    }
});

document.getElementById('fr-link').addEventListener('click', (e) => {
    e.preventDefault();
    translateVirtualMoney('fr');
    location.reload();
});

document.getElementById('ar-link').addEventListener('click', (e) => {
    e.preventDefault();
    translateVirtualMoney('ar');
    location.reload();
});

document.getElementById('en-link').addEventListener('click', (e) => {
    e.preventDefault();
    translateVirtualMoney('en');
    location.reload();
});
