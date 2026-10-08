<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title><?= isset($title_web) ? $title_web : 'Self Service Perpustakaan'; ?></title>

    <link rel="shortcut icon"
          href="<?= base_url('assets_style/image/cirlce.png'); ?>">

    <link rel="stylesheet"
          href="<?= base_url('assets_style/assets/bower_components/bootstrap/dist/css/bootstrap.min.css'); ?>">

    <link rel="stylesheet"
          href="<?= base_url('assets_style/assets/bower_components/font-awesome/css/font-awesome.min.css'); ?>">

    <script src="https://unpkg.com/html5-qrcode"></script>

<style>

/* =========================================================
   RESET
========================================================= */

* {
    box-sizing: border-box;
}

html,
body {
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;

    overflow: hidden;

    font-family: Arial, Helvetica, sans-serif;

    background: #eaf4ff;
}


/* =========================================================
   CONTAINER
========================================================= */

.selfservice {

    width: 100vw;
    height: 100vh;

    padding: 8px;

    display: grid;

    grid-template-rows:
        42px
        minmax(0, 1fr)
        24px;

    gap: 7px;

    overflow: hidden;
}


/* =========================================================
   HEADER
========================================================= */

.header {

    height: 42px;

    background: #1976d2;

    border-radius: 9px;

    color: white;

    padding: 0 18px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    overflow: hidden;
}

.header-title {

    font-size: 20px;

    font-weight: bold;

    white-space: nowrap;
}

.header-school {

    font-size: 16px;

    font-weight: bold;

    white-space: nowrap;
}


/* =========================================================
   DASHBOARD 4 CARD
========================================================= */

.dashboard {

    width: 100%;
    height: 100%;

    min-height: 0;

    display: grid;

    grid-template-columns:
        minmax(0, 1fr)
        minmax(0, 1fr);

    grid-template-rows:
        minmax(0, 1fr)
        minmax(0, 1fr);

    gap: 8px;

    overflow: hidden;
}


/* =========================================================
   CARD
========================================================= */

.card {

    width: 100%;
    height: 100%;

    min-width: 0;
    min-height: 0;

    background: #fff;

    border-radius: 12px;

    overflow: hidden;

    box-shadow:
        0 2px 7px rgba(0,0,0,.12);

    position: relative;
}


/* =========================================================
   CARD HEADER
========================================================= */

.card-header {

    width: 100%;

    height: 38px;
    min-height: 38px;

    background: #f7f9fc;

    border-bottom: 1px solid #e5e5e5;

    padding: 0 14px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    font-size: 16px;

    font-weight: bold;

    color: #333;
}

.card-header i {
    margin-right: 6px;
}

.header-blue {
    color: #1976d2;
}

.header-green {
    color: #388e3c;
}

.header-orange {
    color: #ef6c00;
}

.header-purple {
    color: #8e24aa;
}


/* =========================================================
   CARD BODY
========================================================= */

.card-body {

    width: 100%;
    height: calc(100% - 38px);

    min-height: 0;

    padding: 10px;

    overflow: hidden;
}


/* =========================================================
   CARD SCAN
========================================================= */

.scan-card {
    border-top: 4px solid #1976d2;
}

.scan-body {

    width: 100%;
    height: 100%;

    min-height: 0;

    display: grid;

    grid-template-rows:
        minmax(0, 1fr)
        27px;

    gap: 5px;
}

#reader {

    width: 100%;
    height: 100%;

    min-height: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    overflow: hidden;
}

#reader video {

    width: auto !important;
    height: auto !important;

    max-width: 100% !important;
    max-height: 100% !important;

    object-fit: contain !important;

    border-radius: 8px !important;
}

#reader__dashboard_section_swaplink {
    display: none !important;
}

.scan-status {

    height: 27px;

    display: flex;

    align-items: center;
    justify-content: center;

    font-size: 13px;

    color: #666;

    white-space: nowrap;

    overflow: hidden;
}


/* =========================================================
   MANUAL
========================================================= */

.manual-card {
    border-top: 4px solid #ff9800;
}

.manual-body {

    width: 100%;
    height: 100%;

    display: flex;

    flex-direction: column;

    justify-content: center;

    gap: 10px;

    overflow: hidden;
}

.input-label {

    font-size: 14px;

    font-weight: bold;

    color: #555;

    margin-bottom: 3px;
}

.input-box {

    width: 100%;

    height: 48px;

    border: 2px solid #ddd;

    border-radius: 8px;

    padding: 0 13px;

    font-size: 18px;

    outline: none;
}

.input-box:focus {

    border-color: #1976d2;

    box-shadow:
        0 0 4px rgba(25,118,210,.3);
}


/* =========================================================
   BUTTON CEK
========================================================= */

.btn-cek {

    width: 100%;

    height: 50px;

    border: none;

    border-radius: 9px;

    background: #1976d2;

    color: white;

    font-size: 19px;

    font-weight: bold;

    box-shadow:
        0 3px 0 #0d47a1;

    cursor: pointer;
}

.btn-cek:active {

    transform: translateY(2px);

    box-shadow:
        0 1px 0 #0d47a1;
}

.btn-cek:disabled {

    opacity: .65;

    cursor: not-allowed;
}


.btn-reset {

    width: 100%;

    height: 38px;

    border: none;

    border-radius: 8px;

    background: #eeeeee;

    color: #555;

    font-size: 14px;

    font-weight: bold;

    cursor: pointer;
}


/* =========================================================
   DATA TRANSAKSI
========================================================= */

.data-card {
    border-top: 4px solid #43a047;
}

.data-body {

    width: 100%;
    height: 100%;

    min-height: 0;

    display: grid;

    grid-template-rows:
        1fr
        1fr
        1fr
        50px;

    gap: 6px;

    overflow: hidden;
}

.data-item {

    width: 100%;
    height: 100%;

    min-height: 0;

    background: #f7f9fc;

    border-radius: 8px;

    padding: 6px 10px;

    overflow: hidden;
}

.data-label {

    height: 20px;

    line-height: 20px;

    font-size: 12px;

    color: #777;

    font-weight: bold;
}

.data-value {

    height: calc(100% - 20px);

    line-height: 30px;

    font-size: 20px;

    color: #1565c0;

    font-weight: bold;

    white-space: nowrap;

    overflow: hidden;

    text-overflow: ellipsis;
}

.data-status {
    color: #388e3c;
}


/* =========================================================
   ACTION BUTTON
========================================================= */

.action-button {

    width: 100%;

    height: 50px;

    border: none;

    border-radius: 9px;

    color: white;

    font-size: 18px;

    font-weight: bold;

    cursor: pointer;

    display: none;
}

.btn-pinjam {

    background: #43a047;

    box-shadow:
        0 3px 0 #2e7d32;
}

.btn-kembali {

    background: #ef6c00;

    box-shadow:
        0 3px 0 #e65100;
}

.action-button:disabled {

    opacity: .6;

    cursor: not-allowed;
}


/* =========================================================
   LOAN CARD
========================================================= */

.loan-card {
    border-top: 4px solid #8e24aa;
}

.loan-body {

    width: 100%;
    height: 100%;

    min-height: 0;

    overflow-y: auto;

    overflow-x: hidden;

    padding: 7px;
}

.loan-body::-webkit-scrollbar {
    width: 5px;
}

.loan-body::-webkit-scrollbar-thumb {

    background: #bbb;

    border-radius: 5px;
}

.loan-empty {

    width: 100%;
    height: 100%;

    display: flex;

    align-items: center;
    justify-content: center;

    text-align: center;

    color: #999;

    font-size: 15px;
}


/* =========================================================
   TABEL PINJAMAN
========================================================= */

.loan-table {

    width: 100%;

    border-collapse: collapse;

    font-size: 12px;
}

.loan-table th {

    background: #8e24aa;

    color: white;

    padding: 7px 6px;

    text-align: left;

    white-space: nowrap;

    position: sticky;

    top: 0;

    z-index: 2;
}

.loan-table td {

    padding: 7px 6px;

    border-bottom: 1px solid #eee;

    vertical-align: top;
}

.loan-table tr:nth-child(even) {
    background: #faf7fc;
}

.loan-table .book-title {

    font-weight: bold;

    color: #5e2a68;

    max-width: 180px;
}

.loan-table .overdue {

    color: #d32f2f;

    font-weight: bold;
}

.loan-table .ontime {

    color: #388e3c;

    font-weight: bold;
}


/* =========================================================
   FOOTER
========================================================= */

.footer {

    height: 24px;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #777;

    font-size: 10px;

    white-space: nowrap;

    overflow: hidden;
}


/* =========================================================
   POPUP
========================================================= */

.result-overlay {

    position: fixed;

    z-index: 99999;

    left: 0;
    top: 0;

    width: 100vw;
    height: 100vh;

    background: rgba(0,0,0,.55);

    display: none;

    align-items: center;

    justify-content: center;
}

.result-box {

    width: min(480px, 90vw);

    background: white;

    border-radius: 20px;

    padding: 30px;

    text-align: center;

    box-shadow:
        0 10px 40px rgba(0,0,0,.4);
}

.result-icon {

    font-size: 65px;
}

.result-title {

    margin-top: 5px;

    font-size: 28px;

    font-weight: bold;
}

.result-message {

    margin-top: 8px;

    font-size: 16px;

    color: #555;

    line-height: 1.5;
}


/* =========================================================
   LAPTOP PENDEK
========================================================= */

@media screen
and (min-width: 700px)
and (max-height: 700px) {

    .selfservice {

        padding: 5px;

        grid-template-rows:
            35px
            minmax(0, 1fr)
            18px;

        gap: 5px;
    }

    .header {

        height: 35px;

        padding: 0 12px;
    }

    .header-title {
        font-size: 16px;
    }

    .header-school {
        font-size: 13px;
    }

    .dashboard {
        gap: 5px;
    }

    .card-header {

        height: 31px;

        min-height: 31px;

        font-size: 13px;

        padding: 0 10px;
    }

    .card-body {

        height: calc(100% - 31px);

        padding: 7px;
    }

    .input-box {

        height: 39px;

        font-size: 15px;
    }

    .btn-cek {

        height: 42px;

        font-size: 16px;
    }

    .btn-reset {
        height: 32px;
    }

    .data-label {

        height: 16px;

        line-height: 16px;

        font-size: 10px;
    }

    .data-value {

        height: calc(100% - 16px);

        line-height: 25px;

        font-size: 17px;
    }

    .action-button {

        height: 42px;

        font-size: 15px;
    }

    .loan-table {
        font-size: 10px;
    }

    .loan-table th,
    .loan-table td {
        padding: 5px 4px;
    }

    .footer {
        height: 18px;
        font-size: 8px;
    }
}


/* =========================================================
   MOBILE
========================================================= */

@media screen
and (max-width: 700px) {

    .selfservice {

        padding: 4px;

        grid-template-rows:
            35px
            minmax(0, 1fr)
            16px;

        gap: 4px;
    }

    .header {

        height: 35px;

        justify-content: center;
    }

    .header-title {
        font-size: 14px;
    }

    .header-school {
        display: none;
    }

    .dashboard {
        gap: 5px;
    }

    .card-header {

        height: 29px;

        min-height: 29px;

        font-size: 11px;

        padding: 0 8px;
    }

    .card-body {

        height: calc(100% - 29px);

        padding: 6px;
    }

    .input-label {
        font-size: 11px;
    }

    .input-box {

        height: 35px;

        font-size: 14px;

        padding: 0 8px;
    }

    .btn-cek {

        height: 38px;

        font-size: 14px;
    }

    .btn-reset {

        height: 30px;

        font-size: 11px;
    }

    .data-label {

        height: 15px;

        line-height: 15px;

        font-size: 9px;
    }

    .data-value {

        height: calc(100% - 15px);

        line-height: 22px;

        font-size: 14px;
    }

    .action-button {

        height: 40px;

        font-size: 13px;
    }

    .loan-table {

        font-size: 9px;
    }

    .loan-table th,
    .loan-table td {

        padding: 4px 3px;
    }

    .footer {

        height: 16px;

        font-size: 7px;
    }
}


/* =========================================================
   HP KECIL
========================================================= */

@media screen
and (max-width: 500px)
and (max-height: 700px) {

    .selfservice {

        padding: 3px;

        grid-template-rows:
            30px
            minmax(0, 1fr)
            14px;

        gap: 3px;
    }

    .header {
        height: 30px;
    }

    .header-title {
        font-size: 12px;
    }

    .dashboard {
        gap: 3px;
    }

    .card-header {

        height: 25px;

        min-height: 25px;

        font-size: 9px;
    }

    .card-body {

        height: calc(100% - 25px);

        padding: 4px;
    }

    .input-box {

        height: 31px;

        font-size: 12px;
    }

    .btn-cek {

        height: 34px;

        font-size: 12px;
    }

    .btn-reset {

        height: 27px;

        font-size: 10px;
    }

    .data-label {

        height: 12px;

        line-height: 12px;

        font-size: 8px;
    }

    .data-value {

        height: calc(100% - 12px);

        line-height: 18px;

        font-size: 12px;
    }

    .action-button {

        height: 34px;

        font-size: 11px;
    }

    .loan-table {

        font-size: 8px;
    }

    .loan-table th,
    .loan-table td {

        padding: 3px 2px;
    }

    .footer {

        height: 14px;

        font-size: 6px;
    }
}

</style>

</head>


<body>

<div class="selfservice">


    <!-- HEADER -->

    <div class="header">

        <div class="header-title">

            📚 SELF SERVICE PERPUSTAKAAN

        </div>

        <div class="header-school">

            SD Negeri Getaskerep 01

        </div>

    </div>



    <!-- 4 CARD -->

    <div class="dashboard">


        <!-- =================================================
             KIRI ATAS - SCAN
        ================================================== -->

        <div class="card scan-card">

            <div class="card-header header-blue">

                <span>
                    <i class="fa fa-qrcode"></i>
                    SCAN QR CODE
                </span>

            </div>


            <div class="card-body">

                <div class="scan-body">

                    <div id="reader"></div>

                    <div
                        class="scan-status"
                        id="scanStatus"
                    >

                        📷 Menyiapkan kamera...

                    </div>

                </div>

            </div>

        </div>



        <!-- =================================================
             KANAN ATAS - DATA
        ================================================== -->

        <div class="card data-card">

            <div class="card-header header-green">

                <span>

                    <i class="fa fa-info-circle"></i>

                    DATA TRANSAKSI

                </span>

            </div>


            <div class="card-body">

                <div class="data-body">


                    <!-- ANGGOTA -->

                    <div class="data-item">

                        <div class="data-label">

                            👤 ANGGOTA

                        </div>

                        <div
                            class="data-value"
                            id="memberName"
                        >

                            Belum ada anggota

                        </div>

                    </div>



                    <!-- BUKU -->

                    <div class="data-item">

                        <div class="data-label">

                            📖 BUKU

                        </div>

                        <div
                            class="data-value"
                            id="bookTitle"
                        >

                            Belum ada buku

                        </div>

                    </div>



                    <!-- STATUS -->

                    <div class="data-item">

                        <div class="data-label">

                            STATUS BUKU

                        </div>

                        <div
                            class="data-value data-status"
                            id="transactionStatus"
                        >

                            Silakan scan anggota

                        </div>

                    </div>



                    <!-- ACTION -->

                    <button
                        type="button"
                        id="btnAction"
                        class="action-button"
                    ></button>


                </div>

            </div>

        </div>



        <!-- =================================================
             KIRI BAWAH - MANUAL
        ================================================== -->

        <div class="card manual-card">

            <div class="card-header header-orange">

                <span>

                    <i class="fa fa-keyboard-o"></i>

                    INPUT MANUAL

                </span>

            </div>


            <div class="card-body">

                <div class="manual-body">


                    <div>

                        <div class="input-label">

                            ID Anggota

                        </div>

                        <input
                            type="text"
                            id="idAnggota"
                            class="input-box"
                            placeholder="Masukkan ID anggota"
                            autocomplete="off"
                        >

                    </div>



                    <div>

                        <div class="input-label">

                            Kode Buku

                        </div>

                        <input
                            type="text"
                            id="kodeBuku"
                            class="input-box"
                            placeholder="Masukkan kode buku"
                            autocomplete="off"
                        >

                    </div>



                    <button
                        type="button"
                        id="btnCek"
                        class="btn-cek"
                    >

                        <i class="fa fa-search"></i>

                        CEK

                    </button>



                    <button
                        type="button"
                        id="btnReset"
                        class="btn-reset"
                    >

                        <i class="fa fa-refresh"></i>

                        RESET

                    </button>


                </div>

            </div>

        </div>



        <!-- =================================================
             KANAN BAWAH - PINJAMAN
        ================================================== -->

        <div class="card loan-card">

            <div class="card-header header-purple">

                <span>

                    <i class="fa fa-book"></i>

                    BUKU YANG SEDANG DIPINJAM

                </span>


                <span
                    id="loanCount"
                    class="badge"
                    style="background:#8e24aa;"
                >

                    0

                </span>

            </div>


            <div
                class="loan-body"
                id="loanList"
            >

                <div class="loan-empty">

                    📚

                    <br><br>

                    Scan anggota untuk melihat
                    <br>
                    buku yang sedang dipinjam.

                </div>

            </div>

        </div>


    </div>



    <!-- FOOTER -->

    <div class="footer">

        SD Negeri Getaskerep 01 • Self Service

    </div>

</div>



<!-- =========================================================
     POPUP
========================================================= -->

<div
    class="result-overlay"
    id="resultOverlay"
>

    <div class="result-box">

        <div
            class="result-icon"
            id="resultIcon"
        >
            🎉
        </div>

        <div
            class="result-title"
            id="resultTitle"
        >
            Berhasil!
        </div>

        <div
            class="result-message"
            id="resultMessage"
        ></div>

    </div>

</div>



<script src="<?= base_url('assets_style/assets/bower_components/jquery/dist/jquery.min.js'); ?>"></script>

<script src="<?= base_url('assets_style/assets/bower_components/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>


<script>

$(document).ready(function() {


    /* =====================================================
       VARIABLE
    ===================================================== */

    var scanStep = 'anggota';

    var idAnggota = '';

    var kodeBuku = '';

    var sedangScan = false;

    var sedangProses = false;

    var scanner = null;



    /* =====================================================
       STATUS SCANNER
    ===================================================== */

    function status(text) {

        $('#scanStatus').html(text);

    }



    /* =====================================================
       RESET
    ===================================================== */

    function resetPage() {

        scanStep = 'anggota';

        idAnggota = '';

        kodeBuku = '';

        sedangScan = false;

        sedangProses = false;


        $('#idAnggota').val('');

        $('#kodeBuku').val('');


        $('#memberName')
            .text('Belum ada anggota');


        $('#bookTitle')
            .text('Belum ada buku');


        $('#transactionStatus')
            .text('Silakan scan anggota')
            .css('color', '#388e3c');


        $('#btnAction')
            .hide()
            .removeClass('btn-pinjam btn-kembali')
            .text('');


        $('#btnCek')
            .prop('disabled', false)
            .html(
                '<i class="fa fa-search"></i> CEK'
            );


        status(
            '📷 Silakan scan QR Code anggota'
        );


        loadLoans('');

    }



    /* =====================================================
       LOAD SEMUA PINJAMAN ANGGOTA
    ===================================================== */

    function loadLoans(id) {

        if (id === '') {

            $('#loanCount')
                .text('0');


            $('#loanList').html(

                '<div class="loan-empty">' +

                '📚' +

                '<br><br>' +

                'Scan anggota untuk melihat' +

                '<br>' +

                'buku yang sedang dipinjam.' +

                '</div>'

            );

            return;
        }


        $.ajax({

            url:
                '<?= base_url('selfservice/pinjaman'); ?>',

            type:
                'POST',

            dataType:
                'json',

            data: {

                id_anggota:
                    id

            },

            success:
                function(response) {


                    if (
                        !response.status ||
                        !response.data ||
                        response.data.length === 0
                    ) {

                        $('#loanCount')
                            .text('0');


                        $('#loanList').html(

                            '<div class="loan-empty">' +

                            '🎉' +

                            '<br><br>' +

                            'Anggota tidak sedang meminjam buku.' +

                            '</div>'

                        );

                        return;
                    }


                    $('#loanCount')
                        .text(
                            response.data.length
                        );


                    var html =

                        '<table class="loan-table">' +

                        '<thead>' +

                        '<tr>' +

                        '<th>No</th>' +

                        '<th>Buku</th>' +

                        '<th>Kode</th>' +

                        '<th>Pinjam</th>' +

                        '<th>Harus Kembali</th>' +

                        '<th>Status</th>' +

                        '</tr>' +

                        '</thead>' +

                        '<tbody>';


                    $.each(
                        response.data,
                        function(index, item) {


                            var statusText =
                                item.status_kembali ||
                                item.status ||
                                'Dipinjam';


                            var statusClass =
                                item.terlambat
                                    ? 'overdue'
                                    : 'ontime';


                            html +=

                                '<tr>' +

                                '<td>' +

                                (index + 1) +

                                '</td>' +

                                '<td class="book-title">' +

                                escapeHtml(
                                    item.judul ||
                                    item.title ||
                                    '-'
                                ) +

                                '</td>' +

                                '<td>' +

                                escapeHtml(
                                    item.kode ||
                                    item.kode_buku ||
                                    '-'
                                ) +

                                '</td>' +

                                '<td>' +

                                escapeHtml(
                                    item.tgl_pinjam ||
                                    item.tanggal ||
                                    '-'
                                ) +

                                '</td>' +

                                '<td>' +

                                escapeHtml(
                                    item.tgl_balik ||
                                    '-'
                                ) +

                                '</td>' +

                                '<td class="' +
                                statusClass +
                                '">' +

                                escapeHtml(
                                    statusText
                                ) +

                                '</td>' +

                                '</tr>';

                        }
                    );


                    html +=

                        '</tbody>' +

                        '</table>';


                    $('#loanList')
                        .html(html);

                },


            error:
                function() {

                    $('#loanCount')
                        .text('0');

                    $('#loanList').html(

                        '<div class="loan-empty">' +

                        '⚠️ Gagal mengambil data pinjaman.' +

                        '</div>'

                    );

                }

        });

    }



    /* =====================================================
       ESCAPE HTML
    ===================================================== */

    function escapeHtml(text) {

        return $('<div>')
            .text(text == null ? '' : text)
            .html();

    }



    /* =====================================================
       TAMPILKAN DATA ANGGOTA
    ===================================================== */

    function setMember(data) {

        $('#memberName')
            .text(
                data.nama ||
                data.name ||
                'Anggota'
            );

    }



    /* =====================================================
       CEK ANGGOTA
    ===================================================== */

    function cekAnggota(id) {

        id = $.trim(id);

        if (id === '') {

            alert(
                'Silakan masukkan ID anggota.'
            );

            return false;
        }


        status(
            '<i class="fa fa-spinner fa-spin"></i> Memeriksa anggota...'
        );


        $.ajax({

            url:
                '<?= base_url('selfservice/cek_anggota'); ?>',

            type:
                'POST',

            dataType:
                'json',

            data: {

                id_anggota:
                    id

            },


            success:
                function(response) {


                    if (
                        response.status
                    ) {


                        idAnggota =
                            id;


                        $('#idAnggota')
                            .val(id);


                        setMember(response);


                        $('#transactionStatus')
                            .text(
                                'Anggota ditemukan • Scan buku'
                            )
                            .css(
                                'color',
                                '#388e3c'
                            );


                        scanStep =
                            'buku';


                        loadLoans(id);


                        status(
                            '📖 Silakan scan QR Code buku'
                        );

                    }

                    else {


                        alert(
                            response.message ||
                            'Anggota tidak ditemukan.'
                        );


                        resetPage();

                    }

                },


            error:
                function() {

                    alert(
                        'Gagal memeriksa anggota.'
                    );

                    resetPage();

                }

        });


        return true;

    }



    /* =====================================================
       CEK BUKU
    ===================================================== */

    function cekBuku(kode) {

        kode = $.trim(kode);


        if (kode === '') {

            alert(
                'Silakan masukkan kode buku.'
            );

            return false;

        }


        if (idAnggota === '') {

            alert(
                'Silakan scan/input anggota terlebih dahulu.'
            );

            return false;

        }


        status(
            '<i class="fa fa-spinner fa-spin"></i> Memeriksa buku...'
        );


        $.ajax({

            url:
                '<?= base_url('selfservice/cek_buku'); ?>',

            type:
                'POST',

            dataType:
                'json',

            data: {

                id_anggota:
                    idAnggota,

                kode_buku:
                    kode

            },


            success:
                function(response) {


                    if (
                        response.status
                    ) {


                        kodeBuku =
                            kode;


                        $('#kodeBuku')
                            .val(kode);


                        $('#bookTitle')
                            .text(
                                response.judul ||
                                response.title ||
                                'Buku ditemukan'
                            );


                        /*
                         * Tentukan tombol berdasarkan
                         * status transaksi.
                         */

                        if (
                            response.aksi ===
                            'kembali'
                        ) {


                            $('#transactionStatus')
                                .text(
                                    '↩️ BUKU SEDANG DIPINJAM'
                                )
                                .css(
                                    'color',
                                    '#ef6c00'
                                );


                            $('#btnAction')
                                .removeClass(
                                    'btn-pinjam'
                                )
                                .addClass(
                                    'btn-kembali'
                                )
                                .html(
                                    '<i class="fa fa-undo"></i> KEMBALIKAN BUKU'
                                )
                                .attr(
                                    'data-action',
                                    'kembali'
                                )
                                .show();

                        }

                        else {


                            $('#transactionStatus')
                                .text(
                                    '📚 BUKU TERSEDIA'
                                )
                                .css(
                                    'color',
                                    '#388e3c'
                                );


                            $('#btnAction')
                                .removeClass(
                                    'btn-kembali'
                                )
                                .addClass(
                                    'btn-pinjam'
                                )
                                .html(
                                    '<i class="fa fa-book"></i> PINJAM BUKU'
                                )
                                .attr(
                                    'data-action',
                                    'pinjam'
                                )
                                .show();

                        }


                        status(
                            '👍 Data ditemukan • Silakan pilih tindakan'
                        );

                    }

                    else {


                        alert(
                            response.message ||
                            'Buku tidak ditemukan.'
                        );


                        kodeBuku = '';

                        $('#bookTitle')
                            .text(
                                'Belum ada buku'
                            );

                        $('#btnAction')
                            .hide();

                        status(
                            '📖 Silakan scan buku lagi'
                        );

                    }

                },


            error:
                function() {

                    alert(
                        'Gagal memeriksa buku.'
                    );

                }

        });


        return true;

    }



    /* =====================================================
       SCAN SUCCESS
    ===================================================== */

    function onScanSuccess(decodedText) {

        if (
            sedangScan ||
            sedangProses
        ) {

            return;

        }


        decodedText =
            $.trim(decodedText);


        if (
            decodedText === ''
        ) {

            return;

        }


        sedangScan = true;


        setTimeout(
            function() {

                sedangScan = false;

            },
            1200
        );


        if (
            scanStep === 'anggota'
        ) {


            $('#idAnggota')
                .val(decodedText);


            cekAnggota(
                decodedText
            );

        }

        else {


            $('#kodeBuku')
                .val(decodedText);


            cekBuku(
                decodedText
            );

        }

    }



    /* =====================================================
       SCAN ERROR
    ===================================================== */

    function onScanError(error) {

        // Jangan tampilkan error setiap frame kamera.

    }



    /* =====================================================
       START SCANNER
    ===================================================== */

    function startScanner() {


        scanner =
            new Html5Qrcode(
                'reader'
            );


        scanner.start(

            {
                facingMode:
                    'environment'
            },

            {

                fps: 10,

                qrbox:
                    function(
                        width,
                        height
                    ) {


                        var size =
                            Math.floor(
                                Math.min(
                                    width,
                                    height
                                ) * 0.65
                            );


                        return {

                            width:
                                size,

                            height:
                                size

                        };

                    }

            },

            onScanSuccess,

            onScanError

        )

        .then(
            function() {

                status(
                    '📷 Kamera aktif • Scan anggota'
                );

            }
        )

        .catch(
            function() {

                status(
                    '⚠️ Kamera tidak tersedia • Gunakan input manual'
                );

            }
        );

    }



    /* =====================================================
       TOMBOL CEK
    ===================================================== */

    $('#btnCek').click(
        function() {


            var anggota =
                $.trim(
                    $('#idAnggota')
                        .val()
                );


            var buku =
                $.trim(
                    $('#kodeBuku')
                        .val()
                );


            if (
                anggota === ''
            ) {

                alert(
                    'Silakan masukkan ID anggota.'
                );

                $('#idAnggota')
                    .focus();

                return;

            }


            idAnggota =
                anggota;


            /*
             * Jika buku kosong:
             * cek anggota saja.
             */

            if (
                buku === ''
            ) {

                cekAnggota(
                    anggota
                );

                return;

            }


            /*
             * Jika keduanya ada:
             * cek anggota dahulu,
             * kemudian buku.
             */

            $('#btnCek')
                .prop(
                    'disabled',
                    true
                )
                .html(
                    '<i class="fa fa-spinner fa-spin"></i> CEK...'
                );


            $.ajax({

                url:
                    '<?= base_url('selfservice/cek_anggota'); ?>',

                type:
                    'POST',

                dataType:
                    'json',

                data: {

                    id_anggota:
                        anggota

                },


                success:
                    function(response) {


                        if (
                            response.status
                        ) {


                            setMember(
                                response
                            );


                            idAnggota =
                                anggota;


                            loadLoans(
                                anggota
                            );


                            cekBuku(
                                buku
                            );

                        }

                        else {


                            alert(
                                response.message ||
                                'Anggota tidak ditemukan.'
                            );

                        }

                    },


                error:
                    function() {

                    alert(
                        'Gagal memeriksa anggota.'
                    );

                },


                complete:
                    function() {

                        $('#btnCek')
                            .prop(
                                'disabled',
                                false
                            )
                            .html(
                                '<i class="fa fa-search"></i> CEK'
                            );

                    }

            });

        }
    );



    /* =====================================================
       ENTER ID
    ===================================================== */

    $('#idAnggota').keypress(
        function(e) {


            if (
                e.which === 13
            ) {

                e.preventDefault();

                $('#kodeBuku')
                    .focus();

            }

        }
    );



    /* =====================================================
       ENTER BUKU
    ===================================================== */

    $('#kodeBuku').keypress(
        function(e) {


            if (
                e.which === 13
            ) {

                e.preventDefault();

                $('#btnCek')
                    .click();

            }

        }
    );



    /* =====================================================
       ACTION PINJAM / KEMBALI
    ===================================================== */

    $('#btnAction').click(
        function() {


            if (
                sedangProses
            ) {

                return;

            }


            var action =
                $(this)
                    .attr(
                        'data-action'
                    );


            if (
                idAnggota === ''
            ) {

                alert(
                    'ID anggota belum ada.'
                );

                return;

            }


            if (
                kodeBuku === ''
            ) {

                alert(
                    'Kode buku belum ada.'
                );

                return;

            }


            var konfirmasi;


            if (
                action === 'kembali'
            ) {

                konfirmasi =
                    confirm(
                        'Apakah buku ini akan dikembalikan?'
                    );

            }

            else {

                konfirmasi =
                    confirm(
                        'Apakah buku ini akan dipinjam?'
                    );

            }


            if (
                !konfirmasi
            ) {

                return;

            }


            sedangProses =
                true;


            $('#btnAction')
                .prop(
                    'disabled',
                    true
                )
                .html(
                    '<i class="fa fa-spinner fa-spin"></i> MEMPROSES...'
                );


            $.ajax({

                url:
                    '<?= base_url('selfservice/proses'); ?>',

                type:
                    'POST',

                dataType:
                    'json',

                data: {

                    id_anggota:
                        idAnggota,

                    kode_buku:
                        kodeBuku,

                    aksi:
                        action

                },


                success:
                    function(response) {


                        if (
                            response.status
                        ) {


                            var isReturn =
                                response.aksi ===
                                'kembali' ||
                                action ===
                                'kembali';


                            $('#resultIcon')
                                .text(
                                    isReturn
                                        ? '😊'
                                        : '🎉'
                                );


                            $('#resultTitle')
                                .text(
                                    isReturn
                                        ? 'Buku Dikembalikan!'
                                        : 'Buku Berhasil Dipinjam!'
                                );


                            $('#resultMessage')
                                .html(
                                    response.message ||
                                    'Transaksi berhasil.'
                                );


                            $('#resultOverlay')
                                .css(
                                    'display',
                                    'flex'
                                );


                            /*
                             * REFRESH DATA PINJAMAN
                             * SEBELUM RESET.
                             */

                            loadLoans(
                                idAnggota
                            );


                            setTimeout(
                                function() {


                                    $('#resultOverlay')
                                        .hide();


                                    /*
                                     * Setelah transaksi,
                                     * tetap tampilkan anggota.
                                     */

                                    $('#kodeBuku')
                                        .val('');


                                    kodeBuku =
                                        '';


                                    $('#bookTitle')
                                        .text(
                                            'Belum ada buku'
                                        );


                                    $('#transactionStatus')
                                        .text(
                                            'Silakan scan buku berikutnya'
                                        )
                                        .css(
                                            'color',
                                            '#388e3c'
                                        );


                                    $('#btnAction')
                                        .hide()
                                        .prop(
                                            'disabled',
                                            false
                                        );


                                    sedangProses =
                                        false;


                                    scanStep =
                                        'buku';


                                    status(
                                        '📖 Scan buku berikutnya'
                                    );


                                    /*
                                     * Refresh sekali lagi
                                     * supaya tabel benar-benar
                                     * realtime.
                                     */

                                    loadLoans(
                                        idAnggota
                                    );


                                },
                                1800
                            );

                        }

                        else {


                            sedangProses =
                                false;


                            $('#btnAction')
                                .prop(
                                    'disabled',
                                    false
                                );


                            if (
                                action ===
                                'kembali'
                            ) {

                                $('#btnAction')
                                    .html(
                                        '<i class="fa fa-undo"></i> KEMBALIKAN BUKU'
                                    );

                            }

                            else {

                                $('#btnAction')
                                    .html(
                                        '<i class="fa fa-book"></i> PINJAM BUKU'
                                    );

                            }


                            alert(
                                response.message ||
                                'Transaksi gagal.'
                            );


                            /*
                             * Cek ulang kondisi buku.
                             */

                            cekBuku(
                                kodeBuku
                            );

                        }

                    },


                error:
                    function() {


                        sedangProses =
                            false;


                        $('#btnAction')
                            .prop(
                                'disabled',
                                false
                            );


                        alert(
                            'Terjadi kesalahan pada server.'
                        );


                        cekBuku(
                            kodeBuku
                        );

                    }

            });

        }
    );



    /* =====================================================
       RESET
    ===================================================== */

    $('#btnReset').click(
        function() {

            resetPage();

        }
    );



    /* =====================================================
       START
    ===================================================== */

    resetPage();

    startScanner();


});

</script>

</body>

</html>
