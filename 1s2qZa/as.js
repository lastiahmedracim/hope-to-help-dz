document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('asignup');
    const errorMessagesDiv = document.getElementById('error-messages');
    const adminName = document.getElementById("admin_name");
    const adminLastName = document.getElementById("admin_last_name");
    const emailField = document.getElementById("admin_email");
    const phoneField = document.getElementById("phone_number");
    const passwordField = document.getElementById("admin_password");
    const confirmPasswordField = document.getElementById("confirm_password");
    const termsCheckbox = document.getElementById("terms_conditions");

    const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    const isValidPhone = (phone) => /^\d{10}$/.test(phone); 
    const isValidPassword = (password) => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{10,}$/.test(password);
    const isValidName = (name) => /^[a-zA-Z]{3,}$/.test(name);

    form.addEventListener("submit", async function(event) {
        event.preventDefault();
        console.log("Form submission started");

        errorMessagesDiv.innerHTML = '';
        const errorMessages = [];

        if (!adminName.value || !adminLastName.value || !emailField.value || !phoneField.value || !passwordField.value || !confirmPasswordField.value || !termsCheckbox.checked) {
            errorMessages.push("Veuillez remplir tous les champs.");
        } else {
            if (!isValidEmail(emailField.value)) {
                errorMessages.push("Veuillez entrer une adresse email valide.");
            }

            if (!isValidPhone(phoneField.value)) {
                errorMessages.push("Veuillez entrer un numéro de téléphone valide (10 chiffres).");
            }

            if (!isValidPassword(passwordField.value)) {
                errorMessages.push("Le mot de passe doit avoir au moins 10 caractères, une majuscule, un chiffre et un caractère spécial.");
            }

            if (passwordField.value !== confirmPasswordField.value) {
                errorMessages.push("Les mots de passe ne correspondent pas.");
            }

            if (!isValidName(adminLastName.value) || !isValidName(adminName.value)) {
                errorMessages.push("Le nom et le prénom ne doivent contenir que des lettres et avoir au moins 3 caractères.");
            } else if (adminLastName.value.length < 3) {
                errorMessages.push("Le nom doit avoir au moins 3 caractères.");
            }

            if (!termsCheckbox.checked) {
                errorMessages.push("Vous devez accepter les termes et conditions.");
            }
        }

        if (errorMessages.length > 0) {
            displayErrorMessages(errorMessages);
            console.log("Validation errors:", errorMessages);
            return;
        }

        console.log("Form validation passed, executing reCAPTCHA");
        
        try {
            const token = await new Promise((resolve, reject) => {
                grecaptcha.ready(async () => {
                    try {
                        const token = await grecaptcha.execute('6Ld2gA8qAAAAADdxY7b2fqykkxgs-dUDqd1PaMFU', {action: 'submit'});
                        resolve(token);
                    } catch (error) {
                        reject(error);
                    }
                });
            });

            console.log("reCAPTCHA token generated:", token);
            
            const formData = new FormData(form);
            formData.append('recaptcha_token', token);

            console.log("Sending form data to server");
            const response = await fetch('1s2qZa-asp.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            console.log("Server response:", data);

            if (!data.success) {
                displayErrorMessages([data.message]);
            } else {
                console.log("Registration successful, redirecting");
                window.location.replace('1s2qZa-lf.php');
            }
        } catch (error) {
            console.error('Error:', error);
            displayErrorMessages(['Une erreur s\'est produite. Veuillez réessayer plus tard.', error.message]);
        }
    });

    function displayErrorMessages(messages) {
        errorMessagesDiv.innerHTML = messages.map(msg => `<p>${msg}</p>`).join('');
    }
});