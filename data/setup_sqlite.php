<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
$jsonPath = __DIR__ . '/data.json';
$dbPath = __DIR__ . '/app.sqlite';
$data = [];
if (is_file($jsonPath)) {
    $j = json_decode(file_get_contents($jsonPath), true);
    if (is_array($j)) { $data = $j; }
}
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('PRAGMA foreign_keys = ON');
$pdo->exec('DROP TABLE IF EXISTS settings');
$pdo->exec('DROP TABLE IF EXISTS wilayah');
$pdo->exec('DROP TABLE IF EXISTS demografi');
$pdo->exec('DROP TABLE IF EXISTS usia');
$pdo->exec('DROP TABLE IF EXISTS aparatur');
$pdo->exec('DROP TABLE IF EXISTS layanan');
$pdo->exec('DROP TABLE IF EXISTS galeri');
$pdo->exec('DROP TABLE IF EXISTS berita');
$pdo->exec('DROP TABLE IF EXISTS acara');
$pdo->exec('DROP TABLE IF EXISTS kontak');
$pdo->exec('DROP TABLE IF EXISTS ui');
$pdo->exec('DROP TABLE IF EXISTS kepala_dukuh');
$pdo->exec('CREATE TABLE settings (id INTEGER PRIMARY KEY, nama TEXT, deskripsi TEXT, visi TEXT, sejarah TEXT, header_bg TEXT, logo TEXT, map_embed TEXT)');
$pdo->exec('CREATE TABLE wilayah (dusun INTEGER, rw INTEGER, rt INTEGER, luas TEXT, alamat_kantor TEXT)');
$pdo->exec('CREATE TABLE demografi (jumlah_penduduk INTEGER, laki INTEGER, perempuan INTEGER)');
$pdo->exec('CREATE TABLE usia (kategori TEXT PRIMARY KEY, jumlah INTEGER)');
$pdo->exec('CREATE TABLE aparatur (id INTEGER PRIMARY KEY AUTOINCREMENT, jabatan TEXT, nama TEXT, kontak TEXT)');
$pdo->exec('CREATE TABLE layanan (id INTEGER PRIMARY KEY AUTOINCREMENT, nama TEXT, deskripsi TEXT, lokasi TEXT)');
$pdo->exec('CREATE TABLE galeri (id INTEGER PRIMARY KEY AUTOINCREMENT, url TEXT, caption TEXT)');
$pdo->exec('CREATE TABLE berita (id INTEGER PRIMARY KEY AUTOINCREMENT, judul TEXT, tanggal TEXT, isi TEXT)');
$pdo->exec('CREATE TABLE acara (id INTEGER PRIMARY KEY AUTOINCREMENT, judul TEXT, tanggal TEXT, lokasi TEXT, img TEXT)');
$pdo->exec('CREATE TABLE kontak (email TEXT, telp TEXT)');
$pdo->exec('CREATE TABLE ui (key TEXT PRIMARY KEY, value INTEGER)');
$pdo->exec('CREATE TABLE kepala_dukuh (nama TEXT, jabatan TEXT, foto TEXT, sambutan TEXT)');
$stmt = $pdo->prepare('INSERT INTO settings (id,nama,deskripsi,visi,sejarah,header_bg,logo,map_embed) VALUES (1,?,?,?,?,?,?,?)');
$stmt->execute([
    $data['nama'] ?? '',
    $data['deskripsi'] ?? '',
    $data['visi'] ?? '',
    $data['sejarah'] ?? '',
    $data['header_bg'] ?? '',
    $data['logo'] ?? '',
    $data['map_embed'] ?? ''
]);
$w = $data['wilayah'] ?? [];
$pdo->prepare('INSERT INTO wilayah (dusun,rw,rt,luas,alamat_kantor) VALUES (?,?,?,?,?)')->execute([
    (int)($w['dusun'] ?? 0),
    (int)($w['rw'] ?? 0),
    (int)($w['rt'] ?? 0),
    (string)($w['luas'] ?? ''),
    (string)($w['alamat_kantor'] ?? '')
]);
$d = $data['demografi'] ?? [];
$pdo->prepare('INSERT INTO demografi (jumlah_penduduk,laki,perempuan) VALUES (?,?,?)')->execute([
    (int)($d['jumlah_penduduk'] ?? 0),
    (int)($d['laki'] ?? 0),
    (int)($d['perempuan'] ?? 0)
]);
$usia = $d['usia'] ?? [];
if (is_array($usia)) {
    $uStmt = $pdo->prepare('INSERT INTO usia (kategori,jumlah) VALUES (?,?)');
    foreach ($usia as $k=>$v) {
        $uStmt->execute([ (string)$k, (int)$v ]);
    }
}
$apar = $data['aparatur'] ?? [];
if (is_array($apar)) {
    $aStmt = $pdo->prepare('INSERT INTO aparatur (jabatan,nama,kontak) VALUES (?,?,?)');
    foreach ($apar as $ap) {
        $aStmt->execute([ $ap['jabatan'] ?? '', $ap['nama'] ?? '', $ap['kontak'] ?? '' ]);
    }
}
$lay = $data['layanan'] ?? [];
if (is_array($lay)) {
    $lStmt = $pdo->prepare('INSERT INTO layanan (nama,deskripsi,lokasi) VALUES (?,?,?)');
    foreach ($lay as $lv) {
        $lStmt->execute([ $lv['nama'] ?? '', $lv['deskripsi'] ?? '', $lv['lokasi'] ?? '' ]);
    }
}
$gal = $data['galeri'] ?? [];
if (is_array($gal)) {
    $gStmt = $pdo->prepare('INSERT INTO galeri (url,caption) VALUES (?,?)');
    foreach ($gal as $gv) {
        $gStmt->execute([ $gv['url'] ?? '', $gv['caption'] ?? '' ]);
    }
}
$ber = $data['berita'] ?? [];
if (is_array($ber)) {
    $bStmt = $pdo->prepare('INSERT INTO berita (judul,tanggal,isi) VALUES (?,?,?)');
    foreach ($ber as $bv) {
        $bStmt->execute([ $bv['judul'] ?? '', $bv['tanggal'] ?? '', $bv['isi'] ?? '' ]);
    }
}
$acr = $data['acara'] ?? [];
if (is_array($acr)) {
    $eStmt = $pdo->prepare('INSERT INTO acara (judul,tanggal,lokasi,img) VALUES (?,?,?,?)');
    foreach ($acr as $ev) {
        $eStmt->execute([ $ev['judul'] ?? '', $ev['tanggal'] ?? '', $ev['lokasi'] ?? '', $ev['img'] ?? '' ]);
    }
}
$kontak = $data['kontak'] ?? [];
$pdo->prepare('INSERT INTO kontak (email,telp) VALUES (?,?)')->execute([
    $kontak['email'] ?? '',
    $kontak['telp'] ?? ''
]);
$ui = $data['ui'] ?? [];
if (is_array($ui)) {
    $uiStmt = $pdo->prepare('INSERT INTO ui (key,value) VALUES (?,?)');
    foreach ($ui as $k=>$v) {
        $uiStmt->execute([ (string)$k, (int)(!!$v) ]);
    }
}
$kd = $data['kepala_dukuh'] ?? [];
$pdo->prepare('INSERT INTO kepala_dukuh (nama,jabatan,foto,sambutan) VALUES (?,?,?,?)')->execute([
    $kd['nama'] ?? '',
    $kd['jabatan'] ?? '',
    $kd['foto'] ?? '',
    $kd['sambutan'] ?? ''
]);
echo 'OK: ' . $dbPath;
