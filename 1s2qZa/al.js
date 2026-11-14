document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('adminLoginForm');
    const errorMessagesDiv = document.getElementById('error-messages');
    const userIdentifierField = document.getElementById("email_or_phone");
    const passwordField = document.getElementById("password");

    const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    const isValidPhone = (phone) => /^\d{10}$/.test(phone);

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        errorMessagesDiv.innerHTML = '';
        const errorMessages = [];

        if (!userIdentifierField.value || !passwordField.value) {
            errorMessages.push("Veuillez remplir tous les champs.");
        } else {
            if (!isValidEmail(userIdentifierField.value) && !isValidPhone(userIdentifierField.value)) {
                errorMessages.push("Veuillez entrer une adresse email ou un numéro de téléphone valide.");
            }
        }

        if (errorMessages.length > 0) {
            displayErrorMessages(errorMessages);
            return;
        }
        
        grecaptcha.execute('6Ld2gA8qAAAAADdxY7b2fqykkxgs-dUDqd1PaMFU', {action: 'submit'}).then(function(token) {
            const formData = new FormData(form);
            formData.append('recaptcha_token', token);
            
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    displayErrorMessages([data.message]);
                } else {
                    window.location.replace('1s2qZa-lf.php');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                displayErrorMessages(['Une erreur s\'est produite. Veuillez réessayer plus tard.']);
            });
        });
    });

    function displayErrorMessages(messages) {
        errorMessagesDiv.innerHTML = messages.map(msg => `<p>${msg}</p>`).join('');
    }
});