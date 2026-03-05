<?php
function getVisitorStats(): array {
    $dir = __DIR__ . '/../data';
    if (!is_dir($dir)) { mkdir($dir, 0777, true); }
    $file = $dir . '/visitors.json';
    $data = ['daily'=>[]];
    if (is_file($file)) {
        $raw = json_decode(file_get_contents($file), true);
        if (is_array($raw)) { $data = array_replace_recursive($data, $raw); }
    }
    $today = date('Y-m-d');
    $data['daily'][$today] = ($data['daily'][$today] ?? 0) + 1;
    $yesterday = date('Y-m-d', time()-86400);
    $yearPrefix = date('Y-');
    $monthPrefix = date('Y-m-');
    $all = 0; $year = 0; $month = 0;
    foreach ($data['daily'] as $d=>$c) {
        $all += (int)$c;
        if (strpos($d, $yearPrefix) === 0) { $year += (int)$c; }
        if (strpos($d, $monthPrefix) === 0) { $month += (int)$c; }
    }
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    return [
        'today' => (int)($data['daily'][$today] ?? 0),
        'yesterday' => (int)($data['daily'][$yesterday] ?? 0),
        'month' => (int)$month,
        'year' => (int)$year,
        'all' => (int)$all,
    ];
}
