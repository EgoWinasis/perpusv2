```php
<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= html_escape($title_web); ?></title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #222;
        }

        .toolbar {
            margin-bottom: 20px;
        }

        .toolbar button {
            padding: 8px 14px;
            margin-right: 5px;
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

        h3 {
            margin-bottom: 18px;
        }

        .qr-container {
            display: grid;
            grid-template-columns: repeat(4, 150px);
            gap: 10px;
            align-items: start;
        }

        .qr-item {
            width: 150px;
            padding: 8px;
            border: 1px solid #bbb;
            border-radius: 5px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .qr-code {
            width: 128px;
            height: 128px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-code img,
        .qr-code canvas {
            display: block;
            width: 128px;
            height: 128px;
        }

        .kode {
            width: 100%;
            margin-top: 6px;
            font-size: 12px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .nama {
            width: 100%;
            margin-top: 4px;
            font-size: 11px;
            overflow-wrap: anywhere;
        }

        @media screen and (max-width: 700px) {
            .qr-container {
                grid-template-columns: repeat(2, 150px);
            }
        }

        @media print {
            @page {
                margin: 8mm;
            }

            body {
                margin: 0;
            }

            .toolbar {
                display: none;
            }

            h3 {
                font-size: 16px;
                margin: 0 0 12px;
            }

            .qr-container {
                grid-template-columns: repeat(4, 150px);
                gap: 8px;
            }

            .qr-item {
                border: 1px solid #aaa;
                border-radius: 4px;
            }
        }
    </style>
</head>

<body>

    <div class="toolbar">
        <button class="btn-print" onclick="window.print()">
            <i></i> Cetak QR Code
        </button>

        <button class="btn-close" onclick="window.close()">
            Tutup
        </button>
    </div>

    <h3>QR Code Anggota Perpustakaan</h3>

    <div class="qr-container">

        <?php foreach ($siswa as $item) { ?>
            <div class="qr-item">

                <div
                    class="qr-code"
                    data-kode="<?= html_escape($item['kode_anggota']); ?>">
                </div>

                <div class="kode">
                    <?= html_escape($item['kode_anggota']); ?>
                </div>

                <div class="nama">
                    <?= html_escape($item['nama']); ?>
                </div>

            </div>
        <?php } ?>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            if (typeof QRCode === 'undefined') {
                alert('Library QR Code gagal dimuat. Periksa koneksi internet.');
                return;
            }

            document.querySelectorAll('.qr-code').forEach(function(element) {

                var kodeAnggota = element.getAttribute('data-kode');

                new QRCode(element, {
                    text: kodeAnggota,
                    width: 128,
                    height: 128,
                    correctLevel: QRCode.CorrectLevel.H
                });

            });

        });
    </script>

</body>
</html>
```