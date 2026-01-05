<?php
  include("koneksi.php")
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Daily Journal</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" />
    <link rel="icon" href="img/logo.jpeg" />

    <style>
      .gelap {
        background-color: rgb(43, 48, 53);
      }

      .gelapBody {
        background-color: rgb(33, 37, 41);
      }

      .biru {
        background-color: rgb(3, 22, 51);
      }

      .abu {
        background-color: rgb(39, 43, 47);
      }

      .abutipis {
        background-color: rgb(168, 174, 169);
      }
    </style>
  </head>
  <body>
    <!-- nav begin -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top" id="nav">
      <div class="container">
        <a class="navbar-brand" href="#">My Daily Journal</a>
        <button
          id="toggler"
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0 text-dark">
            <li class="nav-item">
              <a class="nav-link" href="#">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#article">Article</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#gallery">Gallery</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#schedule">Schedule</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#profile">Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php" target="_blank">Login</a>
            </li>

            <li class="nav-item d-flex align-items-center">
              <button class="btn" id="sun">
                <i class="bi bi-sun-fill h4"></i>
              </button>
              <button class="btn" id="moon">
                <i class="bi bi-moon-fill h4"></i>
              </button>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- nav end -->
    <!-- hero begin -->
    <section id="hero" class="text-center p-5 bg-primary-subtle text-sm-start">
      <div class="container">
        <div class="d-sm-flex flex-sm-row-reverse align-items-center">
          <img src="img/banner.jpg" class="img-fluid" width="300" />
          <div>
            <h1 class="fw-bold display-4">Buat Cerita, Simpan Cerita,Setiap Hari</h1>
            <h4 class="lead display-6">Mencatat semua kegiatan sehari-hari yang ada tanpa terkecuali</h4>
            <h6>
              <span id="tanggal"></span>
              <span id="jam"></span>
            </h6>
          </div>
        </div>
      </div>
    </section>
    <!-- hero end -->
    <!-- article begin -->
    <section id="article" class="text-center p-5">
      <div class="container">
        <h1 class="fw-bold display-4 pb-3">article</h1>
        <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
          <?php
          $sql = "SELECT * FROM article ORDER BY tanggal DESC";
          $hasil = $conn->query($sql);

          while($row = $hasil->fetch_assoc()){
          ?>
            <div class="col">
              <div class="card h-100">
                <img src="img/<?= $row["gambar"]?>" class="card-img-top" alt="..." />
                <div class="card-body">
                  <h5 class="card-title"><?= $row["judul"]?></h5>
                  <p class="card-text">
                    <?= $row["isi"]?>
                  </p>
                </div>
                <div class="card-footer">
                  <small class="text-body-secondary">
                    <?= $row["tanggal"]?>
                  </small>
                </div>
              </div>
            </div>
            <?php
          }
          ?>
        </div>
      </div>
    </section>
<!-- article end -->
    <!-- article end -->
    <!-- gallery begin -->
    <section id="gallery" class="text-center p-5 bg-primary-subtle">
      <div class="container">
        <h1 class="fw-bold display-4 pb-3">gallery</h1>
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
          <div class="carousel-inner">
            <?php
            // Ambil data gambar dari database
            $sql = "SELECT * FROM gallery ORDER BY tanggal DESC";
            $hasil = $conn->query($sql);

            // Logika agar item pertama aktif (syarat Bootstrap)
            $first = true;

            // Jika tabel kosong, tampilkan placeholder (opsional, biar tidak rusak)
            if ($hasil->num_rows == 0) {
                 echo '<div class="carousel-item active">
                         <img src="img/besar1a.jpg" class="d-block w-100" alt="Placeholder" />
                         <div class="carousel-caption d-none d-md-block">
                             <h5>Belum ada galeri</h5>
                             <p>Silakan upload foto di halaman admin.</p>
                         </div>
                       </div>';
            }

            while ($row = $hasil->fetch_assoc()) {
                // Set class 'active' hanya untuk gambar pertama
                $aktif = ($first) ? "active" : "";
                $first = false;
            ?>
              <div class="carousel-item <?= $aktif ?>">
                <img src="img/<?= $row['gambar'] ?>" class="d-block w-100" alt="<?= $row['kategori'] ?>" />

                <div class="carousel-caption bg-dark bg-opacity-50 rounded">
                   <h5><span class="badge text-bg-warning"><?= $row['kategori'] ?></span></h5>
                   <p><?= $row['deskripsi'] ?></p>
                </div>
              </div>
            <?php
            }
            ?>
          </div>

          <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      </div>
    </section>
    <!-- gallery end -->
    <!-- schedule begin -->
    <section id="schedule" class="text-center p-5">
      <div class="container">
        <h1 class="fw-bold display-4 pb-3">schedule</h1>
        <div class="row row-cols-1 row-cols-md-4 g-4 justify-content-center">
          <div class="col">
            <div class="card border-primary mx-auto" style="width: 18rem; height: 14rem">
              <div class="card-header bg-primary text-white fw-bold">Senin</div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item border-0">
                  <strong>09.30-12.00</strong>
                  <br />
                  Probabilitas dan Statistik
                  <br />
                  Ruang H.5.11
                </li>
                <li class="list-group-item border-0">
                  <strong>15.30-18.00</strong>
                  <br />
                  Logika Informatika
                  <br />
                  Ruang H.3.9
                </li>
              </ul>
            </div>
          </div>
          <div class="col">
            <div class="card border-success mx-auto" style="width: 18rem; height: 14rem">
              <div class="card-header bg-success text-white fw-bold">Selasa</div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item border-0">
                  <strong>10.20-12.00</strong>
                  <br />
                  Basis Data
                  <br />
                  Ruang D.2.K
                </li>
                <li class="list-group-item border-0">
                  <strong>12.30-14.10</strong>
                  <br />
                  Pemprogaman Berbasis Web
                  <br />
                  Ruang D.2.J
                </li>
              </ul>
            </div>
          </div>
          <div class="col">
            <div class="card border-danger mx-auto" style="width: 18rem; height: 14rem">
              <div class="card-header bg-danger text-white fw-bold">Rabu</div>
              <ul class="list-group">
                <li class="list-group-item border-0">
                  <strong>09.30-12.00</strong>
                  <br />
                  Rekayasa Perangkat Lunak
                  <br />
                  Ruang H.3.10
                </li>
                <li class="list-group-item border-0">
                  <strong>12.30-15.00</strong>
                  <br />
                  Kriptografi
                  <br />
                  Ruang H.5.9
                </li>
              </ul>
            </div>
          </div>
          <div class="col">
            <div class="card border-warning mx-auto" style="width: 18rem; height: 14rem">
              <div class="card-header bg-warning text-white fw-bold">Kamis</div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item border-0">
                  <strong>10.20-12.00</strong>
                  <br />
                  Basis Data
                  <br />
                  Ruang H.5.6
                </li>
                <li class="list-group-item border-0">
                  <strong>12.30-15.00</strong>
                  <br />
                  Sistem Operasi
                  <br />
                  H.3.10
                </li>
              </ul>
            </div>
          </div>
          <div class="col">
            <div class="card border-info mx-auto" style="width: 18rem; height: 14rem">
              <div class="card-header bg-info text-white fw-bold">Jumat</div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item border-0">
                  <strong>12.30-15.00</strong>
                  <br />
                  Penambangan Data
                  <br />
                  Ruang H.4.3
                </li>
              </ul>
            </div>
          </div>
          <div class="col">
            <div class="card border-secondary mx-auto" style="width: 18rem; height: 14rem">
              <div class="card-header bg-secondary text-white fw-bold">Sabtu</div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item border-0">Tidak ada jadwal</li>
              </ul>
            </div>
          </div>
          <div class="col">
            <div class="card border-dark mx-auto" style="width: 18rem; height: 14rem">
              <div class="card-header bg-dark text-white fw-bold">Minggu</div>
              <ul class="list-group list-group-flush">
                <li class="list-group-item border-0">Tidak ada jadwal</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- schedule end -->
    <!-- profile begin -->
   <section id="profile" class="p-5 bg-primary-subtle">
  <div class="container">
    <h1 class="text-center fw-bold display-4 pb-3">Profil Kelompok</h1>

    <div id="carouselProfile" class="carousel slide" data-bs-ride="carousel">

      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselProfile" data-bs-slide-to="0" class="active bg-dark" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselProfile" data-bs-slide-to="1" class="bg-dark" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselProfile" data-bs-slide-to="2" class="bg-dark" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#carouselProfile" data-bs-slide-to="3" class="bg-dark" aria-label="Slide 4"></button>
      </div>

      <div class="carousel-inner pb-5">

        <div class="carousel-item active">
          <div class="card border-0 shadow rounded-4 m-auto" style="max-width: 800px;">
            <div class="card-body p-4">
              <div class="row align-items-center">
                <div class="col-md-4 text-center">
                  <img src="img/fara.jpg" class="img-fluid rounded-circle border border-3 border-primary" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="col-md-8">
                  <h3 class="fw-bold text-primary mt-3 mt-md-0">Syakira Fara Salsabila</h3>
                  <p class="text-muted badge bg-primary-subtle text-primary-emphasis">Ketua Kelompok</p>
                  <hr>
                  <div class="row mb-1">
                    <div class="col-4 fw-bold">NIM</div>
                    <div class="col-8">: A11.2024.15594</div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-4 fw-bold">Prodi</div>
                    <div class="col-8">: Teknik Informatika</div>
                  </div>
                   <div class="mt-3">
                      <a href="https://github.com/syakirafara" target="_blank" class="text-dark me-2"><i class="bi bi-github fs-4"></i></a>
                      <a href="https://www.linkedin.com/in/syakira-fara-salsabila-61a5a8268" target="_blank" class="text-primary me-2"><i class="bi bi-linkedin fs-4"></i></a>
                   </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="carousel-item">
          <div class="card border-0 shadow rounded-4 m-auto" style="max-width: 800px;">
            <div class="card-body p-4">
              <div class="row align-items-center">
                <div class="col-md-4 text-center">
                  <img src="img/aldo.jpg" class="img-fluid rounded-circle border border-3 border-warning" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="col-md-8">
                  <h3 class="fw-bold text-warning mt-3 mt-md-0">Muhammad Aldo Toni S</h3>
                  <p class="text-muted badge bg-warning-subtle text-warning-emphasis">Anggota Tim</p>
                  <hr>
                  <div class="row mb-1">
                    <div class="col-4 fw-bold">NIM</div>
                    <div class="col-8">: A11.2024.15664</div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-4 fw-bold">Prodi</div>
                    <div class="col-8">: Teknik Informatika</div>
                  </div>
                   <div class="mt-3">
                      <a href="https://github.com/AldoToni1" target="_blank" class="text-dark me-2"><i class="bi bi-github fs-4"></i></a>
                      <a href="https://www.linkedin.com/in/muhammadaldotonisaputra/" target="_blank" class="text-primary me-2"><i class="bi bi-linkedin fs-4"></i></a>
                   </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="carousel-item">
          <div class="card border-0 shadow rounded-4 m-auto" style="max-width: 800px;">
            <div class="card-body p-4">
              <div class="row align-items-center">
                <div class="col-md-4 text-center">
                  <img src="img/ihsan.jpg" class="img-fluid rounded-circle border border-3 border-warning" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="col-md-8">
                  <h3 class="fw-bold text-warning mt-3 mt-md-0">Firdaus Ihsan Fadhila</h3>
                  <p class="text-muted badge bg-warning-subtle text-warning-emphasis">Anggota Tim</p>
                  <hr>
                  <div class="row mb-1">
                    <div class="col-4 fw-bold">NIM</div>
                    <div class="col-8">: A11.2024.15735</div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-4 fw-bold">Prodi</div>
                    <div class="col-8">: Teknik Informatika</div>
                  </div>
                   <div class="mt-3">
                      <a href="https://github.com/isann2935" target="_blank" class="text-dark me-2"><i class="bi bi-github fs-4"></i></a>
                      <a href="https://www.linkedin.com/in/ihsan-fi" target="_blank" class="text-primary me-2"><i class="bi bi-linkedin fs-4"></i></a>
                   </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="carousel-item">
          <div class="card border-0 shadow rounded-4 m-auto" style="max-width: 800px;">
            <div class="card-body p-4">
              <div class="row align-items-center">
                <div class="col-md-4 text-center">
                  <img src="img/mahdanaa.jpg" class="img-fluid rounded-circle border border-3 border-warning" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <div class="col-md-8">
                  <h3 class="fw-bold text-warning mt-3 mt-md-0">M Muhyidin Ali Mahdana</h3>
                  <p class="text-muted badge bg-warning-subtle text-warning-emphasis">Anggota Tim</p>
                  <hr>
                  <div class="row mb-1">
                    <div class="col-4 fw-bold">NIM</div>
                    <div class="col-8">: A11.2024.15591</div>
                  </div>
                  <div class="row mb-1">
                    <div class="col-4 fw-bold">Prodi</div>
                    <div class="col-8">: Teknik Informatika</div>
                  </div>
                   <div class="mt-3">
                      <a href="https://github.com/Mahdanaa" target="_blank" class="text-dark me-2"><i class="bi bi-github fs-4"></i></a>
                      <a href="https://www.linkedin.com/in/mahdana" target="_blank" class="text-primary me-2"><i class="bi bi-linkedin fs-4"></i></a>
                   </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#carouselProfile" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselProfile" data-bs-slide="next">
        <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>

    </div>
  </div>
</section>
    <!-- profile end -->
    <!-- footer begin -->
    <footer class="text-center p-5 bg-body-tertiary">
    <div class="container">
        <div class="row">
            <div class="col">
                <p class="text-muted">Created by Group 2
                  <br>
                    <span class="fw-bold text-primary">Dian Nuswantoro University</span>
                </p>
            </div>
        </div>
    </div>
</footer>
    <!-- footer end -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"
    ></script>
    <script type="text/javascript">
      window.setTimeout('tampilWaktu()', 1000);
      let tanggal = document.getElementById('tanggal');
      let jam = document.getElementById('jam');

      function tampilWaktu() {
        let waktu = new Date();
        let bulan = waktu.getMonth() + 1;
        setTimeout('tampilWaktu()', 1000);
        tanggal.innerHTML = waktu.getDate() + '/' + bulan + '/' + waktu.getFullYear();
        jam.innerHTML = waktu.getHours() + ':' + waktu.getMinutes() + ':' + waktu.getSeconds();
      }

      const sun = document.getElementById('sun');
      const moon = document.getElementById('moon');
      const nav = document.getElementById('nav');
      const navA = document.querySelectorAll('nav a');
      const hero = document.querySelector('#hero');
      const article = document.querySelector('#article');
      const articleCard = document.querySelectorAll('#article .card');
      const articleCardh5 = document.querySelectorAll('#article .card h5');
      const articleCardP = document.querySelectorAll('#article .card p');
      const articleFooter = document.querySelectorAll('#article .card .card-footer');
      const articleSmall = document.querySelectorAll('#article .card .card-footer small');
      const gallery = document.querySelector('#gallery');
      const footerIcon = document.querySelectorAll('footer div a i');
      const footerText = document.querySelector('footer div:last-child');
      const schedule = document.querySelector('#schedule');
      const profile = document.querySelector('#profile');
      const toggler = document.getElementById('toggler');

      //dark mode
      moon.addEventListener('click', function () {
        nav.classList.remove('bg-body-tertiary');
        nav.classList.add('gelap');
        document.body.classList.add('gelapBody');

        navA.forEach(function (a) {
          a.classList.add('text-light');
        });

        sun.classList.add('text-light');
        moon.classList.add('text-light');

        hero.classList.remove('bg-primary-subtle');
        hero.classList.add('biru');
        hero.classList.add('text-light');

        article.classList.add('text-light');
        articleCard.forEach(function (c) {
          c.classList.add('gelap');
          c.classList.add('border-light-subtle');
        });
        articleCardh5.forEach(function (h5) {
          h5.classList.add('text-light');
        });
        articleCardP.forEach(function (p) {
          p.classList.add('text-light');
        });
        articleSmall.forEach(function (s) {
          s.classList.remove('text-body-secondary');
          s.classList.add('text-white-50');
        });

        gallery.classList.add('text-light');
        gallery.classList.remove('bg-primary-subtle');
        gallery.classList.add('biru');

        schedule.classList.add('text-light');
        schedule.classList.remove('bg-primary-subtle');

        profile.classList.add('text-light');
        profile.classList.remove('bg-primary-subtle');
        profile.classList.add('biru');

        footerText.classList.add('text-light');
        footerIcon.forEach(function (i) {
          i.classList.remove('text-dark');
          i.classList.add('text-light');
        });

        toggler.classList.add('navbar-dark');

      });

      // light mode
      sun.addEventListener('click', function () {
        nav.classList.add('bg-body-tertiary');
        nav.classList.remove('gelap');
        document.body.classList.remove('gelapBody');
        navA.forEach(function (a) {
          a.classList.remove('text-light');
        });

        sun.classList.remove('text-light');
        moon.classList.remove('text-light');

        hero.classList.add('bg-primary-subtle');
        hero.classList.remove('biru');
        hero.classList.remove('text-light');

        article.classList.remove('text-light');
        articleCard.forEach(function (c) {
          c.classList.remove('gelap');
          c.classList.remove('border-light-subtle');
        });
        articleCardh5.forEach(function (h5) {
          h5.classList.remove('text-light');
        });
        articleCardP.forEach(function (p) {
          p.classList.remove('text-light');
        });
        articleFooter.forEach(function (b) {
          b.classList.add('border-light-subtle');
        });
        articleSmall.forEach(function (s) {
          s.classList.remove('text-white-50');
          s.classList.add('text-body-secondary');
        });

        gallery.classList.remove('text-light');
        gallery.classList.add('bg-primary-subtle');
        gallery.classList.remove('biru');

        schedule.classList.remove('text-light');

        profile.classList.remove('text-light');
        profile.classList.add('bg-primary-subtle');
        profile.classList.remove('biru');

        footerText.classList.remove('text-light');
        footerIcon.forEach(function (icon) {
          icon.classList.remove('text-light');
          icon.classList.add('text-dark');
        });
      });
    </script>
  </body>
</html>
