<?php
require __DIR__ . '/../../includes/auth.php';
require __DIR__ . '/../../data/data.php';
if (isAdminLoggedIn()) {
    header('Location: /admin/dashboard.php');
    exit;
}
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = isset($_POST['username']) ? trim($_POST['username']) : '';
    $p = isset($_POST['password']) ? trim($_POST['password']) : '';
    if (loginAdmin($u, $p)) {
        header('Location: /admin/dashboard.php');
        exit;
    } else {
        $err = 'Login gagal. Periksa username atau password.';
    }
}
require __DIR__ . '/../../includes/header.php';
?>
<main class="container">
<section>
<h2>Login Admin</h2>
<?php if ($err): ?>
<div class="card" style="border-left:4px solid #ef4444;"><?php echo htmlspecialchars($err); ?></div>
<?php endif; ?>
<form method="post" class="form" style="max-width:420px;margin:0 auto;">
<div class="form-row"><label>Username</label><input name="username" required></div>
<div class="form-row"><label>Password</label><input name="password" type="password" required></div>
<button class="btn" type="submit">Masuk</button>
</form>
</section>
</main>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
