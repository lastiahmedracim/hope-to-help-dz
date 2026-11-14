<?php
require_once('../functions/db.php');

session_start();
$user_r_pseudo = $_SESSION['user_r_pseudo'];

function translateMessage($message) {
    $translations = [
        'fr' => [
            'Veuillez remplir tous les champs.' => 'Veuillez remplir tous les champs.',
            'Le numéro de téléphone doit comporter 10 chiffres.' => 'Le numéro de téléphone doit contenir 10 chiffres.',
            'Le nom, le prénom, la wilaya, la ville et le district ne doivent contenir que des lettres.' => 'Le prénom, le nom, la wilaya, la ville et le district doivent contenir uniquement des lettres.',
            'Le format de l\'adresse est invalide.' => 'Le format de l\'adresse est invalide.',
            'Utilisateur non trouvé.' => 'Utilisateur non trouvé.',
            'Votre mise à jour précédente est toujours en attente d\'approbation.' => 'Votre mise à jour précédente est toujours en attente d\'approbation.',
            'succès' => 'Vos informations ont été soumises pour validation. Elles seront mises à jour après approbation.',
            'Erreur lors de la soumission des données utilisateur pour validation :' => 'Une erreur s\'est produite lors de la mise à jour.',
            'Méthode non autorisée.' => 'Méthode non autorisée.'
        ],
        'ar' => [
            'Veuillez remplir tous les champs.' => 'يرجى ملء جميع الحقول.',
            'Le numéro de téléphone doit comporter 10 chiffres.' => 'يجب أن يحتوي رقم الهاتف على 10 أرقام.',
            'Le nom, le prénom, la wilaya, la ville et le district ne doivent contenir que des lettres.' => 'يجب أن يحتوي الاسم الأول واسم العائلة والولاية والمدينة والمنطقة على أحرف فقط.',
            'Le format de l\'adresse est invalide.' => 'تنسيق العنوان غير صالح.',
            'Utilisateur non trouvé.' => 'لم يتم العثور على المستخدم.',
            'Votre mise à jour précédente est toujours en attente d\'approbation.' => 'تحديثك السابق لا يزال في انتظار الموافقة.',
            'succès' => 'تم إرسال معلوماتك للتحقق. سيتم تحديثها بعد الموافقة.',
            'Erreur lors de la soumission des données utilisateur pour validation :' => 'حدث خطأ أثناء التحديث.',
            'Méthode non autorisée.' => 'طريقة غير مصرح بها.'
        ],
        'en' => [
            'Veuillez remplir tous les champs.' => 'Please fill in all fields.',
            'Le numéro de téléphone doit comporter 10 chiffres.' => 'The phone number must contain 10 digits.',
            'Le nom, le prénom, la wilaya, la ville et le district ne doivent contenir que des lettres.' => 'First name, last name, wilaya, city, and district must contain only letters.',
            'Le format de l\'adresse est invalide.' => 'The address format is invalid.',
            'Utilisateur non trouvé.' => 'User not found.',
            'Votre mise à jour précédente est toujours en attente d\'approbation.' => 'Your previous update is still pending approval.',
            'succès' => 'Your information has been submitted for validation. It will be updated after approval.',
            'Erreur lors de la soumission des données utilisateur pour validation :' => 'An error occurred during the update.',
            'Méthode non autorisée.' => 'Method not allowed.'
        ]
    ];

    $lang = $_COOKIE['language'] ?? 'fr';
    return $translations[$lang][$message] ?? $message;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['user_name'] ?? '';
    $user_last_name = $_POST['user_last_name'] ?? '';
    $wilaya = $_POST['wilaya'] ?? '';
    $city = $_POST['city'] ?? '';
    $district = $_POST['district'] ?? '';
    $user_address = $_POST['user_address'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';

    if (empty($user_name) || empty($user_last_name) || empty($wilaya) || empty($city) || empty($district) || empty($user_address) || empty($phone_number)) {
        exit(translateMessage('Veuillez remplir tous les champs.'));
    }

    if (!preg_match('/^\d{10}$/', $phone_number)) {
        exit(translateMessage('Le numéro de téléphone doit comporter 10 chiffres.'));
    }

    if (!preg_match('/^[A-Za-z]+$/', $user_name) || 
        !preg_match('/^[A-Za-z]+$/', $user_last_name) || 
        !preg_match('/^[A-Za-z]+$/', $wilaya) || 
        !preg_match('/^[A-Za-z]+$/', $city) ||
        !preg_match('/^[A-Za-z]+$/', $district)) {
        exit(translateMessage('Le nom, le prénom, la wilaya, la ville et le district ne doivent contenir que des lettres.'));
    }

    if (!preg_match('/^[A-Za-z0-9\s\.,#-]+$/', $user_address)) {
        exit(translateMessage('Le format de l\'adresse est invalide.'));
    }

    $pdo = $GLOBALS['pdo'];

    $stmt = $pdo->prepare("SELECT user_id FROM Users WHERE user_r_pseudo = :user_r_pseudo");
    $stmt->execute([':user_r_pseudo' => $user_r_pseudo]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404);
        exit(translateMessage('Utilisateur non trouvé.'));
    }

    $user_id = $user['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM ValidatedInfo WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $user_id]);
    $existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingRecord) {
        if ($existingRecord['v_i_s'] == 1) {
            $sql = "UPDATE ValidatedInfo SET 
                    user_name = :user_name, 
                    user_last_name = :user_last_name, 
                    wilaya = :wilaya, 
                    city = :city, 
                    district = :district, 
                    user_address = :user_address, 
                    phone_number = :phone_number,
                    v_i_s = 0
                    WHERE user_id = :user_id";
        } else {
            exit(translateMessage('Votre mise à jour précédente est toujours en attente d\'approbation.'));
        }
    } else {
        $sql = "INSERT INTO ValidatedInfo (user_id, v_i_s, user_name, user_last_name, wilaya, city, district, user_address, phone_number) 
                VALUES (:user_id, 0, :user_name, :user_last_name, :wilaya, :city, :district, :user_address, :phone_number)";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_name', $user_name, PDO::PARAM_STR);
    $stmt->bindParam(':user_last_name', $user_last_name, PDO::PARAM_STR);
    $stmt->bindParam(':wilaya', $wilaya, PDO::PARAM_STR);
    $stmt->bindParam(':city', $city, PDO::PARAM_STR);
    $stmt->bindParam(':district', $district, PDO::PARAM_STR);
    $stmt->bindParam(':user_address', $user_address, PDO::PARAM_STR);
    $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);

    if ($stmt->execute()) {
        exit(translateMessage('succès'));
    } else {
        $errorInfo = $stmt->errorInfo();
        exit(translateMessage('Erreur lors de la soumission des données utilisateur pour validation :') . ' ' . $errorInfo[2]);
    }
} else {
    exit(translateMessage('Méthode non autorisée.'));
}
?>