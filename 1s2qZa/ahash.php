<?php
function generateSalt($length = 32) {
    return bin2hex(random_bytes($length));
}

function generatePepper($length = 16) {
    return bin2hex(random_bytes($length));
}

function hashPassword($password, $pepper) {
    $salt = generateSalt();
    $options = [
        'memory_cost' => 1 << 17,
        'time_cost' => 4,
        'threads' => 4,
    ];
    $hashedPassword = password_hash($password . $salt . $pepper, PASSWORD_ARGON2ID, $options);
    return ['hash' => $hashedPassword, 'salt' => $salt];
}

function verifyPassword($password, $hashedPassword, $salt, $pepper) {
    return password_verify($password . $salt . $pepper, $hashedPassword);
}
?>
