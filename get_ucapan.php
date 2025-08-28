<?php
// === BAGIAN BARU YANG DITAMBAHKAN ===
// Perintah ini memaksa browser dan proxy untuk selalu mengambil data terbaru
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
// === AKHIR BAGIAN BARU ===

header('Content-Type: application/json');

$file_path = 'ucapan.json';
$all_ucapan = [];

// Baca seluruh data dari file JSON
if (file_exists($file_path)) {
    $json_data = file_get_contents($file_path);
    $decoded_data = json_decode($json_data, true);
    // Pastikan data valid dan merupakan array
    if (is_array($decoded_data)) {
        $all_ucapan = $decoded_data;
    }
}

// --- 1. HITUNG TOTAL KEHADIRAN (dari semua data) ---
$hadir_count = 0;
$tidak_hadir_count = 0;
foreach ($all_ucapan as $ucapan) {
    if (isset($ucapan['kehadiran'])) {
        if (strtolower($ucapan['kehadiran']) === 'hadir') {
            $hadir_count++;
        } elseif (strtolower($ucapan['kehadiran']) === 'tidak hadir') {
            $tidak_hadir_count++;
        }
    }
}

// Balikkan urutan array agar ucapan terbaru muncul pertama
// Pindahkan ini ke setelah logika paginasi agar tidak mengganggu
// $all_ucapan = array_reverse($all_ucapan);

// --- 2. LOGIKA PAGINASI (Memotong data sesuai halaman) ---
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; 
$offset = ($page - 1) * $limit;

// "Memotong" array untuk mendapatkan data halaman saat ini
$paginated_ucapan = array_slice($all_ucapan, $offset, $limit);


// --- 3. MEMBUAT RESPON JSON ---
// Gabungkan data hitungan (total) dan daftar ucapan (per halaman)
$response = [
    'counts' => [
        'hadir' => $hadir_count,
        'tidak_hadir' => $tidak_hadir_count
    ],
    'ucapan' => $paginated_ucapan 
];

echo json_encode($response);
?>