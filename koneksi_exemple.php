<?php
/**
 * CONTOH FILE KONEKSI DATABASE (koneksi_exemple.php)
 *
 * Ini adalah file CONTOH untuk menunjukkan struktur koneksi database.
 * JANGAN gunakan file ini secara langsung!
 *
 * LANGKAH-LANGKAH SETUP:
 * 1. Salin isi file ini
 * 2. Buat file baru bernama koneksi.php di root project
 * 3. Isi dengan konfigurasi database Anda sendiri
 * 4. Sesuaikan $servername, $username, $password, $db, dan GEMINI_API_KEY
 * 5. File koneksi.php akan di-ignore oleh git (lihat .gitignore)
 */

date_default_timezone_set("Asia/Jakarta");

// Konfigurasi Database
$servername = "";
$username   = "";
$password   = "";
$db         = "";

// Buat Koneksi
$conn = new mysqli($servername, $username, $password, $db);

// Cek Koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Setup API Key untuk Google Gemini
if (!defined('GEMINI_API_KEY')) {
    define('GEMINI_API_KEY', 'your_gemini_api_key_here');
}

/**
 * PENJELASAN VARIABEL:
 *
 * $servername   : Alamat server (localhost untuk XAMPP lokal)
 * $username     : Username database (root untuk XAMPP default)
 * $password     : Password database (kosongkan jika tidak ada)
 * $db           : Nama database yang ingin digunakan
 * GEMINI_API_KEY: API Key dari Google Gemini untuk fitur AI Helper
 */

?>
