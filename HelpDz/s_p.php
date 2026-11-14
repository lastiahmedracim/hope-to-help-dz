<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

function jsonErrors($errno, $errstr, $errfile, $errline) {
    $error = array(
        'error' => array(
            'message' => $errstr,
            'type' => $errno,
            'file' => $errfile,
            'line' => $errline
        )
    );
    echo json_encode($error);
    exit;
}

set_error_handler("jsonErrors");

session_start();
require_once('../functions/db.php');

header('Content-Type: application/json');

function getUserLanguagePreference() {
    if (isset($_SESSION['language'])) {
        return $_SESSION['language'];
    }
    if (isset($_COOKIE['language'])) {
        return $_COOKIE['language'];
    }
    return 'fr';
}

function getMessage($messageKey, $lang = 'fr') {
    $messages = [
        'user_not_authenticated' => [
            'fr' => 'Utilisateur non authentifié',
            'ar' => 'المستخدم غير مصادق عليه',
            'en' => 'User not authenticated'
        ],
        'no_permission' => [
            'fr' => 'Vous n\'avez pas l\'autorisation de créer un projet.',
            'ar' => 'ليس لديك إذن لإنشاء مشروع.',
            'en' => 'You do not have permission to create a project.'
        ],
        'free_project_used' => [
            'fr' => 'Vous avez déjà utilisé votre projet gratuit. Veuillez payer pour créer plus de projets.',
            'ar' => 'لقد استخدمت بالفعل مشروعك المجاني. يرجى الدفع لإنشاء المزيد من المشاريع.',
            'en' => 'You have already used your free project. Please pay to create more projects.'
        ],
        'project_submitted' => [
            'fr' => 'Projet soumis avec succès',
            'ar' => 'تم تقديم المشروع بنجاح',
            'en' => 'Project submitted successfully'
        ],
        'error_saving' => [
            'fr' => 'Une erreur s\'est produite lors de l\'enregistrement du projet. Veuillez réessayer plus tard.',
            'ar' => 'حدث خطأ أثناء حفظ المشروع. يرجى المحاولة مرة أخرى لاحقًا.',
            'en' => 'An error occurred while saving the project. Please try again later.'
        ]
    ];

    return $messages[$messageKey][$lang] ?? $messages[$messageKey]['en'];
}

$lang = getUserLanguagePreference();

if (!isset($_SESSION['user_r_pseudo'])) {
    echo json_encode(['success' => false, 'message' => getMessage('user_not_authenticated', $lang)]);
    exit;
}

$user_r_pseudo = $_SESSION['user_r_pseudo'];
$stmt = $pdo->prepare("
    SELECT u.user_id, u.bought, u.is_f, p.is_p 
    FROM Users u 
    LEFT JOIN Paid p ON u.user_id = p.user_id 
    WHERE u.user_r_pseudo = ?
");
$stmt->execute([$user_r_pseudo]);
$user = $stmt->fetch();

if (!$user['is_p'] && !$user['bought']) {
    echo json_encode(['success' => false, 'message' => getMessage('no_permission', $lang)]);
    exit;
}

if (!$user['is_p'] && $user['bought'] && $user['is_f']) {
    echo json_encode(['success' => false, 'message' => getMessage('free_project_used', $lang)]);
    exit;
}

function validateInput($input, $fieldName, $maxLength = null) {
    $errors = [];
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    
    if ($input === '') {
        $errors[] = [
            'fr' => "$fieldName est requis.",
            'ar' => "$fieldName مطلوب.",
            'en' => "$fieldName is required."
        ];
    }
    if ($maxLength && mb_strlen($input, 'UTF-8') > $maxLength) {
        $errors[] = [
            'fr' => "$fieldName ne doit pas dépasser $maxLength caractères.",
            'ar' => "يجب ألا يتجاوز $fieldName $maxLength حرفًا.",
            'en' => "$fieldName must not exceed $maxLength characters."
        ];
    }
    return ['errors' => $errors, 'sanitized' => $input];
}

$fieldOrder = [
    'projectName' => 'Nom du projet',
    'legalForm' => 'Forme juridique',
    'projectIdea' => 'Idée du projet',
    'projectOwner' => 'Propriétaire du projet',
    'ownerSkills' => 'Compétences du propriétaire',
    'partners' => 'Partenaires',
    'projectReason' => 'Raison du projet',
    'projectLocation' => 'Emplacement du projet',
    'uniqueFeatures' => 'Caractéristiques uniques',
    'addedValue' => 'Valeur ajoutée',
    'targetAudience' => 'Public cible',
    'technologyImpact' => 'Impact technologique',
    'marketingStrategy' => 'Stratégie marketing',
    'employeesCount' => 'Nombre d\'employés',
    'rentalCost' => 'Coût de location',
    'equipmentCost' => 'Coût des équipements',
    'equipmentDetails' => 'Détails des équipements',
    'additionalExpenses' => 'Dépenses supplémentaires',
    'ownerNationalId' => 'Numéro d\'identité nationale du propriétaire',
    'displayLocation' => 'Emplacement d\'affichage',
    'partnershipTerms' => 'Termes du partenariat',
    'partnerInterestRate' => 'Taux d\'intérêt du partenaire',
    'totalProjectCost' => 'Coût total du projet',
    'numberOfPartners' => 'Nombre de partenaires',
    'participationAmountPerPerson' => 'Montant de participation par personne'
];

$errors = [];
$sanitizedData = [];

foreach ($fieldOrder as $field => $label) {
    $value = $_POST[$field] ?? '';
    
    $maxLength = null;
    if ($field === 'projectName' || $field === 'addedValue') {
        $maxLength = 140;
    } elseif ($field === 'projectIdea') {
        $maxLength = 210;
    }
    
    $result = validateInput($value, $label, $maxLength);
    if (!empty($result['errors'])) {
        $errors = array_merge($errors, $result['errors']);
    }
    $sanitizedData[$field] = $result['sanitized'];
    
    if ($field === 'employeesCount' && (!is_numeric($sanitizedData[$field]) || intval($sanitizedData[$field]) < 0)) {
        $errors[] = [
            'fr' => 'Le nombre d\'employés doit être un nombre positif.',
            'ar' => 'يجب أن يكون عدد الموظفين رقمًا موجبًا.',
            'en' => 'The number of employees must be a positive number.'
        ];
    }
    if (($field === 'rentalCost' || $field === 'equipmentCost') && (!is_numeric($sanitizedData[$field]) || floatval($sanitizedData[$field]) < 0)) {
        $errors[] = [
            'fr' => "Le $label doit être un nombre positif.",
            'ar' => "يجب أن يكون $label رقمًا موجبًا.",
            'en' => "The $label must be a positive number."
        ];
    }
    if ($field === 'additionalExpenses' && (!is_numeric($sanitizedData[$field]) || floatval($sanitizedData[$field]) < 500000)) {
        $errors[] = [
            'fr' => 'Les dépenses supplémentaires doivent être d\'au moins 500 000 DZD.',
            'ar' => 'يجب أن تكون المصروفات الإضافية 500000 دينار جزائري على الأقل.',
            'en' => 'Additional expenses must be at least 500,000 DZD.'
        ];
    }
    if ($field === 'totalProjectCost' && (!is_numeric($sanitizedData[$field]) || floatval($sanitizedData[$field]) <= 0)) {
        $errors[] = [
            'fr' => 'Le coût total du projet doit être un nombre positif.',
            'ar' => 'يجب أن تكون التكلفة الإجمالية للمشروع رقمًا موجبًا.',
            'en' => 'The total project cost must be a positive number.'
        ];
    }
    if ($field === 'numberOfPartners' && (!is_numeric($sanitizedData[$field]) || intval($sanitizedData[$field]) < 1)) {
        $errors[] = [
            'fr' => 'Le nombre de partenaires doit être au moins 1.',
            'ar' => 'يجب أن يكون عدد الشركاء 1 على الأقل.',
            'en' => 'The number of partners must be at least 1.'
        ];
    }
    if ($field === 'participationAmountPerPerson' && (!is_numeric($sanitizedData[$field]) || floatval($sanitizedData[$field]) <= 0)) {
        $errors[] = [
            'fr' => 'Le montant de participation par personne doit être un nombre positif.',
            'ar' => 'يجب أن يكون مبلغ المشاركة لكل شخص رقمًا موجبًا.',
            'en' => 'The participation amount per person must be a positive number.'
        ];
    }
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => $errors[0][$lang]]);
    exit;
}

try {
    $pdo->beginTransaction();
    
    $stmt = $pdo->prepare("INSERT INTO Projects (project_name, legal_form, project_idea, project_owner, owner_skills, partners, reason_for_project, project_location, unique_features, added_value, target_audience, technology_impact, marketing_strategy, employees_count, rental_cost, equipment_cost, equipment_details, additional_expenses, owner_national_id, display_location, partnership_terms, partner_interest_rate, total_project_cost, number_of_partners, participation_amount_per_person, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $projectData = array_map(function($field) use ($sanitizedData) {
        return $sanitizedData[$field];
    }, array_keys($fieldOrder));
    $projectData[] = $user['user_id'];

    $stmt->execute($projectData);

    if ($user['bought'] && !$user['is_f']) {
        $updateStmt = $pdo->prepare("UPDATE Users SET is_f = TRUE WHERE user_id = ?");
        $updateStmt->execute([$user['user_id']]);
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'message' => getMessage('project_submitted', $lang)]);
} catch (PDOException $e) {
    $pdo->rollBack();
    $errorMessage = "Database error: " . $e->getMessage();
    error_log($errorMessage);
    echo json_encode(['success' => false, 'message' => getMessage('error_saving', $lang), 'debug' => $errorMessage]);
} catch (Exception $e) {
    $errorMessage = "General error: " . $e->getMessage();
    error_log($errorMessage);
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred', 'debug' => $errorMessage]);
}