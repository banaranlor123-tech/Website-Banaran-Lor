<?php
require __DIR__ . '/../../includes/auth.php';
requireAdmin();
require __DIR__ . '/../../data/data.php';
$saveMsg = '';
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $next = $data;
    if ($tab === 'identitas') {
        $next['nama'] = isset($_POST['nama']) ? trim($_POST['nama']) : ($next['nama'] ?? '');
        $next['deskripsi'] = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : ($next['deskripsi'] ?? '');
        $next['visi'] = isset($_POST['visi']) ? trim($_POST['visi']) : ($next['visi'] ?? '');
        $next['misi'] = isset($_POST['misi']) ? array_values(array_filter(array_map('trim', explode("\n", $_POST['misi'])), fn($x)=>$x!=='')) : ($next['misi'] ?? []);
        $next['sejarah'] = isset($_POST['sejarah']) ? trim($_POST['sejarah']) : ($next['sejarah'] ?? '');
        if (!empty($_FILES['logo_file']) && isset($_FILES['logo_file']['error']) && $_FILES['logo_file']['error'] === UPLOAD_ERR_OK) {
            $type = mime_content_type($_FILES['logo_file']['tmp_name']);
            $allowed = ['image/jpeg'=>'.jpg','image/png'=>'.png','image/webp'=>'.webp'];
            if (isset($allowed[$type])) {
                $uploads = __DIR__ . '/../uploads';
                if (!is_dir($uploads)) { mkdir($uploads, 0777, true); }
                $fname = 'logo_' . time() . '_' . mt_rand(1000,9999) . $allowed[$type];
                $dest = $uploads . '/' . $fname;
                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $dest)) {
                    $next['logo'] = '/uploads/' . $fname;
                }
            }
        }
    } elseif ($tab === 'wilayah') {
        $next['wilayah']['rw'] = isset($_POST['rw']) ? (int)$_POST['rw'] : ($next['wilayah']['rw'] ?? 0);
        $next['wilayah']['rt'] = isset($_POST['rt']) ? (int)$_POST['rt'] : ($next['wilayah']['rt'] ?? 0);
        $next['wilayah']['luas'] = isset($_POST['luas']) ? trim($_POST['luas']) : ($next['wilayah']['luas'] ?? '');
        $next['wilayah']['alamat_kantor'] = isset($_POST['alamat_kantor']) ? trim($_POST['alamat_kantor']) : ($next['wilayah']['alamat_kantor'] ?? '');
    } elseif ($tab === 'demografi') {
        $next['demografi']['jumlah_penduduk'] = isset($_POST['jumlah_penduduk']) ? (int)$_POST['jumlah_penduduk'] : ($next['demografi']['jumlah_penduduk'] ?? 0);
        $next['demografi']['laki'] = isset($_POST['laki']) ? (int)$_POST['laki'] : ($next['demografi']['laki'] ?? 0);
        $next['demografi']['perempuan'] = isset($_POST['perempuan']) ? (int)$_POST['perempuan'] : ($next['demografi']['perempuan'] ?? 0);
        $next['demografi']['usia']['Balita (0-5)'] = isset($_POST['usia_balita']) ? (int)$_POST['usia_balita'] : ($next['demografi']['usia']['Balita (0-5)'] ?? 0);
        $next['demografi']['usia']['Anak (6-12)'] = isset($_POST['usia_anak']) ? (int)$_POST['usia_anak'] : ($next['demografi']['usia']['Anak (6-12)'] ?? 0);
        $next['demografi']['usia']['Remaja (13-17)'] = isset($_POST['usia_remaja']) ? (int)$_POST['usia_remaja'] : ($next['demografi']['usia']['Remaja (13-17)'] ?? 0);
        $next['demografi']['usia']['Dewasa (18-59)'] = isset($_POST['usia_dewasa']) ? (int)$_POST['usia_dewasa'] : ($next['demografi']['usia']['Dewasa (18-59)'] ?? 0);
        $next['demografi']['usia']['Lansia (60+)'] = isset($_POST['usia_lansia']) ? (int)$_POST['usia_lansia'] : ($next['demografi']['usia']['Lansia (60+)'] ?? 0);
    } elseif ($tab === 'kepaladukuh') {
        $next['kepala_dukuh']['nama'] = isset($_POST['kd_nama']) ? trim($_POST['kd_nama']) : ($next['kepala_dukuh']['nama'] ?? '');
        $next['kepala_dukuh']['jabatan'] = isset($_POST['kd_jabatan']) ? trim($_POST['kd_jabatan']) : ($next['kepala_dukuh']['jabatan'] ?? '');
        $next['kepala_dukuh']['sambutan'] = isset($_POST['kd_sambutan']) ? trim($_POST['kd_sambutan']) : ($next['kepala_dukuh']['sambutan'] ?? '');
        if (!empty($_FILES['kd_foto_file']) && isset($_FILES['kd_foto_file']['error']) && $_FILES['kd_foto_file']['error'] === UPLOAD_ERR_OK) {
            $type = mime_content_type($_FILES['kd_foto_file']['tmp_name']);
            $allowed = ['image/jpeg'=>'.jpg','image/png'=>'.png','image/webp'=>'.webp'];
            if (isset($allowed[$type])) {
                $uploads = __DIR__ . '/../uploads';
                if (!is_dir($uploads)) { mkdir($uploads, 0777, true); }
                $fname = 'dukuh_' . time() . '_' . mt_rand(1000,9999) . $allowed[$type];
                $dest = $uploads . '/' . $fname;
                if (move_uploaded_file($_FILES['kd_foto_file']['tmp_name'], $dest)) {
                    $next['kepala_dukuh']['foto'] = '/uploads/' . $fname;
                }
            }
        }
    } elseif ($tab === 'acara') {
        if (isset($_POST['delete_all'])) {
            $next['acara'] = [];
        } elseif (isset($_POST['delete_index'])) {
            $di = (int)$_POST['delete_index'];
            if (isset($next['acara']) && is_array($next['acara']) && array_key_exists($di, $next['acara'])) {
                array_splice($next['acara'], $di, 1);
            }
        } else {
            $judul = isset($_POST['ev_judul']) ? trim($_POST['ev_judul']) : '';
            $tanggal = date('Y-m-d');
            $lokasi = isset($_POST['ev_lokasi']) ? trim($_POST['ev_lokasi']) : '';
            $imgUrl = isset($_POST['ev_img_url']) ? trim($_POST['ev_img_url']) : '';
            $imgPath = $imgUrl;
            if (!empty($_FILES['ev_img_file']) && isset($_FILES['ev_img_file']['error']) && $_FILES['ev_img_file']['error'] === UPLOAD_ERR_OK) {
                $type = mime_content_type($_FILES['ev_img_file']['tmp_name']);
                $allowed = ['image/jpeg'=>'.jpg','image/png'=>'.png','image/webp'=>'.webp'];
                if (isset($allowed[$type])) {
                    $uploads = __DIR__ . '/../uploads';
                    if (!is_dir($uploads)) { mkdir($uploads, 0777, true); }
                    $fname = 'event_' . time() . '_' . mt_rand(1000,9999) . $allowed[$type];
                    $dest = $uploads . '/' . $fname;
                    if (move_uploaded_file($_FILES['ev_img_file']['tmp_name'], $dest)) {
                        $imgPath = '/uploads/' . $fname;
                    }
                }
            }
            if ($judul !== '' && $lokasi !== '') {
                if (!isset($next['acara']) || !is_array($next['acara'])) { $next['acara'] = []; }
                $next['acara'][] = ['judul'=>$judul,'tanggal'=>$tanggal,'lokasi'=>$lokasi,'img'=>$imgPath];
            }
        }
    } elseif ($tab === 'galeri') {
        if (isset($_POST['gal_delete_all'])) {
            $next['galeri'] = [];
        } elseif (isset($_POST['gal_delete_index'])) {
            $di = (int)$_POST['gal_delete_index'];
            if (isset($next['galeri']) && is_array($next['galeri']) && array_key_exists($di, $next['galeri'])) {
                array_splice($next['galeri'], $di, 1);
            }
        } else {
            $url = isset($_POST['gal_url']) ? trim($_POST['gal_url']) : '';
            $cap = isset($_POST['gal_caption']) ? trim($_POST['gal_caption']) : '';
            $imgPath = $url;
            if (!empty($_FILES['gal_img_file']) && isset($_FILES['gal_img_file']['error']) && $_FILES['gal_img_file']['error'] === UPLOAD_ERR_OK) {
                $type = mime_content_type($_FILES['gal_img_file']['tmp_name']);
                $allowed = ['image/jpeg'=>'.jpg','image/png'=>'.png','image/webp'=>'.webp'];
                if (isset($allowed[$type])) {
                    $uploads = __DIR__ . '/../uploads';
                    if (!is_dir($uploads)) { mkdir($uploads, 0777, true); }
                    $fname = 'gal_' . time() . '_' . mt_rand(1000,9999) . $allowed[$type];
                    $dest = $uploads . '/' . $fname;
                    if (move_uploaded_file($_FILES['gal_img_file']['tmp_name'], $dest)) {
                        $imgPath = '/uploads/' . $fname;
                    }
                }
            }
            if ($imgPath !== '') {
                if (!isset($next['galeri']) || !is_array($next['galeri'])) { $next['galeri'] = []; }
                $next['galeri'][] = ['url'=>$imgPath,'caption'=>$cap];
            }
        }
    } elseif ($tab === 'aparatur') {
        if (isset($_POST['ap_delete_all'])) {
            $next['aparatur'] = [];
        } elseif (isset($_POST['ap_delete_index'])) {
            $di = (int)$_POST['ap_delete_index'];
            if (isset($next['aparatur']) && is_array($next['aparatur']) && array_key_exists($di, $next['aparatur'])) {
                array_splice($next['aparatur'], $di, 1);
            }
        } else {
            $jab = isset($_POST['ap_jabatan']) ? trim($_POST['ap_jabatan']) : '';
            $nm = isset($_POST['ap_nama']) ? trim($_POST['ap_nama']) : '';
            $kt = isset($_POST['ap_kontak']) ? trim($_POST['ap_kontak']) : '';
            if ($jab !== '' && $nm !== '') {
                if (!isset($next['aparatur']) || !is_array($next['aparatur'])) { $next['aparatur'] = []; }
                $next['aparatur'][] = ['jabatan'=>$jab,'nama'=>$nm,'kontak'=>$kt];
            }
        }
    } elseif ($tab === 'layanan') {
        if (isset($_POST['srv_delete_all'])) {
            $next['layanan'] = [];
        } elseif (isset($_POST['srv_delete_index'])) {
            $di = (int)$_POST['srv_delete_index'];
            if (isset($next['layanan']) && is_array($next['layanan']) && array_key_exists($di, $next['layanan'])) {
                array_splice($next['layanan'], $di, 1);
            }
        } else {
            $nama = isset($_POST['srv_nama']) ? trim($_POST['srv_nama']) : '';
            $desk = isset($_POST['srv_deskripsi']) ? trim($_POST['srv_deskripsi']) : '';
            $lok = isset($_POST['srv_lokasi']) ? trim($_POST['srv_lokasi']) : '';
            if ($nama !== '') {
                if (!isset($next['layanan']) || !is_array($next['layanan'])) { $next['layanan'] = []; }
                $next['layanan'][] = ['nama'=>$nama,'deskripsi'=>$desk,'lokasi'=>$lok];
            }
        }
    } elseif ($tab === 'berita') {
        if (isset($_POST['news_delete_all'])) {
            $next['berita'] = [];
        } elseif (isset($_POST['news_delete_index'])) {
            $di = (int)$_POST['news_delete_index'];
            if (isset($next['berita']) && is_array($next['berita']) && array_key_exists($di, $next['berita'])) {
                array_splice($next['berita'], $di, 1);
            }
        } else {
            $judul = isset($_POST['news_judul']) ? trim($_POST['news_judul']) : '';
            $isi = isset($_POST['news_isi']) ? trim($_POST['news_isi']) : '';
            $tanggal = date('Y-m-d');
            if ($judul !== '' && $isi !== '') {
                if (!isset($next['berita']) || !is_array($next['berita'])) { $next['berita'] = []; }
                $next['berita'][] = ['judul'=>$judul,'tanggal'=>$tanggal,'isi'=>$isi];
            }
        }
    }
    $jsonPath = __DIR__ . '/../../data/data.json';
    file_put_contents($jsonPath, json_encode($next, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $saveMsg = 'Data tersimpan.';
    $data = $next;
}
require __DIR__ . '/../../includes/header.php';
?>
<main class="container">
<section>
<div class="admin-header">Dashboard Admin</div>
<?php if ($saveMsg): ?>
<div class="card success" style="margin-top:12px;"><?php echo htmlspecialchars($saveMsg); ?></div>
<?php endif; ?>
<div class="admin-layout">
<aside class="sidebar">
<a class="<?php echo $tab==='dashboard'?'active':''; ?>" href="/admin/dashboard.php?tab=dashboard">Dashboard Admin</a>
<a class="<?php echo $tab==='identitas'?'active':''; ?>" href="/admin/dashboard.php?tab=identitas">Data Identitas</a>
<a class="<?php echo $tab==='wilayah'?'active':''; ?>" href="/admin/dashboard.php?tab=wilayah">Data Wilayah</a>
<a class="<?php echo $tab==='demografi'?'active':''; ?>" href="/admin/dashboard.php?tab=demografi">Data Demografi</a>
<a class="<?php echo $tab==='kepaladukuh'?'active':''; ?>" href="/admin/dashboard.php?tab=kepaladukuh">Kepala Dukuh</a>
<a class="<?php echo $tab==='aparatur'?'active':''; ?>" href="/admin/dashboard.php?tab=aparatur">Aparatur</a>
<a class="<?php echo $tab==='acara'?'active':''; ?>" href="/admin/dashboard.php?tab=acara">Acara</a>
<a class="<?php echo $tab==='galeri'?'active':''; ?>" href="/admin/dashboard.php?tab=galeri">Galeri</a>
<a class="<?php echo $tab==='layanan'?'active':''; ?>" href="/admin/dashboard.php?tab=layanan">Layanan</a>
<a class="<?php echo $tab==='berita'?'active':''; ?>" href="/admin/dashboard.php?tab=berita">Berita</a>
</aside>
<div class="admin-content">
<?php if ($tab==='dashboard'): ?>
<div class="stats">
<h3>Ringkasan</h3>
<div class="grid-4">
<div class="card"><div class="icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="#0ea5e9"><circle cx="12" cy="7" r="4"></circle><path d="M4 20c0-4 4-6 8-6s8 2 8 6" /></svg></div><div class="stat"><?php echo htmlspecialchars((string)($data['demografi']['jumlah_penduduk'] ?? 0)); ?></div><div class="label">Penduduk</div></div>
<div class="card"><div class="icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="#0ea5e9"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect></svg></div><div class="stat"><?php echo htmlspecialchars((string)($data['wilayah']['rw'] ?? 0)); ?></div><div class="label">RW</div></div>
<div class="card"><div class="icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="#0ea5e9"><path d="M3 10l9-7 9 7v9a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2v-4H10v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg></div><div class="stat"><?php echo htmlspecialchars((string)($data['wilayah']['rt'] ?? 0)); ?></div><div class="label">RT</div></div>
<div class="card"><div class="icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="#0ea5e9"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M5 5l3 3M16 16l3 3M19 5l-3 3M5 19l3-3"></path></svg></div><div class="stat"><?php echo htmlspecialchars((string)($data['wilayah']['luas'] ?? '-')); ?></div><div class="label">Luas</div></div>
</div>
</div>
<?php else: ?>
<form method="post" class="form" action="/admin/dashboard.php?tab=<?php echo htmlspecialchars($tab); ?>" enctype="multipart/form-data">
<?php if ($tab==='identitas'): ?>
<h3>Identitas</h3>
<div class="form-row"><label>Nama Padukuhan</label><input name="nama" value="<?php echo htmlspecialchars($data['nama'] ?? ''); ?>" required></div>
<div class="form-row"><label>Deskripsi</label><textarea name="deskripsi" rows="3"><?php echo htmlspecialchars($data['deskripsi'] ?? ''); ?></textarea></div>
<div class="form-row"><label>Visi</label><input name="visi" value="<?php echo htmlspecialchars($data['visi'] ?? ''); ?>"></div>
<div class="form-row"><label>Misi (baris per poin)</label><textarea name="misi" rows="4"><?php echo htmlspecialchars(implode("\n", $data['misi'] ?? [])); ?></textarea></div>
<div class="form-row"><label>Sejarah</label><textarea name="sejarah" rows="4"><?php echo htmlspecialchars($data['sejarah'] ?? ''); ?></textarea></div>
<div class="form-row"><label>Upload Logo (png/jpg/webp)</label><input type="file" name="logo_file" accept="image/*"></div>
<?php elseif ($tab==='wilayah'): ?>
<h3>Wilayah</h3>
<div class="form-row"><label>RW</label><input type="number" name="rw" value="<?php echo htmlspecialchars((string)($data['wilayah']['rw'] ?? 0)); ?>" min="0"></div>
<div class="form-row"><label>RT</label><input type="number" name="rt" value="<?php echo htmlspecialchars((string)($data['wilayah']['rt'] ?? 0)); ?>" min="0"></div>
<div class="form-row"><label>Luas</label><input name="luas" value="<?php echo htmlspecialchars($data['wilayah']['luas'] ?? ''); ?>"></div>
<div class="form-row"><label>Alamat Kantor</label><input name="alamat_kantor" value="<?php echo htmlspecialchars($data['wilayah']['alamat_kantor'] ?? ''); ?>"></div>
<?php elseif ($tab==='demografi'): ?>
<h3>Demografi</h3>
<div class="form-row"><label>Jumlah Penduduk</label><input type="number" name="jumlah_penduduk" value="<?php echo htmlspecialchars((string)($data['demografi']['jumlah_penduduk'] ?? 0)); ?>" min="0"></div>
<div class="form-row"><label>Laki-laki</label><input type="number" name="laki" value="<?php echo htmlspecialchars((string)($data['demografi']['laki'] ?? 0)); ?>" min="0"></div>
<div class="form-row"><label>Perempuan</label><input type="number" name="perempuan" value="<?php echo htmlspecialchars((string)($data['demografi']['perempuan'] ?? 0)); ?>" min="0"></div>
<h3>Distribusi Usia</h3>
<div class="form-row"><label>Balita (0-5)</label><input type="number" name="usia_balita" value="<?php echo htmlspecialchars((string)($data['demografi']['usia']['Balita (0-5)'] ?? 0)); ?>" min="0"></div>
<div class="form-row"><label>Anak (6-12)</label><input type="number" name="usia_anak" value="<?php echo htmlspecialchars((string)($data['demografi']['usia']['Anak (6-12)'] ?? 0)); ?>" min="0"></div>
<div class="form-row"><label>Remaja (13-17)</label><input type="number" name="usia_remaja" value="<?php echo htmlspecialchars((string)($data['demografi']['usia']['Remaja (13-17)'] ?? 0)); ?>" min="0"></div>
<div class="form-row"><label>Dewasa (18-59)</label><input type="number" name="usia_dewasa" value="<?php echo htmlspecialchars((string)($data['demografi']['usia']['Dewasa (18-59)'] ?? 0)); ?>" min="0"></div>
<div class="form-row"><label>Lansia (60+)</label><input type="number" name="usia_lansia" value="<?php echo htmlspecialchars((string)($data['demografi']['usia']['Lansia (60+)'] ?? 0)); ?>" min="0"></div>
<?php elseif ($tab==='kepaladukuh'): ?>
<h3>Kepala Dukuh</h3>
<div class="form-row"><label>Nama</label><input name="kd_nama" value="<?php echo htmlspecialchars($data['kepala_dukuh']['nama'] ?? ''); ?>"></div>
<div class="form-row"><label>Jabatan</label><input name="kd_jabatan" value="<?php echo htmlspecialchars($data['kepala_dukuh']['jabatan'] ?? ''); ?>"></div>
<div class="form-row"><label>Upload Foto</label><input type="file" name="kd_foto_file" accept="image/*"></div>
<div class="form-row"><label>Sambutan</label><textarea name="kd_sambutan" rows="4"><?php echo htmlspecialchars($data['kepala_dukuh']['sambutan'] ?? ''); ?></textarea></div>
<?php elseif ($tab==='acara'): ?>
<h3>Tambah Acara</h3>
<div class="form-row"><label>Judul</label><input name="ev_judul" required></div>
<div class="form-row"><label>Lokasi</label><input name="ev_lokasi" required></div>
<div class="form-row"><label>Gambar (URL)</label><input name="ev_img_url"></div>
<div class="form-row"><label>Upload Gambar</label><input type="file" name="ev_img_file" accept="image/*"></div>
<?php elseif ($tab==='galeri'): ?>
<h3>Tambah Galeri</h3>
<div class="form-row"><label>Gambar (URL)</label><input name="gal_url"></div>
<div class="form-row"><label>Upload Gambar</label><input type="file" name="gal_img_file" accept="image/*"></div>
<div class="form-row"><label>Caption</label><input name="gal_caption"></div>
<?php elseif ($tab==='aparatur'): ?>
<h3>Tambah Aparatur</h3>
<div class="form-row"><label>Jabatan</label><input name="ap_jabatan" required></div>
<div class="form-row"><label>Nama</label><input name="ap_nama" required></div>
<div class="form-row"><label>Kontak</label><input name="ap_kontak"></div>
<?php elseif ($tab==='layanan'): ?>
<h3>Tambah Layanan</h3>
<div class="form-row"><label>Nama Layanan</label><input name="srv_nama" required></div>
<div class="form-row"><label>Deskripsi</label><textarea name="srv_deskripsi" rows="3"></textarea></div>
<div class="form-row"><label>Lokasi</label><input name="srv_lokasi"></div>
<?php elseif ($tab==='berita'): ?>
<h3>Tambah Berita</h3>
<div class="form-row"><label>Judul</label><input name="news_judul" required></div>
<div class="form-row"><label>Isi</label><textarea name="news_isi" rows="5" required></textarea></div>
<?php endif; ?>
<button class="btn" type="submit">Simpan</button>
</form>
<?php if ($tab==='acara'): ?>
<h3 style="margin-top:16px;display:flex;align-items:center;justify-content:space-between;gap:8px">
  <span>Daftar Acara</span>
  <form method="post" action="/admin/dashboard.php?tab=acara" onsubmit="return confirm('Hapus semua acara?');">
    <input type="hidden" name="delete_all" value="1">
    <button class="btn ghost" type="submit">Hapus Semua</button>
  </form>
</h3>
<div class="ev-wrap">
<div class="ev-nav"><button class="ev-btn ev-prev" type="button">‹</button><button class="ev-btn ev-next" type="button">›</button></div>
<div class="ev-list">
<?php foreach (($data['acara'] ?? []) as $idx=>$ev): ?>
<div class="card ev-card">
<?php if (!empty($ev['img'])): ?><img class="event-img" src="<?php echo htmlspecialchars($ev['img']); ?>" alt="<?php echo htmlspecialchars($ev['judul'] ?? ''); ?>"><?php endif; ?>
<?php
  $t = $ev['tanggal'] ?? '';
  $full = $t;
  if ($t !== '') {
    $ts = strtotime($t);
    if ($ts !== false) {
      $full = date('d M Y', $ts);
    }
  }
?>
<div class="ev-head"><span class="ev-chip"><?php echo htmlspecialchars($full); ?></span></div>
<div class="ev-title"><?php echo htmlspecialchars($ev['judul'] ?? ''); ?></div>
<div class="ev-meta"><?php echo htmlspecialchars($ev['lokasi'] ?? ''); ?></div>
<form method="post" action="/admin/dashboard.php?tab=acara" style="margin-top:8px">
<input type="hidden" name="delete_index" value="<?php echo (string)$idx; ?>">
<button class="btn" type="submit">Hapus</button>
</form>
</div>
<?php endforeach; ?>
</div>
</div>
<?php elseif ($tab==='galeri'): ?>
<h3 style="margin-top:16px;display:flex;align-items:center;justify-content:space-between;gap:8px">
  <span>Daftar Galeri</span>
  <form method="post" action="/admin/dashboard.php?tab=galeri" onsubmit="return confirm('Hapus semua galeri?');">
    <input type="hidden" name="gal_delete_all" value="1">
    <button class="btn ghost" type="submit">Hapus Semua</button>
  </form>
</h3>
<div class="grid-3">
<?php foreach (($data['galeri'] ?? []) as $gidx=>$g): ?>
<div class="card">
  <?php if (!empty($g['url'])): ?><img class="gallery" src="<?php echo htmlspecialchars($g['url']); ?>" alt="<?php echo htmlspecialchars($g['caption'] ?? ''); ?>"><?php endif; ?>
  <div class="label"><?php echo htmlspecialchars($g['caption'] ?? ''); ?></div>
  <form method="post" action="/admin/dashboard.php?tab=galeri" style="margin-top:8px">
    <input type="hidden" name="gal_delete_index" value="<?php echo (string)$gidx; ?>">
    <button class="btn" type="submit">Hapus</button>
  </form>
</div>
<?php endforeach; ?>
</div>
<?php elseif ($tab==='layanan'): ?>
<?php elseif ($tab==='aparatur'): ?>
<h3 style="margin-top:16px;display:flex;align-items:center;justify-content:space-between;gap:8px">
  <span>Daftar Aparatur</span>
  <form method="post" action="/admin/dashboard.php?tab=aparatur" onsubmit="return confirm('Hapus semua aparatur?');">
    <input type="hidden" name="ap_delete_all" value="1">
    <button class="btn ghost" type="submit">Hapus Semua</button>
  </form>
</h3>
<div class="grid-3">
<?php foreach (($data['aparatur'] ?? []) as $aidx=>$ap): ?>
<div class="card" style="text-align:left">
  <div class="label"><?php echo htmlspecialchars($ap['jabatan'] ?? ''); ?></div>
  <div><?php echo htmlspecialchars($ap['nama'] ?? ''); ?></div>
  <div class="muted"><?php echo htmlspecialchars($ap['kontak'] ?? ''); ?></div>
  <form method="post" action="/admin/dashboard.php?tab=aparatur" style="margin-top:8px">
    <input type="hidden" name="ap_delete_index" value="<?php echo (string)$aidx; ?>">
    <button class="btn" type="submit">Hapus</button>
  </form>
</div>
<?php endforeach; ?>
</div>
<h3 style="margin-top:16px;display:flex;align-items:center;justify-content:space-between;gap:8px">
  <span>Daftar Layanan</span>
  <form method="post" action="/admin/dashboard.php?tab=layanan" onsubmit="return confirm('Hapus semua layanan?');">
    <input type="hidden" name="srv_delete_all" value="1">
    <button class="btn ghost" type="submit">Hapus Semua</button>
    <button class="btn ghost" type="submit">Hapus Semua</button>
  </form>
</h3>
<div class="grid-3">
<?php foreach (($data['layanan'] ?? []) as $lidx=>$lv): ?>
<div class="card" style="text-align:left">
  <div class="label"><?php echo htmlspecialchars($lv['nama'] ?? ''); ?></div>
  <div><?php echo htmlspecialchars($lv['deskripsi'] ?? ''); ?></div>
  <div class="muted"><?php echo htmlspecialchars($lv['lokasi'] ?? ''); ?></div>
  <form method="post" action="/admin/dashboard.php?tab=layanan" style="margin-top:8px">
    <input type="hidden" name="srv_delete_index" value="<?php echo (string)$lidx; ?>">
    <button class="btn" type="submit">Hapus</button>
  </form>
</div>
<?php endforeach; ?>
</div>
<?php elseif ($tab==='berita'): ?>
<h3 style="margin-top:16px;display:flex;align-items:center;justify-content:space-between;gap:8px">
  <span>Daftar Berita</span>
  <form method="post" action="/admin/dashboard.php?tab=berita" onsubmit="return confirm('Hapus semua berita?');">
    <input type="hidden" name="news_delete_all" value="1">
    <button class="btn ghost" type="submit">Hapus Semua</button>
  </form>
</h3>
<div class="grid-3">
<?php foreach (($data['berita'] ?? []) as $bidx=>$bv): ?>
<div class="card" style="text-align:left">
  <div class="muted"><?php echo htmlspecialchars($bv['tanggal'] ?? ''); ?></div>
  <div class="label"><?php echo htmlspecialchars($bv['judul'] ?? ''); ?></div>
  <div class="muted"><?php echo htmlspecialchars(mb_strimwidth((string)($bv['isi'] ?? ''),0,100,'…','UTF-8')); ?></div>
  <form method="post" action="/admin/dashboard.php?tab=berita" style="margin-top:8px">
    <input type="hidden" name="news_delete_index" value="<?php echo (string)$bidx; ?>">
    <button class="btn" type="submit">Hapus</button>
  </form>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
<?php endif; ?>
</div>
</div>
</section>
</main>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
