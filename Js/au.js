document.addEventListener('DOMContentLoaded', function() {
    const translations = {
        'fr': {
            'À propos d\'Hope to Help Dz': 'À propos d\'Hope to Help Dz',
            'Bienvenue chez Hope to Help Dz, où chaque personne mérite la chance de réaliser ses rêves. Fondée avec une vision de soutien et de progrès, nous sommes bien plus qu\'une simple plateforme. Nous sommes votre partenaire dans la concrétisation de vos aspirations, que ce soit en trouvant un emploi dans un domaine de votre choix, en lançant votre propre entreprise ou en investissant dans des projets prometteurs.': 'Bienvenue chez Hope to Help Dz, où chaque personne mérite la chance de réaliser ses rêves. Fondée avec une vision de soutien et de progrès, nous sommes bien plus qu\'une simple plateforme. Nous sommes votre partenaire dans la concrétisation de vos aspirations, que ce soit en trouvant un emploi dans un domaine de votre choix, en lançant votre propre entreprise ou en investissant dans des projets prometteurs.',
            'Notre Histoire': 'Notre Histoire',
            'L\'histoire de Hope to Help Dz commence avec la passion de rendre les opportunités accessibles à tous. Depuis notre création, nous avons travaillé sans relâche pour développer des programmes de formation certifiés dans de nombreux domaines, permettant aux participants de trouver un emploi enrichissant ou de démarrer leur propre entreprise avec confiance.': 'L\'histoire de Hope to Help Dz commence avec la passion de rendre les opportunités accessibles à tous. Depuis notre création, nous avons travaillé sans relâche pour développer des programmes de formation certifiés dans de nombreux domaines, permettant aux participants de trouver un emploi enrichissant ou de démarrer leur propre entreprise avec confiance.',
            'Nos Services': 'Nos Services',
            'Formations et Certifications:': 'Formations et Certifications:',
            'Cours de haute qualité dans divers domaines avec des certifications reconnues.': 'Cours de haute qualité dans divers domaines avec des certifications reconnues.',
            'Financement de Projets:': 'Financement de Projets:',
            'Aide au financement pour concrétiser vos projets entrepreneuriaux.': 'Aide au financement pour concrétiser vos projets entrepreneuriaux.',
            'Investissement:': 'Investissement:',
            'Opportunités d\'investissement dans des projets innovants et rentables.': 'Opportunités d\'investissement dans des projets innovants et rentables.',
            'Nos Valeurs et Mission': 'Nos Valeurs et Mission',
            'Guidés par l\'intégrité, l\'empathie et le désir sincère d\'apporter un changement positif dans la vie des gens. Notre mission est de fournir les outils, le soutien financier et les opportunités nécessaires pour que chacun puisse réaliser son potentiel et contribuer au développement de la société.': 'Guidés par l\'intégrité, l\'empathie et le désir sincère d\'apporter un changement positif dans la vie des gens. Notre mission est de fournir les outils, le soutien financier et les opportunités nécessaires pour que chacun puisse réaliser son potentiel et contribuer au développement de la société.',
            'Pourquoi Choisir Hope to Help Dz?': 'Pourquoi Choisir Hope to Help Dz?',
            'Formations de qualité avec certifications reconnues.': 'Formations de qualité avec certifications reconnues.',
            'Accès au financement pour démarrer des projets.': 'Accès au financement pour démarrer des projets.',
            'Opportunités d\'investissement dans des projets innovants.': 'Opportunités d\'investissement dans des projets innovants.',
            'Engagement envers le succès et le bien-être de nos utilisateurs.': 'Engagement envers le succès et le bien-être de nos utilisateurs.',
            'Contactez-nous': 'Contactez-nous'
        },
        'ar': {
            'À propos d\'Hope to Help Dz': 'عن Hope to Help Dz',
            'Bienvenue chez Hope to Help Dz, où chaque personne mérite la chance de réaliser ses rêves. Fondée avec une vision de soutien et de progrès, nous sommes bien plus qu\'une simple plateforme. Nous sommes votre partenaire dans la concrétisation de vos aspirations, que ce soit en trouvant un emploi dans un domaine de votre choix, en lançant votre propre entreprise ou en investissant dans des projets prometteurs.': 'مرحبًا بكم في Hope to Help Dz، حيث يستحق كل شخص فرصة لتحقيق أحلامه. تأسست برؤية للدعم والتقدم، نحن أكثر من مجرد منصة. نحن شريكك في تحقيق طموحاتك، سواء كان ذلك من خلال إيجاد وظيفة في المجال الذي تختاره، أو بدء عملك الخاص، أو الاستثمار في مشاريع واعدة.',
            'Notre Histoire': 'قصتنا',
            'L\'histoire de Hope to Help Dz commence avec la passion de rendre les opportunités accessibles à tous. Depuis notre création, nous avons travaillé sans relâche pour développer des programmes de formation certifiés dans de nombreux domaines, permettant aux participants de trouver un emploi enrichissant ou de démarrer leur propre entreprise avec confiance.': 'تبدأ قصة Hope to Help Dz بشغف جعل الفرص متاحة للجميع. منذ إنشائنا، عملنا بلا كلل لتطوير برامج تدريبية معتمدة في العديد من المجالات، مما يتيح للمشاركين العثور على وظيفة مجزية أو بدء أعمالهم الخاصة بثقة.',
            'Nos Services': 'خدماتنا',
            'Formations et Certifications:': 'التدريب والشهادات:',
            'Cours de haute qualité dans divers domaines avec des certifications reconnues.': 'دورات عالية الجودة في مجالات متنوعة مع شهادات معترف بها.',
            'Financement de Projets:': 'تمويل المشاريع:',
            'Aide au financement pour concrétiser vos projets entrepreneuriaux.': 'المساعدة في تمويل مشاريعك الريادية.',
            'Investissement:': 'الاستثمار:',
            'Opportunités d\'investissement dans des projets innovants et rentables.': 'فرص الاستثمار في مشاريع مبتكرة ومربحة.',
            'Nos Valeurs et Mission': 'قيمنا ومهمتنا',
            'Guidés par l\'intégrité, l\'empathie et le désir sincère d\'apporter un changement positif dans la vie des gens. Notre mission est de fournir les outils, le soutien financier et les opportunités nécessaires pour que chacun puisse réaliser son potentiel et contribuer au développement de la société.': 'نسترشد بالنزاهة والتعاطف والرغبة الصادقة في إحداث تغيير إيجابي في حياة الناس. مهمتنا هي توفير الأدوات والدعم المالي والفرص اللازمة لكل فرد لتحقيق إمكاناته والمساهمة في تنمية المجتمع.',
            'Pourquoi Choisir Hope to Help Dz?': 'لماذا تختار Hope to Help Dz؟',
            'Formations de qualité avec certifications reconnues.': 'تدريبات عالية الجودة مع شهادات معترف بها.',
            'Accès au financement pour démarrer des projets.': 'الوصول إلى التمويل لبدء المشاريع.',
            'Opportunités d\'investissement dans des projets innovants.': 'فرص الاستثمار في مشاريع مبتكرة.',
            'Engagement envers le succès et le bien-être de nos utilisateurs.': 'الالتزام بنجاح ورفاهية مستخدمينا.',
            'Contactez-nous': 'اتصل بنا'
        },
        'en': {
            'À propos d\'Hope to Help Dz': 'About Hope to Help Dz',
            'Bienvenue chez Hope to Help Dz, où chaque personne mérite la chance de réaliser ses rêves. Fondée avec une vision de soutien et de progrès, nous sommes bien plus qu\'une simple plateforme. Nous sommes votre partenaire dans la concrétisation de vos aspirations, que ce soit en trouvant un emploi dans un domaine de votre choix, en lançant votre propre entreprise ou en investissant dans des projets prometteurs.': 'Welcome to Hope to Help Dz, where every person deserves the chance to realize their dreams. Founded with a vision of support and progress, we are much more than just a platform. We are your partner in bringing your aspirations to life, whether it\'s finding a job in your chosen field, starting your own business, or investing in promising projects.',
            'Notre Histoire': 'Our Story',
            'L\'histoire de Hope to Help Dz commence avec la passion de rendre les opportunités accessibles à tous. Depuis notre création, nous avons travaillé sans relâche pour développer des programmes de formation certifiés dans de nombreux domaines, permettant aux participants de trouver un emploi enrichissant ou de démarrer leur propre entreprise avec confiance.': 'The story of Hope to Help Dz begins with the passion to make opportunities accessible to everyone. Since our inception, we have worked tirelessly to develop certified training programs in many fields, enabling participants to find rewarding employment or start their own business with confidence.',
            'Nos Services': 'Our Services',
            'Formations et Certifications:': 'Training and Certifications:',
            'Cours de haute qualité dans divers domaines avec des certifications reconnues.': 'High-quality courses in various fields with recognized certifications.',
            'Financement de Projets:': 'Project Financing:',
            'Aide au financement pour concrétiser vos projets entrepreneuriaux.': 'Financial assistance to bring your entrepreneurial projects to life.',
            'Investissement:': 'Investment:',
            'Opportunités d\'investissement dans des projets innovants et rentables.': 'Investment opportunities in innovative and profitable projects.',
            'Nos Valeurs et Mission': 'Our Values and Mission',
            'Guidés par l\'intégrité, l\'empathie et le désir sincère d\'apporter un changement positif dans la vie des gens. Notre mission est de fournir les outils, le soutien financier et les opportunités nécessaires pour que chacun puisse réaliser son potentiel et contribuer au développement de la société.': 'Guided by integrity, empathy, and a genuine desire to bring positive change to people\'s lives. Our mission is to provide the tools, financial support, and opportunities necessary for everyone to realize their potential and contribute to the development of society.',
            'Pourquoi Choisir Hope to Help Dz?': 'Why Choose Hope to Help Dz?',
            'Formations de qualité avec certifications reconnues.': 'Quality training with recognized certifications.',
            'Accès au financement pour démarrer des projets.': 'Access to funding to start projects.',
            'Opportunités d\'investissement dans des projets innovants.': 'Investment opportunities in innovative projects.',
            'Engagement envers le succès et le bien-être de nos utilisateurs.': 'Commitment to the success and well-being of our users.',
            'Contactez-nous': 'Contact Us'
        }
    };

    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
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

    function translatePage(lang) {
        document.querySelectorAll('[data-translate]').forEach(element => {
            const key = element.textContent.trim();
            if (translations[lang] && translations[lang][key]) {
                element.textContent = translations[lang][key];
            }
        });

        const ctaButton = document.querySelector('.cta-button span');
        if (ctaButton) {
            ctaButton.textContent = translations[lang]['Contactez-nous'];
        }

        document.body.style.direction = lang === 'ar' ? 'rtl' : 'ltr';

        setCookie('language', lang, 30);
    }

    function initPage() {
        let lang = getCookie('language');
        if (!lang) {
            lang = 'fr';
        }
        translatePage(lang);
    }

    function reloadPage() {
        window.location.reload();
    }

    document.getElementById('fr-link').addEventListener('click', (e) => {
        e.preventDefault();
        translatePage('fr');
        setTimeout(reloadPage, 100);
    });

    document.getElementById('ar-link').addEventListener('click', (e) => {
        e.preventDefault();
        translatePage('ar');
        setTimeout(reloadPage, 100);
    });

    document.getElementById('en-link').addEventListener('click', (e) => {
        e.preventDefault();
        translatePage('en');
        setTimeout(reloadPage, 100);
    });

    initPage();
});