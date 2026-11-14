<?php
require_once('db.php');

function getUserLanguage() {
    return $_COOKIE['language'] ?? 'fr';
}

$translations = [
    'fr' => [
        'parrain_not_found' => 'Parrain non trouvé',
        'error_inserting_user' => 'Erreur lors de l\'insertion de l\'utilisateur',
        'exception_occurred' => 'Une exception s\'est produite : '
    ],
    'en' => [
        'parrain_not_found' => 'Sponsor not found',
        'error_inserting_user' => 'Error inserting user',
        'exception_occurred' => 'An exception occurred: '
    ],
    'ar' => [
        'parrain_not_found' => 'لم يتم العثور على الراعي',
        'error_inserting_user' => 'خطأ في إدخال المستخدم',
        'exception_occurred' => 'حدث استثناء: '
    ]
];

function translate($key) {
    global $translations;
    $lang = getUserLanguage();
    return $translations[$lang][$key] ?? $translations['en'][$key];
}

class User {
    public $user_name;
    public $user_last_name;
    public $user_email;
    public $phone_number;
    public $user_password_hash;
    public $user_password_salt;
    public $user_password_pepper;
    public $wilaya;
    public $city;
    public $district;
    public $user_address;
    public $user_r_pseudo;
    public $user_l_pseudo;
    public $parrain_pseudo;
    public $rip;

    public function __construct($user_name, $user_last_name, $user_email, $phone_number, $user_password_hash, $user_password_salt, $user_password_pepper, $wilaya, $city, $district, $user_address, $user_r_pseudo, $user_l_pseudo, $parrain_pseudo, $rip) {
        $this->user_name = $user_name;
        $this->user_last_name = $user_last_name;
        $this->user_email = $user_email;
        $this->phone_number = $phone_number;
        $this->user_password_hash = $user_password_hash;
        $this->user_password_salt = $user_password_salt;
        $this->user_password_pepper = $user_password_pepper;
        $this->wilaya = $wilaya;
        $this->city = $city;
        $this->district = $district;
        $this->user_address = $user_address;
        $this->user_r_pseudo = $user_r_pseudo;
        $this->user_l_pseudo = $user_l_pseudo;
        $this->parrain_pseudo = $parrain_pseudo;
        $this->rip = $rip;
    }
}

function insertUserIntoTree($user, $pdo) {
    try {
        $parrain = findUserByPseudo($user->parrain_pseudo, $pdo);

        if (!$parrain) {
            echo json_encode(["success" => false, "message" => translate('parrain_not_found')]);
            return false;
        }

        $child_position = ($user->parrain_pseudo == $parrain['user_r_pseudo']) ? 'right_child_id' : 'left_child_id';
        return insertUserRecursively($user, $pdo, $parrain['user_id'], $child_position);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => translate('exception_occurred') . $e->getMessage()]);
        return false;
    }
}

function findUserByPseudo($pseudo, $pdo) {
    $stmt = $pdo->prepare('SELECT user_id, user_r_pseudo, user_l_pseudo, right_child_id, left_child_id FROM Users WHERE user_r_pseudo = ? OR user_l_pseudo = ?');
    $stmt->execute([$pseudo, $pseudo]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insertUserRecursively($user, $pdo, $parent_id, $child_position) {
    $stmt = $pdo->prepare("SELECT user_id, right_child_id, left_child_id FROM Users WHERE user_id = ?");
    $stmt->execute([$parent_id]);
    $parent = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($parent[$child_position] == null) {
        $stmt = $pdo->prepare('INSERT INTO Users (user_name, user_last_name, user_email, phone_number, user_password, wilaya, city, district, user_address, user_r_pseudo, user_l_pseudo, parrain_pseudo, parent_user_id, salt, pepper, RIP) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $success = $stmt->execute([
            $user->user_name,
            $user->user_last_name,
            $user->user_email,
            $user->phone_number,
            $user->user_password_hash,
            $user->wilaya,
            $user->city,
            $user->district,
            $user->user_address,
            $user->user_r_pseudo,
            $user->user_l_pseudo,
            $user->parrain_pseudo,
            $parent_id,
            $user->user_password_salt,
            $user->user_password_pepper,
            $user->rip
        ]);

        if (!$success) {
            echo json_encode(["success" => false, "message" => translate('error_inserting_user')]);
            return false;
        }

        $new_user_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("UPDATE Users SET {$child_position} = ? WHERE user_id = ?");
        $stmt->execute([$new_user_id, $parent_id]);

        return true;
    } else {
        $next_child_position = $child_position;
        return insertUserRecursively($user, $pdo, $parent[$child_position], $next_child_position);
    }
}
?>