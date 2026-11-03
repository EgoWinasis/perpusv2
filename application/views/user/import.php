
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">

    <!-- HEADER -->
    <section class="content-header">
        <h1>
            <i class="fa fa-file-excel-o" style="color:green"></i>
            Import Data Siswa
        </h1>

        <ol class="breadcrumb">
            <li>
                <a href="<?= base_url('dashboard'); ?>">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?= base_url('user'); ?>">Data Siswa</a>
            </li>
            <li class="active">Import Excel</li>
        </ol>
    </section>

    <!-- CONTENT -->
    <section class="content">

        <!-- PETUNJUK -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-info-circle"></i>
                    Petunjuk Import
                </h3>
            </div>

            <div class="box-body">
                <ol>
                    <li>Download template Excel terlebih dahulu.</li>
                    <li>Isi nama siswa dan jenis kelamin.</li>
                    <li>Upload file Excel yang sudah diisi.</li>
                    <li>Periksa data pada tabel preview.</li>
                    <li>Klik tombol Import Data untuk menyimpan.</li>
                </ol>

                <p class="text-muted">
                    Format Excel yang didukung adalah XLSX dan XLS.
                    Kolom yang digunakan hanya Nama Siswa dan Jenis Kelamin.
                </p>
            </div>
        </div>

        <!-- IMPORT -->
        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-upload"></i>
                    Upload File Excel
                </h3>
            </div>

            <div class="box-body">

                <div class="row">

                    <div class="col-md-5">
                        <button type="button"
                                id="btnTemplate"
                                class="btn btn-success btn-block">

                            <i class="fa fa-download"></i>
                            Download Template Excel

                        </button>
                    </div>

                    <div class="col-md-7">

                        <label>Pilih File Excel</label>

                        <input type="file"
                               id="fileExcel"
                               class="form-control"
                               accept=".xlsx,.xls">

                        <small class="text-muted">
                            Pilih file Excel yang akan diimport.
                        </small>

                    </div>

                </div>

                <hr>

                <!-- PREVIEW -->
                <div id="areaPreview" style="display:none;">

                    <div class="row">

                        <div class="col-md-6">
                            <h4>
                                <i class="fa fa-table"></i>
                                Preview Data Siswa
                            </h4>
                        </div>

                        <div class="col-md-6 text-right">
                            <span class="label label-info"
                                  style="font-size:13px;"
                                  id="jumlahData">
                                0 Data
                            </span>
                        </div>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-bordered table-striped table-hover"
                               id="tabelPreview">

                            <thead>
                                <tr class="bg-primary">
                                    <th width="50">No</th>
                                    <th>Nama Siswa</th>
                                    <th>Jenis Kelamin</th>
                                    <th width="120">Status</th>
                                </tr>
                            </thead>

                            <tbody></tbody>

                        </table>

                    </div>

                    <div class="alert alert-warning"
                         id="peringatanData"
                         style="display:none;">

                        <i class="fa fa-exclamation-triangle"></i>
                        Terdapat data yang tidak valid. Perbaiki file Excel
                        sebelum melakukan import.

                    </div>

                    <div class="text-right">

                        <button type="button"
                                id="btnReset"
                                class="btn btn-default">

                            <i class="fa fa-refresh"></i>
                            Reset

                        </button>

                        <button type="button"
                                id="btnImport"
                                class="btn btn-primary btn-lg">

                            <i class="fa fa-upload"></i>
                            Import Data

                        </button>

                    </div>

                </div>

                <!-- PROGRESS -->
                <div id="areaProgress"
                     style="display:none; margin-top:25px;">

                    <hr>

                    <h4>
                        <i class="fa fa-spinner fa-spin"
                           id="iconProgress"></i>

                        <span id="progressText">
                            Menyiapkan proses import...
                        </span>
                    </h4>

                    <div class="progress"
                         style="height:25px;">

                        <div id="progressBar"
                             class="progress-bar progress-bar-success progress-bar-striped active"
                             role="progressbar"
                             style="width:0%; line-height:25px;">

                            0%

                        </div>

                    </div>

                    <div class="row">

                        <div class="col-xs-4">
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3 id="jumlahBerhasil">0</h3>
                                    <p>Berhasil</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-check-circle"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3 id="jumlahGagal">0</h3>
                                    <p>Gagal</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-times-circle"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3 id="jumlahTotal">0</h3>
                                    <p>Total Data</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-users"></i>
                                </div>
                            </div>
                        </div>

                    </div>

                    <h4>Detail Proses Import</h4>

                    <div id="hasilImport"
                         style="max-height:300px; overflow-y:auto;">

                    </div>

                </div>

                <hr>

                <a href="<?= base_url('user'); ?>"
                   class="btn btn-danger">

                    <i class="fa fa-arrow-left"></i>
                    Kembali

                </a>

            </div>

        </div>

    </section>

</div>


<!-- LIBRARY EXCEL -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>


<style>
    #tabelPreview th {
        background: #3c8dbc;
        color: #fff;
        vertical-align: middle;
    }

    #tabelPreview td {
        vertical-align: middle;
    }

    #progressBar {
        font-weight: bold;
        font-size: 13px;
    }

    #hasilImport .item-import {
        padding: 8px 12px;
        border-bottom: 1px solid #eee;
    }

    #hasilImport .item-import:last-child {
        border-bottom: none;
    }

    .small-box {
        margin-bottom: 10px;
    }

    .small-box h3 {
        font-size: 25px;
    }
</style>


<script>
$(document).ready(function() {

    var dataSiswa = [];

    var sedangImport = false;

    var importSelesai = false;


    // =====================================================
    // FUNGSI ESCAPE HTML
    // =====================================================

    function escapeHtml(value) {

        return $('<div>')
            .text(value)
            .html();

    }


    // =====================================================
    // DOWNLOAD TEMPLATE EXCEL
    // =====================================================

    $('#btnTemplate').on('click', function() {

        var data = [
            {
                nama: 'Andi Saputra',
                jenkel: 'Laki-Laki'
            },
            {
                nama: 'Siti Aminah',
                jenkel: 'Perempuan'
            }
        ];

        var worksheet = XLSX.utils.json_to_sheet(data);

        worksheet['!cols'] = [
            { wch: 35 },
            { wch: 20 }
        ];

        var workbook = XLSX.utils.book_new();

        XLSX.utils.book_append_sheet(
            workbook,
            worksheet,
            'Data Siswa'
        );

        XLSX.writeFile(
            workbook,
            'Template_Import_Siswa.xlsx'
        );

    });


    // =====================================================
    // VALIDASI DATA
    // =====================================================

    function validasiBaris(item) {

        var jenis = item.jenkel.toLowerCase();

        return (
            item.nama !== '' &&
            (
                jenis === 'laki-laki' ||
                jenis === 'perempuan'
            )
        );

    }


    // =====================================================
    // BACA FILE EXCEL CLIENT SIDE
    // =====================================================

    $('#fileExcel').on('change', function(e) {

        var file = e.target.files[0];

        if (!file) return;

        if (!/\.(xlsx|xls)$/i.test(file.name)) {

            alert('Format file harus XLSX atau XLS.');

            $(this).val('');

            return;

        }

        var reader = new FileReader();

        reader.onload = function(event) {

            try {

                var workbook = XLSX.read(
                    event.target.result,
                    {
                        type: 'array'
                    }
                );

                var sheetName = workbook.SheetNames[0];

                var worksheet = workbook.Sheets[sheetName];

                var rows = XLSX.utils.sheet_to_json(
                    worksheet,
                    {
                        defval: ''
                    }
                );

                dataSiswa = [];

                rows.forEach(function(row) {

                    var nama = String(
                        row.nama ||
                        row.Nama ||
                        row.NAMA ||
                        ''
                    ).trim();

                    var jenkel = String(
                        row.jenkel ||
                        row.Jenkel ||
                        row.JENKEL ||
                        ''
                    ).trim();

                    // Abaikan baris kosong.
                    if (nama === '' && jenkel === '') {
                        return;
                    }

                    dataSiswa.push({

                        nama: nama,

                        jenkel: jenkel,

                        status: 'Menunggu',

                        pesan: ''

                    });

                });

                if (dataSiswa.length === 0) {

                    alert('Tidak ada data siswa dalam file Excel.');

                    $('#areaPreview').hide();

                    return;

                }

                tampilkanPreview();

            } catch (error) {

                console.error(error);

                alert(
                    'File Excel gagal dibaca. Pastikan format file benar.'
                );

            }

        };

        reader.onerror = function() {

            alert('Gagal membaca file Excel.');

        };

        reader.readAsArrayBuffer(file);

    });


    // =====================================================
    // TAMPILKAN PREVIEW
    // =====================================================

    function tampilkanPreview() {

        var tbody = $('#tabelPreview tbody');

        tbody.empty();

        var jumlahValid = 0;

        dataSiswa.forEach(function(item, index) {

            var valid = validasiBaris(item);

            if (valid) {
                jumlahValid++;
            }

            var statusHtml = valid
                ? '<span class="label label-info">Menunggu</span>'
                : '<span class="label label-danger">Tidak Valid</span>';

            var tr = $('<tr>');

            if (!valid) {
                tr.addClass('danger');
            }

            tr.append(
                $('<td>').text(index + 1)
            );

            tr.append(
                $('<td>').text(item.nama)
            );

            tr.append(
                $('<td>').text(item.jenkel)
            );

            tr.append(
                $('<td class="status-row">').html(statusHtml)
            );

            tbody.append(tr);

        });

        $('#jumlahData').text(
            dataSiswa.length + ' Data Siswa'
        );

        $('#areaPreview').show();

        if (jumlahValid !== dataSiswa.length) {

            $('#peringatanData').show();

            $('#btnImport').prop('disabled', true);

        } else {

            $('#peringatanData').hide();

            $('#btnImport').prop('disabled', false);

        }

        $('#areaProgress').hide();

        importSelesai = false;

    }


    // =====================================================
    // UPDATE STATUS BARIS
    // =====================================================

    function updateStatusBaris(index, status, pesan) {

        var row = $('#tabelPreview tbody tr').eq(index);

        var kolomStatus = row.find('.status-row');

        if (status === 'berhasil') {

            row.removeClass('warning danger').addClass('success');

            kolomStatus.html(
                '<span class="label label-success">' +
                '<i class="fa fa-check"></i> Berhasil</span>'
            );

        } else {

            row.removeClass('warning success').addClass('danger');

            kolomStatus.html(
                '<span class="label label-danger">' +
                '<i class="fa fa-times"></i> Gagal</span>'
            );

        }

        itemImportLog(index, status, pesan);

    }


    // =====================================================
    // LOG IMPORT
    // =====================================================

    function itemImportLog(index, status, pesan) {

        var item = dataSiswa[index];

        var warna = status === 'berhasil'
            ? 'text-success'
            : 'text-danger';

        var icon = status === 'berhasil'
            ? 'fa-check-circle'
            : 'fa-times-circle';

        var html = `
            <div class="item-import ${warna}">
                <i class="fa ${icon}"></i>
                <b>${escapeHtml(item.nama)}</b>
                - ${escapeHtml(pesan)}
            </div>
        `;

        $('#hasilImport').append(html);

    }


    // =====================================================
    // IMPORT DATA SATU PER SATU
    // =====================================================

    $('#btnImport').on('click', async function() {

        if (sedangImport) return;

        if (dataSiswa.length === 0) {

            alert('Tidak ada data untuk diimport.');

            return;

        }

        if (!confirm(
            'Apakah Anda yakin ingin mengimport ' +
            dataSiswa.length +
            ' data siswa?'
        )) {

            return;

        }

        sedangImport = true;

        importSelesai = false;

        var total = dataSiswa.length;

        var berhasil = 0;

        var gagal = 0;

        $('#btnImport').prop('disabled', true);

        $('#btnReset').prop('disabled', true);

        $('#fileExcel').prop('disabled', true);

        $('#areaProgress').show();

        $('#hasilImport').empty();

        $('#jumlahTotal').text(total);

        $('#jumlahBerhasil').text(0);

        $('#jumlahGagal').text(0);

        $('#progressBar')
            .css('width', '0%')
            .text('0%')
            .addClass('active');

        $('#iconProgress')
            .removeClass('fa-check-circle')
            .addClass('fa-spinner fa-spin');

        // Proses berurutan, satu request selesai sebelum request berikutnya.
        for (var i = 0; i < total; i++) {

            var item = dataSiswa[i];

            $('#progressText').html(
                'Mengimport ' +
                (i + 1) +
                ' dari ' +
                total +
                ': ' +
                escapeHtml(item.nama)
            );

            try {

                var response = await $.ajax({

                    url: '<?= base_url('user/import_save'); ?>',

                    type: 'POST',

                    dataType: 'json',

                    data: {

                        nama: item.nama,

                        jenkel: item.jenkel

                    }

                });

                if (response && response.status) {

                    berhasil++;

                    item.status = 'Berhasil';

                    itemImportLog(
                        i,
                        'berhasil',
                        response.message || 'Data berhasil disimpan.'
                    );

                    var row = $('#tabelPreview tbody tr').eq(i);

                    row.removeClass('warning danger').addClass('success');

                    row.find('.status-row').html(
                        '<span class="label label-success">' +
                        '<i class="fa fa-check"></i> Berhasil</span>'
                    );

                } else {

                    gagal++;

                    item.status = 'Gagal';

                    var pesan = response && response.message
                        ? response.message
                        : 'Data gagal disimpan.';

                    itemImportLog(
                        i,
                        'gagal',
                        pesan
                    );

                    var row = $('#tabelPreview tbody tr').eq(i);

                    row.removeClass('warning success').addClass('danger');

                    row.find('.status-row').html(
                        '<span class="label label-danger">' +
                        '<i class="fa fa-times"></i> Gagal</span>'
                    );

                }

            } catch (error) {

                gagal++;

                item.status = 'Gagal';

                var pesanError = 'Kesalahan komunikasi dengan server.';

                if (error.responseJSON && error.responseJSON.message) {
                    pesanError = error.responseJSON.message;
                }

                itemImportLog(
                    i,
                    'gagal',
                    pesanError
                );

                var row = $('#tabelPreview tbody tr').eq(i);

                row.removeClass('warning success').addClass('danger');

                row.find('.status-row').html(
                    '<span class="label label-danger">' +
                    '<i class="fa fa-times"></i> Gagal</span>'
                );

                console.error('Import error:', error);

            }

            // Update progress.
            var persen = Math.round(
                ((i + 1) / total) * 100
            );

            $('#progressBar')
                .css('width', persen + '%')
                .text(persen + '%');

            $('#jumlahBerhasil').text(berhasil);

            $('#jumlahGagal').text(gagal);

        }

        // =================================================
        // IMPORT SELESAI
        // =================================================

        sedangImport = false;

        importSelesai = true;

        $('#btnReset').prop('disabled', false);

        $('#fileExcel').prop('disabled', false);

        $('#iconProgress')
            .removeClass('fa-spinner fa-spin')
            .addClass('fa-check-circle');

        $('#progressText').text('Proses import selesai.');

        $('#progressBar')
            .removeClass('active')
            .css('width', '100%')
            .text('100%');

        $('#btnImport')
            .prop('disabled', true)
            .html(
                '<i class="fa fa-check"></i> Import Selesai'
            );

        $('#hasilImport').prepend(
            '<div class="alert alert-info">' +
            '<b>Import selesai!</b><br>' +
            'Total data: ' + total + '<br>' +
            'Berhasil: ' + berhasil + '<br>' +
            'Gagal: ' + gagal +
            '</div>'
        );

        alert(
            'Import selesai.\n\n' +
            'Total data: ' + total + '\n' +
            'Berhasil: ' + berhasil + '\n' +
            'Gagal: ' + gagal
        );

    });


    // =====================================================
    // RESET IMPORT
    // =====================================================

    $('#btnReset').on('click', function() {

        if (sedangImport) return;

        if (!confirm('Reset data preview dan pilih file baru?')) {
            return;
        }

        dataSiswa = [];

        $('#fileExcel').val('').prop('disabled', false);

        $('#tabelPreview tbody').empty();

        $('#areaPreview').hide();

        $('#areaProgress').hide();

        $('#hasilImport').empty();

        $('#btnImport')
            .prop('disabled', false)
            .html(
                '<i class="fa fa-upload"></i> Import Data'
            );

        importSelesai = false;

    });


});
</script>
