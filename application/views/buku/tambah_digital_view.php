
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="content-wrapper">

    <!-- HEADER -->
    <section class="content-header">
        <h1>
            <i class="fa fa-plus" style="color:green"></i>
            <?= html_escape($title_web); ?>
        </h1>

        <ol class="breadcrumb">
            <li>
                <a href="<?= base_url('dashboard'); ?>">
                    <i class="fa fa-dashboard"></i>&nbsp; Dashboard
                </a>
            </li>
            <li class="active">
                <i class="fa fa-plus"></i>&nbsp;
                <?= html_escape($title_web); ?>
            </li>
        </ol>
    </section>

    <!-- CONTENT -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-primary">

                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-book"></i>
                            Form Tambah Buku Digital
                        </h3>
                    </div>

                    <div class="box-body">

                        <form
                            action="<?= base_url('data/prosesdigital'); ?>"
                            method="POST"
                            enctype="multipart/form-data"
                            id="formBukuDigital"
                        >

                            <!-- CSRF -->
                            <?php if ($this->config->item('csrf_protection')): ?>
                                <input
                                    type="hidden"
                                    name="<?= $this->security->get_csrf_token_name(); ?>"
                                    value="<?= $this->security->get_csrf_hash(); ?>"
                                >
                            <?php endif; ?>

                            <div class="row">

                                <!-- KOLOM KIRI -->
                                <div class="col-sm-6">

                                    <div class="form-group">
                                        <label>
                                            Kategori
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select
                                            class="form-control select2"
                                            required
                                            name="id_kategori"
                                            id="id_kategori"
                                            style="width:100%;"
                                        >
                                            <option value="">
                                                -- Pilih Kategori --
                                            </option>

                                            <?php foreach ($kats as $isi): ?>
                                                <option value="<?= html_escape($isi['id_kategori']); ?>">
                                                    <?= html_escape($isi['nama_kategori']); ?>
                                                </option>
                                            <?php endforeach; ?>

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>ISBN</label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="isbn"
                                            id="isbn"
                                            placeholder="Contoh ISBN : 978-602-8123-35-8"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            Judul Buku
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            required
                                            name="title"
                                            id="title"
                                            placeholder="Contoh : Cara Cepat Belajar Pemrograman Web"
                                        >
                                    </div>

                                </div>

                                <!-- KOLOM KANAN -->
                                <div class="col-sm-6">

                                    <div class="form-group">
                                        <label>
                                            Nama Pengarang
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="pengarang"
                                            id="pengarang"
                                            required
                                            placeholder="Nama Pengarang"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            Penerbit
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            required
                                            name="penerbit"
                                            id="penerbit"
                                            placeholder="Nama Penerbit"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            Tahun Buku
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="number"
                                            class="form-control"
                                            required
                                            min="1900"
                                            max="<?= date('Y'); ?>"
                                            name="thn_buku"
                                            id="thn_buku"
                                            placeholder="Tahun Buku"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            File Buku
                                            <small style="color:green">(PDF)</small>
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input
                                            type="file"
                                            class="form-control"
                                            id="fileBuku"
                                            accept=".pdf,application/pdf"
                                            required
                                        >

                                        <small class="text-muted">
                                            Pilih file buku dalam format PDF.
                                        </small>
                                    </div>

                                </div>

                            </div>

                            <hr>

                            <!-- INFORMASI FILE -->
                            <div
                                id="informasiFile"
                                class="alert alert-info"
                                style="display:none;"
                            >

                                <strong>
                                    <i class="fa fa-file-pdf-o"></i>
                                    Informasi File
                                </strong>

                                <p id="namaFile" style="margin:5px 0;"></p>

                                <p id="ukuranFile" style="margin:0;"></p>

                                <p id="infoJumlahPDF" style="margin:5px 0 0;"></p>

                            </div>

                            <!-- INFORMASI PEMROSESAN -->
                            <div class="alert alert-info">

                                <strong>
                                    <i class="fa fa-info-circle"></i>
                                    Informasi Pemrosesan Buku
                                </strong>

                                <ul style="margin-top:10px;margin-bottom:0;">
                                    <li>PDF akan diproses menjadi gambar PNG.</li>
                                    <li>Halaman portrait akan tetap utuh.</li>
                                    <li>Halaman landscape akan dibagi menjadi dua bagian.</li>
                                    <li>Bagian kiri dan kanan disimpan sebagai halaman terpisah.</li>
                                    <li>Gambar dikirim ke server satu per satu.</li>
                                    <li>Urutan halaman disimpan secara otomatis.</li>
                                </ul>

                            </div>

                            <!-- PROGRESS -->
                            <div id="areaProgress" style="display:none;">

                                <div class="form-group">

                                    <label id="statusProses">
                                        Menyiapkan PDF...
                                    </label>

                                    <div class="progress">
                                        <div
                                            id="progressProses"
                                            class="progress-bar progress-bar-success progress-bar-striped active"
                                            style="width:0%;"
                                        >
                                            0%
                                        </div>
                                    </div>

                                    <small
                                        id="jumlahHalaman"
                                        class="text-muted"
                                    ></small>

                                </div>

                            </div>

                            <!-- BUTTON -->
                            <div class="pull-right">

                                <a
                                    href="<?= base_url('data/bukudigital'); ?>"
                                    class="btn btn-danger"
                                    id="btnKembali"
                                >
                                    <i class="fa fa-arrow-left"></i>
                                    Kembali
                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    id="btnSubmit"
                                >
                                    <i class="fa fa-save"></i>
                                    Simpan Buku
                                </button>

                            </div>

                            <div class="clearfix"></div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </section>

</div>


<!-- ==========================================
     FULL SCREEN LOADING
========================================== -->

<div
    id="loadingOverlay"
    style="
        display:none;
        position:fixed;
        z-index:999999;
        top:0;
        left:0;
        width:100%;
        height:100%;
        background:rgba(0,0,0,0.88);
        align-items:center;
        justify-content:center;
        color:white;
    "
>

    <div
        style="
            width:90%;
            max-width:450px;
            text-align:center;
            padding:25px;
        "
    >

        <i
            class="fa fa-spinner fa-spin"
            style="
                font-size:65px;
                color:#00a65a;
                margin-bottom:25px;
            "
        ></i>

        <h3 id="loadingTitle">
            Memproses Buku Digital
        </h3>

        <p id="loadingStatus">
            Sedang menyiapkan dokumen...
        </p>

        <div
            class="progress"
            style="
                height:25px;
                background:#555;
                margin-top:25px;
            "
        >

            <div
                id="loadingProgress"
                class="progress-bar progress-bar-success progress-bar-striped active"
                role="progressbar"
                style="
                    width:0%;
                    font-weight:bold;
                    line-height:25px;
                "
            >
                0%
            </div>

        </div>

        <h4 id="loadingPage">
            Mohon jangan tutup halaman ini
        </h4>

        <small style="color:#ccc;">
            Proses bergantung pada jumlah halaman dan ukuran file.
        </small>

    </div>

</div>


<!-- ==========================================
     PDF.JS
========================================== -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>


<!-- ==========================================
     JAVASCRIPT PEMROSESAN PDF
========================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    'use strict';

    const form = document.getElementById('formBukuDigital');
    const fileInput = document.getElementById('fileBuku');

    const informasiFile = document.getElementById('informasiFile');
    const namaFile = document.getElementById('namaFile');
    const ukuranFile = document.getElementById('ukuranFile');
    const infoJumlahPDF = document.getElementById('infoJumlahPDF');

    const areaProgress = document.getElementById('areaProgress');
    const statusProses = document.getElementById('statusProses');
    const progressProses = document.getElementById('progressProses');
    const jumlahHalaman = document.getElementById('jumlahHalaman');

    const overlay = document.getElementById('loadingOverlay');

    const loadingTitle = document.getElementById('loadingTitle');
    const loadingStatus = document.getElementById('loadingStatus');
    const loadingProgress = document.getElementById('loadingProgress');
    const loadingPage = document.getElementById('loadingPage');

    const btnSubmit = document.getElementById('btnSubmit');
    const btnKembali = document.getElementById('btnKembali');

    const URL_BACKEND = <?= json_encode(base_url('data/prosesdigital')); ?>;
    const URL_REDIRECT = <?= json_encode(base_url('data/bukudigital')); ?>;

    let sedangProses = false;
    let pdfAktif = null;

    if (typeof pdfjsLib === 'undefined') {
        alert('PDF.js gagal dimuat. Periksa koneksi internet Anda.');
        return;
    }

    pdfjsLib.GlobalWorkerOptions.workerSrc =
        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';


    // ======================================
    // CSRF
    // ======================================

    function ambilInputCSRF() {
        return form.querySelector('input[name^="csrf_"]');
    }

    function tambahkanCSRF(data) {

        const csrfInput = ambilInputCSRF();

        if (csrfInput) {
            data.set(csrfInput.name, csrfInput.value);
        }

        return data;
    }

    function perbaruiCSRF(result) {

        if (!result.csrf_name || !result.csrf_hash) {
            return;
        }

        let csrfInput = form.querySelector(
            'input[name^="csrf_"]'
        );

        if (!csrfInput) {
            csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            form.appendChild(csrfInput);
        }

        csrfInput.name = result.csrf_name;
        csrfInput.value = result.csrf_hash;
    }


    // ======================================
    // INFORMASI FILE
    // ======================================

    fileInput.addEventListener('change', async function () {

        const file = this.files[0];

        informasiFile.style.display = 'none';
        infoJumlahPDF.textContent = '';

        pdfAktif = null;

        if (!file) {
            return;
        }

        if (
            file.type !== 'application/pdf' &&
            !file.name.toLowerCase().endsWith('.pdf')
        ) {

            alert('File harus menggunakan format PDF.');

            this.value = '';

            return;
        }

        namaFile.textContent = file.name;

        ukuranFile.textContent =
            'Ukuran file: ' +
            (file.size / (1024 * 1024)).toFixed(2) +
            ' MB';

        informasiFile.style.display = 'block';

        infoJumlahPDF.textContent = 'Sedang membaca jumlah halaman PDF...';

        try {

            const buffer = await file.arrayBuffer();

            pdfAktif = await pdfjsLib.getDocument({
                data: new Uint8Array(buffer)
            }).promise;

            infoJumlahPDF.textContent =
                'Jumlah halaman PDF: ' + pdfAktif.numPages;

        } catch (error) {

            console.error('PDF ERROR:', error);

            alert(
                'File PDF tidak dapat dibaca. Pastikan file tidak rusak atau diproteksi password.'
            );

            this.value = '';
            pdfAktif = null;

            informasiFile.style.display = 'none';
        }

    });


    // ======================================
    // FULL SCREEN LOADING
    // ======================================

    function tampilkanLoading() {

        overlay.style.display = 'flex';

        document.body.style.overflow = 'hidden';
    }

    function sembunyikanLoading() {

        overlay.style.display = 'none';

        document.body.style.overflow = '';
    }


    // ======================================
    // PROGRESS
    // ======================================

    function updateProgress(persen, status, detail) {

        persen = Math.max(0, Math.min(100, Math.round(persen)));

        loadingProgress.style.width = persen + '%';
        loadingProgress.textContent = persen + '%';

        loadingStatus.textContent = status;

        progressProses.style.width = persen + '%';
        progressProses.textContent = persen + '%';

        statusProses.textContent = status;

        if (detail !== undefined) {
            loadingPage.textContent = detail;
            jumlahHalaman.textContent = detail;
        }
    }


    // ======================================
    // CANVAS KE PNG
    // ======================================

    function canvasToBlob(canvas) {

        return new Promise(function (resolve, reject) {

            canvas.toBlob(function (blob) {

                if (!blob) {
                    reject(new Error('Gagal membuat gambar PNG.'));
                    return;
                }

                resolve(blob);

            }, 'image/png');

        });
    }


    // ======================================
    // REQUEST AJAX
    // ======================================

    async function requestJSON(data) {

        tambahkanCSRF(data);

        const action = data.get('canvas_action');
        const nomor = data.get('nomor');

        console.log('REQUEST DIGITAL:', {
            action: action,
            nomor: nomor,
            folder: data.get('folder')
        });

        const response = await fetch(URL_BACKEND, {
            method: 'POST',
            body: data,
            credentials: 'same-origin'
        });

        const responseText = await response.text();

        console.log('HTTP STATUS:', response.status);
        console.log('SERVER RESPONSE:', responseText);

        let result;

        try {

            result = JSON.parse(responseText);

        } catch (error) {

            throw new Error(
                'HTTP ' + response.status +
                ': Respons server bukan JSON: ' +
                responseText.substring(0, 500)
            );

        }

        perbaruiCSRF(result);

        if (!response.ok || !result.status) {

            throw new Error(
                'HTTP ' + response.status + ': ' +
                (result.pesan || result.message ||
                    'Terjadi kesalahan pada server.')
            );

        }

        return result;
    }


    // ======================================
    // UPLOAD SATU GAMBAR
    // ======================================

    async function uploadHalaman(folder, nomor, blob) {

        const data = new FormData();

        data.append('canvas_action', 'upload');
        data.append('folder', folder);
        data.append('nomor', nomor);

        data.append(
            'halaman',
            blob,
            nomor + '.png'
        );

        await requestJSON(data);
    }


    // ======================================
    // BATAL UPLOAD
    // ======================================

    async function batalkanUpload(folder) {

        if (!folder) {
            return;
        }

        try {

            const data = new FormData();

            data.append('canvas_action', 'batal');
            data.append('folder', folder);

            await requestJSON(data);

            console.log('Upload sementara berhasil dibatalkan.');

        } catch (error) {

            console.error('Gagal membersihkan folder:', error);
        }
    }


    // ======================================
    // SUBMIT FORM
    // ======================================

    form.addEventListener('submit', async function (e) {

        e.preventDefault();

        if (sedangProses) {
            return;
        }

        if (!form.reportValidity()) {
            return;
        }

        const file = fileInput.files[0];

        if (!file) {

            alert('Silakan pilih file PDF.');

            return;
        }

        if (!pdfAktif) {

            alert('PDF belum berhasil dibaca. Silakan pilih ulang file PDF.');

            return;
        }

        sedangProses = true;

        btnSubmit.disabled = true;
        btnKembali.style.pointerEvents = 'none';

        areaProgress.style.display = 'block';

        tampilkanLoading();

        let folder = null;
        let berhasilDisimpan = false;

        try {

            // ==================================
            // 1. MEMBACA PDF
            // ==================================

            loadingTitle.textContent = 'Membaca Buku Digital';

            updateProgress(
                1,
                'Mempersiapkan file PDF...',
                'Menyiapkan dokumen.'
            );

            const totalPDF = pdfAktif.numPages;

            // ==================================
            // 2. MEMULAI UPLOAD
            // ==================================

            loadingTitle.textContent = 'Menyiapkan Penyimpanan';

            updateProgress(
                3,
                'Membuat folder penyimpanan...',
                'Total halaman PDF: ' + totalPDF
            );

            const dataMulai = new FormData();

            dataMulai.append('canvas_action', 'mulai');

            const hasilMulai = await requestJSON(dataMulai);

            folder = hasilMulai.folder;

            if (!folder) {
                throw new Error('Server tidak mengembalikan nama folder upload.');
            }

            console.log('FOLDER UPLOAD:', folder);


            // ==================================
            // 3. RENDER DAN UPLOAD PDF
            // ==================================

            let nomorHasil = 0;

            /*
             * Hitung jumlah gambar terlebih dahulu.
             * Landscape = 2 gambar.
             * Portrait = 1 gambar.
             */

            const jenisHalaman = [];

            let totalGambar = 0;

            for (let i = 1; i <= totalPDF; i++) {

                const page = await pdfAktif.getPage(i);

                const viewport = page.getViewport({
                    scale: 1
                });

                const landscape = viewport.width > viewport.height;

                jenisHalaman.push(landscape ? 2 : 1);

                totalGambar += landscape ? 2 : 1;

                page.cleanup();
            }

            console.log('TOTAL GAMBAR:', totalGambar);


            // ==================================
            // 4. PROSES SETIAP HALAMAN
            // ==================================

            for (let halaman = 1; halaman <= totalPDF; halaman++) {

                const page = await pdfAktif.getPage(halaman);

                const viewport = page.getViewport({
                    scale: 1.3
                });

                const canvas = document.createElement('canvas');

                canvas.width = Math.ceil(viewport.width);
                canvas.height = Math.ceil(viewport.height);

                const context = canvas.getContext('2d', {
                    alpha: false
                });

                await page.render({
                    canvasContext: context,
                    viewport: viewport
                }).promise;

                const hasilCanvas = [];

                // ==================================
                // LANDSCAPE: CROP KIRI DAN KANAN
                // ==================================

                if (canvas.width > canvas.height) {

                    const halfWidth = Math.floor(canvas.width / 2);

                    // KIRI
                    const kiri = document.createElement('canvas');

                    kiri.width = halfWidth;
                    kiri.height = canvas.height;

                    kiri.getContext('2d', {
                        alpha: false
                    }).drawImage(
                        canvas,
                        0,
                        0,
                        halfWidth,
                        canvas.height,
                        0,
                        0,
                        halfWidth,
                        canvas.height
                    );

                    hasilCanvas.push(kiri);


                    // KANAN
                    const kanan = document.createElement('canvas');

                    kanan.width = canvas.width - halfWidth;
                    kanan.height = canvas.height;

                    kanan.getContext('2d', {
                        alpha: false
                    }).drawImage(
                        canvas,
                        halfWidth,
                        0,
                        canvas.width - halfWidth,
                        canvas.height,
                        0,
                        0,
                        canvas.width - halfWidth,
                        canvas.height
                    );

                    hasilCanvas.push(kanan);

                } else {

                    // PORTRAIT: TETAP UTUH
                    hasilCanvas.push(canvas);

                }


                // ==================================
                // 5. UPLOAD PNG SECARA BERURUTAN
                // ==================================

                for (let i = 0; i < hasilCanvas.length; i++) {

                    nomorHasil++;

                    const blob = await canvasToBlob(hasilCanvas[i]);

                    loadingTitle.textContent = 'Mengirim Halaman Buku';

                    updateProgress(
                        3 + (nomorHasil / totalGambar) * 90,
                        'Mengunggah halaman PNG...',
                        'Gambar ' + nomorHasil + ' dari ' + totalGambar +
                        ' | Halaman PDF ' + halaman + ' dari ' + totalPDF
                    );

                    await uploadHalaman(
                        folder,
                        nomorHasil,
                        blob
                    );

                    // Bersihkan canvas hasil.
                    hasilCanvas[i].width = 0;
                    hasilCanvas[i].height = 0;
                }

                canvas.width = 0;
                canvas.height = 0;

                page.cleanup();

            }


            // ==================================
            // 6. SIMPAN DATA BUKU
            // ==================================

            loadingTitle.textContent = 'Menyimpan Data Buku';

            updateProgress(
                96,
                'Menyimpan informasi buku ke database...',
                'Total gambar berhasil diunggah: ' + nomorHasil
            );

            const dataSelesai = new FormData();

            dataSelesai.append('canvas_action', 'selesai');

            dataSelesai.append('folder', folder);

            dataSelesai.append('total_hal', nomorHasil);

            dataSelesai.append(
                'id_kategori',
                document.getElementById('id_kategori').value
            );

            dataSelesai.append(
                'isbn',
                document.getElementById('isbn').value
            );

            dataSelesai.append(
                'title',
                document.getElementById('title').value
            );

            dataSelesai.append(
                'pengarang',
                document.getElementById('pengarang').value
            );

            dataSelesai.append(
                'penerbit',
                document.getElementById('penerbit').value
            );

            dataSelesai.append(
                'thn_buku',
                document.getElementById('thn_buku').value
            );

            const hasilSelesai = await requestJSON(dataSelesai);

            berhasilDisimpan = true;


            // ==================================
            // 7. BERHASIL
            // ==================================

            loadingTitle.textContent = 'Berhasil!';

            updateProgress(
                100,
                hasilSelesai.pesan || 'Buku digital berhasil disimpan.',
                'Total halaman tersimpan: ' + nomorHasil
            );

            loadingStatus.textContent =
                hasilSelesai.pesan || 'Buku digital berhasil disimpan.';

            setTimeout(function () {

                window.location.href = URL_REDIRECT;

            }, 1200);


        } catch (error) {

            console.error('ERROR PDF DIGITAL:', error);

            if (folder && !berhasilDisimpan) {

                await batalkanUpload(folder);
            }

            sembunyikanLoading();

            alert(
                'Gagal memproses buku digital:\n\n' +
                error.message
            );

            sedangProses = false;

            btnSubmit.disabled = false;

            btnKembali.style.pointerEvents = 'auto';

            loadingTitle.textContent = 'Proses Gagal';

            updateProgress(
                0,
                'Silakan periksa data dan file PDF.',
                'Silakan coba kembali.'
            );

        }

    });

});

</script>
