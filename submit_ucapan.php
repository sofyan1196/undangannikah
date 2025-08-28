<?php
// Set header agar browser tahu ini adalah respons JSON
header('Content-Type: application/json');

// Pastikan request adalah metode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Metode request tidak valid.']);
    exit;
}

// Ambil data dari form dan bersihkan
$nama = trim(strip_tags($_POST['nama'] ?? ''));
$ucapan = trim(strip_tags($_POST['ucapan'] ?? ''));
$kehadiran = trim(strip_tags($_POST['kehadiran'] ?? ''));

// Validasi sederhana
if (empty($nama) || empty($ucapan) || empty($kehadiran)) {
    echo json_encode(['status' => 'error', 'message' => 'Semua kolom wajib diisi.']);
    exit;
}

// Data baru yang akan disimpan
$new_entry = [
    'nama' => $nama,
    'ucapan' => $ucapan,
    'kehadiran' => $kehadiran,
    'timestamp' => date('Y-m-d H:i:s') // Tambahkan waktu saat submit
];

$file_path = 'ucapan.json';

// Baca data yang sudah ada dari file JSON
$ucapan_list = [];
if (file_exists($file_path)) {
    $json_data = file_get_contents($file_path);
    $ucapan_list = json_decode($json_data, true);
}

// Tambahkan entri baru di awal array (agar yang terbaru muncul di atas)
array_unshift($ucapan_list, $new_entry);

// Simpan kembali ke file JSON
// JSON_PRETTY_PRINT agar file mudah dibaca manusia
if (file_put_contents($file_path, json_encode($ucapan_list, JSON_PRETTY_PRINT))) {
    echo json_encode(['status' => 'success', 'message' => 'Ucapan berhasil dikirim!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan ucapan.']);
}
?>