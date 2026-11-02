
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-book" style="color:green"></i>
            <?= $title_web; ?>
        </h1>

        <ol class="breadcrumb">
            <li>
                <a href="<?= base_url('dashboard'); ?>">
                    <i class="fa fa-dashboard"></i>&nbsp; Dashboard
                </a>
            </li>
            <li class="active">
                <i class="fa fa-file-text"></i>&nbsp; <?= $title_web; ?>
            </li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">

                    <div class="box-header with-border">
                        <a href="<?= base_url('cetak/pinjam_digital?' . http_build_query($this->input->get())); ?>"
                           target="_blank"
                           class="btn btn-warning">
                            <i class="fa fa-print"></i> Riwayat Baca
                        </a>

                        <a href="<?= current_url(); ?>" class="btn btn-default">
                            <i class="fa fa-refresh"></i> Reset Filter
                        </a>
                    </div>

                    <div class="box-body">

                        <!-- FORM FILTER -->
                        <form method="GET" action="<?= current_url(); ?>">
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Kode Anggota</label>
                                        <input type="text"
                                               name="anggota_id"
                                               class="form-control"
                                               placeholder="Masukkan kode anggota"
                                               value="<?= html_escape($this->input->get('anggota_id')); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Nama Anggota</label>
                                        <input type="text"
                                               name="nama"
                                               class="form-control"
                                               placeholder="Masukkan nama anggota"
                                               value="<?= html_escape($this->input->get('nama')); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Kode Buku</label>
                                        <input type="text"
                                               name="kode"
                                               class="form-control"
                                               placeholder="Masukkan kode buku"
                                               value="<?= html_escape($this->input->get('kode')); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Judul Buku</label>
                                        <input type="text"
                                               name="title"
                                               class="form-control"
                                               placeholder="Masukkan judul buku"
                                               value="<?= html_escape($this->input->get('title')); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tanggal Mulai</label>
                                        <input type="date"
                                               name="tanggal_mulai"
                                               class="form-control"
                                               value="<?= html_escape($this->input->get('tanggal_mulai')); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tanggal Selesai</label>
                                        <input type="date"
                                               name="tanggal_selesai"
                                               class="form-control"
                                               value="<?= html_escape($this->input->get('tanggal_selesai')); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fa fa-search"></i> Cari Data
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </form>

                        <hr>

                        <!-- TABEL DATA -->
                        <div class="table-responsive">
                            <table id="example1"
                                   class="table table-bordered table-striped"
                                   width="100%">

                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Anggota</th>
                                        <th>Nama Anggota</th>
                                        <th>Kode Buku</th>
                                        <th>Judul Buku</th>
                                        <th>Tanggal Baca</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $no = 1;

                                    if (isset($pinjam) && $pinjam->num_rows() > 0) {
                                        foreach ($pinjam->result_array() as $isi) {
                                    ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= html_escape($isi['kode_anggota'] ?? '-'); ?></td>
                                                <td><?= html_escape($isi['nama'] ?? '-'); ?></td>
                                                <td><?= html_escape($isi['kode_buku'] ?? '-'); ?></td>
                                                <td><?= html_escape($isi['title'] ?? '-'); ?></td>
                                                <td><?= html_escape($isi['tgl_baca'] ?? '-'); ?></td>
                                            </tr>
                                    <?php
                                        }
                                    } else {
                                    ?>
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                Data riwayat baca tidak ditemukan.
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>

                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
