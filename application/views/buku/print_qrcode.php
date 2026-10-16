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
        }

        .toolbar {
            margin-bottom: 20px;
        }

        .qr-container {
            display: grid;
            grid-template-columns: repeat(4, 150px);
            gap: 12px;
            align-items: start;
        }

        .qr-item {
            width: 150px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
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
            margin: 0 auto;
        }

        .qr-code img,
        .qr-code canvas {
            display: block;
            max-width: 100%;
            height: auto;
        }

        .kode {
            width: 100%;
            font-size: 12px;
            font-weight: bold;
            margin-top: 6px;
            overflow-wrap: anywhere;
        }

        .judul {
            width: 100%;
            font-size: 11px;
            margin-top: 4px;
            overflow-wrap: anywhere;
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

        @media screen and (max-width: 700px) {
            .qr-container {
                grid-template-columns: repeat(2, 150px);
            }
        }
    </style>
</head>
<body>

    <div class="toolbar">
        <button onclick="window.print()">Cetak QR Code</button>
        <button onclick="window.close()">Tutup</button>
    </div>

    <h3>QR Code Buku Perpustakaan</h3>

    <div class="qr-container">
        <?php foreach ($buku as $item) { ?>
            <div class="qr-item">
                <div class="qr-code"
                     data-kode="<?= html_escape($item['kode_buku']); ?>">
                </div>

                <div class="kode">
                    <?= html_escape($item['kode_buku']); ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.qr-code').forEach(function(element) {
                var kodeBuku = element.getAttribute('data-kode');

                new QRCode(element, {
                    text: kodeBuku,
                    width: 128,
                    height: 128,
                    correctLevel: QRCode.CorrectLevel.H
                });
            });
        });
    </script>

</body>
</html>