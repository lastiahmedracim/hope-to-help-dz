document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.contact-form form');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const subjectInput = document.getElementById('subject');
    const messageInput = document.getElementById('message');
    const errorDiv = document.getElementById('contact-error');

    const nameRegex = /^[A-Za-z\s]{1,30}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const subjectRegex = /^[A-Za-z\s]{1,30}$/;

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        if (nameInput.value.trim() === '') {
            errorDiv.textContent = 'Veuillez entrer votre nom.';
            return;
        }

        if (!nameRegex.test(nameInput.value.trim())) {
            errorDiv.textContent = 'Le nom doit contenir uniquement des lettres et avoir une longueur maximale de 30 caractères.';
            return;
        }

        if (emailInput.value.trim() === '') {
            errorDiv.textContent = 'Veuillez entrer votre adresse e-mail.';
            return;
        }

        if (!emailRegex.test(emailInput.value.trim())) {
            errorDiv.textContent = 'Veuillez entrer une adresse e-mail valide.';
            return;
        }

        if (subjectInput.value.trim() === '') {
            errorDiv.textContent = 'Veuillez entrer un sujet.';
            return;
        }

        if (!subjectRegex.test(subjectInput.value.trim())) {
            errorDiv.textContent = 'Le sujet doit contenir uniquement des lettres et avoir une longueur maximale de 30 caractères.';
            return;
        }

        if (messageInput.value.trim() === '') {
            errorDiv.textContent = 'Veuillez entrer votre message.';
            return;
        }

        if (messageInput.value.trim().length > 500) {
            errorDiv.textContent = 'Le message ne doit pas dépasser 500 caractères.';
            return;
        }

        grecaptcha.execute('6Ld2gA8qAAAAADdxY7b2fqykkxgs-dUDqd1PaMFU', {action: 'contact'}).then(function(token) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 's_c.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    if (xhr.responseText === 'success') {
                        errorDiv.textContent = 'Votre message a été envoyé avec succès.';
                        form.reset();
                    } else {
                        errorDiv.textContent = 'Une erreur s\'est produite lors de l\'envoi du message.';
                    }
                }
            };
            xhr.send(`name=${encodeURIComponent(nameInput.value)}&email=${encodeURIComponent(emailInput.value)}&subject=${encodeURIComponent(subjectInput.value)}&message=${encodeURIComponent(messageInput.value)}&recaptcha_token=${token}`);
        });
    });
});