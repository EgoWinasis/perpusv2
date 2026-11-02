
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-edit" style="color:green"></i>
            <?= html_escape($title_web); ?>
        </h1>

        <ol class="breadcrumb">
            <li>
                <a href="<?= base_url('dashboard'); ?>">
                    <i class="fa fa-dashboard"></i>&nbsp; Dashboard
                </a>
            </li>
            <li class="active">
                <i class="fa fa-file-text"></i>&nbsp;
                <?= html_escape($title_web); ?>
            </li>
        </ol>
    </section>

    <section class="content">

        <?php
        if (!empty($this->session->flashdata())) {
            echo $this->session->flashdata('pesan');
        }
        ?>

        <?php $this->session->set_userdata('pesan', null); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-header with-border">

                        <?php if ($this->session->userdata('level') == 'Petugas') { ?>
                            <a href="<?= base_url('data/digitaltambah'); ?>">
                                <button type="button" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Tambah Buku
                                </button>
                            </a>
                        <?php } ?>

                        <a href="<?= base_url('cetak/buku_digital'); ?>" target="_blank">
                            <button type="button" class="btn btn-warning">
                                <i class="fa fa-print"></i> Buku Digital
                            </button>
                        </a>

                    </div>

                    <!-- FILTER LAPORAN BUKU DIGITAL -->
                    <div class="box-body" style="border-bottom:1px solid #f4f4f4;">

                        <h4>
                            <i class="fa fa-filter"></i> Filter Laporan Buku Digital
                        </h4>

                        <form action="<?= base_url('cetak/buku_digital'); ?>"
                              method="GET"
                              target="_blank">

                            <div class="row">

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label>Kode Buku</label>
                                        <input
                                            type="text"
                                            name="kode_buku"
                                            class="form-control"
                                            placeholder="Cari kode buku"
                                            value="<?= html_escape($this->input->get('kode_buku', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label>ISBN</label>
                                        <input
                                            type="text"
                                            name="isbn"
                                            class="form-control"
                                            placeholder="Cari ISBN"
                                            value="<?= html_escape($this->input->get('isbn', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label>Judul Buku</label>
                                        <input
                                            type="text"
                                            name="title"
                                            class="form-control"
                                            placeholder="Cari judul buku"
                                            value="<?= html_escape($this->input->get('title', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label>Penerbit</label>
                                        <input
                                            type="text"
                                            name="penerbit"
                                            class="form-control"
                                            placeholder="Cari penerbit"
                                            value="<?= html_escape($this->input->get('penerbit', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label>Tahun Buku</label>
                                        <input
                                            type="text"
                                            name="thn_buku"
                                            class="form-control"
                                            placeholder="Contoh: 2024"
                                            value="<?= html_escape($this->input->get('thn_buku', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label>Tanggal Masuk Dari</label>
                                        <input
                                            type="date"
                                            name="tanggal_mulai"
                                            class="form-control"
                                            value="<?= html_escape($this->input->get('tanggal_mulai', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label>Tanggal Masuk Sampai</label>
                                        <input
                                            type="date"
                                            name="tanggal_selesai"
                                            class="form-control"
                                            value="<?= html_escape($this->input->get('tanggal_selesai', TRUE)); ?>">
                                    </div>
                                </div>

                            </div>

                            <button type="submit" class="btn btn-warning">
                                <i class="fa fa-print"></i> Filter & Cetak Laporan
                            </button>

                            <button type="reset" class="btn btn-default" id="resetFilter">
                                <i class="fa fa-refresh"></i> Reset
                            </button>

                            <small class="text-muted" style="display:block; margin-top:8px;">
                                Kosongkan semua filter untuk mencetak seluruh data buku digital.
                            </small>

                        </form>

                    </div>

                    <!-- TABEL DATA BUKU DIGITAL -->
                    <div class="box-body">
                        <br />

                        <div class="table-responsive">

                            <table id="example1"
                                   class="table table-bordered table-striped"
                                   width="100%">

                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Buku</th>
                                        <th>ISBN</th>
                                        <th>Judul</th>
                                        <th>Penerbit</th>
                                        <th>Tahun Buku</th>
                                        <th>Hal</th>
                                        <th>Sampul</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $no = 1;

                                    foreach ($buku->result_array() as $isi) {
                                    ?>

                                        <tr>
                                            <td><?= $no; ?></td>

                                            <td><?= html_escape($isi['kode_buku']); ?></td>

                                            <td><?= html_escape($isi['isbn']); ?></td>

                                            <td><?= html_escape($isi['title']); ?></td>

                                            <td><?= html_escape($isi['penerbit']); ?></td>

                                            <td><?= html_escape($isi['thn_buku']); ?></td>

                                            <td>
                                                <?= html_escape($isi['total_hal']); ?> hal
                                            </td>

                                            <td>
                                                <img
                                                    src="<?= base_url('assets_style/image/buku/lampiran/' . rawurlencode($isi['lampiran']) . '/1.png'); ?>"
                                                    width="50"
                                                    alt="Sampul Buku">
                                            </td>

                                            <td><?= html_escape($isi['tgl_masuk']); ?></td>

                                            <td <?php if ($this->session->userdata('level') == 'Petugas') { ?>
                                                    style="width:17%;"
                                                <?php } ?>>

                                                <?php if ($this->session->userdata('level') == 'Petugas') { ?>

                                                    <a href="<?= base_url('data/digitaledit/' . $isi['id_buku']); ?>">
                                                        <button type="button" class="btn btn-success">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    </a>

                                                    <a href="<?= base_url('data/digitaldetail/' . $isi['id_buku']); ?>">
                                                        <button type="button" class="btn btn-primary">
                                                            <i class="fa fa-sign-in"></i> Detail
                                                        </button>
                                                    </a>

                                                    <a
                                                        href="<?= base_url('data/prosesdigital?buku_id=' . $isi['id_buku']); ?>"
                                                        onclick="return confirm('Anda yakin Buku ini akan dihapus ?');">

                                                        <button type="button" class="btn btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </a>

                                                <?php } else { ?>

                                                    <a href="<?= base_url('data/digitaldetail/' . $isi['id_buku']); ?>">
                                                        <button type="button" class="btn btn-primary">
                                                            <i class="fa fa-sign-in"></i> Detail
                                                        </button>
                                                    </a>

                                                <?php } ?>

                                            </td>
                                        </tr>

                                    <?php
                                        $no++;
                                    }
                                    ?>

                                </tbody>

                            </table>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </section>
</div>

<script>
    $(function () {

        // Reset filter.
        $('#resetFilter').on('click', function () {
            var form = $(this).closest('form');

            setTimeout(function () {
                form.find('input').val('');
            }, 0);
        });

    });
</script>
