document.addEventListener('DOMContentLoaded', function() {
    const translations = {
        'fr': {
            'Afficher les filtres': 'Afficher les filtres',
            'Catégories': 'Catégories',
            'La Langue': 'La Langue',
            'Résultats': 'Résultats',
            'Acheter': 'Acheter',
            'En attente': 'En attente',
            'Regarder': 'Regarder',
            'Aucun cours disponible pour les filtres sélectionnés.': 'Aucun cours disponible pour les filtres sélectionnés.',
            'Formation Professionnelle': 'Formation Professionnelle',
            'Beauté': 'Beauté',
            'Mode': 'Mode',
            'Technologie': 'Technologie',
            'Alimentation': 'Alimentation',
            'Voyage': 'Voyage',
            'Santé': 'Santé',
            'Sports': 'Sports',
            'Divertissement': 'Divertissement',
            'Maison': 'Maison',
            'Éducation': 'Éducation',
            'Art': 'Art',
            'Musique': 'Musique',
            'Livres & Littérature': 'Livres & Littérature',
            'Automobiles': 'Automobiles',
            'Finance': 'Finance',
            'Animaux de Compagnie': 'Animaux de Compagnie',
            'Plein Air': 'Plein Air',
            'Fitness': 'Fitness',
            'Informatique': 'Informatique',
            'Français': 'Français',
            'Arabe': 'Arabe',
            'Anglais': 'Anglais'
        },
        'ar': {
            'Afficher les filtres': 'إظهار الفلاتر',
            'Catégories': 'الفئات',
            'La Langue': 'اللغة',
            'Résultats': 'النتائج',
            'Acheter': 'شراء',
            'En attente': 'قيد الانتظار',
            'Regarder': 'مشاهدة',
            'Aucun cours disponible pour les filtres sélectionnés.': 'لا توجد دورات متاحة للفلاتر المحددة.',
            'Formation Professionnelle': 'التكوين المهني',
            'Beauté': 'الجمال',
            'Mode': 'الموضة',
            'Technologie': 'التكنولوجيا',
            'Alimentation': 'الطعام',
            'Voyage': 'السفر',
            'Santé': 'الصحة',
            'Sports': 'الرياضة',
            'Divertissement': 'الترفيه',
            'Maison': 'المنزل',
            'Éducation': 'التعليم',
            'Art': 'الفن',
            'Musique': 'الموسيقى',
            'Livres & Littérature': 'الكتب والأدب',
            'Automobiles': 'السيارات',
            'Finance': 'المالية',
            'Animaux de Compagnie': 'الحيوانات الأليفة',
            'Plein Air': 'الهواء الطلق',
            'Fitness': 'اللياقة البدنية',
            'Informatique': 'علوم الكمبيوتر',
            'Français': 'الفرنسية',
            'Arabe': 'العربية',
            'Anglais': 'الإنجليزية'
        },
        'en': {
            'Afficher les filtres': 'Show filters',
            'Catégories': 'Categories',
            'La Langue': 'Language',
            'Résultats': 'Results',
            'Acheter': 'Buy',
            'En attente': 'Pending',
            'Regarder': 'Watch',
            'Aucun cours disponible pour les filtres sélectionnés.': 'No courses available for the selected filters.',
            'Formation Professionnelle': 'Professional Training',
            'Beauté': 'Beauty',
            'Mode': 'Fashion',
            'Technologie': 'Technology',
            'Alimentation': 'Food',
            'Voyage': 'Travel',
            'Santé': 'Health',
            'Sports': 'Sports',
            'Divertissement': 'Entertainment',
            'Maison': 'Home',
            'Éducation': 'Education',
            'Art': 'Art',
            'Musique': 'Music',
            'Livres & Littérature': 'Books & Literature',
            'Automobiles': 'Automobiles',
            'Finance': 'Finance',
            'Animaux de Compagnie': 'Pets',
            'Plein Air': 'Outdoors',
            'Fitness': 'Fitness',
            'Informatique': 'Computer Science',
            'Français': 'French',
            'Arabe': 'Arabic',
            'Anglais': 'English'
        }
    };

    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    function translatePage(lang) {
        document.querySelectorAll('[data-translate]').forEach(element => {
            const key = element.textContent.trim();
            if (translations[lang] && translations[lang][key]) {
                element.textContent = translations[lang][key];
            }
        });

        document.getElementById('showFilterBtn').textContent = translations[lang]['Afficher les filtres'];
        document.querySelector('.filter1 h3:first-child').textContent = translations[lang]['Catégories'];
        document.querySelector('.filter1 h3:last-child').textContent = translations[lang]['La Langue'];
        document.querySelector('.search-result h1').innerHTML = `<span id="resultCount">0</span>&nbsp;${translations[lang]['Résultats']}`;

        document.querySelectorAll('.filter1 label').forEach(label => {
            const key = label.textContent.trim();
            if (translations[lang][key]) {
                label.textContent = translations[lang][key];
            }
        });

        document.title = translations[lang]['Formation Professionnelle'];

        document.body.style.direction = lang === 'ar' ? 'rtl' : 'ltr';

        setCookie('language', lang, 30);

        filterCourses();
    }

    function filterCourses() {
        const checkedCategories = getCheckedValues('.filter1 input[name="category[]"]:checked');
        const checkedLanguages = getCheckedValues('.filter1 input[name="language[]"]:checked');

        fetch('filter_courses.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                category: checkedCategories,
                language: checkedLanguages
            })
        })
            .then(response => response.text())
            .then(data => {
                document.querySelector('.courses').innerHTML = data;
                updateResultCount();
                translateDynamicContent();
            })
            .catch(error => console.error('Error:', error));
    }

    function translateDynamicContent() {
        const lang = getCookie('language') || 'fr';
        document.querySelectorAll('.watch-button').forEach(button => {
            const key = button.textContent.trim();
            if (translations[lang] && translations[lang][key]) {
                button.textContent = translations[lang][key];
            }
        });
    }

    function getCheckedValues(selector) {
        return Array.from(document.querySelectorAll(selector)).map(checkbox => checkbox.value);
    }

    function updateResultCount() {
        const courseCount = document.querySelectorAll('.courses .course').length;
        document.getElementById('resultCount').textContent = courseCount;
    }

    function initPage() {
        let lang = getCookie('language');
        if (!lang) {
            lang = 'fr';
            setCookie('language', lang, 30);
        }
        translatePage(lang);
    }

    document.getElementById('fr-link').addEventListener('click', (e) => {
        e.preventDefault();
        setCookie('language', 'fr', 30);
        window.location.reload();
    });

    document.getElementById('ar-link').addEventListener('click', (e) => {
        e.preventDefault();
        setCookie('language', 'ar', 30);
        window.location.reload();
    });

    document.getElementById('en-link').addEventListener('click', (e) => {
        e.preventDefault();
        setCookie('language', 'en', 30);
        window.location.reload();
    });

    initPage();

    window.translateDynamicContent = translateDynamicContent;
});
