<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		//validasi jika user belum login
		$this->data['CI'] = &get_instance();
		$this->load->helper(array('form', 'url'));
		$this->load->helper('string');
		$this->load->model('M_Admin');
		if ($this->session->userdata('masuk_perpus') != TRUE) {
			$url = base_url('login');
			redirect($url);
		}
	}

	public function index()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['buku'] = $this->db->query("SELECT * FROM tbl_buku ORDER BY kode_buku ");
		$this->data['title_web'] = 'Data Buku';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('buku/buku_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}
	public function jumlah()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['buku'] = $this->db->query("SELECT * FROM tbl_buku GROUP BY title ORDER BY title ASC");
		$this->data['title_web'] = 'Data Jumlah Buku';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('buku/jumlah_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function bukudetail()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_buku', 'id_buku', $this->uri->segment('3'));
		if ($count > 0) {
			$this->data['buku'] = $this->M_Admin->get_tableid_edit('tbl_buku', 'id_buku', $this->uri->segment('3'));
			$this->data['kats'] = $this->db->query("SELECT * FROM tbl_kategori ORDER BY id_kategori DESC")->result_array();
			$this->data['rakbuku'] = $this->db->query("SELECT * FROM tbl_rak ORDER BY id_rak DESC")->result_array();
		} else {
			echo '<script>alert("BUKU TIDAK DITEMUKAN");window.location="' . base_url('data') . '"</script>';
		}

		$this->data['title_web'] = 'Data Buku Detail';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('buku/detail', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function bukuedit()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_buku', 'id_buku', $this->uri->segment('3'));
		if ($count > 0) {

			$this->data['buku'] = $this->M_Admin->get_tableid_edit('tbl_buku', 'id_buku', $this->uri->segment('3'));

			$this->data['kats'] = $this->db->query("SELECT * FROM tbl_kategori ORDER BY id_kategori DESC")->result_array();
			$this->data['rakbuku'] = $this->db->query("SELECT * FROM tbl_rak ORDER BY id_rak DESC")->result_array();
		} else {
			echo '<script>alert("BUKU TIDAK DITEMUKAN");window.location="' . base_url('data') . '"</script>';
		}

		$this->data['title_web'] = 'Data Buku Edit';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('buku/edit_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function bukutambah()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');

		$this->data['kats'] = $this->db->query("SELECT * FROM tbl_kategori ORDER BY id_kategori DESC")->result_array();
		$this->data['rakbuku'] = $this->db->query("SELECT * FROM tbl_rak ORDER BY id_rak DESC")->result_array();


		$this->data['title_web'] = 'Tambah Buku';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('buku/tambah_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}


	public function prosesbuku()
	{
		if ($this->session->userdata('masuk_perpus') != TRUE) {
			$url = base_url('login');
			redirect($url);
		}

		// hapus aksi form proses buku
		if (!empty($this->input->get('buku_id'))) {

			$buku = $this->M_Admin->get_tableid_edit('tbl_buku', 'id_buku', htmlentities($this->input->get('buku_id')));
			$bukuDel = $this->db->query("SELECT * FROM `tbl_buku` WHERE `id_buku` = '" . $this->input->get('buku_id') . "'")->result_array();
			$count = $this->db->query("SELECT * FROM `tbl_pinjam` WHERE `kode` = '" . $bukuDel[0]['kode_buku'] . "'")->result_array();

			if ((count($count)) > 0) {

				if ($this->$_SESSION['pesan'] == null) {
					$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-danger">
							<p> Gagal Hapus Buku ! Data Buku ada di table dipinjam / dikembalikan</p>
						</div></div>');
				}
			} else {

				$this->M_Admin->delete_table('tbl_buku', 'id_buku', $this->input->get('buku_id'));
				if ($this->$_SESSION['pesan'] == null) {
					$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
							<p> Berhasil Hapus Buku !</p>
						</div></div>');
				}
			}
			redirect(base_url('data'));
		}

		// tambah aksi form proses buku
		if (!empty($this->input->post('tambah'))) {
			$post = $this->input->post();
			$id_kategori = $post['kategori'];
			$count = $this->M_Admin->CountTableId('tbl_kategori', 'id_kategori', $id_kategori);
			if ($count > 0) {
				$query = $this->db->query("SELECT * FROM tbl_kategori WHERE id_kategori='$id_kategori'")->row();
			} // cek dulu apakah ada sudah ada kode di tabel.
			;
			$kode_kategori = $query->kode_kategori;
			$buku_id = $this->M_Admin->buat_kode('tbl_buku', 'BK', $id_kategori, $kode_kategori, 'kode_buku', 'ORDER BY kode_buku DESC LIMIT 1');
			$data = array(
				'kode_buku' => $buku_id,
				'id_kategori' => htmlentities($post['kategori']),
				'id_rak' => htmlentities($post['rak']),
				'isbn' => htmlentities($post['isbn']),
				'title' => htmlentities($post['title']),
				'pengarang' => htmlentities($post['pengarang']),
				'penerbit' => htmlentities($post['penerbit']),
				'thn_buku' => htmlentities($post['thn']),
				'jml' => htmlentities($post['jml']),
				'kondisi' => htmlentities($post['kondisi']),
				'tgl_masuk' => date('Y-m-d H:i:s')
			);


			$this->db->insert('tbl_buku', $data);
			if ($this->$_SESSION['pesan'] == null) {
				$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
				<p> Tambah Buku Sukses !</p>
				</div></div>');
			}
			redirect(base_url('data'));
		}

		// edit aksi form proses buku
		if (!empty($this->input->post('edit'))) {
			$post = $this->input->post();

			$bukuEdit = $this->db->query("SELECT * FROM `tbl_buku` WHERE `id_buku` = '" . $this->input->post('edit') . "'")->result_array();
			$count = $this->db->query("SELECT * FROM `tbl_pinjam` WHERE `kode` = '" . $bukuEdit[0]['kode_buku'] . "'")->result_array();


			if ((count($count)) > 0) {
				if ($this->$_SESSION['pesan'] == null) {
					$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-danger">
							<p> Gagal Edit Buku ! Data Buku ada di table dipinjam / dikembalikan</p>
						</div></div>');
				}
			} else {


				if (($post['kategori'] != $post['id_kategori_old'])) {
					$id_kategori = $post['kategori'];
					$count = $this->M_Admin->CountTableId('tbl_kategori', 'id_kategori', $id_kategori);
					if ($count > 0) {
						$query = $this->db->query("SELECT *FROM tbl_kategori WHERE id_kategori='$id_kategori'")->row();
					}
					$kode_kategori = $query->kode_kategori;
					$buku_id = $this->M_Admin->buat_kode_edit('tbl_buku', 'BK', $id_kategori, $kode_kategori, 'kode_buku', 'ORDER BY kode_buku DESC LIMIT 1');
					$data = array(
						'kode_buku' => $buku_id,
						'id_kategori' => htmlentities($post['kategori']),
						'id_rak' => htmlentities($post['rak']),
						'isbn' => htmlentities($post['isbn']),
						'title' => htmlentities($post['title']),
						'pengarang' => htmlentities($post['pengarang']),
						'penerbit' => htmlentities($post['penerbit']),
						'thn_buku' => htmlentities($post['thn']),
						'jml' => htmlentities($post['jml']),
						'kondisi' => htmlentities($post['kondisi']),
						'tgl_masuk' => date('Y-m-d H:i:s')
					);
				} else {

					$data = array(
						'id_kategori' => htmlentities($post['kategori']),
						'id_rak' => htmlentities($post['rak']),
						'isbn' => htmlentities($post['isbn']),
						'title' => htmlentities($post['title']),
						'pengarang' => htmlentities($post['pengarang']),
						'penerbit' => htmlentities($post['penerbit']),
						'thn_buku' => htmlentities($post['thn']),
						'jml' => htmlentities($post['jml']),
						'kondisi' => htmlentities($post['kondisi']),
						'tgl_masuk' => date('Y-m-d H:i:s')
					);
				}

				$this->db->where('id_buku', htmlentities($post['edit']));
				$this->db->update('tbl_buku', $data);
				if ($this->$_SESSION['pesan'] == null) {

					$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
						<p> Edit Buku Sukses !</p>
					</div></div>');
				}
			}
			redirect(base_url('data/bukuedit/' . $post['edit']));
		}
	}

	public function kategori()
	{

		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['kategori'] = $this->db->query("SELECT * FROM tbl_kategori ORDER BY kode_kategori DESC");

		if (!empty($this->input->get('id'))) {
			$id = $this->input->get('id');
			$count = $this->M_Admin->CountTableId('tbl_kategori', 'id_kategori', $id);
			if ($count > 0) {
				$this->data['kat'] = $this->db->query("SELECT *FROM tbl_kategori WHERE id_kategori='$id'")->row();
			} else {
				echo '<script>alert("KATEGORI TIDAK DITEMUKAN");window.location="' . base_url('data/kategori') . '"</script>';
			}
		}

		$this->data['title_web'] = 'Data Kategori ';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('kategori/kat_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}
	public function printbuku()
	{

		$this->data['title_web'] = 'Cetak Label Buku ';
		$this->load->view('buku/print_buku');
	}
	public function printfullbuku()
	{

		$this->data['title_web'] = 'Cetak Label Buku ';
		$this->load->view('buku/print_full_buku');
	}

	public function katproses()
	{
		if (!empty($this->input->post('tambah'))) {
			$post = $this->input->post();
			$data = array(
				'kode_kategori' => htmlentities($post['kode_kategori']),
				'nama_kategori' => htmlentities($post['kategori'])
			);
			$kode_kategori = $data['kode_kategori'];
			$getKategoriExist = $this->db->query("SELECT * FROM tbl_kategori WHERE kode_kategori = '$kode_kategori'");

			if ($getKategoriExist->num_rows() > 0) {
				$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-danger">
				<p> Gagal Menambahkan Kategori ' . $data['nama_kategori'] . ' !, Kode Sudah Terpakai</p>
				</div></div>');
				redirect(base_url('data/kategori'));
			} else {
				$this->db->insert('tbl_kategori', $data);

				if ($this->$_SESSION['pesan'] == null) {
					$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
					<p> Tambah Kategori Sukses !</p>
					</div></div>');
				}
			}
			redirect(base_url('data/kategori'));
		}

		if (!empty($this->input->post('edit'))) {
			$post = $this->input->post();
			$data = array(
				'kode_kategori' => htmlentities($post['kode_kategori']),
				'nama_kategori' => htmlentities($post['kategori']),
			);
			$this->db->where('id_kategori', htmlentities($post['edit']));
			$this->db->update('tbl_kategori', $data);

			if ($this->$_SESSION['pesan'] == null) {
				$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
				<p> Edit Kategori Sukses !</p>
				</div></div>');
			}
			redirect(base_url('data/kategori'));
		}

		if (!empty($this->input->get('kat_id'))) {


			$count = $this->M_Admin->CountTableId('tbl_buku', 'id_kategori', $this->input->get('kat_id'));
			// print_r($count);
			if ($count > 0) {
				if ($this->$_SESSION['pesan'] == null) {
					$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-danger">
					<p> Hapus Kategori Gagal ! Kategori sedang digunakan</p>
					</div></div>');
				}
			} else {
				$this->db->where('id_kategori', $this->input->get('kat_id'));
				$this->db->delete('tbl_kategori');
				if ($this->$_SESSION['pesan'] == null) {
					$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
					<p> Hapus Kategori Sukses !</p>
					</div></div>');
				}
			}
			// die();
			redirect(base_url('data/kategori'));
		}
	}

	public function rak()
	{

		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['rakbuku'] = $this->db->query("SELECT * FROM tbl_rak ORDER BY id_rak DESC");

		if (!empty($this->input->get('id'))) {
			$id = $this->input->get('id');
			$count = $this->M_Admin->CountTableId('tbl_rak', 'id_rak', $id);
			if ($count > 0) {
				$this->data['rak'] = $this->db->query("SELECT *FROM tbl_rak WHERE id_rak='$id'")->row();
			} else {
				echo '<script>alert("KATEGORI TIDAK DITEMUKAN");window.location="' . base_url('data/rak') . '"</script>';
			}
		}

		$this->data['title_web'] = 'Data Rak Buku ';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('rak/rak_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function rakproses()
	{
		if (!empty($this->input->post('tambah'))) {
			$post = $this->input->post();
			$data = array(
				'nama_rak' => htmlentities($post['rak']),
			);

			$this->db->insert('tbl_rak', $data);

			if ($this->$_SESSION['pesan'] == null) {
				$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
				<p> Tambah Rak Buku Sukses !</p>
				</div></div>');
			}
			redirect(base_url('data/rak'));
		}

		if (!empty($this->input->post('edit'))) {
			$post = $this->input->post();
			$data = array(
				'nama_rak' => htmlentities($post['rak']),
			);
			$this->db->where('id_rak', htmlentities($post['edit']));
			$this->db->update('tbl_rak', $data);

			if ($this->$_SESSION['pesan'] == null) {

				$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
				<p> Edit Rak Sukses !</p>
				</div></div>');
			}
			redirect(base_url('data/rak'));
		}

		if (!empty($this->input->get('rak_id'))) {
			$count = $this->M_Admin->CountTableId('tbl_buku', 'id_rak', $this->input->get('rak_id'));
			// print_r($count);
			if ($count > 0) {
				if ($this->$_SESSION['pesan'] == null) {
					$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-danger">
					<p> Hapus Rak Buku Gagal ! Rak sedang digunakan</p>
					</div></div>');
				}
			} else {

				$this->db->where('id_rak', $this->input->get('rak_id'));
				$this->db->delete('tbl_rak');

				if ($this->$_SESSION['pesan'] == null) {
					$this->session->set_flashdata('pesan', '<div id="notifikasi"><div class="alert alert-success">
					<p> Hapus Rak Buku Sukses !</p>
					</div></div>');
				}
			}
			redirect(base_url('data/rak'));
		}
	}

	// buku digital

	public function bukudigital()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$this->data['buku'] = $this->db->query("SELECT * FROM tbl_buku_digital ORDER BY kode_buku");
		$this->data['title_web'] = 'Data Buku Digital';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('buku/buku_digital_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function digitaltambah()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');

		$this->data['kats'] = $this->db->query("SELECT * FROM tbl_kategori ORDER BY id_kategori DESC")->result_array();


		$this->data['title_web'] = 'Tambah Buku';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('buku/tambah_digital_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}



	/**
	 * PROSES BUKU DIGITAL
	 * Upload halaman PNG satu per satu dari PDF.js.
	 * Tidak membutuhkan Imagick atau Spatie PDF-to-Image.
	 */
	public function prosesdigital()
	{
		if (!$this->session->userdata('masuk_perpus')) {
			if ($this->input->is_ajax_request()) {
				return $this->_digitalJson(false, 'Sesi login berakhir. Silakan login kembali.', [], 401);
			}

			redirect('login');
			return;
		}

		$action = $this->input->post('canvas_action', true);

		/*
		 * PROSES AJAX PDF.JS
		 */
		if ($action) {
			try {
				$basePath = FCPATH . 'assets_style/image/buku/lampiran/';

				if (!is_dir($basePath) && !mkdir($basePath, 0755, true)) {
					throw new Exception('Folder penyimpanan buku digital tidak dapat dibuat.');
				}

				/*
				 * MULAI UPLOAD
				 * Membuat folder sementara untuk satu proses upload.
				 */
				if ($action === 'mulai') {
					$folderAktif = $this->session->userdata('digital_canvas_folder');

					// Bersihkan folder proses sebelumnya yang masih tertinggal.
					if ($folderAktif) {
						$this->_hapusFolderDigital($folderAktif);
					}

					$folder = 'digital_' . bin2hex(random_bytes(12));
					$folderPath = $basePath . $folder;

					if (!mkdir($folderPath, 0755, true)) {
						throw new Exception('Gagal membuat folder sementara.');
					}

					$this->session->set_userdata('digital_canvas_folder', $folder);
					$this->session->set_userdata('digital_canvas_nomor', 0);

					return $this->_digitalJson(true, 'Folder upload berhasil dibuat.', [
						'folder' => $folder
					]);
				}

				/*
				 * VALIDASI FOLDER SESSION
				 */
				$folder = $this->session->userdata('digital_canvas_folder');

				if (
					!$folder ||
					!preg_match('/^digital_[a-f0-9]{24}$/', $folder)
				) {
					throw new Exception('Folder upload tidak valid atau sesi upload sudah berakhir.');
				}

				$folderPath = $basePath . $folder;

				if (!is_dir($folderPath)) {
					throw new Exception('Folder upload tidak ditemukan.');
				}

				/*
				 * UPLOAD SATU HALAMAN PNG
				 */
				if ($action === 'upload') {
					if (!isset($_FILES['halaman'])) {
						throw new Exception('File halaman tidak ditemukan.');
					}

					$nomor = (int) $this->input->post('nomor', true);
					$nomorTerakhir = (int) $this->session->userdata('digital_canvas_nomor');

					if ($nomor < 1 || $nomor !== ($nomorTerakhir + 1)) {
						throw new Exception('Urutan halaman tidak valid.');
					}

					$file = $_FILES['halaman'];

					if ($file['error'] !== UPLOAD_ERR_OK) {
						throw new Exception('Upload halaman gagal. Kode error: ' . $file['error']);
					}

					if (!is_uploaded_file($file['tmp_name'])) {
						throw new Exception('File upload tidak valid.');
					}

					// Validasi MIME dan ekstensi PNG.
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
					$mime = finfo_file($finfo, $file['tmp_name']);
					finfo_close($finfo);

					if ($mime !== 'image/png') {
						throw new Exception('File harus berupa gambar PNG.');
					}

					$infoGambar = getimagesize($file['tmp_name']);

					if (!$infoGambar || $infoGambar[2] !== IMAGETYPE_PNG) {
						throw new Exception('Format gambar PNG tidak valid.');
					}

					$namaFile = $nomor . '.png';
					$tujuan = $folderPath . DIRECTORY_SEPARATOR . $namaFile;

					if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
						throw new Exception('Gagal menyimpan halaman PNG.');
					}

					$this->session->set_userdata('digital_canvas_nomor', $nomor);

					return $this->_digitalJson(true, 'Halaman berhasil diupload.', [
						'nomor' => $nomor
					]);
				}

				/*
				 * BATAL UPLOAD
				 */
				if ($action === 'batal') {
					$this->_hapusFolderDigital($folder);

					$this->session->unset_userdata('digital_canvas_folder');
					$this->session->unset_userdata('digital_canvas_nomor');

					return $this->_digitalJson(true, 'Upload dibatalkan.');
				}

				/*
				 * SELESAIKAN UPLOAD DAN SIMPAN DATABASE
				 */
				if ($action === 'selesai') {
					$totalHalaman = (int) $this->session->userdata('digital_canvas_nomor');

					if ($totalHalaman < 1) {
						throw new Exception('Belum ada halaman buku yang berhasil diupload.');
					}

					// Pastikan file tersimpan lengkap dan berurutan.
					for ($i = 1; $i <= $totalHalaman; $i++) {
						if (!is_file($folderPath . DIRECTORY_SEPARATOR . $i . '.png')) {
							throw new Exception('Halaman ke-' . $i . ' tidak ditemukan.');
						}
					}

					$idBuku = (int) $this->input->post('id_buku', true);
					$isEdit = $idBuku > 0;

					$kategoriInput = $this->input->post('id_kategori', true);

					// Mendukung input berupa ID kategori maupun kode kategori.
					if (is_numeric($kategoriInput)) {
						$kategori = $this->db
							->get_where('tbl_kategori', ['id_kategori' => (int) $kategoriInput])
							->row();
					} else {
						$kategori = $this->db
							->get_where('tbl_kategori', ['kode_kategori' => $kategoriInput])
							->row();
					}

					if (!$kategori) {
						throw new Exception('Kategori buku tidak ditemukan.');
					}

					$isbn = trim($this->input->post('isbn', true));
					$judul = trim($this->input->post('title', true));
					$pengarang = trim($this->input->post('pengarang', true));
					$penerbit = trim($this->input->post('penerbit', true));
					$tahun = trim($this->input->post('thn_buku', true));

					if ($judul === '') {
						throw new Exception('Judul buku wajib diisi.');
					}

					if ($pengarang === '') {
						throw new Exception('Nama pengarang wajib diisi.');
					}

					if ($penerbit === '') {
						throw new Exception('Nama penerbit wajib diisi.');
					}

					$data = [
						'id_kategori' => $kategori->id_kategori,
						'isbn' => $isbn,
						'title' => $judul,
						'pengarang' => $pengarang,
						'penerbit' => $penerbit,
						'thn_buku' => $tahun,
						'total_hal' => $totalHalaman,
						'lampiran' => $folder
					];

					if ($isEdit) {
						$bukuLama = $this->db
							->get_where('tbl_buku_digital', ['id_buku' => $idBuku])
							->row();

						if (!$bukuLama) {
							throw new Exception('Data buku yang akan diedit tidak ditemukan.');
						}

						$this->db->where('id_buku', $idBuku);
						$berhasil = $this->db->update('tbl_buku_digital', $data);

						if (!$berhasil) {
							throw new Exception('Gagal memperbarui data buku.');
						}

						// Hapus folder lama setelah database berhasil diperbarui.
						if (
							!empty($bukuLama->lampiran) &&
							$bukuLama->lampiran !== $folder
						) {
							$this->_hapusFolderDigital($bukuLama->lampiran);
						}

						$pesan = 'Data buku digital berhasil diperbarui.';
					} else {
						$data['kode_buku'] = $this->_buatKodeDigital();
						$data['tgl_masuk'] = date('Y-m-d');

						$berhasil = $this->db->insert('tbl_buku_digital', $data);

						if (!$berhasil) {
							throw new Exception('Gagal menyimpan data buku.');
						}

						$pesan = 'Buku digital berhasil ditambahkan.';
					}

					$this->session->unset_userdata('digital_canvas_folder');
					$this->session->unset_userdata('digital_canvas_nomor');

					return $this->_digitalJson(true, $pesan, [
						'total_hal' => $totalHalaman
					]);
				}

				throw new Exception('Perintah upload tidak dikenal.');

			} catch (Throwable $e) {
				log_message('error', 'Proses buku digital: ' . $e->getMessage());

				return $this->_digitalJson(false, $e->getMessage(), [], 400);
			}
		}

		/*
		 * HAPUS BUKU DIGITAL
		 */
		$hapusId = $this->input->get('buku_id', true);

		if ($hapusId) {
			$buku = $this->db
				->get_where('tbl_buku_digital', ['id_buku' => (int) $hapusId])
				->row();

			if ($buku) {
				$this->db->where('id_buku', (int) $hapusId);
				$hapus = $this->db->delete('tbl_buku_digital');

				if ($hapus) {
					if (!empty($buku->lampiran)) {
						$this->_hapusFolderDigital($buku->lampiran);
					}

					$this->session->set_flashdata('pesan', 'Buku digital berhasil dihapus.');
				} else {
					$this->session->set_flashdata('pesan', 'Buku digital gagal dihapus.');
				}
			} else {
				$this->session->set_flashdata('pesan', 'Data buku tidak ditemukan.');
			}

			redirect('data/bukudigital');
			return;
		}

		redirect('data/bukudigital');
	}


	/**
	 * Membuat respons JSON sekaligus mengirim token CSRF terbaru.
	 */
	private function _digitalJson($status, $pesan, $tambahan = [], $httpCode = 200)
	{
		$hasil = array_merge([
			'status' => (bool) $status,
			'pesan' => $pesan,
			'csrf_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		], $tambahan);

		$this->output
			->set_status_header($httpCode)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($hasil));

		return;
	}


	/**
	 * Menghapus folder halaman buku digital.
	 * Hanya menerima nama folder, bukan path bebas.
	 */
	private function _hapusFolderDigital($folder)
	{
		if (
			empty($folder) ||
			!preg_match('/^(digital_[a-f0-9]{24}|[a-zA-Z0-9_-]+)$/', $folder)
		) {
			return false;
		}

		$basePath = FCPATH . 'assets_style/image/buku/lampiran/';
		$folderPath = $basePath . $folder;

		if (!is_dir($folderPath)) {
			return true;
		}

		$files = scandir($folderPath);

		foreach ($files as $file) {
			if ($file === '.' || $file === '..') {
				continue;
			}

			$path = $folderPath . DIRECTORY_SEPARATOR . $file;

			if (is_file($path)) {
				unlink($path);
			} elseif (is_dir($path)) {
				$this->_hapusFolderDigital($folder . '/' . $file);
			}
		}

		return rmdir($folderPath);
	}


	/**
	 * Membuat kode buku digital otomatis.
	 * Format: BD0001, BD0002, dan seterusnya.
	 */
	private function _buatKodeDigital()
	{
		$query = $this->db->query("
        SELECT MAX(CAST(SUBSTRING(kode_buku, 3) AS UNSIGNED)) AS nomor
        FROM tbl_buku_digital
        WHERE kode_buku REGEXP '^BD[0-9]+$'
    ");

		$row = $query->row();
		$nomor = ($row && $row->nomor) ? ((int) $row->nomor + 1) : 1;

		return 'BD' . str_pad($nomor, 4, '0', STR_PAD_LEFT);
	}



	public function digitaldetail()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_buku_digital', 'id_buku', $this->uri->segment('3'));
		if ($count > 0) {
			$this->data['buku'] = $this->M_Admin->get_tableid_edit('tbl_buku_digital', 'id_buku', $this->uri->segment('3'));
			$this->data['kats'] = $this->db->query("SELECT * FROM tbl_kategori ORDER BY id_kategori DESC")->result_array();
		} else {
			echo '<script>alert("BUKU TIDAK DITEMUKAN");window.location="' . base_url('data/bukudigital') . '"</script>';
		}

		$this->data['title_web'] = 'Detail Buku Digital';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('buku/digital_detail', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function digitaledit()
	{
		$this->data['idbo'] = $this->session->userdata('ses_id');
		$count = $this->M_Admin->CountTableId('tbl_buku_digital', 'id_buku', $this->uri->segment('3'));
		if ($count > 0) {

			$this->data['buku'] = $this->M_Admin->get_tableid_edit('tbl_buku_digital', 'id_buku', $this->uri->segment('3'));

			$this->data['kats'] = $this->db->query("SELECT * FROM tbl_kategori ORDER BY id_kategori DESC")->result_array();
		} else {
			echo '<script>alert("BUKU TIDAK DITEMUKAN");window.location="' . base_url('data/bukudigital') . '"</script>';
		}

		$this->data['title_web'] = 'Edit Buku Digital';
		$this->load->view('header_view', $this->data);
		$this->load->view('sidebar_view', $this->data);
		$this->load->view('buku/digital_edit_view', $this->data);
		$this->load->view('footer_view', $this->data);
	}

	public function read()
	{
		$id = $this->uri->segment('3');

		$this->data['buku'] = $this->db->query("SELECT * FROM tbl_buku_digital WHERE id_buku = " . $id)->result_array();

		$this->load->view('buku/read_view', $this->data);
	}

	public function cropImage(string $nameFolder)
	{

		//get all image
		$nameFolder = $nameFolder;
		$directory = $_SERVER['DOCUMENT_ROOT'] . '/assets_style/image/buku/lampiran/' . $nameFolder;
		$images = glob($directory . "/*.png");

		$newFolder = random_string('numeric', 5) . str_replace('-', '', date('Y-m-d'));
		mkdir('./assets_style/image/buku/lampiran/' . $newFolder, 0777, TRUE);
		$index = 1;
		for ($i = 1; $i <= count($images); $i++) {
			list($width, $height) = getimagesize($directory . '/' . $i . '.png');
			if ($width > $height) {
				// Create an imagick object
				$image = new Imagick($directory . '/' . $i . '.png');
				// Imagick function to crop Image 
				$image->cropImage($image->getImageWidth() / 2, $image->getImageHeight(), 0, 0);
				$image->writeImage($_SERVER['DOCUMENT_ROOT'] . '/assets_style/image/buku/lampiran/' . $newFolder . '/' . $index . '.png');

				$image = new Imagick($directory . '/' . $i . '.png');
				$image->cropImage($image->getImageWidth() / 2, $image->getImageHeight(), $image->getImageWidth() / 2, 0);
				$image->writeImage($_SERVER['DOCUMENT_ROOT'] . '/assets_style/image/buku/lampiran/' . $newFolder . '/' . ($index + 1) . '.png');

				$index += 1;
			} else {
				$image = new Imagick($directory . '/' . $i . '.png');
				$image->writeImage($_SERVER['DOCUMENT_ROOT'] . '/assets_style/image/buku/lampiran/' . $newFolder . '/' . $index . '.png');
			}

			$index++;
		}

		$lampiran = './assets_style/image/buku/lampiran/' . $nameFolder;
		if (is_dir($lampiran)) {
			array_map('unlink', glob("$lampiran/*.*"));
			rmdir($lampiran);
		}

		$directory = $_SERVER['DOCUMENT_ROOT'] . '/assets_style/image/buku/lampiran/' . $newFolder;
		$images = glob($directory . "/*.png");
		$this->db->set('total_hal', count($images));
		$this->db->set('lampiran', $newFolder);
	}

	public function printqrcode()
	{
		$this->data['title_web'] = 'Cetak QR Code Buku';

		$ids = $this->input->get('id');

		if (empty($ids)) {
			show_error(
				'ID buku tidak diterima. Silakan pilih buku dari halaman Data Buku.',
				400
			);
			return;
		}

		// Ubah daftar ID menjadi array
		$ids = explode(',', $ids);

		// Validasi ID buku
		$ids = array_values(array_unique(array_filter($ids, function ($id) {
			return ctype_digit((string) $id) && (int) $id > 0;
		})));

		if (empty($ids)) {
			show_error('ID buku tidak valid.', 400);
			return;
		}

		// Ambil buku berdasarkan ID yang dipilih
		$this->db->where_in('id_buku', $ids);
		$this->db->order_by('kode_buku', 'ASC');

		$this->data['buku'] = $this->db->get('tbl_buku')->result_array();

		if (empty($this->data['buku'])) {
			show_error('Data buku tidak ditemukan.', 404);
			return;
		}

		$this->load->view('buku/print_qrcode', $this->data);
	}


}
