<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
} ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-edit" style="color:green"></i> Daftar Data Siswa
        </h1>

        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('dashboard'); ?>">
                    <i class="fa fa-dashboard"></i>&nbsp; Dashboard
                </a>
            </li>
            <li class="active">
                <i class="fa fa-file-text"></i>&nbsp; Daftar Data Siswa
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

                        <a href="<?= base_url('user/tambah'); ?>">
                            <button type="button" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Tambah Siswa
                            </button>
                        </a>

                        <button id="print_button" class="btn btn-success" type="button">
                            <i class="fa fa-print"></i> Kartu
                        </button>

                        <button id="print_full_card_button" class="btn btn-success" type="button">
                            <i class="fa fa-id-card"></i> Cetak Kartu Full
                        </button>

                        <button id="print_qrcode_button" class="btn btn-info" type="button">
                            <i class="fa fa-qrcode"></i> Cetak QR Code
                        </button>

                    </div>

                    <!-- Filter khusus laporan PDF Data Siswa -->
                    <div class="box-body" style="border-bottom:1px solid #f4f4f4;">
                        <form action="<?= base_url('cetak/cetak_siswa'); ?>"
                              method="GET"
                              target="_blank"
                              class="form-inline">

                            <div class="form-group" style="margin:5px 5px 5px 0;">
                                <label for="filter_nama" style="display:block;">Nama Siswa</label>
                                <input
                                    type="text"
                                    name="nama"
                                    id="filter_nama"
                                    class="form-control"
                                    placeholder="Cari nama siswa"
                                    style="min-width:180px;">
                            </div>

                            <div class="form-group" style="margin:5px 5px 5px 0;">
                                <label for="filter_kode" style="display:block;">Kode Anggota</label>
                                <input
                                    type="text"
                                    name="kode_anggota"
                                    id="filter_kode"
                                    class="form-control"
                                    placeholder="Cari kode anggota"
                                    style="min-width:160px;">
                            </div>

                            <div class="form-group" style="margin:5px 5px 5px 0;">
                                <label for="filter_jenkel" style="display:block;">Jenis Kelamin</label>
                                <select
                                    name="jenkel"
                                    id="filter_jenkel"
                                    class="form-control"
                                    style="min-width:160px;">
                                    <option value="">Semua Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>

                            <div class="form-group" style="margin:5px 5px 5px 0; vertical-align:bottom;">
                                <label style="display:block;">&nbsp;</label>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fa fa-print"></i> Filter & Cetak Laporan
                                </button>

                                <button type="reset" class="btn btn-default">
                                    <i class="fa fa-refresh"></i> Reset
                                </button>
                            </div>

                        </form>
                        <small class="text-muted">
                            Kosongkan semua filter untuk mencetak seluruh data siswa.
                        </small>
                    </div>

                    <div class="box-body">
                        <br />

                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Anggota</th>
                                        <th>Nama</th>
                                        <th>Jenis Kelamin</th>
                                        <th style="width:150px;">
                                            Cetak Kartu
                                            <input class="form-check-input" type="checkbox" id="checkAll">
                                        </th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $no = 1;
                                    foreach ($user as $isi) {
                                    ?>
                                        <tr>
                                            <td><?= $no; ?></td>

                                            <td>
                                                <?= html_escape($isi['kode_anggota']); ?>
                                            </td>

                                            <td>
                                                <?= html_escape($isi['nama']); ?>
                                            </td>

                                            <td>
                                                <?= html_escape($isi['jenkel']); ?>
                                            </td>

                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input check"
                                                        type="checkbox"
                                                        value="<?= (int) $isi['id_siswa']; ?>"
                                                        aria-label="Pilih <?= html_escape($isi['nama']); ?>">
                                                </div>
                                            </td>

                                            <td style="width:10%;">
                                                <a href="<?= base_url('user/edit/' . (int) $isi['id_siswa']); ?>">
                                                    <button type="button" class="btn btn-success">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                </a>

                                                <a
                                                    href="<?= base_url('user/del/' . (int) $isi['id_siswa']); ?>"
                                                    onclick="return confirm('Anda yakin user akan dihapus ?');">
                                                    <button type="button" class="btn btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </a>
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

        // Mengambil ID siswa yang dipilih.
        function getSelectedStudentIds() {
            var selectedIds = [];

            $('.check:checked').each(function () {
                var id = String($(this).val()).trim();

                if (/^\d+$/.test(id)) {
                    selectedIds.push(id);
                }
            });

            // Menghilangkan ID duplikat.
            return selectedIds.filter(function (id, index, array) {
                return array.indexOf(id) === index;
            });
        }

        // Memastikan minimal satu siswa dipilih.
        function requireSelectedStudents() {
            var ids = getSelectedStudentIds();

            if (ids.length === 0) {
                alert('Pilih Siswa terlebih dahulu');
                return null;
            }

            return ids;
        }

        // Membuka halaman cetak dan memeriksa apakah popup diblokir.
        function openPrintPage(url) {
            var printWindow = window.open(url, '_blank');

            if (!printWindow) {
                alert('Halaman cetak tidak dapat dibuka. Izinkan pop-up untuk website ini, lalu coba kembali.');
                return false;
            }

            return true;
        }

        // Cetak kartu siswa (format lama).
        $('#print_button').on('click', function (e) {
            e.preventDefault();

            var ids = requireSelectedStudents();
            if (!ids) {
                return;
            }

            document.cookie = 'data=' + ids.join(',') + '; path=/; SameSite=Lax';
            openPrintPage('<?= base_url('user/print/'); ?>');
        });

        // Cetak kartu siswa format full.
        $('#print_full_card_button').on('click', function (e) {
            e.preventDefault();

            var ids = requireSelectedStudents();
            if (!ids) {
                return;
            }

            document.cookie = 'data=' + ids.join(',') + '; path=/; SameSite=Lax';
            openPrintPage('<?= base_url('user/print_full/'); ?>');
        });

        // Cetak QR Code siswa yang dipilih.
        $('#print_qrcode_button').on('click', function (e) {
            e.preventDefault();

            var ids = requireSelectedStudents();
            if (!ids) {
                return;
            }

            var url = '<?= base_url('user/printqrcode'); ?>?id='
                + encodeURIComponent(ids.join(','));

            openPrintPage(url);
        });

        // Pilih semua siswa.
        $('#checkAll').on('change', function () {
            $('.check').prop('checked', this.checked);
            $(this).prop('indeterminate', false);
        });

        // Sinkronisasi checkbox pilih semua.
        $(document).on('change', '.check', function () {
            var total = $('.check').length;
            var checked = $('.check:checked').length;

            $('#checkAll')
                .prop('checked', total > 0 && checked === total)
                .prop('indeterminate', checked > 0 && checked < total);
        });

        // Status checkbox saat halaman pertama kali dimuat.
        var totalAwal = $('.check').length;
        var checkedAwal = $('.check:checked').length;

        $('#checkAll')
            .prop('checked', totalAwal > 0 && checkedAwal === totalAwal)
            .prop('indeterminate', checkedAwal > 0 && checkedAwal < totalAwal);
    });
</script>
