<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require __DIR__ . '/../data/data.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/header.php';
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<main class="container">
<?php
if ($page === 'home') {
    $wilayah = $data['wilayah'] ?? ['rw'=>0,'rt'=>0,'luas'=>'-','dusun'=>0];
    $penduduk = $data['demografi']['jumlah_penduduk'] ?? 0;
    $bg = htmlspecialchars($data['header_bg'] ?? 'https://picsum.photos/id/1043/1200/300');
    echo '<section class="hero hero-banner full-bleed" style="background-image: url(' . $bg . '); background-size: cover; background-position: center;">';
    echo '<div class="title-xl">' . htmlspecialchars(strtoupper($data['nama'] ?? 'Padukuhan')) . '</div>';
    echo '<div class="subtitle">' . htmlspecialchars($data['deskripsi'] ?? '') . '</div>';
    echo '<div class="hero-badges">';
    echo '<a class="hero-badge" href="/?page=profil"><div class="circle"><svg width="36" height="36" viewBox="0 0 24 24" fill="#0ea5e9"><path d="M12 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 22a8 8 0 1 1 16 0"/></svg></div><span>Profil Desa</span></a>';
    echo '<a class="hero-badge" href="/?page=layanan"><div class="circle"><svg width="36" height="36" viewBox="0 0 24 24" fill="#0ea5e9"><path d="M3 6h18v4H3zM3 12h12v4H3zM3 18h18v2H3z"/></svg></div><span>Administrasi</span></a>';
    echo '<a class="hero-badge" href="/?page=galeri"><div class="circle"><svg width="36" height="36" viewBox="0 0 24 24" fill="#0ea5e9"><path d="M4 5h16v14H4z"/><path d="M4 15l4-4 4 4 4-3 4 3v4H4z" fill="#0284c7"/></svg></div><span>Produk Desa</span></a>';
    echo '</div>';
    echo '</section>';
    $kd = $data['kepala_dukuh'] ?? ['nama'=>'','jabatan'=>'','foto'=>'','sambutan'=>''];
    echo '<section class="dukuh"><div class="dukuh-wrap">';
    echo '<img class="dukuh-photo" src="' . htmlspecialchars($kd['foto'] ?? '') . '" alt="' . htmlspecialchars($kd['nama'] ?? '') . '">';
    echo '<div><div class="dukuh-title">' . htmlspecialchars($kd['nama'] ?? '') . ' · ' . htmlspecialchars($kd['jabatan'] ?? '') . '</div><p class="dukuh-text">' . nl2br(htmlspecialchars($kd['sambutan'] ?? '')) . '</p></div>';
    echo '</div></section>';
    $mapSrc = $data['map_embed'] ?? '';
    if ($mapSrc !== '') {
        echo '<section class="map"><h2>Lokasi Banaran Lor</h2><div class="map-frame"><iframe src="' . htmlspecialchars($mapSrc) . '" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div></section>';
    }
    $acara = $data['acara'] ?? [];
    if (!empty($acara)) {
        echo '<section class="events"><h2>Acara</h2>';
        echo '<div class="ev-wrap"><div class="ev-nav"><button class="ev-btn ev-prev" type="button">‹</button><button class="ev-btn ev-next" type="button">›</button></div>';
        echo '<div class="ev-list">';
        foreach ($acara as $ev) {
            $j = $ev['judul'] ?? '';
            $t = $ev['tanggal'] ?? '';
            $l = $ev['lokasi'] ?? '';
            $img = $ev['img'] ?? '';
            $day = '';
            $mon = '';
            $full = $t;
            if ($t !== '') {
                $ts = strtotime($t);
                if ($ts !== false) {
                    $day = date('d', $ts);
                    $mon = strtoupper(date('M', $ts));
                    $full = date('d M Y', $ts);
                }
            }
            echo '<div class="card ev-card">';
            if ($img !== '') {
                echo '<img class="event-img" src="' . htmlspecialchars($img) . '" alt="' . htmlspecialchars($j) . '">';
            }
            echo '<div class="ev-head"><span class="ev-chip">' . htmlspecialchars($full) . '</span></div>';
            echo '<div class="ev-title">' . htmlspecialchars($j) . '</div>';
            echo '<div class="ev-meta">' . htmlspecialchars($l) . '</div>';
            echo '</div>';
        }
        echo '</div></div></section>';
    }
    echo '<section class="stats"><h2>Statistik Desa</h2><div class="grid-4">';
    echo '<div class="card"><div class="icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="#0ea5e9"><circle cx="12" cy="7" r="4"></circle><path d="M4 20c0-4 4-6 8-6s8 2 8 6" /></svg></div><div class="stat">' . htmlspecialchars((string)$penduduk) . '</div><div class="label">Penduduk</div></div>';
    echo '<div class="card"><div class="icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="#0ea5e9"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect></svg></div><div class="stat">' . htmlspecialchars((string)$wilayah['rw']) . '</div><div class="label">RW</div></div>';
    echo '<div class="card"><div class="icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="#0ea5e9"><path d="M3 10l9-7 9 7v9a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2v-4H10v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg></div><div class="stat">' . htmlspecialchars((string)$wilayah['rt']) . '</div><div class="label">RT</div></div>';
    echo '<div class="card"><div class="icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="#0ea5e9"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M5 5l3 3M16 16l3 3M19 5l-3 3M5 19l3-3"></path></svg></div><div class="stat">' . htmlspecialchars((string)$wilayah['luas']) . '</div><div class="label">Luas</div></div>';
    echo '</div></section>';
} elseif ($page === 'profil') {
    $visiText = isset($data['visi']) ? (string)$data['visi'] : '';
    $misiArr = $data['misi'] ?? [];
    $misiText = is_array($misiArr) ? implode(' • ', array_map('strval', $misiArr)) : (string)$misiArr;
    echo '<section class="profile"><h2>Profil Padukuhan</h2>';
    echo '<div class="profile-cards">';
    echo '<div class="pcard pc-visi"><div class="pc-head"><div class="pc-ico"><svg viewBox=\"0 0 24 24\" width=\"16\" height=\"16\" fill=\"#fff\"><path d=\"M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6z\"/></svg></div><div class=\"pc-title\">Visi &amp; Misi</div></div><div class=\"pc-body\"><p><strong>Visi:</strong> ' . htmlspecialchars($visiText) . '</p><p><strong>Misi:</strong> ' . htmlspecialchars($misiText) . '</p></div></div>';
    echo '<div class="pcard pc-sejarah"><div class="pc-head"><div class="pc-ico"><svg viewBox=\"0 0 24 24\" width=\"16\" height=\"16\" fill=\"#0f172a\"><path d=\"M12 8v5l4 2\" stroke=\"#0f172a\" stroke-width=\"2\" fill=\"none\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/><circle cx=\"12\" cy=\"12\" r=\"9\" stroke=\"#0f172a\" stroke-width=\"2\" fill=\"none\"/></svg></div><div class=\"pc-title\">Sejarah</div></div><div class=\"pc-body\">' . nl2br(htmlspecialchars($data['sejarah'] ?? '')) . '</div></div>';
    echo '</div></section>';
} elseif ($page === 'wilayah') {
    $w = $data['wilayah'] ?? [];
    echo '<section><h2>Data Wilayah</h2>';
    echo '<table class="table"><tbody>';
    echo '<tr><th>Dusun</th><td>' . htmlspecialchars((string)($w['dusun'] ?? 0)) . '</td></tr>';
    echo '<tr><th>RW</th><td>' . htmlspecialchars((string)($w['rw'] ?? 0)) . '</td></tr>';
    echo '<tr><th>RT</th><td>' . htmlspecialchars((string)($w['rt'] ?? 0)) . '</td></tr>';
    echo '<tr><th>Luas</th><td>' . htmlspecialchars((string)($w['luas'] ?? '-')) . '</td></tr>';
    echo '<tr><th>Alamat Kantor</th><td>' . htmlspecialchars((string)($w['alamat_kantor'] ?? '-')) . '</td></tr>';
    echo '</tbody></table></section>';
} elseif ($page === 'demografi') {
    $d = $data['demografi'] ?? [];
    $usia = $d['usia'] ?? [];
    echo '<section><h2>Demografi</h2><div class="grid-3">';
    echo '<div class="card"><div class="stat">' . htmlspecialchars((string)($d['jumlah_penduduk'] ?? 0)) . '</div><div class="label">Jumlah Penduduk</div></div>';
    echo '<div class="card"><div class="stat">' . htmlspecialchars((string)($d['laki'] ?? 0)) . '</div><div class="label">Laki-laki</div></div>';
    echo '<div class="card"><div class="stat">' . htmlspecialchars((string)($d['perempuan'] ?? 0)) . '</div><div class="label">Perempuan</div></div>';
    echo '</div>';
    echo '<h3>Distribusi Usia</h3><table class="table"><tbody>';
    foreach ($usia as $k=>$v) {
        echo '<tr><th>' . htmlspecialchars($k) . '</th><td>' . htmlspecialchars((string)$v) . '</td></tr>';
    }
    echo '</tbody></table></section>';
} elseif ($page === 'aparatur') {
    $a = $data['aparatur'] ?? [];
    echo '<section><h2>Aparatur Padukuhan</h2><div class="grid-3">';
    foreach ($a as $ap) {
        echo '<div class="card"><div class="label">' . htmlspecialchars($ap['jabatan'] ?? '') . '</div><div>' . htmlspecialchars($ap['nama'] ?? '') . '</div><div class="muted">' . htmlspecialchars($ap['kontak'] ?? '') . '</div></div>';
    }
    echo '</div></section>';
} elseif ($page === 'layanan') {
    $l = $data['layanan'] ?? [];
    echo '<section><h2>Layanan</h2><div class="grid-3">';
    foreach ($l as $lv) {
        echo '<div class="card"><div class="label">' . htmlspecialchars($lv['nama'] ?? '') . '</div><div>' . htmlspecialchars($lv['deskripsi'] ?? '') . '</div><div class="muted">' . htmlspecialchars($lv['lokasi'] ?? '') . '</div></div>';
    }
    echo '</div></section>';
} elseif ($page === 'galeri') {
    $g = $data['galeri'] ?? [];
    echo '<section><h2>Galeri</h2><div class="grid-3">';
    foreach ($g as $img) {
        $url = $img['url'] ?? '';
        $cap = $img['caption'] ?? '';
        echo '<figure class="card"><img class="gallery" src="' . htmlspecialchars($url) . '" alt="' . htmlspecialchars($cap) . '"><figcaption>' . htmlspecialchars($cap) . '</figcaption></figure>';
    }
    echo '</div></section>';
} elseif ($page === 'berita') {
    $b = $data['berita'] ?? [];
    echo '<section><h2>Berita</h2>';
    foreach ($b as $bv) {
        $tgl = $bv['tanggal'] ?? '';
        $judul = $bv['judul'] ?? '';
        $isi = $bv['isi'] ?? '';
        echo '<article class="card"><div class="muted">' . htmlspecialchars($tgl) . '</div><h3>' . htmlspecialchars($judul) . '</h3><p>' . nl2br(htmlspecialchars($isi)) . '</p></article>';
    }
    echo '</section>';
} elseif ($page === 'kontak') {
    $kontak = $data['kontak'] ?? [];
    $msg = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
        $pesan = isset($_POST['pesan']) ? trim($_POST['pesan']) : '';
        if ($nama !== '' && $pesan !== '') {
            $msg = 'Terima kasih, ' . htmlspecialchars($nama) . '. Pesan Anda telah terkirim.';
        } else {
            $msg = 'Lengkapi form terlebih dahulu.';
        }
    }
    echo '<section><h2>Kontak</h2>';
    if ($msg !== '') {
        echo '<div class="card success">' . $msg . '</div>';
    }
    echo '<form method="post" class="form">';
    echo '<div class="form-row"><label>Nama</label><input name="nama" required></div>';
    echo '<div class="form-row"><label>Pesan</label><textarea name="pesan" rows="4" required></textarea></div>';
    echo '<button class="btn" type="submit">Kirim</button>';
    echo '</form>';
    echo '</section>';
} else {
    http_response_code(404);
    echo '<section><h2>Tidak ditemukan</h2><p>Halaman tidak tersedia.</p></section>';
}
?>
</main>
<?php
require __DIR__ . '/../includes/footer.php';
?>
