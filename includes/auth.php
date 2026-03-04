<?php
if (session_status() === PHP_SESSION_NONE) {
    $sessPath = __DIR__ . '/../data/sessions';
    if (!is_dir($sessPath)) {
        mkdir($sessPath, 0777, true);
    }
    session_save_path($sessPath);
    $upTmp = __DIR__ . '/../data/tmp';
    if (!is_dir($upTmp)) {
        mkdir($upTmp, 0777, true);
    }
    if (function_exists('ini_set')) {
        ini_set('upload_tmp_dir', $upTmp);
    }
    session_start();
}
$AUTH_USER = 'admin';
$AUTH_PASS = 'admin123';
function isAdminLoggedIn(): bool {
    return !empty($_SESSION['admin_logged_in']);
}
function loginAdmin(string $user, string $pass): bool {
    global $AUTH_USER, $AUTH_PASS;
    if ($user === $AUTH_USER && $pass === $AUTH_PASS) {
        $_SESSION['admin_logged_in'] = true;
        return true;
    }
    return false;
}
function logoutAdmin(): void {
    unset($_SESSION['admin_logged_in']);
}
function requireAdmin(): void {
    if (!isAdminLoggedIn()) {
        header('Location: /admin/login.php');
        exit;
    }
}
