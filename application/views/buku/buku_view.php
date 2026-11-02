
<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
} ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-edit" style="color:green"></i> <?= html_escape($title_web); ?>
        </h1>

        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url('dashboard'); ?>">
                    <i class="fa fa-dashboard"></i>&nbsp; Dashboard
                </a>
            </li>
            <li class="active">
                <i class="fa fa-file-text"></i>&nbsp; <?= html_escape($title_web); ?>
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
                            <a href="<?= base_url('data/bukutambah'); ?>">
                                <button type="button" class="btn btn-primary">
                                    <i class="fa fa-plus"></i> Tambah Buku
                                </button>
                            </a>
                        <?php } ?>

                        <button id="print_button" class="btn btn-success" type="button">
                            <i class="fa fa-print"></i> Cetak Label
                        </button>

                        <button id="print_full_button" class="btn btn-primary" type="button">
                            <i class="fa fa-print"></i> Cetak Full
                        </button>

                        <button id="print_qrcode_button" class="btn btn-info" type="button">
                            <i class="fa fa-qrcode"></i> Cetak QR Code
                        </button>

                    </div>

                    <!-- FILTER LAPORAN DATA BUKU -->
                    <div class="box-body" style="border-bottom:1px solid #f4f4f4;">

                        <h4>
                            <i class="fa fa-filter"></i> Filter Laporan Data Buku
                        </h4>

                        <form action="<?= base_url('cetak/buku'); ?>"
                              method="GET"
                              target="_blank">

                            <div class="row">

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="filter_kode_buku">Kode Buku</label>
                                        <input
                                            type="text"
                                            name="kode_buku"
                                            id="filter_kode_buku"
                                            class="form-control"
                                            placeholder="Cari kode buku"
                                            value="<?= html_escape($this->input->get('kode_buku', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="filter_isbn">ISBN</label>
                                        <input
                                            type="text"
                                            name="isbn"
                                            id="filter_isbn"
                                            class="form-control"
                                            placeholder="Cari ISBN"
                                            value="<?= html_escape($this->input->get('isbn', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="filter_title">Judul Buku</label>
                                        <input
                                            type="text"
                                            name="title"
                                            id="filter_title"
                                            class="form-control"
                                            placeholder="Cari judul buku"
                                            value="<?= html_escape($this->input->get('title', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="filter_penerbit">Penerbit</label>
                                        <input
                                            type="text"
                                            name="penerbit"
                                            id="filter_penerbit"
                                            class="form-control"
                                            placeholder="Cari penerbit"
                                            value="<?= html_escape($this->input->get('penerbit', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="filter_tahun">Tahun Buku</label>
                                        <input
                                            type="text"
                                            name="thn_buku"
                                            id="filter_tahun"
                                            class="form-control"
                                            placeholder="Contoh: 2024"
                                            value="<?= html_escape($this->input->get('thn_buku', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="filter_kondisi">Kondisi Buku</label>
                                        <select
                                            name="kondisi"
                                            id="filter_kondisi"
                                            class="form-control">

                                            <option value="">Semua Kondisi</option>

                                            <option value="Baik"
                                                <?= $this->input->get('kondisi') == 'Baik' ? 'selected' : ''; ?>>
                                                Baik
                                            </option>

                                            <option value="Rusak"
                                                <?= $this->input->get('kondisi') == 'Rusak' ? 'selected' : ''; ?>>
                                                Rusak
                                            </option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="filter_tanggal_mulai">Tanggal Masuk Dari</label>
                                        <input
                                            type="date"
                                            name="tanggal_mulai"
                                            id="filter_tanggal_mulai"
                                            class="form-control"
                                            value="<?= html_escape($this->input->get('tanggal_mulai', TRUE)); ?>">
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-6">
                                    <div class="form-group">
                                        <label for="filter_tanggal_selesai">Tanggal Masuk Sampai</label>
                                        <input
                                            type="date"
                                            name="tanggal_selesai"
                                            id="filter_tanggal_selesai"
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
                                Kosongkan semua filter untuk mencetak seluruh data buku.
                            </small>

                        </form>
                    </div>

                    <!-- TABEL DATA BUKU -->
                    <div class="box-body">
                        <br />

                        <div class="table-responsive">

                            <table id="example1" class="table table-bordered table-striped" width="100%">

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

                                        <th style="width:50px;">
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
                                        $idBuku = (int) $isi['id_buku'];
                                    ?>

                                        <tr>
                                            <td><?= $no; ?></td>

                                            <td><?= html_escape($isi['kode_buku']); ?></td>

                                            <td><?= html_escape($isi['isbn']); ?></td>

                                            <td><?= html_escape($isi['title']); ?></td>

                                            <td><?= html_escape($isi['penerbit']); ?></td>

                                            <td><?= html_escape($isi['thn_buku']); ?></td>

                                            <td><?= html_escape($isi['jml']); ?></td>

                                            <td><?= html_escape($isi['kondisi']); ?></td>

                                            <td><?= html_escape($isi['tgl_masuk']); ?></td>

                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input check"
                                                        type="checkbox"
                                                        value="<?= $idBuku; ?>"
                                                        aria-label="Pilih buku <?= html_escape($isi['title']); ?>">
                                                </div>
                                            </td>

                                            <td <?php if ($this->session->userdata('level') == 'Petugas') { ?>
                                                    style="width:17%;"
                                                <?php } ?>>

                                                <?php if ($this->session->userdata('level') == 'Petugas') { ?>

                                                    <a href="<?= base_url('data/bukuedit/' . $idBuku); ?>">
                                                        <button type="button" class="btn btn-success">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    </a>

                                                    <a href="<?= base_url('data/bukudetail/' . $idBuku); ?>">
                                                        <button type="button" class="btn btn-primary">
                                                            <i class="fa fa-sign-in"></i> Detail
                                                        </button>
                                                    </a>

                                                    <a
                                                        href="<?= base_url('data/prosesbuku?buku_id=' . $idBuku); ?>"
                                                        onclick="return confirm('Anda yakin Buku ini akan dihapus ?');">

                                                        <button type="button" class="btn btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </a>

                                                <?php } else { ?>

                                                    <a href="<?= base_url('data/bukudetail/' . $idBuku); ?>">
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

        // Mengambil ID buku yang dipilih.
        function getSelectedBookIds(checkedOnly) {
            var ids = [];
            var selector = checkedOnly ? '.check:checked' : '.check';

            $(selector).each(function () {
                var id = String($(this).val()).trim();

                if (/^\d+$/.test(id)) {
                    ids.push(id);
                }
            });

            // Menghilangkan ID duplikat.
            return ids.filter(function (id, index, array) {
                return array.indexOf(id) === index;
            });
        }

        // Membuka halaman cetak.
        function openPrintPage(url) {
            var printWindow = window.open(url, '_blank');

            if (!printWindow) {
                alert('Halaman cetak tidak dapat dibuka. Izinkan pop-up untuk website ini, lalu coba kembali.');
                return false;
            }

            return true;
        }

        // ==========================================
        // CETAK LABEL BUKU TERPILIH
        // ==========================================
        $('#print_button').on('click', function (e) {
            e.preventDefault();

            var ids = getSelectedBookIds(true);

            if (ids.length > 0) {
                document.cookie = 'dataBuku=' + ids.join(',') + '; path=/; SameSite=Lax';

                openPrintPage('<?= base_url('data/printbuku/'); ?>');
            } else {
                alert('Pilih Buku terlebih dahulu');
            }
        });

        // ==========================================
        // CETAK FULL SELURUH BUKU YANG TAMPIL
        // ==========================================
        $('#print_full_button').on('click', function (e) {
            e.preventDefault();

            var ids = getSelectedBookIds(false);

            if (ids.length > 0) {
                document.cookie = 'dataBuku=' + ids.join(',') + '; path=/; SameSite=Lax';

                openPrintPage('<?= base_url('data/printfullbuku/'); ?>');
            } else {
                alert('Data buku tidak ditemukan');
            }
        });

        // ==========================================
        // CETAK QR CODE
        // ==========================================
        $('#print_qrcode_button').on('click', function (e) {
            e.preventDefault();

            var ids = getSelectedBookIds(true);

            if (ids.length > 0) {
                var url = '<?= base_url('data/printqrcode'); ?>?id='
                    + encodeURIComponent(ids.join(','));

                openPrintPage(url);
            } else {
                alert('Pilih Buku terlebih dahulu');
            }
        });

        // ==========================================
        // CHECKBOX PILIH SEMUA
        // ==========================================
        $('#checkAll').on('change', function () {
            $('.check').prop('checked', this.checked);
            $(this).prop('indeterminate', false);
        });

        // ==========================================
        // UPDATE CHECKBOX PILIH SEMUA
        // ==========================================
        $(document).on('change', '.check', function () {
            var total = $('.check').length;
            var checked = $('.check:checked').length;

            $('#checkAll')
                .prop('checked', total > 0 && checked === total)
                .prop('indeterminate', checked > 0 && checked < total);
        });

        // Status checkbox saat halaman dimuat.
        var totalAwal = $('.check').length;
        var checkedAwal = $('.check:checked').length;

        $('#checkAll')
            .prop('checked', totalAwal > 0 && checkedAwal === totalAwal)
            .prop('indeterminate', checkedAwal > 0 && checkedAwal < totalAwal);

        // Reset form filter.
        $('#resetFilter').on('click', function () {
            setTimeout(function () {
                $('#filter_kode_buku, #filter_isbn, #filter_title, #filter_penerbit, #filter_tahun, #filter_tanggal_mulai, #filter_tanggal_selesai').val('');
                $('#filter_kondisi').val('');
            }, 0);
        });

    });
</script>
