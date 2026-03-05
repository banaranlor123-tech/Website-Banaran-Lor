<?php
$defaults = [
    'nama' => 'Banaran Lor',
    'deskripsi' => 'Portal informasi resmi Padukuhan Banaran Lor sebagai rujukan data, layanan, dan kegiatan warga.',
    'visi' => 'Terwujudnya padukuhan yang mandiri, sehat, cerdas, dan sejahtera.',
    'misi' => [
        'Meningkatkan pelayanan administrasi kepada warga.',
        'Menguatkan ekonomi lokal melalui UMKM dan pertanian.',
        'Mendorong pendidikan dan literasi digital.',
        'Mewujudkan lingkungan bersih dan hijau.'
    ],
    'sejarah' => 'Padukuhan Sembada berdiri sejak awal abad ke-20 sebagai permukiman pertanian. Perkembangan pesat terjadi setelah dibangun balai padukuhan dan sarana jalan, sehingga aktivitas sosial dan ekonomi semakin meningkat.',
    'wilayah' => [
        'dusun' => 3,
        'rw' => 2,
        'rt' => 4,
        'luas' => '215 ha',
        'alamat_kantor' => '56P6+V3Q, RT.24/RW.12, Banaran Lor, Banguncipto, Kec. Sentolo, Kabupaten Kulon Progo, Daerah Istimewa Yogyakarta 55664'
    ],
    'demografi' => [
        'jumlah_penduduk' => 200,
        'laki' => 100,
        'perempuan' => 100,
        'usia' => [
            'Balita (0-5)' => 160,
            'Anak (6-12)' => 280,
            'Remaja (13-17)' => 220,
            'Dewasa (18-59)' => 980,
            'Lansia (60+)' => 200
        ]
    ],
    'aparatur' => [
        ['jabatan' => 'Kepala Dukuh', 'nama' => 'Sutrisno', 'kontak' => '0812-0000-1111'],
        ['jabatan' => 'Sekretaris', 'nama' => 'Rina Kartika', 'kontak' => '0812-0000-2222'],
        ['jabatan' => 'Kepala Seksi Pemerintahan', 'nama' => 'Agus Prasetyo', 'kontak' => '0812-0000-3333'],
        ['jabatan' => 'Kepala Seksi Kesejahteraan', 'nama' => 'Lestari Widya', 'kontak' => '0812-0000-4444'],
        ['jabatan' => 'Kepala Seksi Pelayanan', 'nama' => 'Budi Santoso', 'kontak' => '0812-0000-5555']
    ],
    'layanan' => [
        ['nama' => 'Administrasi Surat', 'deskripsi' => 'Pembuatan surat keterangan domisili, usaha, dan lainnya.', 'lokasi' => 'Balai Padukuhan'],
        ['nama' => 'Layanan Kependudukan', 'deskripsi' => 'Pengantar KK, KTP, Akta kelahiran.', 'lokasi' => 'Balai Padukuhan'],
        ['nama' => 'Posyandu', 'deskripsi' => 'Pelayanan kesehatan ibu dan anak tiap bulan.', 'lokasi' => 'Posyandu Sembada']
    ],
    'galeri' => [
        ['url' => 'https://picsum.photos/id/1018/800/500', 'caption' => 'Balai Padukuhan'],
        ['url' => 'https://picsum.photos/id/1025/800/500', 'caption' => 'Kegiatan Posyandu'],
        ['url' => 'https://picsum.photos/id/1039/800/500', 'caption' => 'Kerja Bakti Warga'],
        ['url' => 'https://picsum.photos/id/1043/800/500', 'caption' => 'Area Persawahan']
    ],
    'berita' => [
        ['judul' => 'Gotong Royong Bersih Lingkungan', 'tanggal' => '2026-02-10', 'isi' => 'Warga bersama-sama melaksanakan kerja bakti membersihkan saluran air dan ruang publik.'],
        ['judul' => 'Pelatihan UMKM Digital', 'tanggal' => '2026-02-02', 'isi' => 'Pelaku UMKM mengikuti pelatihan pemasaran digital untuk meningkatkan penjualan.'],
        ['judul' => 'Posyandu Bulan Februari', 'tanggal' => '2026-02-15', 'isi' => 'Kegiatan posyandu dilaksanakan di balai padukuhan dengan layanan pemeriksaan kesehatan.']
    ],
    'kontak' => [
        'email' => 'informasi@sembada.desa.id',
        'telp' => '0812-1234-5678'
    ],
    'header_bg' => 'https://picsum.photos/id/1043/1200/300',
    'logo' => 'https://picsum.photos/seed/banaranlor-logo/80/80'
    ,
    'kepala_dukuh' => [
        'nama' => 'Sutrisno',
        'jabatan' => 'Kepala Dukuh',
        'foto' => 'https://picsum.photos/seed/banaranlor-dukuh/300/300',
        'sambutan' => 'Assalamualaikum warga Banaran Lor. Mari bersama membangun padukuhan yang tertib, sehat, dan sejahtera.'
    ],
    'map_embed' => 'https://www.google.com/maps?q=Banaran%20Lor%2C%20Banguncipto%2C%20Sentolo%2C%20Kulon%20Progo&output=embed'
    ,
    'acara' => [
        ['judul' => 'Kerja Bakti Mingguan', 'tanggal' => '2026-02-22', 'lokasi' => 'RT 01', 'img' => 'https://picsum.photos/id/1050/600/360'],
        ['judul' => 'Rapat Warga Bulanan', 'tanggal' => '2026-02-25', 'lokasi' => 'Balai Padukuhan', 'img' => 'https://picsum.photos/id/1052/600/360'],
        ['judul' => 'Posyandu Maret', 'tanggal' => '2026-03-05', 'lokasi' => 'Posyandu Sembada', 'img' => 'https://picsum.photos/id/1060/600/360']
    ],
    'ui' => [
        'hero_cta' => false,
        'hero_badges' => true,
        'map_button' => true,
        'scroll_top' => true,
        'footer_stats' => true,
        'show_events' => true
    ]
];
$jsonPath = __DIR__ . '/data.json';
if (is_file($jsonPath)) {
    $jsonData = json_decode(file_get_contents($jsonPath), true);
    if (is_array($jsonData)) {
        $data = array_replace_recursive($defaults, $jsonData);
        if (array_key_exists('acara', $jsonData)) {
            $data['acara'] = is_array($jsonData['acara']) ? $jsonData['acara'] : [];
        }
        if (array_key_exists('galeri', $jsonData)) {
            $data['galeri'] = is_array($jsonData['galeri']) ? $jsonData['galeri'] : [];
        }
        if (array_key_exists('berita', $jsonData)) {
            $data['berita'] = is_array($jsonData['berita']) ? $jsonData['berita'] : [];
        }
        if (array_key_exists('aparatur', $jsonData)) {
            $data['aparatur'] = is_array($jsonData['aparatur']) ? $jsonData['aparatur'] : [];
        }
        if (array_key_exists('layanan', $jsonData)) {
            $data['layanan'] = is_array($jsonData['layanan']) ? $jsonData['layanan'] : [];
        }
        if (array_key_exists('misi', $jsonData)) {
            $data['misi'] = is_array($jsonData['misi']) ? $jsonData['misi'] : [];
        }
    } else {
        $data = $defaults;
    }
} else {
    $data = $defaults;
}
