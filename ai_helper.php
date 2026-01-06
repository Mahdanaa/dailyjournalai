<?php
// 1. MEMULAI SESI
session_start();

// Validasi Keamanan: Cek apakah user sudah login
if (!isset($_SESSION["username"])) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Akses ditolak. Silakan login."]);
    exit;
}

header('Content-Type: application/json');

// 2. PANGGIL KONEKSI
require_once 'koneksi.php';

// 3. AMBIL API KEY
if (defined('GEMINI_API_KEY') && !empty(GEMINI_API_KEY)) {
    $api_key = GEMINI_API_KEY;
} else {
    $api_key = getenv('GEMINI_API_KEY');
}

// Validasi Kunci API
if (!$api_key) {
    echo json_encode(["status" => "error", "message" => "API Key tidak ditemukan. Cek file .env dan koneksi.php"]);
    exit;
}

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . $api_key;

// 4. PROSES REQUEST DARI USER
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['gambar'])) {

    $tmp_file = $_FILES['gambar']['tmp_name'];

    if (!file_exists($tmp_file)) {
        echo json_encode(["status" => "error", "message" => "File gambar tidak ditemukan."]);
        exit;
    }

    // 5. PERSIAPAN DATA GAMBAR
    $mime_type = mime_content_type($tmp_file);
    $image_data = file_get_contents($tmp_file);
    $base64_image = base64_encode($image_data);

    // 6. KONSTRUKSI PROMPT
    $prompt = "Deskripsikan gambar ini dalam bahasa Indonesia dengan nada santai untuk caption Instagram (maksimal 1 kalimat). Lalu, berikan SATU kategori singkat (1-3 kata) yang paling menggambarkan isi gambar. Format jawaban WAJIB: CAPTION | KATEGORI. Contoh: Serunya bermain bola di sore hari. | Sepak Bola";

    // 7. PAYLOAD DATA
    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $prompt],
                    [
                        "inline_data" => [
                            "mime_type" => $mime_type,
                            "data" => $base64_image
                        ]
                    ]
                ]
            ]
        ]
    ];

    // 8. EKSEKUSI REQUEST (cURL)
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        echo json_encode([
            "status" => "error",
            "message" => "Koneksi AI Gagal. Detail: " . $error_msg . ". (Saran: Jika error SSL, ubah SSL_VERIFYPEER jadi false)"
        ]);
        exit;
    }

    curl_close($ch);

    // 9. PARSING RESPON DARI GOOGLE
    $result = json_decode($response, true);

    // Cek apakah ada teks jawaban
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        $text_response = trim($result['candidates'][0]['content']['parts'][0]['text']);

        $parts = explode("|", $text_response);

        echo json_encode([
            "status" => "success",
            "deskripsi" => trim($parts[0]),
            "kategori" => isset($parts[1]) ? trim($parts[1]) : "Umum"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Respon Google tidak valid atau API Key salah. Raw Response: " . substr($response, 0, 200) . "..."
        ]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}
?>
