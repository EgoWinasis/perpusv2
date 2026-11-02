
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

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">

                    <div class="box-header with-border">

                        <a href="<?= base_url('cetak/jumlah_buku'); ?>" target="_blank">
                            <button type="button" class="btn btn-warning">
                                <i class="fa fa-print"></i> Data Jumlah Buku
                            </button>
                        </a>

                    </div>

                    <!-- FILTER LAPORAN -->
                    <div class="box-body" style="border-bottom:1px solid #f4f4f4;">

                        <h4>
                            <i class="fa fa-filter"></i> Filter Laporan Jumlah Buku
                        </h4>

                        <form action="<?= base_url('cetak/jumlah_buku'); ?>"
                              method="GET"
                              target="_blank">

                            <div class="row">

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
                                        <label>Kategori</label>

                                        <input
                                            type="text"
                                            name="kategori"
                                            class="form-control"
                                            placeholder="Cari kategori"
                                            value="<?= html_escape($this->input->get('kategori', TRUE)); ?>">
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

                            </div>

                            <button type="submit" class="btn btn-warning">
                                <i class="fa fa-print"></i> Filter & Cetak Laporan
                            </button>

                            <button type="reset" class="btn btn-default" id="resetFilter">
                                <i class="fa fa-refresh"></i> Reset
                            </button>

                            <small class="text-muted" style="display:block; margin-top:8px;">
                                Kosongkan semua filter untuk mencetak seluruh data jumlah buku.
                            </small>

                        </form>

                    </div>

                    <!-- TABEL DATA JUMLAH BUKU -->
                    <div class="box-body">

                        <br />

                        <div class="table-responsive">

                            <table id="example1"
                                   class="table table-bordered table-striped"
                                   width="100%">

                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Penerbit</th>
                                        <th>Tahun Buku</th>
                                        <th>Jumlah Total Buku</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $no = 1;

                                    // Menghindari query kategori dan jumlah berulang.
                                    // Data dikelompokkan berdasarkan kombinasi judul,
                                    // kategori, penerbit, dan tahun buku.
                                    $daftarBuku = [];

                                    foreach ($buku->result_array() as $isi) {

                                        $key = implode('|', [
                                            $isi['title'],
                                            $isi['id_kategori'],
                                            $isi['penerbit'],
                                            $isi['thn_buku']
                                        ]);

                                        if (!isset($daftarBuku[$key])) {
                                            $daftarBuku[$key] = [
                                                'title' => $isi['title'],
                                                'id_kategori' => $isi['id_kategori'],
                                                'penerbit' => $isi['penerbit'],
                                                'thn_buku' => $isi['thn_buku'],
                                                'jumlah' => 0
                                            ];
                                        }

                                        $daftarBuku[$key]['jumlah'] += (int) $isi['jml'];
                                    }

                                    foreach ($daftarBuku as $isi) {

                                        // Ambil nama kategori dari data yang sudah tersedia
                                        // jika controller menyertakan nama_kategori.
                                        $namaKategori = isset($isi['nama_kategori'])
                                            ? $isi['nama_kategori']
                                            : '';
                                    ?>

                                        <tr>
                                            <td><?= $no; ?></td>

                                            <td>
                                                <?= html_escape($isi['title']); ?>
                                            </td>

                                            <td>
                                                <?= html_escape($namaKategori); ?>
                                            </td>

                                            <td>
                                                <?= html_escape($isi['penerbit']); ?>
                                            </td>

                                            <td>
                                                <?= html_escape($isi['thn_buku']); ?>
                                            </td>

                                            <td>
                                                <?= number_format($isi['jumlah'], 0, ',', '.'); ?>
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

        // Reset seluruh filter.
        $('#resetFilter').on('click', function () {

            var form = $(this).closest('form');

            setTimeout(function () {
                form.find('input').val('');
            }, 0);

        });

    });
</script>
