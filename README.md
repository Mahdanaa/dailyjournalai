# 📸 Web Daily Journal + AI Integration (Computer Vision)

Aplikasi web Daily Journal yang memungkinkan pengguna untuk membuat, mengelola, dan membagikan artikel serta galeri foto dengan integrasi kecerdasan buatan dan computer vision.

---

## Fitur Utama

- ✍️ **Manajemen Artikel** - Buat, edit, dan hapus artikel harian
- 🖼️ **Galeri Foto** - Upload dan kelola koleksi foto
- 🤖 **AI Helper** - Integrasi kecerdasan buatan untuk membantu pengguna
- 👤 **Sistem Autentikasi** - Login dan logout yang aman
- 📊 **Dashboard Admin** - Panel administrasi untuk mengelola konten
- 🎨 **Interface Modern** - Desain responsif dengan Bootstrap 5

---

## Teknologi yang Digunakan

- **Backend**: PHP
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, Bootstrap 5
- **Icons**: Bootstrap Icons
- **Server**: XAMPP (Apache + MySQL + PHP)

---

## Struktur Proyek

```
├── .gitignore             # Git ignore file
├── admin.php              # Panel admin
├── ai_helper.php          # Integrasi AI
├── article.php            # Halaman artikel
├── article_data.php       # API/data handler artikel
├── dashboard.php          # Dashboard pengguna
├── gallery.php            # Halaman galeri
├── gallery_data.php       # API/data handler galeri
├── img/                   # Folder penyimpanan gambar
├── index.php              # Halaman utama/dashboard publik
├── koneksi.php            # Koneksi database
├── koneksi_exemple.php    # Contoh struktur koneksi database
├── login.php              # Halaman login
├── logout.php             # Handler logout
├── upload_foto.php        # Handler upload foto
└── README.md              # File dokumentasi
```

---

## Instalasi

### 1. Clone atau Download Project

```bash
git clone https://github.com/Mahdanaa/dailyjournalai.git
# atau download dan ekstrak file
```

### 2. Setup Database

1. Buka phpMyAdmin (biasanya di `http://localhost/phpmyadmin`)
2. Buat database baru dengan nama `webdailyjournal`
3. Import file database (jika ada file `.sql`) atau buat tabel secara manual dengan struktur berikut:
   - Tabel `users` - Data pengguna dan login
   - Tabel `article` - Artikel/jurnal harian
   - Tabel `gallery` - Data galeri foto

### 3. Konfigurasi Koneksi Database

Edit file `koneksi.php` dan sesuaikan konfigurasi:

1. Buka file `koneksi_exemple.php` untuk melihat struktur template
2. Buat file baru bernama `koneksi.php` di root proyek
3. Salin isi `koneksi_exemple.php` dan sesuaikan dengan konfigurasi Anda:

```php
date_default_timezone_set("Asia/Jakarta");

$servername = "";  // Alamat server database
$username   = "";  // Username MySQL Anda
$password   = "";  // Password MySQL Anda
$db         = ""; // Nama database

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!defined('GEMINI_API_KEY')) {
    define('GEMINI_API_KEY', 'your_gemini_api_key_here'); // Ganti dengan API Key Anda
}
```

4. Dapatkan API Key dari [Google AI Studio](https://aistudio.google.com/)
5. Ganti `'your_gemini_api_key_here'` dengan API Key yang sudah Anda dapatkan

**Catatan Keamanan:**

- File `koneksi.php` sudah di-ignore dalam `.gitignore` dan tidak akan di-push ke repository
- Jangan pernah share file `koneksi.php` Anda ke publik atau GitHub

### 5. Jalankan Aplikasi

1. Pastikan XAMPP sudah berjalan (Apache & MySQL)
2. Buka browser dan akses: `http://localhost/capstone`
3. Lakukan login dengan akun Anda

---

## Penggunaan Aplikasi

### Sebagai Pengguna

1. **Buat Artikel** - Navigasi ke menu artikel dan buat artikel baru
2. **Upload Foto** - Gunakan fitur galeri untuk mengunggah foto
3. **Edit Konten** - Edit atau hapus artikel/foto yang sudah dibuat
4. **Gunakan AI Helper** - Manfaatkan AI untuk bantuan penulisan

### Sebagai Admin

1. **Kelola Pengguna** - Tambah, edit, atau hapus akun pengguna
2. **Moderasi Konten** - Tinjau dan kelola semua konten di sistem

---

## Struktur Database

Proyek ini menggunakan 3 tabel utama:

| Tabel     | Deskripsi                          |
| --------- | ---------------------------------- |
| `users`   | Data pengguna dan kredensial login |
| `article` | Artikel dan jurnal harian          |
| `gallery` | Data galeri foto dengan metadata   |

---

## Arsitektur Sistem

Proyek ini menggunakan pola arsitektur **MVC (Model-View-Controller)** sederhana dengan PHP Native:

- **Model**: `koneksi.php` (Manajemen Database)
- **View**: `gallery_data.php`, `index.php` (Antarmuka Pengguna)
- **Controller**: `admin.php`, `ai_helper.php` (Logika Bisnis & API)

### Alur Proses Fitur AI

**Alur Proses:**

1. **User Action**: Pengguna memilih foto di menu Tambah Gallery dan menekan tombol "Generate Deskripsi & Kategori (AI)"

2. **AJAX Request**: JavaScript (jQuery) mengambil file gambar dari input form dan mengirimkannya ke server (`ai_helper.php`) secara asynchronous (tanpa reload halaman)

3. **Secure Processing** (`ai_helper.php`):

   - **Validasi Sesi**: Mengecek apakah user adalah admin yang sah
   - **Encoding**: Mengubah gambar binary menjadi format Base64
   - **API Call**: Mengirim request POST ke Google Gemini API (Model: `gemini-flash-latest`) menggunakan cURL
   - **Prompting**: Mengirim instruksi untuk deskripsi dan kategori

4. **JSON Response**: Google mengembalikan teks, PHP memparsing hasilnya, dan mengirimkan kembali ke browser:

```json
{
  "status": "success",
  "deskripsi": "Suasana belajar di kelas yang kondusif.",
  "kategori": "Pendidikan"
}
```

5. **Auto-Fill**: JavaScript menerima data JSON dan otomatis mengisi kolom input form

---

## Teknologi & Library

- **Backend**: PHP 8.2 (Native), cURL Extension
- **Database**: MYSQL Ver 15.1 Distrib 10.4.32-MariaDB,
- **Frontend**: Bootstrap 5.3 (Responsive UI), jQuery 3.7 (AJAX Handling)
- **AI Service**: Google Gemini 1.5 Flash

### Security Features

- **Session Management**: Proteksi akses file PHP
- **Prepared Statements**: Mencegah SQL Injection pada login & CRUD
- **.env**: Penyimpanan API Key yang aman

---

## Panduan Penggunaan Fitur AI

### Cara Menggunakan Auto-Caption & Kategori

#### 1. Login ke Dashboard

- Akses halaman `/login.php`
- Masuk menggunakan akun admin Anda

#### 2. Buka Menu Gallery

- Klik navigasi Gallery di bagian atas dashboard

#### 3. Upload Foto

- Klik tombol biru "Tambah Gallery"
- Pilih foto yang ingin diunggah (Format: JPG/PNG)

#### 4. Gunakan Magic AI ✨

- Biarkan kolom Deskripsi dan Kategori kosong
- Klik tombol berwarna kuning: "Generate Deskripsi & Kategori (AI)"
- Tunggu beberapa detik hingga muncul notifikasi "Sukses! AI telah memberikan saran"
- Kolom akan terisi otomatis sesuai isi gambar (misal: gambar bola → Kategori: Olahraga)

#### 5. Simpan & Publikasi

- Anda bisa mengedit teks hasil AI jika diperlukan
- Klik Simpan. Foto akan langsung tampil di halaman depan (`index.php`) pada bagian Carousel
