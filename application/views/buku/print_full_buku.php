
<?php
$CI =& get_instance();

// Ambil ID buku dari cookie
$data_print = $CI->input->cookie('dataBuku', TRUE);
$ids = array_filter(
    array_map('intval', explode(',', (string) $data_print))
);

// Ambil data buku beserta kategori
$buku = [];

if (!empty($ids)) {
    $CI->db->select('tbl_buku.*, tbl_kategori.nama_kategori');
    $CI->db->from('tbl_buku');
    $CI->db->join(
        'tbl_kategori',
        'tbl_kategori.id_kategori = tbl_buku.id_kategori',
        'left'
    );
    $CI->db->where_in('tbl_buku.id_buku', $ids);
    $CI->db->order_by('tbl_buku.id_buku', 'ASC');

    $buku = $CI->db->get()->result_array();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cetak Label Buku</title>

    <!-- Library QR Code -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #eee;
            margin: 0;
            padding: 15px;
        }

        .toolbar {
            text-align: center;
            margin-bottom: 20px;
        }

        .toolbar button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            background: #198754;
            margin: 3px;
        }

        .toolbar .close {
            background: #dc3545;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 0 auto 15px;
            padding: 10mm;
        }

        .label-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 4mm;
        }

        .card {
            height: 32mm;
            border: 1px solid #222;
            padding: 3mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 5px;
            overflow: hidden;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .info {
            flex: 1;
            min-width: 0;
        }

        .header {
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #333;
            padding-bottom: 3px;
            margin-bottom: 4px;
        }

        .detail {
            font-size: 9px;
            margin: 3px 0;
            display: flex;
        }

        .detail .label {
            width: 58px;
            flex-shrink: 0;
        }

        .detail .value {
            flex: 1;
            overflow-wrap: anywhere;
            font-weight: bold;
        }

        .qr-wrapper {
            width: 75px;
            flex-shrink: 0;
            text-align: center;
        }

        .qr-code {
            width: 68px;
            height: 68px;
            margin: auto;
        }

        .qr-code img,
        .qr-code canvas {
            width: 68px !important;
            height: 68px !important;
            display: block;
        }

        .qr-caption {
            font-size: 8px;
            font-weight: bold;
            margin-top: 2px;
        }

        .empty {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 5px;
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 0;
            }

            body {
                padding: 0;
                margin: 0;
                background: white;
            }

            .toolbar {
                display: none;
            }

            .page {
                margin: 0;
                width: 210mm;
                height: 297mm;
                min-height: 297mm;
                padding: 10mm;
                page-break-after: always;
                break-after: page;
            }

            .page:last-child {
                page-break-after: auto;
                break-after: auto;
            }
        }
    </style>
</head>

<body>

    <div class="toolbar">
        <button onclick="window.print()">
            <i>🖨</i> Cetak Label + QR Code
        </button>

        <button class="close" onclick="window.close()">
            Tutup
        </button>
    </div>

    <?php if (!empty($buku)) : ?>

        <?php
        $total = count($buku);
        $index = 0;
        ?>

        <?php foreach ($buku as $item) : ?>

            <?php if ($index % 16 == 0) : ?>
                <div class="page">
                    <div class="label-grid">
            <?php endif; ?>

            <div class="card">

                <!-- INFORMASI BUKU -->
                <div class="info">

                    <div class="header">
                        PERPUSTAKAAN<br>
                        SDN GETASKEREP 01
                    </div>

                    <div class="detail">
                        <span class="label">KODE</span>
                        <span>:</span>
                        <span class="value">
                            <?= html_escape($item['kode_buku']); ?>
                        </span>
                    </div>

                    <div class="detail">
                        <span class="label">JUDUL</span>
                        <span>:</span>
                        <span class="value">
                            <?= html_escape($item['title']); ?>
                        </span>
                    </div>

                    <div class="detail">
                        <span class="label">KATEGORI</span>
                        <span>:</span>
                        <span class="value">
                            <?= html_escape($item['nama_kategori'] ?? '-'); ?>
                        </span>
                    </div>

                </div>

                <!-- QR CODE -->
                <div class="qr-wrapper">

                    <div
                        class="qr-code"
                        data-kode="<?= html_escape($item['kode_buku']); ?>">
                    </div>

                    <div class="qr-caption">
                        <?= html_escape($item['kode_buku']); ?>
                    </div>

                </div>

            </div>

            <?php
            $index++;

            if ($index % 16 == 0 || $index == $total) :
            ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php endforeach; ?>

    <?php else : ?>

        <div class="empty">
            <h3>Data buku tidak ditemukan!</h3>
            <p>Silakan pilih buku terlebih dahulu.</p>
        </div>

    <?php endif; ?>


    <!-- GENERATE QR CODE -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var qrElements = document.querySelectorAll('.qr-code');

            qrElements.forEach(function(element) {

                var kodeBuku = element.getAttribute('data-kode');

                new QRCode(element, {
                    text: kodeBuku,
                    width: 68,
                    height: 68,
                    correctLevel: QRCode.CorrectLevel.H
                });

            });

            // Cetak otomatis setelah QR selesai dibuat
            setTimeout(function() {
                if (qrElements.length > 0) {
                    window.print();
                }
            }, 1000);

        });

        // Hapus cookie setelah proses cetak selesai
        window.addEventListener('afterprint', function() {
            document.cookie =
                'dataBuku=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        });
    </script>

</body>
</html>
