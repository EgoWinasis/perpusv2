
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-check" style="color:green"></i>
            Self Service Kehadiran
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?= base_url('dashboard'); ?>">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="<?= base_url('kehadiran'); ?>">Data Kehadiran Siswa</a>
            </li>
            <li class="active">Self Service</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <div class="box box-success">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">
                            <i class="fa fa-id-card"></i>
                            Identifikasi Anggota
                        </h3>
                    </div>

                    <div class="box-body">

                        <div class="text-center" style="margin-bottom:25px;">
                            <i class="fa fa-users fa-4x text-success"></i>
                            <h3>Self Service Kehadiran</h3>
                            <p class="text-muted">
                                Masukkan kode anggota atau pindai kartu anggota
                                menggunakan kamera.
                            </p>
                        </div>

                        <!-- Input Kode Anggota -->
                        <div class="form-group">
                            <label>Kode Anggota</label>
                            <div class="input-group">
                                <input type="text"
                                       id="kode_anggota"
                                       class="form-control input-lg"
                                       placeholder="Masukkan kode anggota..."
                                       autocomplete="off">

                                <span class="input-group-btn">
                                    <button type="button"
                                            id="btnCari"
                                            class="btn btn-success btn-lg">
                                        <i class="fa fa-search"></i> Cari
                                    </button>
                                </span>
                            </div>
                        </div>

                        <div class="text-center" style="margin:20px 0;">
                            <span class="text-muted">ATAU</span>
                        </div>

                        <!-- Tombol Scan -->
                        <div class="text-center">
                            <button type="button"
                                    id="btnBukaScan"
                                    class="btn btn-primary btn-lg">
                                <i class="fa fa-camera"></i>
                                Scan dengan Webcam
                            </button>
                        </div>

                        <!-- Area Scanner -->
                        <div id="areaScanner"
                             style="display:none; margin-top:25px;">

                            <div class="alert alert-info">
                                Arahkan kamera ke QR Code atau barcode kartu anggota.
                            </div>

                            <div id="reader"
                                 style="width:100%; max-width:500px; margin:auto;">
                            </div>

                            <div class="text-center" style="margin-top:15px;">
                                <button type="button"
                                        id="btnTutupScan"
                                        class="btn btn-danger">
                                    <i class="fa fa-times"></i> Tutup Kamera
                                </button>
                            </div>
                        </div>

                        <hr>

                        <!-- Pesan -->
                        <div id="pesan"></div>

                        <!-- Hasil Data Anggota -->
                        <div id="hasilAnggota" style="display:none;">

                            <div class="alert alert-success text-center">
                                <i class="fa fa-check-circle fa-2x"></i>
                                <h4>Data Anggota Ditemukan</h4>
                            </div>

                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <strong>
                                        <i class="fa fa-id-card"></i>
                                        Informasi Anggota
                                    </strong>
                                </div>

                                <div class="panel-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="35%">Kode Anggota</th>
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

                        </div>

                        <div class="text-center">
                            <a href="<?= base_url('kehadiran'); ?>"
                               class="btn btn-default">
                                <i class="fa fa-arrow-left"></i>
                                Kembali
                            </a>

                            <button type="button"
                                    id="btnReset"
                                    class="btn btn-warning">
                                <i class="fa fa-refresh"></i>
                                Reset
                            </button>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<!-- Library QR Code dan Barcode Scanner -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
$(document).ready(function() {

    let scanner = null;
    let sedangScan = false;

    // Fungsi mencari anggota
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

                    $('#hasilAnggota').show();

                    $('#pesan').html('');

                } else {

                    $('#hasilAnggota').hide();

                    $('#pesan').html(
                        '<div class="alert alert-danger">' +
                        '<i class="fa fa-exclamation-circle"></i> ' +
                        response.pesan +
                        '</div>'
                    );
                }
            },

            error: function() {
                $('#pesan').html(
                    '<div class="alert alert-danger">' +
                    'Terjadi kesalahan saat mengambil data anggota.' +
                    '</div>'
                );
            }
        });
    }

    // Pencarian manual
    $('#btnCari').click(function() {
        cariAnggota($('#kode_anggota').val());
    });

    // Tekan Enter
    $('#kode_anggota').keypress(function(e) {
        if (e.which === 13) {
            e.preventDefault();
            cariAnggota($(this).val());
        }
    });

    // Membuka kamera
    $('#btnBukaScan').click(function() {

        $('#areaScanner').show();
        $('#pesan').html('');

        if (!scanner) {
            scanner = new Html5Qrcode("reader");
        }

        scanner.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 150
                }
            },

            function(decodedText) {

                // Hasil scan dimasukkan ke input
                $('#kode_anggota').val(decodedText);

                // Hentikan kamera setelah berhasil scan
                scanner.stop().then(function() {
                    sedangScan = false;
                    $('#areaScanner').hide();

                    // Cari data anggota otomatis
                    cariAnggota(decodedText);

                }).catch(function(error) {
                    console.log(error);
                });
            },

            function(errorMessage) {
                // Error pembacaan frame diabaikan
            }

        ).then(function() {
            sedangScan = true;
        }).catch(function(error) {

            $('#areaScanner').hide();

            $('#pesan').html(
                '<div class="alert alert-danger">' +
                'Kamera tidak dapat dibuka. Pastikan izin kamera diberikan ' +
                'dan browser menggunakan HTTPS atau localhost.' +
                '</div>'
            );

            console.log(error);
        });
    });

    // Menutup kamera
    $('#btnTutupScan').click(function() {

        if (scanner && sedangScan) {

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

    // Reset form
    $('#btnReset').click(function() {

        $('#kode_anggota').val('');
        $('#hasilAnggota').hide();
        $('#pesan').html('');

    });

});
</script>
