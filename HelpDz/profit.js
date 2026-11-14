document.addEventListener('DOMContentLoaded', function() {
    const translations = {
        'fr': {
            'Veuillez entrer un montant de profit valide.': 'Veuillez entrer un montant de profit valide.',
            'Erreur:': 'Erreur:',
            'Le champ RIP ne peut pas être vide.': 'Le champ RIP ne peut pas être vide.',
            'RIP doit contenir exactement 20 chiffres.': 'RIP doit contenir exactement 20 chiffres.',
            'RIP ne peut pas être négatif.': 'RIP ne peut pas être négatif.',
            'Une erreur s\'est produite lors de l\'envoi de la demande. Veuillez réessayer plus tard.': 'Une erreur s\'est produite lors de l\'envoi de la demande. Veuillez réessayer plus tard.'
        },
        'ar': {
            'Veuillez entrer un montant de profit valide.': 'الرجاء إدخال مبلغ ربح صالح.',
            'Erreur:': 'خطأ:',
            'Le champ RIP ne peut pas être vide.': 'لا يمكن أن يكون حقل رقم التعريف البريدي فارغًا.',
            'RIP doit contenir exactement 20 chiffres.': 'يجب أن يحتوي رقم التعريف البريدي على 20 رقمًا بالضبط.',
            'RIP ne peut pas être négatif.': 'لا يمكن أن يكون رقم التعريف البريدي سالبًا.',
            'Une erreur s\'est produite lors de l\'envoi de la demande. Veuillez réessayer plus tard.': 'حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى لاحقًا.'
        },
        'en': {
            'Veuillez entrer un montant de profit valide.': 'Please enter a valid profit amount.',
            'Erreur:': 'Error:',
            'Le champ RIP ne peut pas être vide.': 'The Postal identity number field cannot be empty.',
            'RIP doit contenir exactement 20 chiffres.': 'Postal identity number must contain exactly 20 digits.',
            'RIP ne peut pas être négatif.': 'Postal identity number cannot be negative.',
            'Une erreur s\'est produite lors de l\'envoi de la demande. Veuillez réessayer plus tard.': 'An error occurred while sending the request. Please try again later.'
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

    document.getElementById('reclameButton').addEventListener('click', function() {
        console.log('Reclame button clicked'); 

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            console.log('XHR state change: ' + xhr.readyState);

            if (xhr.readyState === XMLHttpRequest.DONE) {
                console.log('XHR status: ' + xhr.status);

                if (xhr.status === 200) {
                    console.log('XHR response: ' + xhr.responseText);
                    document.getElementById('profit').innerHTML = xhr.responseText;
                } else {
                    console.error(translateMessage('Erreur:') + ' ' + xhr.status);
                }
            }
        };

        xhr.open('POST', '../functions/c_p.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('reclameProfit=1');
    });

    document.getElementById('sendProfitButton').addEventListener('click', function() {
        var descendantId = document.getElementById('descendant').value;
        var profitAmount = document.getElementById('profit_amount').value.trim();
        var sendingError = document.getElementById('sending-error');

        sendingError.innerText = '';

        if (!/^\d+$/.test(profitAmount) || profitAmount.includes('-')) {
            sendingError.innerText = translateMessage('Veuillez entrer un montant de profit valide.');
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    sendingError.innerText = response.message;
                } else {
                    sendingError.innerText = translateMessage('Erreur:') + ' ' + xhr.status;
                }
            }
        };

        xhr.open('POST', '../functions/s_p.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('descendant_id=' + encodeURIComponent(descendantId) + '&profit_amount=' + encodeURIComponent(profitAmount));
    });

    document.getElementById('reclaimProfitButton').addEventListener('click', function() {
        document.getElementById('reclaiming-error').innerText = '';

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    document.getElementById('reclaiming-error').innerText = response.message;
                } else {
                    document.getElementById('reclaiming-error').innerText = translateMessage('Erreur:') + ' ' + xhr.status;
                }
            }
        };

        xhr.open('POST', '../functions/r_p.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send();
    });

    const changeRipBtn = document.getElementById('change-rip-btn');
    const changeRipForm = document.getElementById('change-rip-form');
    const newRipInput = document.getElementById('new-rip');
    const submitRipChangeBtn = document.getElementById('submit-rip-change');
    const demandeError = document.getElementById('demande-error');
    const demandeSuccess = document.getElementById('demande-success');
    
    changeRipBtn.addEventListener('click', function () {
        changeRipForm.style.display = 'block';
        demandeSuccess.style.display = 'none';
    });
    
    submitRipChangeBtn.addEventListener('click', function () {
        const newRip = newRipInput.value.trim();
        
        demandeError.textContent = '';
        demandeSuccess.style.display = 'none';
        
        if (!newRip) {
            demandeError.textContent = translateMessage('Le champ RIP ne peut pas être vide.');
            return;
        }
        
        if (!/^\d{20}$/.test(newRip)) {
            demandeError.textContent = translateMessage('RIP doit contenir exactement 20 chiffres.');
            return;
        }
        
        if (BigInt(newRip) < 0) {
            demandeError.textContent = translateMessage('RIP ne peut pas être négatif.');
            return;
        }
        
        fetch('../functions/s_r_ch_r.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ newRip }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                demandeSuccess.textContent = data.message;
                demandeSuccess.style.display = 'block';
                changeRipForm.style.display = 'none';
                newRipInput.value = '';
                demandeError.textContent = '';
            } else {
                demandeError.textContent = data.message;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            demandeError.textContent = translateMessage('Une erreur s\'est produite lors de l\'envoi de la demande. Veuillez réessayer plus tard.');
        });
    });
});