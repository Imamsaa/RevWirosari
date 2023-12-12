<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="<?= base_url('public/dist/bootstrap5/css/bootstrap.min.css'); ?>">
    <link rel="shortcut icon" href="<?= base_url('public/admin/img/'.$sekolah['logo']); ?>" type="image/x-icon">
    <!-- Google Font: Source Sans Pro -->
    <style>
        *{
            font-family: "Times New Roman", Times, serif;
        }
        td{
            padding: 0 30px 0 0;
        }
        .tajuk{
            width: 70%;
        }
        .garis{
            width: 100%;
            border-bottom: 1px dotted black;
            margin : 20px 0;
        }
        .container{
            break-inside: avoid;
            height: 27cm;
        }
    </style>
</head>
<body>
    <?php foreach($cetak as $c) :?>
    <div class="container">
        <h3 class="text-center">SURAT KETERANGAN BEBAS <br> PERPUSTAKAAN <?= strtoupper($perpus['nama_perpus']) ?> <br> <?= strtoupper($sekolah['nama_sekolah']) ?></h3>
        <div class="garis"></div>
        <p class="text-justify">Yang bertanda tangan dibawah ini, Petugas Perpustakaan <?= strtoupper($perpus['nama_perpus']); ?> <?= $sekolah['nama_sekolah'];?> menerangkan bahwa :</p>
        <table>
            <tr>
                <td>1. Nama     </td>
                <td>: <?= $c['nama_siswa']?></td>
            </tr>
            <tr>
                <td>2. NIS      </td>
                <td>: <?= $c['nis']?></td>
            </tr>
            <tr>
                <td>3. Kelas    </td>
                <td>: <?= $c['nama_kelas']?></td>
            </tr>
        </table>
        <br>
        <p>Benar-benar telah mengembalikan segala pinjaman buku di Perpustakaan <?= strtoupper($perpus['nama_perpus']) ?> <?= $sekolah['nama_sekolah'] ?>. Demikian Surat Keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
        <br>
        <table>
            <tr>
                <td class="tajuk"></td>
                <td>
                    <p class="text-center"><?= $sekolah['kecamatan'] ?>, <?= date('d/m/Y'); ?><br>Petugas Perpustakaan <br><br><br>........................................</p>
                </td>
            </tr>
        </table>
    </div>
    <?php endforeach; ?>
</body>
</html>
<script>
    // setTimeout(function () { window.print(); }, 500);
    // window.onfocus = function () { setTimeout(function () { window.close(); }, 500); }
</script>