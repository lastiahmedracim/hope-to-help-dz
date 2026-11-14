document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('projectForm');
    const messageDiv = document.getElementById('pro-message');

    const translations = {
        'fr': {
            'Tous les champs doivent être remplis.': 'Tous les champs doivent être remplis.',
            'Le nom du projet ne doit pas dépasser 140 caractères.': 'Le nom du projet ne doit pas dépasser 140 caractères.',
            'L\'idée du projet ne doit pas dépasser 210 caractères.': 'L\'idée du projet ne doit pas dépasser 210 caractères.',
            'La valeur ajoutée ne doit pas dépasser 140 caractères.': 'La valeur ajoutée ne doit pas dépasser 140 caractères.',
            'Le nombre d\'employés doit être un nombre positif.': 'Le nombre d\'employés doit être un nombre positif.',
            'Le coût de location doit être un nombre positif.': 'Le coût de location doit être un nombre positif.',
            'Le coût des équipements doit être un nombre positif.': 'Le coût des équipements doit être un nombre positif.',
            'Les dépenses supplémentaires doivent être d\'au moins 500 000 DZD.': 'Les dépenses supplémentaires doivent être d\'au moins 500 000 DZD.',
            'Veuillez sélectionner les termes du partenariat.': 'Veuillez sélectionner les termes du partenariat.',
            'Le taux d\'intérêt du partenaire doit être un nombre entre 0 et 100.': 'Le taux d\'intérêt du partenaire doit être un nombre entre 0 et 100.',
            'Le coût total du projet doit être un nombre positif.': 'Le coût total du projet doit être un nombre positif.',
            'Le nombre de partenaires doit être au moins 1.': 'Le nombre de partenaires doit être au moins 1.',
            'Le montant de participation par personne doit être un nombre positif.': 'Le montant de participation par personne doit être un nombre positif.',
            'Une erreur s\'est produite lors de la soumission du formulaire.': 'Une erreur s\'est produite lors de la soumission du formulaire.'
        },
        'ar': {
            'Tous les champs doivent être remplis.': 'يجب ملء جميع الحقول.',
            'Le nom du projet ne doit pas dépasser 140 caractères.': 'يجب ألا يتجاوز اسم المشروع 140 حرفًا.',
            'L\'idée du projet ne doit pas dépasser 210 caractères.': 'يجب ألا تتجاوز فكرة المشروع 210 أحرف.',
            'La valeur ajoutée ne doit pas dépasser 140 caractères.': 'يجب ألا تتجاوز القيمة المضافة 140 حرفًا.',
            'Le nombre d\'employés doit être un nombre positif.': 'يجب أن يكون عدد الموظفين رقمًا موجبًا.',
            'Le coût de location doit être un nombre positif.': 'يجب أن تكون تكلفة الإيجار رقمًا موجبًا.',
            'Le coût des équipements doit être un nombre positif.': 'يجب أن تكون تكلفة المعدات رقمًا موجبًا.',
            'Les dépenses supplémentaires doivent être d\'au moins 500 000 DZD.': 'يجب أن تكون النفقات الإضافية 500000 دينار جزائري على الأقل.',
            'Veuillez sélectionner les termes du partenariat.': 'يرجى اختيار شروط الشراكة.',
            'Le taux d\'intérêt du partenaire doit être un nombre entre 0 et 100.': 'يجب أن يكون معدل فائدة الشريك رقمًا بين 0 و 100.',
            'Le coût total du projet doit être un nombre positif.': 'يجب أن تكون التكلفة الإجمالية للمشروع رقمًا موجبًا.',
            'Le nombre de partenaires doit être au moins 1.': 'يجب أن يكون عدد الشركاء 1 على الأقل.',
            'Le montant de participation par personne doit être un nombre positif.': 'يجب أن يكون مبلغ المشاركة لكل شخص رقمًا موجبًا.',
            'Une erreur s\'est produite lors de la soumission du formulaire.': 'حدث خطأ أثناء إرسال النموذج.'
        },
        'en': {
            'Tous les champs doivent être remplis.': 'All fields must be filled.',
            'Le nom du projet ne doit pas dépasser 140 caractères.': 'The project name must not exceed 140 characters.',
            'L\'idée du projet ne doit pas dépasser 210 caractères.': 'The project idea must not exceed 210 characters.',
            'La valeur ajoutée ne doit pas dépasser 140 caractères.': 'The added value must not exceed 140 characters.',
            'Le nombre d\'employés doit être un nombre positif.': 'The number of employees must be a positive number.',
            'Le coût de location doit être un nombre positif.': 'The rental cost must be a positive number.',
            'Le coût des équipements doit être un nombre positif.': 'The equipment cost must be a positive number.',
            'Les dépenses supplémentaires doivent être d\'au moins 500 000 DZD.': 'Additional expenses must be at least 500,000 DZD.',
            'Veuillez sélectionner les termes du partenariat.': 'Please select the partnership terms.',
            'Le taux d\'intérêt du partenaire doit être un nombre entre 0 et 100.': 'The partner interest rate must be a number between 0 and 100.',
            'Le coût total du projet doit être un nombre positif.': 'The total project cost must be a positive number.',
            'Le nombre de partenaires doit être au moins 1.': 'The number of partners must be at least 1.',
            'Le montant de participation par personne doit être un nombre positif.': 'The participation amount per person must be a positive number.',
            'Une erreur s\'est produite lors de la soumission du formulaire.': 'An error occurred while submitting the form.'
        }
    };

    function getTranslation(key) {
        const lang = getCookie('language') || 'fr';
        return translations[lang][key] || key;
    }

    function getCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for(let i=0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        messageDiv.textContent = '';
        messageDiv.className = '';

        const fields = form.elements;
        for (let i = 0; i < fields.length; i++) {
            if (fields[i].type !== 'submit' && fields[i].value.trim() === '') {
                showError(getTranslation('Tous les champs doivent être remplis.'));
                return;
            }
        }

        if (fields.projectName.value.length > 140) {
            showError(getTranslation('Le nom du projet ne doit pas dépasser 140 caractères.'));
            return;
        }

        if (fields.projectIdea.value.length > 210) {
            showError(getTranslation('L\'idée du projet ne doit pas dépasser 210 caractères.'));
            return;
        }

        if (fields.addedValue.value.length > 140) {
            showError(getTranslation('La valeur ajoutée ne doit pas dépasser 140 caractères.'));
            return;
        }

        if (isNaN(fields.employeesCount.value) || parseInt(fields.employeesCount.value) < 0) {
            showError(getTranslation('Le nombre d\'employés doit être un nombre positif.'));
            return;
        }

        if (isNaN(fields.rentalCost.value) || parseFloat(fields.rentalCost.value) < 0) {
            showError(getTranslation('Le coût de location doit être un nombre positif.'));
            return;
        }

        if (isNaN(fields.equipmentCost.value) || parseFloat(fields.equipmentCost.value) < 0) {
            showError(getTranslation('Le coût des équipements doit être un nombre positif.'));
            return;
        }

        if (isNaN(fields.additionalExpenses.value) || parseFloat(fields.additionalExpenses.value) < 500000) {
            showError(getTranslation('Les dépenses supplémentaires doivent être d\'au moins 500 000 DZD.'));
            return;
        }

        if (fields.partnershipTerms.value === '') {
            showError(getTranslation('Veuillez sélectionner les termes du partenariat.'));
            return;
        }

        if (isNaN(fields.partnerInterestRate.value) || parseFloat(fields.partnerInterestRate.value) < 0 || parseFloat(fields.partnerInterestRate.value) > 100) {
            showError(getTranslation('Le taux d\'intérêt du partenaire doit être un nombre entre 0 et 100.'));
            return;
        }

        if (isNaN(fields.totalProjectCost.value) || parseFloat(fields.totalProjectCost.value) <= 0) {
            showError(getTranslation('Le coût total du projet doit être un nombre positif.'));
            return;
        }

        if (isNaN(fields.numberOfPartners.value) || parseInt(fields.numberOfPartners.value) < 1) {
            showError(getTranslation('Le nombre de partenaires doit être au moins 1.'));
            return;
        }

        if (isNaN(fields.participationAmountPerPerson.value) || parseFloat(fields.participationAmountPerPerson.value) <= 0) {
            showError(getTranslation('Le montant de participation par personne doit être un nombre positif.'));
            return;
        }

        const formData = new FormData(form);
        
        fetch('s_p.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message);
                form.reset();
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            showError(getTranslation('Une erreur s\'est produite lors de la soumission du formulaire.'));
        });
    });

    function showError(message) {
        messageDiv.textContent = message;
        messageDiv.className = 'error';
    }

    function showSuccess(message) {
        messageDiv.textContent = message;
        messageDiv.className = 'success';
    }
});