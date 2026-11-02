

<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>



<?php

$CI =& get_instance();

$CI->load->database();



// ==========================================

// FILTER DATA

// ==========================================



$filterNama = trim($CI->input->get('nama', TRUE) ?? '');

$filterJenkel = $CI->input->get('jenkel', TRUE) ?? '';



$tanggalMulai = $CI->input->get('tanggal_mulai', TRUE) ?? '';

$tanggalSelesai = $CI->input->get('tanggal_selesai', TRUE) ?? '';



// Validasi format tanggal

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalMulai)) {

    $tanggalMulai = '';

}



if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggalSelesai)) {

    $tanggalSelesai = '';

}



// Validasi urutan tanggal

if (

    $tanggalMulai !== '' &&

    $tanggalSelesai !== '' &&

    $tanggalMulai > $tanggalSelesai

) {

    $tanggalMulai = '';

    $tanggalSelesai = '';

}



// ==========================================

// FUNGSI MENGHITUNG KEHADIRAN

// ==========================================



function getJumlah($CI, $idSiswa, $tanggalMulai = '', $tanggalSelesai = '')

{

    $CI->db->from('tbl_kehadiran');



    // Mengikuti struktur pencarian pada kode awal

    $CI->db->like('siswa_id', $idSiswa);



    if ($tanggalMulai !== '') {

        $CI->db->where('tgl_kehadiran >=', $tanggalMulai);

    }



    if ($tanggalSelesai !== '') {

        $CI->db->where(

            'tgl_kehadiran <',

            date('Y-m-d', strtotime($tanggalSelesai . ' +1 day'))

        );

    }



    return $CI->db->count_all_results();

}



// ==========================================

// FILTER SISWA

// ==========================================



$dataSiswa = [];



if (!empty($siswa)) {



    foreach ($siswa as $isi) {



        // Filter nama siswa

        if (

            $filterNama !== '' &&

            stripos($isi['nama'], $filterNama) === false

        ) {

            continue;

        }



        // Filter jenis kelamin

        if (

            $filterJenkel !== '' &&

            strtolower(trim($isi['jenkel'])) !== strtolower(trim($filterJenkel))

        ) {

            continue;

        }



        // Hitung jumlah kehadiran

        $isi['jumlah_kehadiran'] = getJumlah(

            $CI,

            $isi['id_siswa'],

            $tanggalMulai,

            $tanggalSelesai

        );



        $dataSiswa[] = $isi;

    }

}



// ==========================================

// REKAPITULASI

// ==========================================



$totalSiswa = count($dataSiswa);

$totalKehadiran = 0;

$totalLaki = 0;

$totalPerempuan = 0;



foreach ($dataSiswa as $isi) {



    $totalKehadiran += $isi['jumlah_kehadiran'];



    if (strtolower(trim($isi['jenkel'])) == 'laki-laki') {

        $totalLaki++;

    } elseif (strtolower(trim($isi['jenkel'])) == 'perempuan') {

        $totalPerempuan++;

    }

}



// ==========================================

// INFORMASI PERIODE CETAK

// ==========================================



$periodeMulai = $tanggalMulai

    ? date('d-m-Y', strtotime($tanggalMulai))

    : 'Semua Tanggal';



$periodeSelesai = $tanggalSelesai

    ? date('d-m-Y', strtotime($tanggalSelesai))

    : 'Sekarang';



?>



<div class="content-wrapper">



    <!-- HEADER -->

    <section class="content-header">



        <h1>

            <i class="fa fa-calendar-check-o" style="color:green"></i>

            Data Jumlah Kehadiran Siswa

        </h1>



        <ol class="breadcrumb">

            <li>

                <a href="<?= base_url('dashboard'); ?>">

                    <i class="fa fa-dashboard"></i> Dashboard

                </a>

            </li>



            <li class="active">

                Data Jumlah Kehadiran Siswa

            </li>

        </ol>



    </section>



    <section class="content">



        <!-- FLASH MESSAGE -->

        <?php if (!empty($this->session->flashdata('pesan'))) {

            echo $this->session->flashdata('pesan');

        } ?>



        <!-- ======================================

             FILTER DATA

        ======================================= -->



        <div class="box box-primary">



            <div class="box-header with-border">



                <h3 class="box-title">

                    <i class="fa fa-filter"></i>

                    Filter Data Kehadiran

                </h3>



            </div>



            <div class="box-body">



                <form method="GET" action="<?= current_url(); ?>">



                    <div class="row">



                        <!-- NAMA -->

                        <div class="col-md-3">



                            <div class="form-group">



                                <label>Nama Siswa</label>



                                <input

                                    type="text"

                                    name="nama"

                                    class="form-control"

                                    placeholder="Cari nama siswa..."

                                    value="<?= html_escape($filterNama); ?>">



                            </div>



                        </div>



                        <!-- JENIS KELAMIN -->

                        <div class="col-md-2">



                            <div class="form-group">



                                <label>Jenis Kelamin</label>



                                <select name="jenkel" class="form-control">



                                    <option value="">Semua</option>



                                    <option value="Laki-laki"

                                        <?= $filterJenkel == 'Laki-laki' ? 'selected' : ''; ?>>

                                        Laki-laki

                                    </option>



                                    <option value="Perempuan"

                                        <?= $filterJenkel == 'Perempuan' ? 'selected' : ''; ?>>

                                        Perempuan

                                    </option>



                                </select>



                            </div>



                        </div>



                        <!-- TANGGAL MULAI -->

                        <div class="col-md-2">



                            <div class="form-group">



                                <label>Tanggal Mulai</label>



                                <input

                                    type="date"

                                    name="tanggal_mulai"

                                    class="form-control"

                                    value="<?= html_escape($tanggalMulai); ?>">



                            </div>



                        </div>



                        <!-- TANGGAL SELESAI -->

                        <div class="col-md-2">



                            <div class="form-group">



                                <label>Tanggal Selesai</label>



                                <input

                                    type="date"

                                    name="tanggal_selesai"

                                    class="form-control"

                                    value="<?= html_escape($tanggalSelesai); ?>">



                            </div>



                        </div>



                        <!-- BUTTON -->

                        <div class="col-md-3">



                            <div class="form-group">



                                <label>&nbsp;</label>



                                <div>



                                    <button type="submit" class="btn btn-primary">

                                        <i class="fa fa-search"></i>

                                        Filter

                                    </button>



                                    <a href="<?= current_url(); ?>"

                                       class="btn btn-default">

                                        <i class="fa fa-refresh"></i>

                                        Reset

                                    </a>



                                </div>



                            </div>



                        </div>



                    </div>



                </form>



            </div>



        </div>



        <!-- ======================================

             REKAP DATA

        ======================================= -->



        <div class="row">



            <div class="col-md-3 col-sm-6">



                <div class="small-box bg-aqua">



                    <div class="inner">



                        <h3><?= $totalSiswa; ?></h3>



                        <p>Total Siswa</p>



                    </div>



                    <div class="icon">

                        <i class="fa fa-users"></i>

                    </div>



                </div>



            </div>



            <div class="col-md-3 col-sm-6">



                <div class="small-box bg-green">



                    <div class="inner">



                        <h3><?= $totalKehadiran; ?></h3>



                        <p>Total Kehadiran</p>



                    </div>



                    <div class="icon">

                        <i class="fa fa-calendar-check-o"></i>

                    </div>



                </div>



            </div>



            <div class="col-md-3 col-sm-6">



                <div class="small-box bg-blue">



                    <div class="inner">



                        <h3><?= $totalLaki; ?></h3>



                        <p>Siswa Laki-laki</p>



                    </div>



                    <div class="icon">

                        <i class="fa fa-male"></i>

                    </div>



                </div>



            </div>



            <div class="col-md-3 col-sm-6">



                <div class="small-box bg-purple">



                    <div class="inner">



                        <h3><?= $totalPerempuan; ?></h3>



                        <p>Siswa Perempuan</p>



                    </div>



                    <div class="icon">

                        <i class="fa fa-female"></i>

                    </div>



                </div>



            </div>



        </div>



        <!-- ======================================

             TABEL DATA

        ======================================= -->



        <div class="box box-primary">



            <div class="box-header with-border">



                <h3 class="box-title">

                    <i class="fa fa-table"></i>

                    Laporan Kehadiran Siswa

                </h3>



                <div class="box-tools pull-right">



                    <button

                        type="button"

                        onclick="cetakReportPDF()"

                        class="btn btn-warning">



                        <i class="fa fa-print"></i>

                        Cetak Report



                    </button>



                </div>



            </div>



            <div class="box-body">



                <div class="table-responsive">



                    <table class="table table-bordered table-striped"

                           width="100%">



                        <thead>



                            <tr>

                                <th width="5%">No</th>

                                <th>Kode Anggota</th>

                                <th>Nama</th>

                                <th>Jenis Kelamin</th>

                                <th>Jumlah Kehadiran</th>

                            </tr>



                        </thead>



                        <tbody>



                            <?php if (!empty($dataSiswa)) : ?>



                                <?php $no = 1; ?>



                                <?php foreach ($dataSiswa as $isi) : ?>



                                    <tr>



                                        <td><?= $no++; ?></td>



                                        <td>

                                            <?= html_escape($isi['kode_anggota']); ?>

                                        </td>



                                        <td>

                                            <?= html_escape($isi['nama']); ?>

                                        </td>



                                        <td>

                                            <?= html_escape($isi['jenkel']); ?>

                                        </td>



                                        <td>

                                            <strong>

                                                <?= $isi['jumlah_kehadiran']; ?> Kali

                                            </strong>

                                        </td>



                                    </tr>



                                <?php endforeach; ?>



                            <?php else : ?>



                                <tr>



                                    <td colspan="5" class="text-center">



                                        <i class="fa fa-info-circle"></i>

                                        Data kehadiran tidak ditemukan.



                                    </td>



                                </tr>



                            <?php endif; ?>



                        </tbody>



                    </table>



                </div>



            </div>



        </div>



    </section>



</div>





<!-- ==================================================

     TEMPLATE KHUSUS CETAK REPORT

\=================================================== -->



<div id="printArea">



    <!-- KOP SURAT -->



    <div class="kop-laporan">



        <div class="kop-logo">



            <img

                src="<?= base_url('assets_style/assets/img/logo.png'); ?>"

                onerror="this.style.display='none';"

                alt="Logo Sekolah">



        </div>



        <div class="kop-text">



            <h2>PEMERINTAH KABUPATEN TEGAL</h2>



            <h2>SEKOLAH DASAR NEGERI GETASKEREP 01</h2>



            <h3>PERPUSTAKAAN SEKOLAH</h3>



            <p>LAPORAN REKAPITULASI JUMLAH KEHADIRAN SISWA</p>



        </div>



    </div>



    <div class="kop-garis"></div>





    <!-- PARAMETER LAPORAN -->



    <div class="parameter-laporan">



        <h3>INFORMASI LAPORAN</h3>



        <table class="tabel-parameter">



            <tr>



                <td>Nama Siswa</td>

                <td>:</td>

                <td>

                    <?= $filterNama !== '' ? html_escape($filterNama) : 'Semua Siswa'; ?>

                </td>



                <td>Jenis Kelamin</td>

                <td>:</td>

                <td>

                    <?= $filterJenkel !== '' ? html_escape($filterJenkel) : 'Semua'; ?>

                </td>



            </tr>



            <tr>



                <td>Tanggal Mulai</td>

                <td>:</td>

                <td><?= $periodeMulai; ?></td>



                <td>Tanggal Selesai</td>

                <td>:</td>

                <td><?= $periodeSelesai; ?></td>



            </tr>



            <tr>



                <td>Tanggal Cetak</td>

                <td>:</td>

                <td colspan="4">

                    <?= date('d-m-Y H:i:s'); ?>

                </td>



            </tr>



        </table>



    </div>





    <!-- TABEL LAPORAN -->



    <table class="tabel-laporan">



        <thead>



            <tr>



                <th width="6%">No</th>



                <th width="19%">Kode Anggota</th>



                <th width="35%">Nama Siswa</th>



                <th width="20%">Jenis Kelamin</th>



                <th width="20%">Jumlah Kehadiran</th>



            </tr>



        </thead>



        <tbody>



            <?php if (!empty($dataSiswa)) : ?>



                <?php $noCetak = 1; ?>



                <?php foreach ($dataSiswa as $isi) : ?>



                    <tr>



                        <td class="text-center">

                            <?= $noCetak++; ?>

                        </td>



                        <td>

                            <?= html_escape($isi['kode_anggota']); ?>

                        </td>



                        <td>

                            <?= html_escape($isi['nama']); ?>

                        </td>



                        <td class="text-center">

                            <?= html_escape($isi['jenkel']); ?>

                        </td>



                        <td class="text-center">

                            <?= $isi['jumlah_kehadiran']; ?> Kali

                        </td>



                    </tr>



                <?php endforeach; ?>



            <?php else : ?>



                <tr>



                    <td colspan="5" class="text-center">

                        Tidak ada data kehadiran.

                    </td>



                </tr>



            <?php endif; ?>



        </tbody>



        <tfoot>
            <tr>
                <td colspan="4"></td>
                <th class="text-center">TOTAL: <?= $totalKehadiran; ?> Kali</th>
            </tr>
        </tfoot>



    </table>





    <!-- RINGKASAN -->



    <div class="ringkasan-laporan">



        <strong>RINGKASAN LAPORAN</strong>



        <table>



            <tr>

                <td>Total Siswa</td>

                <td>:</td>

                <td><?= $totalSiswa; ?> Siswa</td>

            </tr>



            <tr>

                <td>Siswa Laki-laki</td>

                <td>:</td>

                <td><?= $totalLaki; ?> Siswa</td>

            </tr>



            <tr>

                <td>Siswa Perempuan</td>

                <td>:</td>

                <td><?= $totalPerempuan; ?> Siswa</td>

            </tr>



            <tr>

                <td>Total Kehadiran</td>

                <td>:</td>

                <td><?= $totalKehadiran; ?> Kali</td>

            </tr>



        </table>



    </div>





    <!-- TANDA TANGAN -->
    <table class="ttd-laporan">
        <tr>
            <td>
                <p>Mengetahui,</p>
                <p>Kepala Sekolah</p>
                <br><br><br>
                <hr class="garis-ttd">
                <p>NIP. ................................</p>
            </td>
            <td>
                <p><?= date('d F Y'); ?></p>
                <p>Petugas Perpustakaan</p>
                <br><br><br>
                <hr class="garis-ttd">
                <p>NIP. ................................</p>
            </td>
        </tr>
    </table>


</div>





<!-- ==================================================

     CSS

\=================================================== -->



<style>



    #printArea {

        display: none;

        font-family: "Times New Roman", serif;

        color: #000;

    }



    @media print {



        @page {

            size: A4 portrait;

            margin: 15mm;

        }



        body \* {

            visibility: hidden !important;

        }



        #printArea,

        #printArea \* {

            visibility: visible !important;

        }



        #printArea {

            display: block !important;

            position: absolute;

            top: 0;

            left: 0;

            width: 100%;

            background: white;

            font-family: "Times New Roman", serif;

            font-size: 11pt;

        }



        .content-wrapper {

            display: none !important;

        }



        /\* KOP LAPORAN \*/



        .kop-laporan {

            display: flex;

            align-items: center;

            justify-content: center;

            position: relative;

            min-height: 95px;

            text-align: center;

        }



        .kop-logo {

            position: absolute;

            left: 0;

            width: 75px;

        }



        .kop-logo img {

            width: 65px;

            height: 65px;

            object-fit: contain;

        }



        .kop-text {

            width: 100%;

        }



        .kop-text h2 {

            font-size: 15pt;

            font-weight: bold;

            margin: 3px 0;

        }



        .kop-text h3 {

            font-size: 12pt;

            margin: 4px 0;

            font-weight: bold;

        }



        .kop-text p {

            font-size: 12pt;

            font-weight: bold;

            margin: 5px 0;

        }



        .kop-garis {

            border-top: 3px solid black;

            border-bottom: 1px solid black;

            height: 5px;

            margin: 8px 0 18px;

        }



        /\* PARAMETER \*/



        .parameter-laporan {

            margin-bottom: 18px;

        }



        .parameter-laporan h3 {

            font-size: 12pt;

            margin-bottom: 8px;

        }



        .tabel-parameter {

            width: 100%;

            font-size: 10pt;

            border-collapse: collapse;

        }



        .tabel-parameter td {

            padding: 4px 3px;

            vertical-align: top;

        }



        .tabel-parameter td:nth-child(1),

        .tabel-parameter td:nth-child(4) {

            font-weight: bold;

            white-space: nowrap;

        }



        /\* TABEL \*/



        .tabel-laporan {

            width: 100%;

            border-collapse: collapse;

            margin-top: 12px;

            font-size: 10pt;

        }



        .tabel-laporan th,

        .tabel-laporan td {

            border: 1px solid black !important;

            padding: 8px 6px;

            color: black !important;

        }



        .tabel-laporan thead th {

            text-align: center;

            font-weight: bold;

            background: #e5e5e5 !important;

            -webkit-print-color-adjust: exact;

            print-color-adjust: exact;

        }



        .tabel-laporan tfoot th {

            font-weight: bold;

        }



        .tabel-laporan tr {

            page-break-inside: avoid;

        }



        /\* RINGKASAN \*/



        .ringkasan-laporan {

            margin-top: 18px;

            font-size: 10pt;

        }



        .ringkasan-laporan table {

            margin-top: 5px;

        }



        .ringkasan-laporan td {

            padding: 3px 8px 3px 0;

        }



        /\* TANDA TANGAN \*/



        .ttd-laporan {
            width: 100%;
            margin-top: 35px;
            border-collapse: collapse;
            border: none;
            text-align: center;
            font-size: 10pt;
            page-break-inside: avoid;
        }
        .ttd-laporan td {
            width: 50%;
            padding: 0 12px;
            border: none !important;
            vertical-align: top;
        }
        .ttd-laporan p { margin: 4px 0; }
        .garis-ttd {
            width: 65%;
            margin: 0 auto 4px;
            border: 0;
            border-top: 1px solid #000;
        }



        .text-center {

            text-align: center;

        }



        .text-right {

            text-align: right;

        }



    }



</style>

<script>
function cetakReportPDF() {
    var area = document.getElementById('printArea');

    if (!area) {
        alert('Area cetak #printArea tidak ditemukan.');
        return;
    }

    // Konten #printArea sudah dirender oleh PHP, termasuk seluruh baris data.
    var isiLaporan = area.innerHTML;
    if (!isiLaporan || isiLaporan.trim() === '') {
        alert('Data laporan kosong.');
        return;
    }

    var jendelaCetak = window.open('', '_blank', 'width=1000,height=750');
    if (!jendelaCetak) {
        alert('Jendela cetak diblokir. Izinkan pop-up untuk situs ini, lalu coba lagi.');
        return;
    }

    var cssCetak = `
        @page { size: A4 portrait; margin: 15mm; }
        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; background: #fff; }
        body { color: #000; font-family: "Times New Roman", serif; font-size: 11pt; }
        .kop-laporan { display: flex; align-items: center; justify-content: center; position: relative; min-height: 95px; text-align: center; }
        .kop-logo { position: absolute; left: 0; width: 75px; }
        .kop-logo img { width: 65px; height: 65px; object-fit: contain; }
        .kop-text { width: 100%; }
        .kop-text h2 { font-size: 15pt; font-weight: bold; margin: 3px 0; }
        .kop-text h3 { font-size: 12pt; margin: 4px 0; font-weight: bold; }
        .kop-text p { font-size: 12pt; font-weight: bold; margin: 5px 0; }
        .kop-garis { border-top: 3px solid #000; border-bottom: 1px solid #000; height: 5px; margin: 8px 0 18px; }
        .parameter-laporan { margin-bottom: 18px; }
        .parameter-laporan h3 { font-size: 12pt; margin-bottom: 8px; }
        .tabel-parameter { width: 100%; border-collapse: collapse; font-size: 10pt; }
        .tabel-parameter td { padding: 4px 3px; vertical-align: top; }
        .tabel-laporan { width: 100%; border-collapse: collapse; margin-top: 12px; font-size: 10pt; }
        .tabel-laporan th, .tabel-laporan td { border: 1px solid #000; padding: 8px 6px; color: #000; }
        .tabel-laporan thead th { text-align: center; font-weight: bold; background: #e5e5e5; }
        .tabel-laporan tr { page-break-inside: avoid; }
        .ringkasan-laporan { margin-top: 18px; font-size: 10pt; }
        .ringkasan-laporan table { margin-top: 5px; }
        .ringkasan-laporan td { padding: 3px 8px 3px 0; }
        .ttd-laporan { width: 100%; margin-top: 35px; border-collapse: collapse; border: none; text-align: center; font-size: 10pt; page-break-inside: avoid; }
        .ttd-laporan td { width: 50%; padding: 0 12px; border: none !important; vertical-align: top; }
        .ttd-laporan p { margin: 4px 0; }
        .garis-ttd { width: 65%; margin: 0 auto 4px; border: 0; border-top: 1px solid #000; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        table { page-break-inside: auto; }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
        img { max-width: 100%; }
    `;

    // Pertahankan URL gambar agar logo tetap ditemukan dari halaman aplikasi.
    var baseUrl = window.location.origin + '/';
    var html = `<!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <base href="${baseUrl}">
            <title>Laporan Rekapitulasi Kehadiran Siswa</title>
            <style>${cssCetak}</style>
        </head>
        <body>${isiLaporan}</body>
        </html>`;

    // Pastikan dialog cetak hanya dipanggil satu kali.
    var sudahCetak = false;
    function mulaiCetak() {
        if (sudahCetak || jendelaCetak.closed) return;
        sudahCetak = true;
        jendelaCetak.focus();
        jendelaCetak.print();
    }

    // Pasang handler sebelum dokumen ditulis agar tidak kehilangan event load.
    jendelaCetak.onload = function () {
        setTimeout(mulaiCetak, 700);
    };

    jendelaCetak.document.open();
    jendelaCetak.document.write(html);
    jendelaCetak.document.close();

    // Fallback jika event onload tidak terpanggil pada browser tertentu.
    setTimeout(mulaiCetak, 1800);
}
</script>

