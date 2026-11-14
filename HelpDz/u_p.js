document.addEventListener('DOMContentLoaded', function() {
    const translations = {
        'fr': {
            'Veuillez remplir tous les champs.': 'Veuillez remplir tous les champs.',
            'Le numéro de téléphone doit comporter 10 chiffres.': 'Le numéro de téléphone doit comporter 10 chiffres.',
            'Le nom, le prénom, la wilaya, la ville et le district ne doivent contenir que des lettres.': 'Le nom, le prénom, la wilaya, la ville et le district ne doivent contenir que des lettres.',
            'Le format de l\'adresse est invalide.': 'Le format de l\'adresse est invalide.',
            'Utilisateur non trouvé.': 'Utilisateur non trouvé.',
            'Votre mise à jour précédente est toujours en attente d\'approbation.': 'Votre mise à jour précédente est toujours en attente d\'approbation.',
            'Vos informations ont été soumises pour validation. Elles seront mises à jour après approbation.': 'Vos informations ont été soumises pour validation. Elles seront mises à jour après approbation.',
            'Erreur lors de la soumission des données utilisateur pour validation :': 'Erreur lors de la soumission des données utilisateur pour validation :',
            'Méthode non autorisée.': 'Méthode non autorisée.',
            'Aucun changement détecté.': 'Aucun changement détecté.'
        },
        'ar': {
            'Veuillez remplir tous les champs.': 'يرجى ملء جميع الحقول.',
            'Le numéro de téléphone doit comporter 10 chiffres.': 'يجب أن يحتوي رقم الهاتف على 10 أرقام.',
            'Le nom, le prénom, la wilaya, la ville et le district ne doivent contenir que des lettres.': 'يجب أن يحتوي الاسم والكنية والولاية والمدينة والمنطقة على أحرف فقط.',
            'Le format de l\'adresse est invalide.': 'تنسيق العنوان غير صالح.',
            'Utilisateur non trouvé.': 'لم يتم العثور على المستخدم.',
            'Votre mise à jour précédente est toujours en attente d\'approbation.': 'التحديث السابق لا يزال في انتظار الموافقة.',
            'Vos informations ont été soumises pour validation. Elles seront mises à jour après approbation.': 'تم إرسال معلوماتك للتحقق. سيتم تحديثها بعد الموافقة.',
            'Erreur lors de la soumission des données utilisateur pour validation :': 'خطأ أثناء إرسال بيانات المستخدم للتحقق:',
            'Méthode non autorisée.': 'طريقة غير مصرح بها.',
            'Aucun changement détecté.': 'لم يتم اكتشاف أي تغييرات.'
        },
        'en': {
            'Veuillez remplir tous les champs.': 'Please fill in all fields.',
            'Le numéro de téléphone doit comporter 10 chiffres.': 'The phone number must contain 10 digits.',
            'Le nom, le prénom, la wilaya, la ville et le district ne doivent contenir que des lettres.': 'The name, surname, wilaya, city, and district must contain only letters.',
            'Le format de l\'adresse est invalide.': 'The address format is invalid.',
            'Utilisateur non trouvé.': 'User not found.',
            'Votre mise à jour précédente est toujours en attente d\'approbation.': 'Your previous update is still pending approval.',
            'Vos informations ont été soumises pour validation. Elles seront mises à jour après approbation.': 'Your information has been submitted for validation. It will be updated after approval.',
            'Erreur lors de la soumission des données utilisateur pour validation :': 'Error while submitting user data for validation:',
            'Méthode non autorisée.': 'Method not allowed.',
            'Aucun changement détecté.': 'No changes detected.'
        }
    };

    function translateMessage(message) {
        const lang = getCookie('language') || 'fr';
        return translations[lang][message] || message;
    }

    function getCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function updateUserData() {
        const form = document.getElementById('updateUserForm');
        const formData = new FormData(form);
        const phoneNumber = formData.get('phone_number');
        const name = formData.get('user_name');
        const lastName = formData.get('user_last_name');
        const wilaya = formData.get('wilaya');
        const city = formData.get('city');
        const district = formData.get('district');
        const userAddress = formData.get('user_address');
        const infoMessage = document.getElementById('info-message');

        infoMessage.textContent = '';

        if (!name || !lastName || !wilaya || !city || !district || !userAddress || !phoneNumber) {
            infoMessage.textContent = translateMessage('Veuillez remplir tous les champs.');
            return;
        }

        if (!(/^\d{10}$/.test(phoneNumber))) {
            infoMessage.textContent = translateMessage('Le numéro de téléphone doit comporter 10 chiffres.');
            return;
        }

        const letterOnlyRegex = /^[A-Za-z]+$/;
        if (!letterOnlyRegex.test(name) || 
            !letterOnlyRegex.test(lastName) || 
            !letterOnlyRegex.test(wilaya) || 
            !letterOnlyRegex.test(city) ||
            !letterOnlyRegex.test(district)) {
            infoMessage.textContent = translateMessage('Le nom, le prénom, la wilaya, la ville et le district ne doivent contenir que des lettres.');
            return;
        }

        const addressRegex = /^[A-Za-z0-9\s\.,#-]+$/;
        if (!addressRegex.test(userAddress)) {
            infoMessage.textContent = translateMessage('Le format de l\'adresse est invalide.');
            return;
        }

        if (name === currentUserData.user_name && 
            lastName === currentUserData.user_last_name && 
            wilaya === currentUserData.wilaya && 
            city === currentUserData.city && 
            district === currentUserData.district && 
            userAddress === currentUserData.user_address && 
            phoneNumber === currentUserData.phone_number) {
            infoMessage.textContent = translateMessage('Aucun changement détecté.');
            return;
        }

        fetch('u_p.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(data => {
            console.log('Server response:', data);
            infoMessage.textContent = translateMessage(data);
        })
        .catch(error => {
            console.error('Erreur :', error);
            infoMessage.textContent = translateMessage('Erreur lors de la soumission des données utilisateur pour validation :') + ' ' + error.message;
        });
    }
    
    window.updateUserData = updateUserData;

    const form = document.getElementById('updateUserForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        updateUserData();
    });
});