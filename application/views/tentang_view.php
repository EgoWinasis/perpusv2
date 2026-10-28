
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
if ($this->session->userdata('level') == 'Anggota') {
    redirect(base_url('transaksi'));
}
?>

<!-- Content Wrapper -->
<div class="content-wrapper">

    <!-- Content Header -->
    <section class="content-header">
        <h1>
            Tentang Kami
            <small>Informasi Perpustakaan</small>
        </h1>

        <ol class="breadcrumb">
            <li>
                <a href="#">
                    <i class="fa fa-dashboard"></i> Home
                </a>
            </li>
            <li class="active">Tentang Kami</li>
        </ol>
    </section>

    <!-- Main Content -->
    <section class="content">

        <!-- Profil Perpustakaan -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-university"></i>
                    Profil Perpustakaan
                </h3>
            </div>

            <div class="box-body">
                <p style="text-align: justify; line-height: 1.8;">
                    Perpustakaan SDN Getaskerep 01 merupakan fasilitas
                    pendidikan yang menyediakan berbagai bahan bacaan
                    dan sumber belajar bagi siswa. Perpustakaan ini
                    diharapkan dapat membantu meningkatkan minat baca,
                    menambah wawasan, serta mendukung kegiatan belajar
                    mengajar di lingkungan sekolah.
                </p>

                <p style="text-align: justify; line-height: 1.8;">
                    Untuk mendukung pengelolaan perpustakaan agar lebih
                    efektif dan terorganisasi, digunakan sistem informasi
                    perpustakaan yang membantu proses pengelolaan data
                    anggota, data buku, serta transaksi peminjaman dan
                    pengembalian buku.
                </p>
            </div>
        </div>

        <!-- Tujuan Perpustakaan -->
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-bullseye"></i>
                    Tujuan Perpustakaan
                </h3>
            </div>

            <div class="box-body">
                <p style="text-align: justify; line-height: 1.8;">
                    Perpustakaan memiliki tujuan untuk menyediakan sumber
                    belajar yang mudah diakses oleh siswa dan mendukung
                    terciptanya lingkungan belajar yang nyaman. Selain
                    itu, perpustakaan diharapkan dapat menumbuhkan
                    kebiasaan membaca sejak dini serta membantu siswa
                    memperoleh informasi yang bermanfaat.
                </p>

                <ul style="line-height: 2;">
                    <li>Meningkatkan minat dan kebiasaan membaca siswa.</li>
                    <li>Menyediakan sumber informasi dan bahan pembelajaran.</li>
                    <li>Mendukung kegiatan belajar mengajar di sekolah.</li>
                    <li>Meningkatkan ketertiban administrasi perpustakaan.</li>
                </ul>
            </div>
        </div>

        <!-- Layanan Perpustakaan -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-book"></i>
                    Layanan Perpustakaan
                </h3>
            </div>

            <div class="box-body">
                <div class="row">

                    <!-- Data Buku -->
                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-blue">
                                <i class="fa fa-book"></i>
                            </span>

                            <div class="info-box-content">
                                <span class="info-box-text">
                                    Data Buku
                                </span>
                                <span class="info-box-number"
                                      style="font-size: 13px;">
                                    Pengelolaan koleksi buku
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Data Anggota -->
                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-green">
                                <i class="fa fa-users"></i>
                            </span>

                            <div class="info-box-content">
                                <span class="info-box-text">
                                    Data Anggota
                                </span>
                                <span class="info-box-number"
                                      style="font-size: 13px;">
                                    Pengelolaan anggota perpustakaan
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Transaksi -->
                    <div class="col-md-4 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-yellow">
                                <i class="fa fa-exchange"></i>
                            </span>

                            <div class="info-box-content">
                                <span class="info-box-text">
                                    Transaksi
                                </span>
                                <span class="info-box-number"
                                      style="font-size: 13px;">
                                    Peminjaman dan pengembalian
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                <p style="text-align: justify; line-height: 1.8;">
                    Sistem informasi perpustakaan digunakan untuk membantu
                    petugas dalam mengelola data buku, data anggota,
                    serta transaksi peminjaman dan pengembalian. Dengan
                    adanya sistem ini, proses administrasi diharapkan
                    menjadi lebih terstruktur, efisien, dan mudah dipantau.
                </p>
            </div>
        </div>

        <!-- Tim Pengembang -->
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-code"></i>
                    Pengembang Sistem
                </h3>
            </div>

            <div class="box-body">

                <div class="row">
                    <div class="col-md-12">

                        <div class="box box-widget widget-user-2"
                             style="border: 1px solid #e5e5e5;
                                    border-radius: 8px;
                                    overflow: hidden;">

                            <!-- Header Profil -->
                            <div class="widget-user-header bg-blue"
                                 style="padding: 25px;">

                                <div class="widget-user-image">
                                    <div style="
                                        width: 70px;
                                        height: 70px;
                                        border-radius: 50%;
                                        background: rgba(255,255,255,0.2);
                                        display: flex;
                                        align-items: center;
                                        justify-content: center;
                                        border: 2px solid #fff;
                                    ">
                                        <i class="fa fa-user"
                                           style="font-size: 35px;
                                                  color: #fff;"></i>
                                    </div>
                                </div>

                                <h3 class="widget-user-username"
                                    style="font-weight: 700;">
                                    Ego Winasis, A.Md.T
                                </h3>

                                <h5 class="widget-user-desc">
                                    Pengembang Sistem Informasi Perpustakaan
                                </h5>
                            </div>

                            <!-- Informasi Pengembang -->
                            <div class="box-footer"
                                 style="padding: 20px;">

                                <p style="line-height: 1.8;
                                          text-align: justify;">
                                    Ego Winasis, A.Md.T merupakan lulusan
                                    D3 Teknik Komputer dari Politeknik
                                    Harapan Bersama. Pengembangan sistem
                                    informasi perpustakaan ini bertujuan
                                    untuk membantu pengelolaan data buku,
                                    data anggota, serta transaksi
                                    peminjaman dan pengembalian agar
                                    lebih terstruktur dan efisien.
                                </p>

                                <hr>

                                <p style="font-weight: 600;
                                          margin-bottom: 12px;">
                                    <i class="fa fa-address-card"></i>
                                    Profil dan Media Sosial
                                </p>

                                <a
                                    href="https://www.instagram.com/egowinasis/"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-danger"
                                    style="margin: 0 8px 8px 0;">

                                    <i class="fa fa-instagram"></i>
                                    Instagram
                                </a>

                                <a
                                    href="https://www.linkedin.com/in/ego-winasis-2178b2180"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="btn btn-primary"
                                    style="margin: 0 8px 8px 0;">

                                    <i class="fa fa-linkedin"></i>
                                    LinkedIn
                                </a>

                            </div>
                        </div>

                    </div>
                </div>

                <p style="text-align: justify; line-height: 1.8;">
                    Dengan bekal pengetahuan di bidang Teknik Komputer,
                    pengembang berupaya menghadirkan sistem informasi
                    yang mudah digunakan dan dapat membantu petugas
                    perpustakaan dalam menjalankan kegiatan administrasi
                    secara lebih efektif.
                </p>

            </div>
        </div>

        <!-- Komitmen -->
        <div class="callout callout-info">
            <h4>
                <i class="fa fa-heart"></i>
                Komitmen Kami
            </h4>

            <p style="text-align: justify; line-height: 1.8;">
                Melalui pengelolaan perpustakaan yang lebih baik,
                kami berharap perpustakaan SDN Getaskerep 01 dapat
                menjadi tempat belajar yang bermanfaat, meningkatkan
                budaya literasi, dan mendukung perkembangan pengetahuan
                siswa. Sistem informasi ini diharapkan dapat membantu
                proses administrasi perpustakaan menjadi lebih mudah,
                tertib, dan efisien.
            </p>
        </div>

    </section>
</div>
<!-- /.content -->
