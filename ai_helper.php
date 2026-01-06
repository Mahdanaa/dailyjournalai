<?php
// 1. MEMULAI SESI & KEAMANAN
session_start();

// Cek login
if (!isset($_SESSION["username"])) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Akses ditolak. Silakan login."]);
    exit;
}

header('Content-Type: application/json');

// 2. FUNGSI load file .env
function loadEnv($path) {
    if (!file_exists($path)) {
        return false;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        putenv(sprintf('%s=%s', $name, $value));
    }
}

// memanggil fungsi di atas untuk membaca file .env
loadEnv(__DIR__ . '/.env');

// 3. AMBIL KUNCI API
$api_key = getenv('GEMINI_API_KEY');

//cek kunci
if (!$api_key) {
    echo json_encode(["status" => "error", "message" => "API Key tidak ditemukan. Pastikan file .env ada."]);
    exit;
}

// Alamat server Google Gemini
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . $api_key;

// 4. PROSES REQUEST DARI USER
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['gambar'])) {

    $tmp_file = $_FILES['gambar']['tmp_name'];

    if (!file_exists($tmp_file)) {
        echo json_encode(["status" => "error", "message" => "File gambar tidak ditemukan."]);
        exit;
    }

    // 5. PERSIAPAN DATA GAMBAR
    $image_data = file_get_contents($tmp_file);
    $base64_image = base64_encode($image_data);

    // 6. PERINTAH UNTUK AI (PROMPT)
    $prompt = "Deskripsikan gambar ini dalam bahasa Indonesia dengan nada santai untuk caption Instagram (maksimal 1 kalimat). Lalu, berikan SATU kategori singkat (1-3 kata) yang paling menggambarkan isi gambar. Format jawaban WAJIB: CAPTION | KATEGORI. Contoh: Serunya bermain bola di sore hari. | Sepak Bola";

    // 7. BUNGKUS PAKET DATA
    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $prompt],
                    [
                        "inline_data" => [
                            "mime_type" => "image/jpeg",
                            "data" => $base64_image
                        ]
                    ]
                ]
            ]
        ]
    ];

    // 8. KIRIM Perintah dengan cURL untuk komunikasi server-to-server.
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true );

    // Eksekusi pengiriman dan menangkap respons.
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo json_encode(["status" => "error", "message" => "Koneksi ke AI gagal: " . curl_error($ch)]);
        exit;
    }

    curl_close($ch);

    // 9. BACA BALASAN DARI GOOGLE
    $result = json_decode($response, true);

    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        $text_response = trim($result['candidates'][0]['content']['parts'][0]['text']);

        $parts = explode("|", $text_response);

        // 10. KIRIM HASIL KE FRONTEND
        echo json_encode([
            "status" => "success",
            "deskripsi" => trim($parts[0]),
            "kategori" => isset($parts[1]) ? trim($parts[1]) : "Dokumentasi Pribadi"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Server Google Merespon Error: " . $response
        ]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Invalid Request"]);
}
?>
