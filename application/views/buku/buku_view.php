<?php if (!defined('BASEPATH'))
    exit('No direct script acess allowed'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-edit" style="color:green"> </i> <?= $title_web; ?>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('dashboard'); ?>">
                    <i class="fa fa-dashboard"></i>&nbsp; Dashboard
                </a>
            </li>
            <li class="active">
                <i class="fa fa-file-text"></i>&nbsp; <?= $title_web; ?>
            </li>
        </ol>
    </section>

    <section class="content">
        <?php if (!empty($this->session->flashdata())) {
            echo $this->session->flashdata('pesan');
        } ?>

        <?php $this->session->set_userdata('pesan', null); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">

                    <div class="box-header with-border">

                        <?php if ($this->session->userdata('level') == 'Petugas') { ?>
                            <a href="data/bukutambah">
                                <button class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Tambah Buku
                                </button>
                            </a>
                        <?php } ?>

                        <!-- Tombol Cetak Label -->
                        <button id="print_button" class="btn btn-success">
                            <i class="fa fa-print"></i> Cetak Label
                        </button>

                        <!-- Tombol Cetak QR Code -->
                        <button id="print_qrcode_button" class="btn btn-info">
                            <i class="fa fa-qrcode"></i> Cetak QR Code
                        </button>

                        <!-- Tombol Cetak Buku -->
                        <a href="<?php echo base_url('cetak/buku'); ?>" target="_blank">
                            <button class="btn btn-warning">
                                <i class="fa fa-print"></i> Buku
                            </button>
                        </a>

                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">
                        <br />

                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped table" width="100%">

                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Buku</th>
                                        <th>ISBN</th>
                                        <th>Judul</th>
                                        <th>Penerbit</th>
                                        <th>Tahun Buku</th>
                                        <th>Stok Buku</th>
                                        <th>Kondisi</th>
                                        <th>Tanggal Masuk</th>

                                        <th style="width: 50px;">
                                            Cetak Label
                                            <input class="form-check-input" type="checkbox" id="checkAll">
                                        </th>

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
                                            <td><?= $isi['kode_buku']; ?></td>
                                            <td><?= $isi['isbn']; ?></td>
                                            <td><?= $isi['title']; ?></td>
                                            <td><?= $isi['penerbit']; ?></td>
                                            <td><?= $isi['thn_buku']; ?></td>
                                            <td><?= $isi['jml']; ?></td>
                                            <td><?= $isi['kondisi']; ?></td>
                                            <td><?= $isi['tgl_masuk']; ?></td>

                                            <!-- Checkbox Pilih Buku -->
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input class="form-check-input check" type="checkbox"
                                                        value="<?= $isi['id_buku']; ?>" id="printCheckbox">
                                                </div>
                                            </td>

                                            <!-- Aksi -->
                                            <td <?php if ($this->session->userdata('level') == 'Petugas') { ?>
                                                    style="width:17%;" <?php } ?>>

                                                <?php if ($this->session->userdata('level') == 'Petugas') { ?>

                                                    <a href="<?= base_url('data/bukuedit/' . $isi['id_buku']); ?>">
                                                        <button class="btn btn-success">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    </a>

                                                    <a href="<?= base_url('data/bukudetail/' . $isi['id_buku']); ?>">
                                                        <button class="btn btn-primary">
                                                            <i class="fa fa-sign-in"></i> Detail
                                                        </button>
                                                    </a>

                                                    <a href="<?= base_url('data/prosesbuku?buku_id=' . $isi['id_buku']); ?>"
                                                        onclick="return confirm('Anda yakin Buku ini akan dihapus ?');">
                                                        <button class="btn btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </a>

                                                <?php } else { ?>

                                                    <a href="<?= base_url('data/bukudetail/' . $isi['id_buku']); ?>">
                                                        <button class="btn btn-primary">
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

        // ==========================================
        // CETAK LABEL
        // ==========================================
        $('#print_button').click(function () {

            var val = [];

            $('.check:checkbox:checked').each(function (i) {
                val[i] = $(this).val();
            });

            if (val.length > 0) {

                let text = val.toString();

                document.cookie = `dataBuku=${text}; path=/`;

                window.open(
                    '<?= base_url('data/printbuku/') ?>',
                    '_blank'
                );

            } else {
                alert("Pilih Buku terlebih dahulu");
            }
        });


        // ==========================================
        // CETAK QR CODE
        // ==========================================
        $('#print_qrcode_button').click(function () {
            var val = [];

            $('.check:checkbox:checked').each(function () {
                val.push($(this).val());
            });

            if (val.length > 0) {
                var url = '<?= base_url('data/printqrcode') ?>?id='
                    + val.join(',');

                window.open(url, '_blank');
            } else {
                alert('Pilih Buku terlebih dahulu');
            }
        });


        // ==========================================
        // CHECKBOX PILIH SEMUA
        // ==========================================
        $("#checkAll").click(function () {

            $('.check').prop('checked', this.checked);

        });


        // ==========================================
        // UPDATE CHECKBOX PILIH SEMUA
        // ==========================================
        $('.check').change(function () {

            $('#checkAll').prop(
                'checked',
                $('.check').length === $('.check:checked').length
            );

        });

    });
</script>