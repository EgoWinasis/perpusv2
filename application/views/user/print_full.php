
<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

$CI =& get_instance();
$CI->load->database();

// Ambil ID siswa yang dipilih dari cookie.
$data_print = isset($_COOKIE['data']) ? $_COOKIE['data'] : '';
$ids = array_filter(
    array_map('intval', explode(',', $data_print)),
    function ($id) {
        return $id > 0;
    }
);

// Ambil data siswa menggunakan Query Builder.
$siswa = array();

if (!empty($ids)) {
    $siswa = $CI->db
        ->where_in('id_siswa', $ids)
        ->get('tbl_siswa')
        ->result_array();
}

function nama_kartu_full($nama)
{
    $nama = trim($nama);
    $bagian = preg_split('/\s+/', $nama);

    if (count($bagian) >= 3) {
        return $bagian[0] . ' ' . $bagian[1] . ' ' .
            substr($bagian[2], 0, 1) . '.';
    }

    return $nama;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= html_escape($title_web ?? 'Cetak Kartu Siswa Full'); ?></title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 20px;
            font-family: Arial, sans-serif;
            color: #222;
            background: #eee;
        }

        .toolbar {
            margin-bottom: 20px;
        }

        .toolbar button {
            padding: 9px 15px;
            margin-right: 6px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }

        .btn-print {
            background: #198754;
        }

        .btn-close {
            background: #dc3545;
        }

        .card-list {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            gap: 10px;
        }

        .student-card {
            width: 8.6cm;
            height: 5.4cm;
            padding: 5mm;
            border: 1px solid #333;
            border-radius: 3mm;
            background: white;
            overflow: hidden;
            position: relative;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding-bottom: 4px;
            border-bottom: 1px solid #333;
        }

        .school-logo {
            width: 35px;
            height: 35px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .school-title {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            line-height: 1.4;
        }

        .card-body {
            display: flex;
            align-items: center;
            gap: 6px;
            padding-top: 7px;
        }

        .student-info {
            flex: 1;
            min-width: 0;
        }

        .student-info table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .student-info td {
            padding: 3px 1px;
            vertical-align: top;
            overflow-wrap: anywhere;
        }

        .student-info td:first-child {
            width: 66px;
        }

        .qr-section {
            width: 92px;
            flex-shrink: 0;
            text-align: center;
        }

        .qr-code {
            width: 82px;
            height: 82px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .qr-code img,
        .qr-code canvas {
            width: 82px !important;
            height: 82px !important;
            display: block;
        }

        .qr-label {
            margin-top: 3px;
            font-size: 8px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .empty-message {
            padding: 20px;
            background: white;
            border: 1px solid #ddd;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 8mm;
            }

            body {
                margin: 0;
                background: white;
            }

            .toolbar,
            .empty-message {
                display: none;
            }

            .card-list {
                gap: 5mm;
                align-content: flex-start;
            }

            .student-card {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    <div class="toolbar">
        <button class="btn-print" onclick="window.print()">
            <i></i> Cetak Kartu Full
        </button>

        <button class="btn-close" onclick="window.close()">
            Tutup
        </button>
    </div>

    <?php if (empty($siswa)) : ?>
        <div class="empty-message">
            Data siswa tidak ditemukan. Silakan kembali ke halaman data
            siswa dan pilih siswa yang akan dicetak.
        </div>
    <?php else : ?>

        <div class="card-list">

            <?php foreach ($siswa as $item) : ?>
                <div class="student-card">

                    <div class="card-header">
                        <img
                            class="school-logo"
                            src="<?= base_url('assets_style/image/tutwurihandayani.png'); ?>"
                            alt="Logo sekolah"
                        >

                        <div class="school-title">
                            KARTU PERPUSTAKAAN<br>
                            SDN GETASKEREP 01
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="student-info">
                            <table>
                                <tr>
                                    <td>Kode</td>
                                    <td>:</td>
                                    <td>
                                        <?= html_escape($item['kode_anggota'] ?? ''); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td>
                                        <?= html_escape(nama_kartu_full($item['nama'] ?? '')); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Jenis Kel.</td>
                                    <td>:</td>
                                    <td>
                                        <?= html_escape($item['jenkel'] ?? ''); ?>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div class="qr-section">
                            <div
                                class="qr-code"
                                data-kode="<?= html_escape($item['kode_anggota'] ?? ''); ?>">
                            </div>

                            <div class="qr-label">
                                <?= html_escape($item['kode_anggota'] ?? ''); ?>
                            </div>
                        </div>

                    </div>

                </div>
            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var qrElements = document.querySelectorAll('.qr-code');

            if (qrElements.length === 0) {
                return;
            }

            if (typeof QRCode === 'undefined') {
                alert('Library QR Code gagal dimuat. Periksa koneksi internet.');
                return;
            }

            var selesai = 0;

            qrElements.forEach(function (element) {
                var kode = element.getAttribute('data-kode');

                new QRCode(element, {
                    text: kode,
                    width: 82,
                    height: 82,
                    correctLevel: QRCode.CorrectLevel.H
                });

                selesai++;
            });

            // Beri waktu agar QR Code selesai dirender sebelum mencetak.
            setTimeout(function () {
                window.print();
            }, 700);
        });

        // Hapus cookie setelah proses cetak selesai atau dibatalkan.
        window.addEventListener('afterprint', function () {
            document.cookie =
                'data=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
        });
    </script>

</body>
</html>
