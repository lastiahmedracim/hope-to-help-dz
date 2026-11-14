document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('adminRecoveryForm');
    const emailInput = document.getElementById('email');
    const errorMessagesDiv = document.getElementById('error-messages');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        errorMessagesDiv.innerHTML = '';

        const email = emailInput.value.trim();

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email) {
            showError('Veuillez entrer votre email.');
            return;
        }

        if (!emailRegex.test(email)) {
            showError('Veuillez entrer un email valide.');
            return;
        }

        fetch('s_a.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `email=${encodeURIComponent(email)}`
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.text();
        })
        .then(text => {
            try {
                return JSON.parse(text);
            } catch (error) {
                console.error('Response is not valid JSON:', text);
                throw new Error('Réponse invalide du serveur');
            }
        })
        .then(data => {
            if (data.success) {
                showSuccess(data.message);
                setTimeout(() => {
                    window.location.href = 'adp_r.php';
                }, 3000);
            } else {
                showError(data.message || 'Une erreur s\'est produite');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Une erreur s\'est produite. Veuillez réessayer.');
        });
    });

    function showError(message) {
        const errorElement = document.createElement('p');
        errorElement.textContent = message;
        errorElement.style.color = 'red';
        errorMessagesDiv.appendChild(errorElement);
    }

    function showSuccess(message) {
        const successElement = document.createElement('p');
        successElement.textContent = message;
        successElement.style.color = 'green';
        errorMessagesDiv.appendChild(successElement);
    }
});