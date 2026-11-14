document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('signin-form');
    const errorMessagesDiv = document.getElementById('error-messages');

    const translations = {
        'fr': {
            'fill_all_fields': "Veuillez remplir tous les champs.",
            'error_occurred': "Une erreur s'est produite. Veuillez réessayer plus tard."
        },
        'ar': {
            'fill_all_fields': "يرجى ملء جميع الحقول.",
            'error_occurred': "حدث خطأ. يرجى المحاولة مرة أخرى لاحقًا."
        },
        'en': {
            'fill_all_fields': "Please fill in all fields.",
            'error_occurred': "An error occurred. Please try again later."
        }
    };

    let currentLang = document.documentElement.lang || 'fr';

    // Function to safely add event listeners
    function addClickListener(id, lang) {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('click', (e) => {
                e.preventDefault();
                translatePage(lang);
            });
        }
    }

    // Safely add event listeners for language switching
    addClickListener('fr-link', 'fr');
    addClickListener('ar-link', 'ar');
    addClickListener('en-link', 'en');

    function translatePage(lang) {
        currentLang = lang;
        // Implement your translation logic here
        // This function should update the text content of elements based on the selected language
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            errorMessagesDiv.innerHTML = '';

            const rightPseudoInput = document.getElementById('user_r_pseudo');
            const passwordInput = document.getElementById('password');

            if (!rightPseudoInput || !passwordInput) {
                console.error('Could not find input elements');
                return;
            }

            const rightPseudo = rightPseudoInput.value.trim();
            const password = passwordInput.value.trim();

            if (!rightPseudo || !password) {
                displayErrorMessages([translations[currentLang].fill_all_fields]);
                return;
            }

            grecaptcha.execute('6Ld2gA8qAAAAADdxY7b2fqykkxgs-dUDqd1PaMFU', {action: 'submit'}).then(function(token) {
                const formData = new FormData(form);
                formData.append('recaptcha_token', token);
                formData.append('lang', currentLang);

                fetch('../functions/ul.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    console.log('Raw response:', text);
                    try {
                        const data = JSON.parse(text);
                        if (!data.success) {
                            displayErrorMessages([data.message]);
                        } else {
                            window.location.href = '../index.php';
                        }
                    } catch (error) {
                        console.error('Error parsing JSON:', error);
                        displayErrorMessages([translations[currentLang].error_occurred]);
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    displayErrorMessages([translations[currentLang].error_occurred]);
                });
            });
        });
    } else {
        console.error('Could not find form element');
    }

    function displayErrorMessages(messages) {
        if (errorMessagesDiv) {
            errorMessagesDiv.innerHTML = messages.map(msg => `<p>${msg}</p>`).join('');
        } else {
            console.error('Could not find error messages div');
        }
    }
});