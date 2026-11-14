document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const otpInput = document.getElementById('otp');
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const errorMessagesDiv = document.getElementById('error-messages');

    function showError(message) {
        errorMessagesDiv.innerHTML = '';
        const errorElement = document.createElement('p');
        errorElement.textContent = message;
        errorElement.style.color = 'red';
        errorMessagesDiv.appendChild(errorElement);
    }

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        errorMessagesDiv.innerHTML = '';

        const otp = otpInput.value.trim();
        const newPassword = newPasswordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();

        const passwordRegex = /^(?=.*\d)(?=.*[A-Z])(?=.*[@#$%^&+=]).{10,}$/;

        if (otp === '' || newPassword === '' || confirmPassword === '') {
            showError('Veuillez remplir tous les champs.');
            return;
        }

        if (!passwordRegex.test(newPassword)) {
            showError('Le mot de passe doit contenir au moins 10 caractères, au moins un chiffre, au moins une lettre majuscule et au moins un caractère spécial.');
            return;
        }

        if (newPassword !== confirmPassword) {
            showError('Les mots de passe ne correspondent pas.');
            return;
        }

        form.submit();
    });
});