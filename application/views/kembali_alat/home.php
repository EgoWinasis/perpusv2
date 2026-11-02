<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed'); ?>

<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa fa-edit" style="color:green"></i>
			<?= $title_web; ?>
		</h1>

		<ol class="breadcrumb">
			<li>
				<a href="<?= base_url('dashboard'); ?>">
					<i class="fa fa-dashboard"></i>&nbsp; Dashboard
				</a>
			</li>
			<li class="active">
				<i class="fa fa-file-text"></i>&nbsp; <?= $title_web; ?>
			</li>
		</ol>
	</section>

	<section class="content">

		<?php
		if (!empty($this->session->flashdata('pesan'))) {
			echo $this->session->flashdata('pesan');
		}
		?>

		<div class="row">
			<div class="col-md-12">

				<div class="box box-primary">


					<div class="box-header with-border">

						<a href="<?= base_url('cetak/kembali_alat?' . http_build_query($this->input->get())); ?>"
							target="_blank" class="btn btn-warning">
							<i class="fa fa-print"></i> Cetak Riwayat Pengembalian
						</a>

						<a href="<?= current_url(); ?>" class="btn btn-default">
							<i class="fa fa-refresh"></i> Reset Filter
						</a>

					</div>


					<div class="box-body">

						<!-- FORM FILTER PENGEMBALIAN -->
						<form method="GET" action="<?= current_url(); ?>">
							<div class="row">

								<div class="col-md-3">
									<div class="form-group">
										<label>Nomor Pinjam</label>
										<input type="text" name="pinjam_id" class="form-control"
											placeholder="Masukkan nomor pinjam"
											value="<?= html_escape($this->input->get('pinjam_id')); ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label>Kode Anggota</label>
										<input type="text" name="anggota_id" class="form-control"
											placeholder="Masukkan kode anggota"
											value="<?= html_escape($this->input->get('anggota_id')); ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label>Nama Anggota</label>
										<input type="text" name="nama" class="form-control"
											placeholder="Masukkan nama anggota"
											value="<?= html_escape($this->input->get('nama')); ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label>Kode Alat</label>
										<input type="text" name="kode" class="form-control"
											placeholder="Masukkan kode alat"
											value="<?= html_escape($this->input->get('kode')); ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label>Tanggal Mulai Pengembalian</label>
										<input type="date" name="tanggal_mulai" class="form-control"
											value="<?= html_escape($this->input->get('tanggal_mulai')); ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label>Tanggal Selesai Pengembalian</label>
										<input type="date" name="tanggal_selesai" class="form-control"
											value="<?= html_escape($this->input->get('tanggal_selesai')); ?>">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label>&nbsp;</label>
										<button type="submit" class="btn btn-primary btn-block">
											<i class="fa fa-search"></i> Cari Data
										</button>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label>&nbsp;</label>
										<a href="<?= current_url(); ?>" class="btn btn-default btn-block">
											<i class="fa fa-refresh"></i> Reset
										</a>
									</div>
								</div>

							</div>
						</form>

						<hr>

						<!-- TABEL DATA PENGEMBALIAN -->
						<div class="table-responsive">
							<table id="example1" class="table table-bordered table-striped" width="100%">

								<thead>
									<tr>
										<th>No</th>
										<th>No Pinjam</th>
										<th>Kode Anggota</th>
										<th>Nama</th>
										<th>Kode Alat</th>
										<th>Pinjam</th>
										<th>Balik</th>
										<th>Status</th>
										<th>Kembali</th>
										<th>Denda</th>
										<th>Aksi</th>
									</tr>
								</thead>

								<tbody>
									<?php
									$no = 1;

									if (isset($pinjam) && $pinjam->num_rows() > 0) {
										foreach ($pinjam->result_array() as $isi) {

											$pinjam_id = $isi['pinjam_id'];

											// Ambil data denda
											$denda = $this->db->get_where(
												'tbl_denda',
												array('pinjam_id' => $pinjam_id)
											);

											$total_denda = $denda->row();

											// Hitung jumlah alat dalam transaksi
											$jml = $this->db->get_where(
												'tbl_pinjam_alat',
												array('pinjam_id' => $pinjam_id)
											)->num_rows();
											?>

											<tr>
												<td><?= $no++; ?></td>

												<td>
													<?= html_escape($isi['pinjam_id']); ?>
												</td>

												<td>
													<?= html_escape($isi['anggota_id']); ?>
												</td>

												<td>
													<?= html_escape($isi['nama'] ?? '-'); ?>
												</td>

												<td>
													<?= html_escape($isi['kode']); ?>
												</td>

												<td>
													<?= html_escape($isi['tgl_pinjam']); ?>
												</td>

												<td>
													<?= html_escape($isi['tgl_balik']); ?>
												</td>

												<td>
													<span class="label label-success">
														<?= html_escape($isi['status']); ?>
													</span>
												</td>

												<td>
													<?php
													if (
														empty($isi['tgl_kembali']) ||
														$isi['tgl_kembali'] == '0'
													) {
														echo '<span style="color:red;">Belum dikembalikan</span>';
													} else {
														echo html_escape($isi['tgl_kembali']);
													}
													?>
												</td>

												<td>
													<?php
													if ($denda->num_rows() > 0 && $total_denda) {

														echo $this->M_Admin->rp($total_denda->denda);

													} else {

														$date1 = date('Ymd');
														$date2 = preg_replace(
															'/[^0-9]/',
															'',
															$isi['tgl_balik']
														);

														$diff = (int) $date2 - (int) $date1;

														if ($diff >= 0) {

															echo '<span style="color:green;">Tidak Ada Denda</span>';

														} else {

															$dd = $this->M_Admin->get_tableid_edit(
																'tbl_biaya_denda',
																'stat',
																'Aktif'
															);

															if ($dd) {

																$nominal = $jml * (
																	$dd->harga_denda * abs($diff)
																);

																echo '<p style="color:red;font-size:18px;">'
																	. $this->M_Admin->rp($nominal)
																	. '</p>';

																echo '<small style="color:#333;">* Untuk '
																	. $jml . ' Alat</small>';

															} else {

																echo '<span style="color:red;">Biaya denda belum diatur</span>';

															}
														}
													}
													?>
												</td>

												<td>
													<?php if ($this->session->userdata('level') == 'Petugas') { ?>

														<a href="<?= base_url('transaksi_alat/detailpinjam/' . $isi['pinjam_id']); ?>"
															class="btn btn-primary btn-sm" title="Detail pengembalian">
															<i class="fa fa-eye"></i>
														</a>

														<a href="<?= base_url('transaksi_alat/prosespinjam?pinjam_id=' . $isi['pinjam_id']); ?>"
															onclick="return confirm('Anda yakin data peminjaman ini akan dihapus?');"
															class="btn btn-danger btn-sm" title="Hapus data">
															<i class="fa fa-trash"></i>
														</a>

													<?php } else { ?>

														<a href="<?= base_url('transaksi_alat/detailpinjam/' . $isi['pinjam_id']); ?>"
															class="btn btn-primary btn-sm" title="Detail pengembalian">
															<i class="fa fa-eye"></i> Detail
														</a>

													<?php } ?>
												</td>
											</tr>

											<?php
										}
									} else {
										?>
										<tr>
											<td colspan="11" class="text-center">
												<i class="fa fa-info-circle"></i>
												Data pengembalian alat tidak ditemukan.
											</td>
										</tr>
									<?php } ?>
								</tbody>

							</table>
						</div>

					</div>
				</div>

			</div>
		</div>

	</section>
</div>