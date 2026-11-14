document.addEventListener('DOMContentLoaded', function() {
    const createBtn = document.querySelector('.create-notification-btn');
    const modal = document.querySelector('.notification-modal');
    const closeBtn = document.querySelector('.notification-close');
    const form = document.getElementById('notificationForm');
    const messageDiv = document.getElementById('n-message');
    const targetType = document.getElementById('targetType');
    const userSelection = document.getElementById('userSelection');

    createBtn.addEventListener('click', () => {
        modal.style.display = 'block';
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    targetType.addEventListener('change', function() {
        userSelection.style.display = this.value === 'specific' ? 'block' : 'none';
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        if (!formData.get('expiresAt')) {
            formData.delete('expiresAt');
        }

        fetch('c_n.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                afficherMessage(data.message, 'succes');
                form.reset();
                setTimeout(() => {
                    modal.style.display = 'none';
                    location.reload();
                }, 2000);
            } else {
                afficherMessage(data.message, 'erreur');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            afficherMessage('Une erreur est survenue. Veuillez réessayer.', 'erreur');
        });
    });

    function afficherMessage(message, type) {
        messageDiv.textContent = message;
        messageDiv.className = type;
        messageDiv.style.display = 'block';
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
    }
});