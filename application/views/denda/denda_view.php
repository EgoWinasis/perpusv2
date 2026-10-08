<?php if(! defined('BASEPATH')) exit('No direct script acess allowed');?>
<div class="content-wrapper">

    <section class="content-header">

        <h1>
            <i class="fa fa-edit" style="color:green"></i>
            <?= $title_web;?>
        </h1>

        <ol class="breadcrumb">

            <li>
                <a href="<?= base_url('dashboard');?>">
                    <i class="fa fa-dashboard"></i>
                    &nbsp; Dashboard
                </a>
            </li>

            <li class="active">
                <i class="fa fa-file-text"></i>
                &nbsp; <?= $title_web;?>
            </li>

        </ol>

    </section>


    <section class="content">

        <?php
        if(!empty($this->session->flashdata())){
            echo $this->session->flashdata('pesan');
        }
        ?>


        <div class="row">

            <div class="col-md-12">

                <div class="row">


                    <!-- ================================= -->
                    <!-- FORM TAMBAH / EDIT -->
                    <!-- ================================= -->

                    <div class="col-sm-4">

                        <div class="box box-primary">

                            <div class="box-header with-border">

                                <?php if(!empty($this->input->get('id'))){ ?>

                                    <h4>
                                        <i class="fa fa-edit"></i>
                                        Edit Harga Denda
                                    </h4>

                                <?php }else{ ?>

                                    <h4>
                                        <i class="fa fa-plus"></i>
                                        Tambah Harga Denda
                                    </h4>

                                <?php } ?>

                            </div>


                            <div class="box-body">


                                <!-- ================================= -->
                                <!-- EDIT -->
                                <!-- ================================= -->

                                <?php if(!empty($this->input->get('id'))){ ?>

                                <form
                                    method="post"
                                    action="<?= base_url('transaksi/dendaproses');?>"
                                >


                                    <!-- NOMINAL -->
                                    <div class="form-group">

                                        <label>
                                            Nominal Denda
                                        </label>

                                        <input
                                            type="number"
                                            name="harga"
                                            class="form-control"
                                            value="<?= $den->harga_denda;?>"
                                            placeholder="Contoh : 10000"
                                            min="0"
                                            required
                                        >

                                    </div>


                                    <!-- JENIS DENDA -->
                                    <div class="form-group">

                                        <label>
                                            Jenis Denda
                                        </label>

                                        <select
                                            name="jenis_denda"
                                            class="form-control"
                                            required
                                        >

                                            <option
                                                value="Harian"
                                                <?= ($den->jenis_denda == 'Harian') ? 'selected' : '';?>
                                            >
                                                Harian
                                            </option>

                                            <option
                                                value="Mingguan"
                                                <?= ($den->jenis_denda == 'Mingguan') ? 'selected' : '';?>
                                            >
                                                Mingguan
                                            </option>

                                            <option
                                                value="Bulanan"
                                                <?= ($den->jenis_denda == 'Bulanan') ? 'selected' : '';?>
                                            >
                                                Bulanan
                                            </option>

                                        </select>

                                    </div>


                                    <!-- MAKSIMAL PERHITUNGAN -->
                                    <div class="form-group">

                                        <label>
                                            Maksimal Perhitungan Denda
                                        </label>

                                        <div class="input-group">

                                            <input
                                                type="number"
                                                name="maksimal_perhitungan"
                                                class="form-control"
                                                value="<?= $den->maksimal_perhitungan;?>"
                                                placeholder="Contoh : 3"
                                                min="0"
                                                required
                                            >

                                            <span
                                                class="input-group-addon"
                                                id="satuan_denda"
                                            >
                                                <?= $den->jenis_denda;?>
                                            </span>

                                        </div>

                                        <small class="text-muted">
                                            Contoh: jika 3 Bulanan,
                                            maka maksimal denda yang dihitung adalah 3 bulan.
                                        </small>

                                    </div>


                                    <!-- STATUS -->
                                    <div class="form-group">

                                        <label>
                                            Status
                                        </label>

                                        <select
                                            name="status"
                                            class="form-control"
                                            required
                                        >

                                            <option
                                                value="Aktif"
                                                <?= ($den->stat == 'Aktif') ? 'selected' : '';?>
                                            >
                                                Aktif
                                            </option>

                                            <option
                                                value="Tidak Aktif"
                                                <?= ($den->stat == 'Tidak Aktif') ? 'selected' : '';?>
                                            >
                                                Tidak Aktif
                                            </option>

                                        </select>

                                    </div>


                                    <br>


                                    <input
                                        type="hidden"
                                        name="edit"
                                        value="<?= $den->id_biaya_denda;?>"
                                    >


                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >

                                        <i class="fa fa-edit"></i>
                                        Edit Harga Denda

                                    </button>


                                </form>


                                <?php }else{ ?>


                                <!-- ================================= -->
                                <!-- TAMBAH -->
                                <!-- ================================= -->

                                <form
                                    method="post"
                                    action="<?= base_url('transaksi/dendaproses');?>"
                                >


                                    <!-- NOMINAL -->
                                    <div class="form-group">

                                        <label>
                                            Nominal Denda
                                        </label>

                                        <input
                                            type="number"
                                            name="harga"
                                            class="form-control"
                                            placeholder="Contoh : 10000"
                                            min="0"
                                            required
                                        >

                                    </div>


                                    <!-- JENIS DENDA -->
                                    <div class="form-group">

                                        <label>
                                            Jenis Denda
                                        </label>

                                        <select
                                            name="jenis_denda"
                                            class="form-control"
                                            id="jenis_denda"
                                            required
                                        >

                                            <option value="">
                                                - Pilih Jenis Denda -
                                            </option>

                                            <option value="Harian">
                                                Harian
                                            </option>

                                            <option value="Mingguan">
                                                Mingguan
                                            </option>

                                            <option value="Bulanan">
                                                Bulanan
                                            </option>

                                        </select>

                                    </div>


                                    <!-- MAKSIMAL -->
                                    <div class="form-group">

                                        <label>
                                            Maksimal Perhitungan Denda
                                        </label>

                                        <div class="input-group">

                                            <input
                                                type="number"
                                                name="maksimal_perhitungan"
                                                class="form-control"
                                                placeholder="Contoh : 3"
                                                min="0"
                                                required
                                            >

                                            <span
                                                class="input-group-addon"
                                                id="satuan_denda"
                                            >
                                                Satuan
                                            </span>

                                        </div>

                                        <small class="text-muted">
                                            Tentukan batas maksimal perhitungan
                                            berdasarkan jenis denda.
                                        </small>

                                    </div>


                                    <br>


                                    <input
                                        type="hidden"
                                        name="tambah"
                                        value="tambah"
                                    >


                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >

                                        <i class="fa fa-plus"></i>
                                        Tambah Harga Denda

                                    </button>


                                </form>

                                <?php } ?>


                            </div>

                        </div>

                    </div>



                    <!-- ================================= -->
                    <!-- TABLE -->
                    <!-- ================================= -->

                    <div class="col-sm-8">

                        <div class="box box-primary">

                            <div class="box-header with-border">

                            </div>


                            <div class="box-body">

                                <div class="table-responsive">

                                    <table
                                        id="example1"
                                        class="table table-bordered table-striped"
                                        width="100%"
                                    >

                                        <thead>

                                            <tr>

                                                <th>No</th>

                                                <th>
                                                    Nominal
                                                </th>

                                                <th>
                                                    Jenis Denda
                                                </th>

                                                <th>
                                                    Maksimal Perhitungan
                                                </th>

                                                <th>
                                                    Status
                                                </th>

                                                <th>
                                                    Mulai Tanggal
                                                </th>

                                                <th>
                                                    Aksi
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody>

                                        <?php

                                        $no = 1;

                                        foreach($denda->result_array() as $isi){

                                        ?>

                                            <tr>

                                                <td>
                                                    <?= $no;?>
                                                </td>


                                                <td>

                                                    Rp <?= number_format(
                                                        $isi['harga_denda'],
                                                        0,
                                                        ',',
                                                        '.'
                                                    );?>

                                                </td>


                                                <td>

                                                    <?php
                                                    if($isi['jenis_denda'] == 'Harian'){
                                                    ?>

                                                        <span class="label label-info">
                                                            Harian
                                                        </span>

                                                    <?php
                                                    }elseif($isi['jenis_denda'] == 'Mingguan'){
                                                    ?>

                                                        <span class="label label-warning">
                                                            Mingguan
                                                        </span>

                                                    <?php
                                                    }else{
                                                    ?>

                                                        <span class="label label-primary">
                                                            Bulanan
                                                        </span>

                                                    <?php } ?>

                                                </td>


                                                <td>

                                                    <?php
                                                    if($isi['maksimal_perhitungan'] > 0){
                                                    ?>

                                                        <?= $isi['maksimal_perhitungan'];?>
                                                        <?= $isi['jenis_denda'];?>

                                                    <?php
                                                    }else{
                                                    ?>

                                                        <span class="label label-default">
                                                            Tanpa Batas
                                                        </span>

                                                    <?php } ?>

                                                </td>


                                                <td>

                                                    <?php
                                                    if($isi['stat'] == 'Aktif'){
                                                    ?>

                                                        <span class="label label-success">
                                                            Aktif
                                                        </span>

                                                    <?php
                                                    }else{
                                                    ?>

                                                        <span class="label label-default">
                                                            Tidak Aktif
                                                        </span>

                                                    <?php } ?>

                                                </td>


                                                <td>
                                                    <?= $isi['tgl_tetap'];?>
                                                </td>


                                                <td style="width:20%;">

                                                    <!-- EDIT -->
                                                    <a
                                                        href="<?= base_url(
                                                            'transaksi/denda?id='.
                                                            $isi['id_biaya_denda']
                                                        );?>"
                                                    >

                                                        <button
                                                            type="button"
                                                            class="btn btn-success"
                                                            title="Edit"
                                                        >

                                                            <i class="fa fa-edit"></i>

                                                        </button>

                                                    </a>


                                                    <?php if($isi['stat'] == 'Aktif'){ ?>

                                                        <button
                                                            type="button"
                                                            class="btn btn-warning"
                                                            title="Aktif"
                                                        >

                                                            <i class="fa fa-ban"></i>

                                                        </button>

                                                    <?php }else{ ?>


                                                        <!-- HAPUS -->
                                                        <a
                                                            href="<?= base_url(
                                                                'transaksi/dendaproses?denda_id='.
                                                                $isi['id_biaya_denda']
                                                            );?>"
                                                            onclick="return confirm(
                                                                'Anda yakin Biaya denda ini akan dihapus ?'
                                                            );"
                                                        >

                                                            <button
                                                                type="button"
                                                                class="btn btn-danger"
                                                                title="Hapus"
                                                            >

                                                                <i class="fa fa-trash"></i>

                                                            </button>

                                                        </a>


                                                    <?php } ?>

                                                </td>

                                            </tr>

                                        <?php

                                            $no++;

                                        }

                                        ?>

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>



<script>

$(document).ready(function(){

    function updateSatuan()
    {
        var jenis = $('#jenis_denda').val();

        if(jenis == 'Harian')
        {
            $('#satuan_denda').text('Hari');
        }
        else if(jenis == 'Mingguan')
        {
            $('#satuan_denda').text('Minggu');
        }
        else if(jenis == 'Bulanan')
        {
            $('#satuan_denda').text('Bulan');
        }
        else
        {
            $('#satuan_denda').text('Satuan');
        }
    }

    $('#jenis_denda').change(function(){

        updateSatuan();

    });

    updateSatuan();

});

</script>
