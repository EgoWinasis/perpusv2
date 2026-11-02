
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Self Service Kehadiran</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #e8f5e9, #f5fff6);
            font-family: Arial, sans-serif;
        }

        .self-container {
            width: 100%;
            min-height: 100vh;
            padding: 35px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .self-card {
            background: #fff;
            width: 100%;
            max-width: 850px;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 8px 35px rgba(0, 0, 0, .10);
        }

        .brand-icon {
            width: 85px;
            height: 85px;
            line-height: 85px;
            border-radius: 50%;
            background: #e8f5e9;
            color: #2e7d32;
            font-size: 38px;
            margin: auto;
        }

        .self-title {
            color: #1b5e20;
            font-weight: bold;
            margin-top: 18px;
        }

        .form-control {
            height: 48px;
            font-size: 16px;
        }

        .btn-self {
            min-height: 48px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 7px;
        }

        .scan-area {
            display: none;
            margin-top: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
        }

        #reader {
            width: 100%;
            max-width: 500px;
            margin: auto;
        }

        .result-card {
            display: none;
            margin-top: 25px;
        }

        .result-heading {
            background: #e8f5e9;
            padding: 15px;
            color: #1b5e20;
            border-radius: 8px;
            text-align: center;
        }

        .result-table th {
            width: 35%;
            background: #f5f5f5;
        }

        .btn-hadir {
            width: 100%;
            padding: 16px;
            font-size: 19px;
            font-weight: bold;
            border-radius: 8px;
        }

        .date-label {
            display: inline-block;
            padding: 9px 18px;
            background: #e8f5e9;
            color: #1b5e20;
            border-radius: 20px;
            font-weight: bold;
        }

        .footer-text {
            color: #999;
            font-size: 12px;
            margin-top: 25px;
            text-align: center;
        }

        @media(max-width: 600px) {
            .self-container {
                padding: 15px;
            }

            .self-card {
                padding: 22px 16px;
            }

            .self-title {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

<div class="self-container">

    <div class="self-card">

        <!-- HEADER -->
        <div class="text-center">

            <div class="brand-icon">
                <i class="fa fa-users"></i>
            </div>

            <h2 class="self-title">
                SELF SERVICE KEHADIRAN
            </h2>

            <p class="text-muted">
                Silakan lakukan identifikasi anggota untuk mencatat kehadiran.
            </p>

            <div class="date-label">
                <i class="fa fa-calendar"></i>
                <?= date('d-m-Y'); ?>
            </div>

        </div>

        <hr>

        <!-- INPUT KODE -->
        <div class="form-group">

            <label>
                <i class="fa fa-id-card"></i>
                Kode Anggota
            </label>

            <div class="input-group input-group-lg">

                <input type="text"
                       id="kode_anggota"
                       class="form-control"
                       placeholder="Masukkan kode anggota..."
                       autocomplete="off">

                <span class="input-group-btn">

                    <button type="button"
                            id="btnCari"
                            class="btn btn-success">
                        <i class="fa fa-search"></i>
                        Cari
                    </button>

                </span>

            </div>

        </div>

        <!-- PEMISAH -->
        <div class="text-center" style="margin:25px 0;">
            <span class="text-muted">ATAU SCAN KARTU ANGGOTA</span>
        </div>

        <!-- SCAN -->
        <div class="text-center">

            <button type="button"
                    id="btnBukaScan"
                    class="btn btn-primary btn-self">
                <i class="fa fa-camera"></i>
                Scan Menggunakan Webcam
            </button>

        </div>

        <!-- AREA KAMERA -->
        <div class="scan-area" id="areaScanner">

            <div class="alert alert-info text-center">
                Arahkan kamera ke QR Code atau barcode kartu anggota.
            </div>

            <div id="reader"></div>

            <div class="text-center" style="margin-top:15px;">

                <button type="button"
                        id="btnTutupScan"
                        class="btn btn-danger">
                    <i class="fa fa-times"></i>
                    Tutup Kamera
                </button>

            </div>

        </div>

        <!-- PESAN -->
        <div id="pesan" style="margin-top:20px;"></div>

        <!-- HASIL ANGGOTA -->
        <div class="result-card" id="hasilAnggota">

            <div class="result-heading">

                <i class="fa fa-check-circle fa-2x"></i>

                <h3>Data Anggota Ditemukan</h3>

                <p>Periksa informasi berikut sebelum melakukan konfirmasi.</p>

            </div>

            <div class="panel panel-success" style="margin-top:20px;">

                <div class="panel-heading">
                    <strong>
                        <i class="fa fa-user"></i>
                        Informasi Anggota
                    </strong>
                </div>

                <div class="panel-body">

                    <table class="table table-bordered result-table">

                        <tr>
                            <th>Kode Anggota</th>
                            <td id="hasilKode"></td>
                        </tr>

                        <tr>
                            <th>Nama Anggota</th>
                            <td id="hasilNama"></td>
                        </tr>

                        <tr>
                            <th>Jenis Kelamin</th>
                            <td id="hasilJenkel"></td>
                        </tr>

                    </table>

                </div>

            </div>

            <!-- TOMBOL HADIR -->
            <div style="margin-top:20px;">

                <button type="button"
                        id="btnHadir"
                        class="btn btn-success btn-hadir">

                    <i class="fa fa-check-circle"></i>
                    KONFIRMASI HADIR

                </button>

                <div id="statusKehadiran" style="margin-top:15px;"></div>

            </div>

        </div>

        <hr>

        <!-- TOMBOL BAWAH -->
        <div class="text-center">

            <button type="button"
                    id="btnReset"
                    class="btn btn-warning btn-self">

                <i class="fa fa-refresh"></i>
                Reset

            </button>

            <button type="button"
                    id="btnKembali"
                    class="btn btn-default btn-self">

                <i class="fa fa-arrow-left"></i>
                Kembali ke Aplikasi

            </button>

        </div>

        <div class="footer-text">
            Sistem Informasi Kehadiran Siswa
        </div>

    </div>

</div>


<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script src="https://unpkg.com/html5-qrcode"></script>


<script>
$(document).ready(function() {

    let scanner = null;
    let sedangScan = false;
    let prosesHadir = false;


    // =====================================
    // PENCARIAN ANGGOTA
    // =====================================

    function cariAnggota(kode) {

        kode = $.trim(kode);

        if (kode === '') {

            $('#pesan').html(
                '<div class="alert alert-warning">' +
                'Masukkan kode anggota terlebih dahulu.' +
                '</div>'
            );

            return;
        }

        $('#hasilAnggota').hide();

        $('#pesan').html(
            '<div class="alert alert-info">' +
            '<i class="fa fa-spinner fa-spin"></i> Mencari data anggota...' +
            '</div>'
        );

        $.ajax({

            type: 'POST',

            url: '<?= base_url("kehadiran/cari_anggota"); ?>',

            dataType: 'json',

            data: {
                kode_anggota: kode
            },

            success: function(response) {

                if (response.status) {

                    let anggota = response.data;

                    $('#hasilKode').text(anggota.kode_anggota);
                    $('#hasilNama').text(anggota.nama);
                    $('#hasilJenkel').text(anggota.jenkel);

                    $('#btnHadir').data(
                        'id-siswa',
                        anggota.id_siswa
                    );

                    prosesHadir = false;

                    $('#hasilAnggota').show();

                    if (response.sudah_hadir) {

                        $('#btnHadir')
                            .prop('disabled', true)
                            .html(
                                '<i class="fa fa-check"></i> SUDAH HADIR'
                            );

                        $('#statusKehadiran').html(
                            '<div class="alert alert-success">' +
                            '<strong>Kehadiran sudah tercatat.</strong>' +
                            '<br>Anda sudah hadir hari ini.' +
                            '</div>'
                        );

                    } else {

                        $('#btnHadir')
                            .prop('disabled', false)
                            .html(
                                '<i class="fa fa-check-circle"></i> ' +
                                'KONFIRMASI HADIR'
                            );

                        $('#statusKehadiran').html(
                            '<div class="alert alert-info">' +
                            'Silakan tekan tombol Konfirmasi Hadir.' +
                            '</div>'
                        );

                    }

                    $('#pesan').html('');

                } else {

                    $('#hasilAnggota').hide();

                    $('#pesan').html(
                        '<div class="alert alert-danger">' +
                        response.pesan +
                        '</div>'
                    );

                }

            },

            error: function() {

                $('#pesan').html(
                    '<div class="alert alert-danger">' +
                    'Terjadi kesalahan saat mencari anggota.' +
                    '</div>'
                );

            }

        });

    }


    // =====================================
    // CARI MANUAL
    // =====================================

    $('#btnCari').click(function() {

        cariAnggota($('#kode_anggota').val());

    });


    $('#kode_anggota').on('keypress', function(e) {

        if (e.which === 13) {

            e.preventDefault();

            cariAnggota($(this).val());

        }

    });


    // =====================================
    // SCAN WEBCAM
    // =====================================

    $('#btnBukaScan').click(function() {

        if (sedangScan) {
            return;
        }

        $('#areaScanner').show();

        $('#pesan').html('');

        scanner = new Html5Qrcode('reader');

        scanner.start(

            {
                facingMode: 'environment'
            },

            {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 150
                }
            },

            function(decodedText) {

                $('#kode_anggota').val(decodedText);

                scanner.stop().then(function() {

                    sedangScan = false;

                    $('#areaScanner').hide();

                    cariAnggota(decodedText);

                }).catch(function(error) {

                    console.log(error);

                });

            },

            function(errorMessage) {

                // Tidak perlu menampilkan error pembacaan frame.

            }

        ).then(function() {

            sedangScan = true;

        }).catch(function(error) {

            sedangScan = false;

            $('#areaScanner').hide();

            $('#pesan').html(
                '<div class="alert alert-danger">' +
                'Kamera tidak dapat dibuka. Periksa izin kamera ' +
                'dan pastikan halaman menggunakan HTTPS atau localhost.' +
                '</div>'
            );

            console.log(error);

        });

    });


    // =====================================
    // TUTUP KAMERA
    // =====================================

    $('#btnTutupScan').click(function() {

        if (scanner !== null && sedangScan) {

            scanner.stop().then(function() {

                sedangScan = false;

                $('#areaScanner').hide();

            }).catch(function(error) {

                console.log(error);

            });

        } else {

            $('#areaScanner').hide();

        }

    });


    // =====================================
    // KONFIRMASI HADIR
    // =====================================

    $('#btnHadir').click(function() {

        let tombol = $(this);

        let idSiswa = tombol.data('id-siswa');

        if (!idSiswa) {

            alert('Data siswa tidak ditemukan.');

            return;

        }

        if (prosesHadir) {
            return;
        }

        if (!confirm('Apakah Anda yakin ingin mencatat kehadiran?')) {
            return;
        }

        prosesHadir = true;

        tombol
            .prop('disabled', true)
            .html(
                '<i class="fa fa-spinner fa-spin"></i> MENYIMPAN...'
            );

        $.ajax({

            type: 'POST',

            url: '<?= base_url("kehadiran/add"); ?>',

            dataType: 'json',

            data: {

                id_siswa: idSiswa,

                tanggal: '<?= date("Y-m-d"); ?>'

            },

            success: function(response) {

                if (response.status) {

                    tombol
                        .prop('disabled', true)
                        .html(
                            '<i class="fa fa-check"></i> SUDAH HADIR'
                        );

                    $('#statusKehadiran').html(
                        '<div class="alert alert-success">' +
                        '<i class="fa fa-check-circle fa-2x"></i>' +
                        '<h4><strong>KEHADIRAN BERHASIL!</strong></h4>' +
                        response.pesan +
                        '<br>Nama: ' +
                        $('<div>').text(response.nama).html() +
                        '<br>Tanggal: <?= date("d-m-Y"); ?>' +
                        '</div>'
                    );

                } else {

                    prosesHadir = false;

                    if (
                        response.pesan &&
                        response.pesan.toLowerCase().includes('sudah')
                    ) {

                        tombol
                            .prop('disabled', true)
                            .html(
                                '<i class="fa fa-check"></i> SUDAH HADIR'
                            );

                    } else {

                        tombol
                            .prop('disabled', false)
                            .html(
                                '<i class="fa fa-check-circle"></i> ' +
                                'KONFIRMASI HADIR'
                            );

                    }

                    $('#statusKehadiran').html(
                        '<div class="alert alert-warning">' +
                        response.pesan +
                        '</div>'
                    );

                }

            },

            error: function() {

                prosesHadir = false;

                tombol
                    .prop('disabled', false)
                    .html(
                        '<i class="fa fa-check-circle"></i> ' +
                        'KONFIRMASI HADIR'
                    );

                $('#statusKehadiran').html(
                    '<div class="alert alert-danger">' +
                    'Gagal menghubungi server. Silakan coba kembali.' +
                    '</div>'
                );

            }

        });

    });


    // =====================================
    // RESET
    // =====================================

    $('#btnReset').click(function() {

        if (scanner !== null && sedangScan) {

            scanner.stop().then(function() {

                sedangScan = false;

                $('#areaScanner').hide();

            }).catch(function(error) {

                console.log(error);

            });

        }

        $('#kode_anggota').val('');

        $('#hasilAnggota').hide();

        $('#pesan').html('');

        $('#statusKehadiran').html('');

        $('#btnHadir')
            .removeData('id-siswa')
            .prop('disabled', false)
            .html(
                '<i class="fa fa-check-circle"></i> KONFIRMASI HADIR'
            );

        prosesHadir = false;

        $('#kode_anggota').focus();

    });


    // =====================================
    // KEMBALI KE APLIKASI
    // =====================================

    $('#btnKembali').click(function() {

        window.location.href = '<?= base_url("kehadiran"); ?>';

    });


    // Fokus otomatis ke input
    $('#kode_anggota').focus();

});
</script>

</body>
</html>
