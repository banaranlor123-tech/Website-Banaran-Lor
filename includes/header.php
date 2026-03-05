<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($data['nama']); ?></title>
<?php
$doc = $_SERVER['DOCUMENT_ROOT'] ?? '';
$styleHref = '/style.css';
if ($doc && !is_file($doc . '/style.css') && is_file($doc . '/public/style.css')) {
    $styleHref = '/public/style.css';
}
?>
<link rel="stylesheet" href="<?php echo $styleHref; ?>">
</head>
<?php
$req = $_SERVER['REQUEST_URI'] ?? '';
$path = parse_url($req, PHP_URL_PATH) ?? '';
$self = $_SERVER['PHP_SELF'] ?? '';
$isAdminRoute = (strpos($path, '/admin') === 0) || (strpos($self, '/admin') === 0);
$isLoginPage = (substr($path, -strlen('/admin/login.php')) === '/admin/login.php') || (substr($self, -strlen('/admin/login.php')) === '/admin/login.php');
$pageParam = $_GET['page'] ?? 'home';
$isPublicHome = (!$isAdminRoute) && ($pageParam === 'home');
$isPublicInner = (!$isAdminRoute) && ($pageParam !== 'home');
?>
<body class="<?php echo trim(($isLoginPage ? 'admin-login ' : '') . ($isPublicInner ? 'stick-footer' : '')); ?>">
<header class="site-header<?php echo $isPublicHome ? ' overlay' : ''; ?>">
<div class="container nav">
<div class="brand">
<?php if (!empty($data['logo'])): ?>
<img src="<?php echo htmlspecialchars($data['logo']); ?>" alt="<?php echo htmlspecialchars($data['nama']); ?>">
<?php endif; ?>
<span><?php echo htmlspecialchars($data['nama']); ?></span>
</div>
<nav>
<?php if ($isAdminRoute): ?>
    <?php if ($isLoginPage): ?>
        <?php if (!empty($_SESSION['admin_logged_in'])): ?>
        <a href="/admin/dashboard.php">Dashboard</a>
        <?php else: ?>
        <a href="/?page=home">Beranda</a>
        <?php endif; ?>
        <a href="/?page=profil">Profil</a>
        <a href="/?page=wilayah">Wilayah</a>
        <a href="/?page=demografi">Demografi</a>
        <a href="/?page=aparatur">Aparatur</a>
        <a href="/?page=layanan">Layanan</a>
        <a href="/?page=galeri">Galeri</a>
        <a href="/?page=berita">Berita</a>
        <a href="/?page=kontak">Kontak</a>
        <?php if (empty($_SESSION['admin_logged_in'])): ?>
        <a href="/admin/login.php" class="login-icon" title="Login Admin" aria-label="Login Admin"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M10 3h8a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1h-8a1 1 0 0 1-1-1v-2h2v1h6V5h-6v1H9V4a1 1 0 0 1 1-1z"/><path d="M13 12H4m0 0 3-3m-3 3 3 3" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        <?php endif; ?>
        <?php if (!empty($_SESSION['admin_logged_in'])): ?>
        <a href="/admin/logout.php">Logout</a>
        <?php endif; ?>
    <?php elseif (!empty($_SESSION['admin_logged_in'])): ?>
    <a href="/admin/dashboard.php">Dashboard</a>
    <a href="/admin/logout.php">Logout</a>
    <?php else: ?>
    <a href="/admin/login.php">Login</a>
    <?php endif; ?>
<?php else: ?>
    <?php if (!empty($_SESSION['admin_logged_in'])): ?>
    <a href="/admin/dashboard.php">Dashboard</a>
    <?php else: ?>
    <a href="/?page=home">Beranda</a>
    <?php endif; ?>
    <a href="/?page=profil">Profil</a>
    <a href="/?page=wilayah">Wilayah</a>
    <a href="/?page=demografi">Demografi</a>
    <a href="/?page=aparatur">Aparatur</a>
    <a href="/?page=layanan">Layanan</a>
    <a href="/?page=galeri">Galeri</a>
    <a href="/?page=berita">Berita</a>
    <a href="/?page=kontak">Kontak</a>
    <?php if (empty($_SESSION['admin_logged_in'])): ?>
    <a href="/admin/login.php" class="login-icon" title="Login Admin" aria-label="Login Admin"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M10 3h8a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1h-8a1 1 0 0 1-1-1v-2h2v1h6V5h-6v1H9V4a1 1 0 0 1 1-1z"/><path d="M13 12H4m0 0 3-3m-3 3 3 3" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
    <?php endif; ?>
    <?php if (!empty($_SESSION['admin_logged_in'])): ?>
    <a href="/admin/logout.php">Logout</a>
    <?php endif; ?>
<?php endif; ?>
</nav>
</div>
</header>
