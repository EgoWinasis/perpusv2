
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="content-wrapper">

    <!-- HEADER -->
    <section class="content-header">
        <h1>
            <i class="fa fa-plus" style="color:green"></i>
            <?= $title_web; ?>
        </h1>

        <ol class="breadcrumb">
            <li>
                <a href="<?= base_url('dashboard'); ?>">
                    <i class="fa fa-dashboard"></i>&nbsp; Dashboard
                </a>
            </li>
            <li class="active">
                <i class="fa fa-plus"></i>&nbsp; <?= $title_web; ?>
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

                            <div class="row">

                                <!-- KOLOM KIRI -->
                                <div class="col-sm-6">

                                    <div class="form-group">
                                        <label>Kategori</label>

                                        <select
                                            class="form-control select2"
                                            required
                                            name="kategori"
                                        >
                                            <option disabled selected value="">
                                                -- Pilih Kategori --
                                            </option>

                                            <?php foreach ($kats as $isi) { ?>
                                                <option value="<?= $isi['id_kategori']; ?>">
                                                    <?= $isi['nama_kategori']; ?>
                                                </option>
                                            <?php } ?>

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>ISBN</label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="isbn"
                                            placeholder="Contoh ISBN : 978-602-8123-35-8"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>Judul Buku</label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            required
                                            name="title"
                                            placeholder="Contoh : Cara Cepat Belajar Pemrograman Web"
                                        >
                                    </div>

                                </div>

                                <!-- KOLOM KANAN -->
                                <div class="col-sm-6">

                                    <div class="form-group">
                                        <label>Nama Pengarang</label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            name="pengarang"
                                            placeholder="Nama Pengarang"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>Penerbit</label>

                                        <input
                                            type="text"
                                            class="form-control"
                                            required
                                            name="penerbit"
                                            placeholder="Nama Penerbit"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>Tahun Buku</label>

                                        <input
                                            type="number"
                                            class="form-control"
                                            required
                                            min="1900"
                                            max="<?= date('Y'); ?>"
                                            name="thn"
                                            placeholder="Tahun Buku"
                                        >
                                    </div>

                                    <div class="form-group">
                                        <label>
                                            File Buku
                                            <small style="color:green">(PDF)</small>
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

                            </div>

                            <!-- INFORMASI CROP -->
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
                                </ul>

                            </div>

                            <!-- PROGRESS DI FORM -->
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

                                <input
                                    type="hidden"
                                    name="tambah"
                                    value="tambah"
                                >

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

    const form = document.getElementById('formBukuDigital');

    const fileInput = document.getElementById('fileBuku');

    const informasiFile = document.getElementById('informasiFile');
    const namaFile = document.getElementById('namaFile');
    const ukuranFile = document.getElementById('ukuranFile');

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

    let sedangProses = false;

    const URL_BACKEND = '<?= base_url("data/prosesdigital"); ?>';

    // Konfigurasi PDF.js
    pdfjsLib.GlobalWorkerOptions.workerSrc =
        'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';


    // ======================================
    // INFORMASI FILE
    // ======================================

    fileInput.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {
            informasiFile.style.display = 'none';
            return;
        }

        if (
            file.type !== 'application/pdf' &&
            !file.name.toLowerCase().endsWith('.pdf')
        ) {

            alert('File harus menggunakan format PDF.');

            this.value = '';

            informasiFile.style.display = 'none';

            return;
        }

        namaFile.textContent = file.name;

        ukuranFile.textContent =
            'Ukuran file: ' +
            (file.size / (1024 * 1024)).toFixed(2) +
            ' MB';

        informasiFile.style.display = 'block';

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
    // UPDATE PROGRESS
    // ======================================

    function updateProgress(persen, status, halaman) {

        persen = Math.max(0, Math.min(100, persen));

        loadingProgress.style.width = persen + '%';
        loadingProgress.textContent = persen + '%';

        loadingStatus.textContent = status;

        progressProses.style.width = persen + '%';
        progressProses.textContent = persen + '%';

        statusProses.textContent = status;

        if (halaman) {
            loadingPage.textContent = halaman;
            jumlahHalaman.textContent = halaman;
        }

    }


    // ======================================
    // CANVAS MENJADI PNG
    // ======================================

    function canvasToBlob(canvas) {

        return new Promise(function (resolve, reject) {

            canvas.toBlob(function (blob) {

                if (blob) {
                    resolve(blob);
                } else {
                    reject(new Error('Gagal membuat gambar PNG.'));
                }

            }, 'image/png');

        });

    }


    // ======================================
    // REQUEST KE BACKEND
    // ======================================

    async function requestJSON(data) {

        const response = await fetch(URL_BACKEND, {
            method: 'POST',
            body: data,
            credentials: 'same-origin'
        });

        const responseText = await response.text();

        let result;

        try {

            result = JSON.parse(responseText);

        } catch (error) {

            console.error('Respons server:', responseText);

            throw new Error(
                'HTTP ' + response.status +
                '. Respons server tidak valid. Periksa log CodeIgniter.'
            );

        }

        if (!response.ok || !result.status) {

            throw new Error(
                result.message || 'Terjadi kesalahan pada server.'
            );

        }

        return result;

    }


    // ======================================
    // TAMBAHKAN TOKEN CSRF JIKA AKTIF
    // ======================================

    function tambahkanCSRF(data) {

        const csrfInput = form.querySelector(
            'input[name^="csrf_"]'
        );

        if (csrfInput && !data.has(csrfInput.name)) {

            data.append(
                csrfInput.name,
                csrfInput.value
            );

        }

        return data;

    }


    // ======================================
    // SUBMIT FORM
    // ======================================

    form.addEventListener('submit', async function (e) {

        e.preventDefault();

        if (sedangProses) {
            return;
        }

        const file = fileInput.files[0];

        if (!file) {

            alert('Silakan pilih file PDF.');

            return;

        }

        if (!file.name.toLowerCase().endsWith('.pdf')) {

            alert('File harus berformat PDF.');

            return;

        }

        sedangProses = true;

        btnSubmit.disabled = true;

        btnKembali.classList.add('disabled');

        areaProgress.style.display = 'block';

        tampilkanLoading();


        let folder = null;

        try {

            // ==================================
            // 1. MEMBACA PDF
            // ==================================

            loadingTitle.textContent = 'Membaca Buku Digital';

            updateProgress(
                1,
                'Membuka file PDF...',
                'Menyiapkan dokumen'
            );

            const arrayBuffer = await file.arrayBuffer();

            const pdf = await pdfjsLib.getDocument({
                data: new Uint8Array(arrayBuffer)
            }).promise;

            const totalPDF = pdf.numPages;

            if (totalPDF < 1) {
                throw new Error('PDF tidak memiliki halaman.');
            }

            // ==================================
            // 2. MENYIAPKAN METADATA
            // ==================================

            loadingTitle.textContent = 'Menyiapkan Penyimpanan';

            let metadata = new FormData(form);

            metadata.delete('lampiran');

            metadata.delete('tambah');

            metadata.append('canvas_action', 'mulai');

            metadata.append('jenis_proses', 'tambah');

            metadata = tambahkanCSRF(metadata);

            updateProgress(
                3,
                'Membuat folder penyimpanan...',
                'Menyiapkan ' + totalPDF + ' halaman PDF'
            );

            const mulai = await requestJSON(metadata);

            folder = mulai.folder;

            if (!folder) {
                throw new Error('Server tidak mengembalikan nama folder.');
            }


            // ==================================
            // 3. PROSES PDF SATU PER SATU
            // ==================================

            let nomorHasil = 0;

            // Maksimal sementara untuk progres selama rendering
            const progresRender = 90;

            for (let halaman = 1; halaman <= totalPDF; halaman++) {

                loadingTitle.textContent = 'Memproses Halaman Buku';

                loadingStatus.textContent =
                    'Membaca halaman ' + halaman + ' dari ' + totalPDF;

                const page = await pdf.getPage(halaman);

                const viewport = page.getViewport({
                    scale: 1.8
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


                // ==================================
                // 4. CROP LANDSCAPE
                // ==================================

                const hasilCanvas = [];

                if (canvas.width > canvas.height) {

                    const halfWidth = Math.floor(canvas.width / 2);

                    // BAGIAN KIRI
                    const kiri = document.createElement('canvas');

                    kiri.width = halfWidth;
                    kiri.height = canvas.height;

                    kiri.getContext('2d').drawImage(
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


                    // BAGIAN KANAN
                    const kanan = document.createElement('canvas');

                    kanan.width = canvas.width - halfWidth;

                    kanan.height = canvas.height;

                    kanan.getContext('2d').drawImage(
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

                    // PORTRAIT TIDAK DIPOTONG
                    hasilCanvas.push(canvas);

                }


                // ==================================
                // 5. KIRIM SATU GAMBAR PER REQUEST
                // ==================================

                for (let i = 0; i < hasilCanvas.length; i++) {

                    nomorHasil++;

                    const blob = await canvasToBlob(hasilCanvas[i]);

                    const uploadData = new FormData();

                    uploadData.append('canvas_action', 'upload');

                    uploadData.append('folder', folder);

                    uploadData.append('nomor', nomorHasil);

                    uploadData.append(
                        'halaman',
                        blob,
                        nomorHasil + '.png'
                    );

                    tambahkanCSRF(uploadData);

                    loadingTitle.textContent = 'Mengirim Halaman Buku';

                    const persen = Math.round(
                        3 + (nomorHasil / (totalPDF * 2)) * progresRender
                    );

                    updateProgress(
                        Math.min(persen, 93),
                        'Mengirim gambar ke server...',
                        'PDF halaman ' + halaman +
                        ' dari ' + totalPDF +
                        ' | Gambar ke-' + nomorHasil
                    );

                    // Tunggu upload selesai sebelum kirim berikutnya
                    await requestJSON(uploadData);

                    // Bersihkan canvas setelah upload
                    hasilCanvas[i].width = 0;
                    hasilCanvas[i].height = 0;

                }

                canvas.width = 0;
                canvas.height = 0;

                page.cleanup();

            }


            // ==================================
            // 6. SIMPAN DATA DATABASE
            // ==================================

            loadingTitle.textContent = 'Menyimpan Data Buku';

            updateProgress(
                96,
                'Memeriksa gambar dan menyimpan database...',
                'Total gambar: ' + nomorHasil
            );

            const selesaiData = new FormData(form);

            selesaiData.delete('lampiran');

            selesaiData.append('canvas_action', 'selesai');

            selesaiData.append('folder', folder);

            selesaiData.append('total_hal', nomorHasil);

            selesaiData.append('jenis_proses', 'tambah');

            tambahkanCSRF(selesaiData);

            const selesai = await requestJSON(selesaiData);


            // ==================================
            // 7. BERHASIL
            // ==================================

            updateProgress(
                100,
                'Buku digital berhasil disimpan.',
                'Total halaman tersimpan: ' + nomorHasil
            );

            loadingTitle.textContent = 'Berhasil!';

            loadingStatus.textContent = selesai.message;

            setTimeout(function () {

                window.location.href =
                    '<?= base_url("data/bukudigital"); ?>';

            }, 1200);


        } catch (error) {

            console.error('ERROR PDF DIGITAL:', error);

            // ==================================
            // HAPUS FOLDER JIKA GAGAL
            // ==================================

            if (folder) {

                const hapusData = new FormData();

                hapusData.append('canvas_action', 'batal');

                hapusData.append('folder', folder);

                tambahkanCSRF(hapusData);

                try {

                    await requestJSON(hapusData);

                } catch (hapusError) {

                    console.error(
                        'Gagal membersihkan folder:',
                        hapusError
                    );

                }

            }

            sembunyikanLoading();

            alert(
                'Gagal memproses buku digital:\n\n' +
                error.message
            );

            sedangProses = false;

            btnSubmit.disabled = false;

            btnKembali.classList.remove('disabled');

            loadingTitle.textContent = 'Proses Gagal';

            updateProgress(
                0,
                'Silakan periksa file dan koneksi server.',
                ''
            );

        }

    });

});

</script>
