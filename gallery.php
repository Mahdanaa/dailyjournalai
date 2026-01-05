<?php
include "koneksi.php";
include "upload_foto.php";

if (isset($_POST['simpan'])) {
    $deskripsi = $_POST['deskripsi'];
    $kategori = $_POST['kategori'];
    $username = $_SESSION['username'];
    $gambar = '';
    $nama_gambar = $_FILES['gambar']['name'];

    // 1. Jika ada gambar baru, upload dulu
    if ($nama_gambar != '') {
        $cek_upload = upload_foto($_FILES["gambar"]);
        if ($cek_upload['status']) {
            $gambar = $cek_upload['message'];
        } else {
            echo "<script>alert('" . $cek_upload['message'] . "'); document.location='admin.php?page=gallery';</script>";
            die;
        }
    }

    // 2. Cek ID untuk Update atau Insert
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        if ($nama_gambar == '') {
            $gambar = $_POST['gambar_lama'];
        } else {
            // Hapus gambar lama jika ada gambar baru
            unlink("img/" . $_POST['gambar_lama']);
        }

        $stmt = $conn->prepare("UPDATE gallery SET gambar=?, deskripsi=?, kategori=?, username=? WHERE id=?");
        $stmt->bind_param("ssssi", $gambar, $deskripsi, $kategori, $username, $id);
        $simpan = $stmt->execute();
    } else {
        // INSERT
        $stmt = $conn->prepare("INSERT INTO gallery (gambar, deskripsi, kategori, username) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $gambar, $deskripsi, $kategori, $username);
        $simpan = $stmt->execute();
    }

    if ($simpan) {
        echo "<script>alert('Simpan data sukses'); document.location='admin.php?page=gallery';</script>";
    } else {
        echo "<script>alert('Simpan data gagal'); document.location='admin.php?page=gallery';</script>";
    }

    $stmt->close();
    $conn->close();
}

// Delete
if (isset($_POST['hapus'])) {
    $id = $_POST['id'];
    $gambar = $_POST['gambar'];

    if ($gambar != '') {
        unlink("img/" . $gambar);
    }

    $stmt = $conn->prepare("DELETE FROM gallery WHERE id =?");
    $stmt->bind_param("i", $id);
    $hapus = $stmt->execute();

    if ($hapus) {
        echo "<script>alert('Hapus data sukses'); document.location='admin.php?page=gallery';</script>";
    } else {
        echo "<script>alert('Hapus data gagal'); document.location='admin.php?page=gallery';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="container">
    <button type="button" class="btn btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg"></i> Tambah Gallery
    </button>

    <div class="row">
        <div class="table-responsive" id="gallery_data">
            </div>
    </div>

    <div class="modal fade" id="modalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Gallery</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="formGroupExampleInput2" class="form-label">Gambar</label>
                            <input type="file" class="form-control" name="gambar" id="inputFile" required>
                        </div>

                        <div class="mb-3">
                            <button type="button" id="btnGenerateAI" class="btn btn-warning btn-sm w-100">
                                <i class="bi bi-magic"></i> Generate Deskripsi & Kategori (AI)
                            </button>
                            <div id="loadingAI" class="text-center mt-2" style="display:none;">
                                <div class="spinner-border text-warning spinner-border-sm" role="status"></div>
                                <small>Sedang meminta saran AI...</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Kategori</label>
                            <input type="text" class="form-control" name="kategori" id="inputKategori" placeholder="Contoh: Hobi, Liburan, atau biarkan AI mengisi" required>
                        </div>
                        <div class="mb-3">
                            <label for="floatingTextarea2">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="inputDeskripsi" placeholder="Tulis deskripsi atau gunakan tombol AI di atas" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="submit" value="simpan" name="simpan" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // 1. Load Data Awal
    load_data();
    function load_data(hlm){
        $.ajax({
            url : "gallery_data.php",
            method : "POST",
            data : { hlm: hlm },
            success : function(data){
                    $('#gallery_data').html(data);
            }
        })
    }

    // 2. Pagination Click
    $(document).on('click', '.halaman', function(){
        var hlm = $(this).attr("id");
        load_data(hlm);
    });

    // 3. LOGIKA TOMBOL AI (JQuery)
    $('#btnGenerateAI').click(function(){
        var file_data = $('#inputFile').prop('files')[0];

        if (!file_data) {
            alert('Pilih gambar dulu ya, Sob!');
            return;
        }

        $('#loadingAI').show();
        $('#btnGenerateAI').prop('disabled', true);

        var form_data = new FormData();
        form_data.append('gambar', file_data);

        // Kirim ke ai_helper.php
        $.ajax({
            url: 'ai_helper.php',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response){
                // Sembunyikan loading
                $('#loadingAI').hide();
                $('#btnGenerateAI').prop('disabled', false);

                if(response.status === 'success') {
                    // Isi kolom otomatis!
                    $('#inputDeskripsi').val(response.deskripsi);
                    $('#inputKategori').val(response.kategori);
                    alert('Sukses! AI telah memberikan saran.');
                } else {
                    alert('Gagal: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                $('#loadingAI').hide();
                $('#btnGenerateAI').prop('disabled', false);
                console.error(xhr);
                alert('Terjadi kesalahan koneksi ke server.');
            }
        });
    });
});
</script>
