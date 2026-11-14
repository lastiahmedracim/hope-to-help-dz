document.addEventListener('DOMContentLoaded', function() {
    const translations = {
        'fr': {
            'Cours et Certifications': 'Cours et Certifications',
            'Découvrez les cours professionnels accrédités Élevez votre expertise avec une certification reconnue!': 'Découvrez les cours professionnels accrédités Élevez votre expertise avec une certification reconnue!',
            'Commencez à apprendre maintenant': 'Commencez à apprendre maintenant',
            'Aide aux Projets': 'Aide aux Projets',
            'Soumettez vos idées innovantes. Recevez un soutien pour vos projets visionnaires.': 'Soumettez vos idées innovantes. Recevez un soutien pour vos projets visionnaires.',
            'Lancez votre projet maintenant': 'Lancez votre projet maintenant',
            'Bientôt disponible': 'Bientôt disponible',
            'Opportunités d\'Investissement': 'Opportunités d\'Investissement',
            'Améliorez votre portefeuille d\'investissement Explorez les opportunités dans les projets en croissance': 'Améliorez votre portefeuille d\'investissement Explorez les opportunités dans les projets en croissance',
            'PDG': 'PDG',
            'Explorez nos formations spécialisées : développez vos talents': 'Explorez nos formations spécialisées : développez vos talents',
            'Éducation': 'Éducation',
            'Alimentation': 'Alimentation',
            'Informatique': 'Informatique',
            'Maison': 'Maison',
            'Beauté': 'Beauté',
            'Mode': 'Mode',
            'Qu\'attendez-vous pour obtenir votre certification et commencer à travailler ?': 'Qu\'attendez-vous pour obtenir votre certification et commencer à travailler ?',
            'Obtenez votre certification maintenant': 'Obtenez votre certification maintenant'
        },
        'ar': {
            'Cours et Certifications': 'الدورات والشهادات',
            'Découvrez les cours professionnels accrédités Élevez votre expertise avec une certification reconnue!': 'اكتشف الدورات المهنية المعتمدة وارفع خبرتك بشهادة معترف بها!',
            'Commencez à apprendre maintenant': 'ابدأ التعلم الآن',
            'Aide aux Projets': 'دعم المشاريع',
            'Soumettez vos idées innovantes. Recevez un soutien pour vos projets visionnaires.': 'قدم أفكارك المبتكرة. احصل على الدعم لمشاريعك الرؤيوية.',
            'Lancez votre projet maintenant': 'أطلق مشروعك الآن',
            'Bientôt disponible': 'قريباً',
            'Opportunités d\'Investissement': 'فرص الاستثمار',
            'Améliorez votre portefeuille d\'investissement Explorez les opportunités dans les projets en croissance': 'حسن محفظتك الاستثمارية واستكشف الفرص في المشاريع النامية',
            'PDG': 'الرئيس التنفيذي',
            'Explorez nos formations spécialisées : développez vos talents': 'استكشف تدريباتنا المتخصصة: طور مواهبك',
            'Éducation': 'التعليم',
            'Alimentation': 'الغذاء',
            'Informatique': 'المعلوماتية',
            'Maison': 'المنزل',
            'Beauté': 'الجمال',
            'Mode': 'الموضة',
            'Qu\'attendez-vous pour obtenir votre certification et commencer à travailler ?': 'ماذا تنتظر للحصول على شهادتك وبدء العمل؟',
            'Obtenez votre certification maintenant': 'احصل على شهادتك الآن',
            "Notre entreprise se consacre à l'autonomisation des individus en proposant une gamme diversifiée de cours menant à de précieuses certifications. Grâce à nos programmes de soutien financier, nous offrons aux individus la possibilité d'accéder à du financement pour leurs projets, les aidant ainsi à transformer leurs idées en réalité. De plus, nous offrir des opportunités d'investissement à ceux qui cherchent à accroître leur patrimoine. Notre mission est de soutenir l'apprentissage, l'innovation et la croissance financière, en créant à terme une plateforme où les individus peuvent atteindre leurs objectifs et réussir.": "تكرس شركتنا جهودها لتمكين الأفراد من خلال تقديم مجموعة متنوعة من الدورات التي تمنح شهادات معترف بها. من خلال برامج الدعم المالي لدينا، نوفر للأفراد فرصة الحصول على تمويل لمشاريعهم، مما يساعدهم على تحويل أفكارهم إلى واقع. بالإضافة إلى ذلك، نقدم فرص استثمارية لأولئك الذين يتطلعون إلى تنمية ثرواتهم. مهمتنا هي دعم التعلم والابتكار والنمو المالي، مما يؤدي في النهاية إلى إنشاء منصة يمكن للأفراد من خلالها تحقيق أهدافهم والنجاح.",

            "Cours liés à l'éducation et à l'enseignement, avec des spécialisations telles que le guidage Umrah et touristique, la garde d'enfants, et la formation de secrétariat.": "دورات متعلقة بالتعليم والتدريس، مع تخصصات مثل الإرشاد في العمرة والسياحة، ورعاية الأطفال، والتدريب السكرتاري.",
            "Certification : Guide Umrah et Touristique, Nanny Qualifiée, ou Secrétaire Professionnel, ou équivalent.": "الشهادة: مرشد عمرة وسياحة، مربية أطفال مؤهلة، أو سكرتير محترف، أو ما يعادلها.",
            "Qualification professionnelle : Opportunités dans le secteur du tourisme religieux, la garde d'enfants, l'assistance administrative, ainsi que dans l'éducation et la formation.": "المؤهلات المهنية: فرص في قطاع السياحة الدينية، ورعاية الأطفال، والمساعدة الإدارية، وكذلك في التعليم والتدريب.",

            "Cours variés axés sur l'alimentation et la nutrition, comprenant des modules spécialisés tels que la boulangerie, la boucherie, la fabrication de pizzas, la production de fromage et d'autres compétences culinaires.": "دورات متنوعة تركز على الغذاء والتغذية، بما في ذلك وحدات متخصصة مثل الخبز، والجزارة، وصناعة البيتزا، وإنتاج الجبن، ومهارات الطهي الأخرى.",
            "Certification : Spécialiste en Alimentation et Nutrition avec spécialisation en boulangerie, boucherie, pizza, fromage, ou équivalent.": "الشهادة: متخصص في الغذاء والتغذية مع تخصص في الخبز، الجزارة، البيتزا، الجبن، أو ما يعادلها.",
            "Qualification professionnelle : Opportunités dans la gestion de boulangeries, boucheries, pizzerias, fromageries, et autre connexes de l'industrie alimentaire.": "المؤهلات المهنية: فرص في إدارة المخابز، محلات الجزارة، مطاعم البيتزا، محلات الجبن، وغيرها من الأعمال المتعلقة بصناعة الأغذية.",

            "Cours liés à l'informatique et aux technologies de l'information.": "دورات متعلقة بعلوم الكمبيوتر وتكنولوجيا المعلومات.",
            "Certification : Développeur Web Front-end, Back-end ou Full Stack, ou équivalent.": "الشهادة: مطور ويب واجهة أمامية، خلفية، أو كامل، أو ما يعادلها.",
            "Qualification professionnelle : Opportunités dans le développement Web, la conception de l'interface utilisateur, la programmation côté serveur, la gestion de bases de données et d'autres domaines liés à la création d'applications et de sites Web.": "المؤهلات المهنية: فرص في تطوير الويب، تصميم واجهة المستخدم، برمجة الخادم، إدارة قواعد البيانات، ومجالات أخرى متعلقة بإنشاء التطبيقات والمواقع الإلكترونية.",

            "Cours liés à la décoration intérieure et au design d'intérieur.": "دورات متعلقة بالديكور الداخلي وتصميم الداخلي.",
            "Certification : Designer d'Intérieur ou équivalent.": "الشهادة: مصمم داخلي أو ما يعادلها.",
            "Qualification professionnelle : Carrières en design intérieur, mise en scène de maison, design de meubles, décoration intérieure et des domaines connexes.": "المؤهلات المهنية: وظائف في التصميم الداخلي، تنسيق المنازل، تصميم الأثاث، الديكور الداخلي والمجالات ذات الصلة.",

            "Cours liés à la beauté et aux cosmétiques.": "دورات متعلقة بالجمال ومستحضرات التجميل.",
            "Certification : Expert en Beauté ou équivalent.": "الشهادة: خبير تجميل أو ما يعادلها.",
            "Qualification professionnelle : Opportunités en maquillage, conseil en beauté, soins de la peau, gestion de salon et des domaines connexes.": "المؤهلات المهنية: فرص في المكياج، استشارات التجميل، العناية بالبشرة، إدارة صالونات التجميل والمجالات ذات الصلة.",

            "Cours liés à la mode et au style.": "دورات متعلقة بالموضة والأزياء.",
            "Certification : Styliste de Mode ou équivalent.": "الشهادة: مصمم أزياء أو ما يعادلها.",
            "Qualification professionnelle : Carrières en design de mode, stylisme, gestion de vente au détail, marketing de mode et des domaines connexes.": "المؤهلات المهنية: وظائف في تصميم الأزياء، التنسيق، إدارة البيع بالتجزئة، تسويق الموضة والمجالات ذات الصلة.",
            "Cours et Certifications": "الدورات والشهادات",
            "Découvrez les cours professionnels accrédités Élevez votre expertise avec une certification reconnue!": "اكتشف الدورات المهنية المعتمدة وارفع خبرتك بشهادة معترف بها!",
            "Commencez à apprendre maintenant": "ابدأ التعلم الآن",
            "Financement Innovant": "التمويل المبتكر",
            "Soumettez vos idées innovantes Débloquez des financements pour vos projets visionnaires": "قدم أفكارك المبتكرة واحصل على تمويل لمشاريعك الرؤيوية",
            "Bientôt disponible": "قريباً",
            "Opportunités d'Investissement": "فرص الاستثمار",
            "Améliorez votre portefeuille d'investissement Explorez les opportunités dans les projets en croissance": "حسن محفظتك الاستثمارية واستكشف الفرص في المشاريع النامية"
        },
        'en': {
            'Cours et Certifications': 'Courses and Certifications',
            'Découvrez les cours professionnels accrédités Élevez votre expertise avec une certification reconnue!': 'Discover accredited professional courses Elevate your expertise with a recognized certification!',
            'Commencez à apprendre maintenant': 'Start learning now',
            'Aide aux Projets': 'Project Support',
            'Soumettez vos idées innovantes. Recevez un soutien pour vos projets visionnaires.': 'Submit your innovative ideas. Receive support for your visionary projects.',
            'Lancez votre projet maintenant': 'Launch your project now',
            'Bientôt disponible': 'Coming soon',
            'Opportunités d\'Investissement': 'Investment Opportunities',
            'Améliorez votre portefeuille d\'investissement Explorez les opportunités dans les projets en croissance': 'Improve your investment portfolio Explore opportunities in growing projects',
            'PDG': 'CEO',
            'Explorez nos formations spécialisées : développez vos talents': 'Explore our specialized training: develop your talents',
            'Éducation': 'Education',
            'Alimentation': 'Food',
            'Informatique': 'Computer Science',
            'Maison': 'Home',
            'Beauté': 'Beauty',
            'Mode': 'Fashion',
            'Qu\'attendez-vous pour obtenir votre certification et commencer à travailler ?': 'What are you waiting for to get your certification and start working?',
            'Obtenez votre certification maintenant': 'Get your certification now',
            "Notre entreprise se consacre à l'autonomisation des individus en proposant une gamme diversifiée de cours menant à de précieuses certifications. Grâce à nos programmes de soutien financier, nous offrons aux individus la possibilité d'accéder à du financement pour leurs projets, les aidant ainsi à transformer leurs idées en réalité. De plus, nous offrir des opportunités d'investissement à ceux qui cherchent à accroître leur patrimoine. Notre mission est de soutenir l'apprentissage, l'innovation et la croissance financière, en créant à terme une plateforme où les individus peuvent atteindre leurs objectifs et réussir.": "Our company is dedicated to empowering individuals by offering a diverse range of courses leading to valuable certifications. Through our financial support programs, we provide individuals with the opportunity to access funding for their projects, helping them turn their ideas into reality. Additionally, we offer investment opportunities to those looking to grow their wealth. Our mission is to support learning, innovation, and financial growth, ultimately creating a platform where individuals can achieve their goals and succeed.",

            "Cours liés à l'éducation et à l'enseignement, avec des spécialisations telles que le guidage Umrah et touristique, la garde d'enfants, et la formation de secrétariat.": "Courses related to education and teaching, with specializations such as Umrah and tourist guiding, childcare, and secretarial training.",
            "Certification : Guide Umrah et Touristique, Nanny Qualifiée, ou Secrétaire Professionnel, ou équivalent.": "Certification: Umrah and Tourist Guide, Qualified Nanny, or Professional Secretary, or equivalent.",
            "Qualification professionnelle : Opportunités dans le secteur du tourisme religieux, la garde d'enfants, l'assistance administrative, ainsi que dans l'éducation et la formation.": "Professional Qualification: Opportunities in religious tourism, childcare, administrative assistance, as well as education and training.",
        
            "Cours variés axés sur l'alimentation et la nutrition, comprenant des modules spécialisés tels que la boulangerie, la boucherie, la fabrication de pizzas, la production de fromage et d'autres compétences culinaires.": "Various courses focused on food and nutrition, including specialized modules such as bakery, butchery, pizza making, cheese production, and other culinary skills.",
            "Certification : Spécialiste en Alimentation et Nutrition avec spécialisation en boulangerie, boucherie, pizza, fromage, ou équivalent.": "Certification: Food and Nutrition Specialist with specialization in bakery, butchery, pizza, cheese, or equivalent.",
            "Qualification professionnelle : Opportunités dans la gestion de boulangeries, boucheries, pizzerias, fromageries, et autre connexes de l'industrie alimentaire.": "Professional Qualification: Opportunities in managing bakeries, butcheries, pizzerias, cheese shops, and other related food industry businesses.",
        
            "Cours liés à l'informatique et aux technologies de l'information.": "Courses related to computer science and information technology.",
            "Certification : Développeur Web Front-end, Back-end ou Full Stack, ou équivalent.": "Certification: Front-end, Back-end, or Full Stack Web Developer, or equivalent.",
            "Qualification professionnelle : Opportunités dans le développement Web, la conception de l'interface utilisateur, la programmation côté serveur, la gestion de bases de données et d'autres domaines liés à la création d'applications et de sites Web.": "Professional Qualification: Opportunities in Web development, user interface design, server-side programming, database management, and other areas related to application and website creation.",
        
            "Cours liés à la décoration intérieure et au design d'intérieur.": "Courses related to interior decoration and interior design.",
            "Certification : Designer d'Intérieur ou équivalent.": "Certification: Interior Designer or equivalent.",
            "Qualification professionnelle : Carrières en design intérieur, mise en scène de maison, design de meubles, décoration intérieure et des domaines connexes.": "Professional Qualification: Careers in interior design, home staging, furniture design, interior decoration, and related fields.",
        
            "Cours liés à la beauté et aux cosmétiques.": "Courses related to beauty and cosmetics.",
            "Certification : Expert en Beauté ou équivalent.": "Certification: Beauty Expert or equivalent.",
            "Qualification professionnelle : Opportunités en maquillage, conseil en beauté, soins de la peau, gestion de salon et des domaines connexes.": "Professional Qualification: Opportunities in makeup, beauty consulting, skincare, salon management, and related fields.",
        
            "Cours liés à la mode et au style.": "Courses related to fashion and style.",
            "Certification : Styliste de Mode ou équivalent.": "Certification: Fashion Stylist or equivalent.",
            "Qualification professionnelle : Carrières en design de mode, stylisme, gestion de vente au détail, marketing de mode et des domaines connexes.": "Professional Qualification: Careers in fashion design, styling, retail management, fashion marketing, and related fields.",
            "Cours et Certifications": "Courses and Certifications",
            "Découvrez les cours professionnels accrédités Élevez votre expertise avec une certification reconnue!": "Discover accredited professional courses Elevate your expertise with a recognized certification!",
            "Commencez à apprendre maintenant": "Start learning now",
            "Financement Innovant": "Innovative Financing",
            "Soumettez vos idées innovantes Débloquez des financements pour vos projets visionnaires": "Submit your innovative ideas Unlock funding for your visionary projects",
            "Bientôt disponible": "Coming soon",
            "Opportunités d'Investissement": "Investment Opportunities",
            "Améliorez votre portefeuille d'investissement Explorez les opportunités dans les projets en croissance": "Improve your investment portfolio Explore opportunities in growing projects"
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

        // Translate buttons
        document.querySelectorAll('button').forEach(button => {
            const key = button.textContent.trim();
            if (translations[lang] && translations[lang][key]) {
                button.textContent = translations[lang][key];
            }
        });

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