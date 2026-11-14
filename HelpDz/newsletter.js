function translateMessage(message) {
    const translations = {
        'fr': {
            'Please enter an email address.': 'Veuillez entrer une adresse e-mail.',
            'Invalid email address format.': 'Format d\'adresse email invalide.',
            'Error subscribing to the newsletter.': 'Erreur lors de l\'abonnement à la newsletter.',
            'An error occurred. Please try again later.': 'Une erreur s\'est produite. Veuillez réessayer plus tard.'
        },
        'ar': {
            'Please enter an email address.': 'الرجاء إدخال عنوان بريد إلكتروني.',
            'Invalid email address format.': 'صيغة البريد الإلكتروني غير صالحة.',
            'Error subscribing to the newsletter.': 'خطأ في الاشتراك في النشرة الإخبارية.',
            'An error occurred. Please try again later.': 'حدث خطأ. الرجاء المحاولة مرة أخرى لاحقًا.'
        },
        'en': {
            'Please enter an email address.': 'Please enter an email address.',
            'Invalid email address format.': 'Invalid email address format.',
            'Error subscribing to the newsletter.': 'Error subscribing to the newsletter.',
            'An error occurred. Please try again later.': 'An error occurred. Please try again later.'
        }
    };

    const lang = document.cookie.replace(/(?:(?:^|.*;\s*)language\s*\=\s*([^;]*).*$)|^.*$/, "$1") || 'fr';
    return translations[lang][message] || message;
}

document.getElementById('newsletterForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const email = formData.get('nw_email');

    const submitMessage = document.getElementById('submit-message');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!email) {
        submitMessage.textContent = translateMessage('Please enter an email address.');
        return;
    } else if (!emailRegex.test(email)) {
        submitMessage.textContent = translateMessage('Invalid email address format.');
        return;
    }

    try {
        const response = await fetch(form.action, {
            method: form.method,
            body: formData
        });

        if (!response.ok) {
            throw new Error(translateMessage('Error subscribing to the newsletter.'));
        }

        const data = await response.json();

        if (data.success) {
            submitMessage.textContent = data.message;
            form.reset();
        } else {
            submitMessage.textContent = data.message;
        }

        setTimeout(() => {
            submitMessage.textContent = '';
        }, 3000);
    } catch (error) {
        console.error(error.message);
        submitMessage.textContent = translateMessage('An error occurred. Please try again later.');
    }
});