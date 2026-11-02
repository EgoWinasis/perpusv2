
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

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">

                    <!-- HEADER -->
                    <div class="box-header with-border">

                        <h3 class="box-title">
                            <i class="fa fa-filter"></i>
                            Filter Riwayat Peminjaman
                        </h3>

                    </div>

                    <!-- FILTER -->
                    <div class="box-body">

                        <form action="<?= base_url('cetak/pinjam_buku'); ?>"
                              method="GET"
                              target="_blank">

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Nomor Peminjaman</label>
                                        <input type="text"
                                               name="pinjam_id"
                                               class="form-control"
                                               placeholder="Masukkan nomor pinjam"
                                               value="<?= html_escape($this->input->get('pinjam_id', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Kode Anggota</label>
                                        <input type="text"
                                               name="anggota_id"
                                               class="form-control"
                                               placeholder="Masukkan kode anggota"
                                               value="<?= html_escape($this->input->get('anggota_id', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Nama Anggota</label>
                                        <input type="text"
                                               name="nama"
                                               class="form-control"
                                               placeholder="Masukkan nama anggota"
                                               value="<?= html_escape($this->input->get('nama', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Kode Buku</label>
                                        <input type="text"
                                               name="kode"
                                               class="form-control"
                                               placeholder="Masukkan kode buku"
                                               value="<?= html_escape($this->input->get('kode', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status Peminjaman</label>
                                        <select name="status" class="form-control">
                                            <option value="">-- Semua Status --</option>
                                            <option value="Dipinjam"
                                                <?= $this->input->get('status') == 'Dipinjam' ? 'selected' : ''; ?>>
                                                Dipinjam
                                            </option>
                                            <option value="Kembali"
                                                <?= $this->input->get('status') == 'Kembali' ? 'selected' : ''; ?>>
                                                Kembali
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tanggal Mulai Pinjam</label>
                                        <input type="date"
                                               name="tanggal_mulai"
                                               class="form-control"
                                               value="<?= html_escape($this->input->get('tanggal_mulai', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tanggal Selesai Pinjam</label>
                                        <input type="date"
                                               name="tanggal_selesai"
                                               class="form-control"
                                               value="<?= html_escape($this->input->get('tanggal_selesai', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fa fa-print"></i>
                                        Riwayat Pinjam
                                    </button>

                                    <a href="<?= base_url('cetak/pinjam_buku'); ?>"
                                       target="_blank"
                                       class="btn btn-default">
                                        <i class="fa fa-refresh"></i>
                                        Reset Filter
                                    </a>

                                    <a href="<?= base_url('cetak/total_pinjam_buku'); ?>"
                                       target="_blank"
                                       class="btn btn-primary">
                                        <i class="fa fa-print"></i>
                                        Total Pinjam
                                    </a>
                                </div>

                            </div>

                        </form>

                    </div>

                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-list"></i>
                            Daftar Riwayat Peminjaman
                        </h3>
                    </div>

                    <!-- TABEL DATA -->
                    <div class="box-body">

                        <div class="table-responsive">

                            <table id="example1"
                                   class="table table-bordered table-striped"
                                   width="100%">

                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Pinjam</th>
                                        <th>Kode Anggota</th>
                                        <th>Nama</th>
                                        <th>Kode Buku</th>
                                        <th>Pinjam</th>
                                        <th>Balik</th>
                                        <th style="width:10%">Status</th>
                                        <th>Kembali</th>
                                        <th>Denda</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $no = 1;

                                    foreach ($pinjam->result_array() as $isi) {

                                        $anggota_id = $isi['anggota_id'];
                                        $pinjam_id = $isi['pinjam_id'];

                                        // Data anggota
                                        $ang = $this->db
                                            ->get_where('tbl_siswa', [
                                                'kode_anggota' => $anggota_id
                                            ])
                                            ->row();

                                        // Data denda
                                        $denda = $this->db
                                            ->get_where('tbl_denda', [
                                                'pinjam_id' => $pinjam_id
                                            ]);

                                        $total_denda = $denda->row();
                                    ?>

                                    <tr>

                                        <td><?= $no; ?></td>

                                        <td><?= html_escape($isi['pinjam_id']); ?></td>

                                        <td><?= html_escape($isi['anggota_id']); ?></td>

                                        <td>
                                            <?= $ang ? html_escape($ang->nama) : '-'; ?>
                                        </td>

                                        <td><?= html_escape($isi['kode']); ?></td>

                                        <td><?= html_escape($isi['tgl_pinjam']); ?></td>

                                        <td><?= html_escape($isi['tgl_balik']); ?></td>

                                        <td>
                                            <?php
                                            if ($isi['status'] == 'Dipinjam') {
                                                echo '<span class="label label-warning">Dipinjam</span>';
                                            } elseif ($isi['status'] == 'Kembali') {
                                                echo '<span class="label label-success">Kembali</span>';
                                            } else {
                                                echo html_escape($isi['status']);
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            if ($isi['tgl_kembali'] == '0') {
                                                echo '<span style="color:red;">Belum dikembalikan</span>';
                                            } else {
                                                echo html_escape($isi['tgl_kembali']);
                                            }
                                            ?>
                                        </td>

                                        <!-- DENDA -->
                                        <td>
                                            <?php

                                            $jml = $this->db
                                                ->get_where('tbl_pinjam', [
                                                    'pinjam_id' => $pinjam_id
                                                ])
                                                ->num_rows();

                                            if ($denda->num_rows() > 0) {

                                                $s = $denda->row();

                                                echo $this->M_Admin->rp($s->denda);

                                            } else {

                                                $date1 = date('Ymd');

                                                $date2 = preg_replace(
                                                    '/[^0-9]/',
                                                    '',
                                                    $isi['tgl_balik']
                                                );

                                                $diff = (int) $date2 - (int) $date1;

                                                if ($diff >= 0 || $isi['tgl_kembali'] != '0') {

                                                    echo '<p style="color:green;">
                                                        Tidak Ada Denda
                                                    </p>';

                                                } else {

                                                    $dd = $this->M_Admin->get_tableid_edit(
                                                        'tbl_biaya_denda',
                                                        'stat',
                                                        'Aktif'
                                                    );

                                                    if ($dd) {

                                                        $nominalDenda = $jml * (
                                                            $dd->harga_denda * abs($diff)
                                                        );

                                                        echo '<p style="color:red;font-size:16px;">'
                                                            . $this->M_Admin->rp($nominalDenda)
                                                            . '</p>';

                                                        echo '<small>* Untuk '
                                                            . $jml
                                                            . ' Buku</small>';

                                                    } else {

                                                        echo '-';
                                                    }
                                                }
                                            }
                                            ?>
                                        </td>

                                        <!-- AKSI -->
                                        <td>

                                            <a href="<?= base_url('transaksi/detailpinjam/' . rawurlencode($isi['pinjam_id'])); ?>"
                                               class="btn btn-primary btn-sm"
                                               title="Detail Peminjaman">

                                                <i class="fa fa-eye"></i>

                                                <?php if ($this->session->userdata('level') != 'Petugas') { ?>
                                                    Detail
                                                <?php } ?>

                                            </a>

                                            <?php if ($this->session->userdata('level') == 'Petugas') { ?>

                                                <a href="<?= base_url('transaksi/prosespinjam?pinjam_id=' . rawurlencode($isi['pinjam_id'])); ?>"
                                                   onclick="return confirm('Anda yakin peminjaman ini akan dihapus?');"
                                                   class="btn btn-danger btn-sm"
                                                   title="Hapus Peminjaman">

                                                    <i class="fa fa-trash"></i>

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
