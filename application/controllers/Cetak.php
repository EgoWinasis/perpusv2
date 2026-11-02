<?php
defined('BASEPATH') or exit('No direct script access allowed');

use \Mpdf\Mpdf as PDF;

class Cetak extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		//validasi jika user belum login
		$this->data['CI'] = &get_instance();
		$this->load->helper(array('form', 'url'));
		$this->load->model('M_Admin');
		if ($this->session->userdata('masuk_perpus') != TRUE) {
			$url = base_url('login');
			redirect($url);
		}
	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function cetak_siswa()
	{
		// Ambil parameter filter dari URL
		$nama = trim($this->input->get('nama', TRUE));
		$kode_anggota = trim($this->input->get('kode_anggota', TRUE));
		$jenkel = trim($this->input->get('jenkel', TRUE));

		// Query data siswa menggunakan Query Builder
		$this->db->from('tbl_siswa');

		if ($nama !== '') {
			$this->db->like('nama', $nama);
		}

		if ($kode_anggota !== '') {
			$this->db->like('kode_anggota', $kode_anggota);
		}

		if ($jenkel !== '') {
			$this->db->where('jenkel', $jenkel);
		}

		$this->db->order_by('kode_anggota', 'ASC');

		$siswa = $this->db->get()->result_array();

		// Buat dokumen PDF
		$document = new PDF([
			'mode' => 'utf-8',
			'format' => 'A4',
			'margin_header' => 3,
			'margin_top' => 10,
			'margin_bottom' => 10,
			'margin_footer' => 2,
			'default_font_size' => 12,
			'default_font' => 'sans-serif'
		]);

		$document->SetTitle('DATABASE SISWA');

		$stylesheet = file_get_contents('cetak.css');

		if ($stylesheet !== false) {
			$document->WriteHTML(
				$stylesheet,
				\Mpdf\HTMLParserMode::HEADER_CSS
			);
		}

		// Judul laporan
		$html = "
        <h4 style='text-align:center;'>
            DATA ANGGOTA PERPUSTAKAAN SDN GETASKEREP 01
        </h4>

        <p style='font-size:10px;'>
            <b>Filter Laporan:</b><br>
            Nama: " . htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') . "<br>
            Kode Anggota: " . htmlspecialchars($kode_anggota, ENT_QUOTES, 'UTF-8') . "<br>
            Jenis Kelamin: " . htmlspecialchars($jenkel, ENT_QUOTES, 'UTF-8') . "
        </p>

        <table class='table-border' width='100%'>
            <thead>
                <tr>
                    <th class='table-border' width='5%'>No.</th>
                    <th class='table-border' width='15%'>Kode Anggota</th>
                    <th class='table-border' width='60%'>Nama</th>
                    <th class='table-border' width='20%'>Jenis Kelamin</th>
                </tr>
            </thead>
            <tbody>
    ";

		if (!empty($siswa)) {
			$no = 1;

			foreach ($siswa as $row) {
				$html .= "
                <tr>
                    <td class='table-border'>" . $no . "</td>
                    <td class='table-border'>" .
					htmlspecialchars($row['kode_anggota'], ENT_QUOTES, 'UTF-8') .
					"</td>
                    <td class='table-border'>" .
					htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8') .
					"</td>
                    <td class='table-border'>" .
					htmlspecialchars($row['jenkel'], ENT_QUOTES, 'UTF-8') .
					"</td>
                </tr>
            ";

				$no++;
			}
		} else {
			$html .= "
            <tr>
                <td class='table-border' colspan='4' style='text-align:center;'>
                    Data siswa tidak ditemukan.
                </td>
            </tr>
        ";
		}

		$html .= "
            </tbody>
        </table>

        <p style='text-align:right; font-size:11px;'>
            Total Data Siswa: " . count($siswa) . "
        </p>
    ";

		$document->WriteHTML($html);

		$document->Output('Data Anggota.pdf', 'I');
	}





	public function jumlah_buku()
	{
		// ==========================================
		// FILTER LAPORAN JUMLAH BUKU
		// ==========================================

		$title = trim($this->input->get('title', TRUE));
		$kategori = trim($this->input->get('kategori', TRUE));
		$penerbit = trim($this->input->get('penerbit', TRUE));
		$thn_buku = trim($this->input->get('thn_buku', TRUE));

		// ==========================================
		// QUERY DATA JUMLAH BUKU
		// ==========================================

		$this->db->select('
        tbl_buku.title,
        tbl_buku.id_kategori,
        tbl_kategori.nama_kategori,
        tbl_buku.penerbit,
        tbl_buku.thn_buku,
        SUM(tbl_buku.jml) AS jumlah_total
    ');

		$this->db->from('tbl_buku');

		$this->db->join(
			'tbl_kategori',
			'tbl_kategori.id_kategori = tbl_buku.id_kategori',
			'left'
		);

		// Filter judul
		if ($title !== '') {
			$this->db->like('tbl_buku.title', $title);
		}

		// Filter kategori
		if ($kategori !== '') {
			$this->db->like('tbl_kategori.nama_kategori', $kategori);
		}

		// Filter penerbit
		if ($penerbit !== '') {
			$this->db->like('tbl_buku.penerbit', $penerbit);
		}

		// Filter tahun buku
		if ($thn_buku !== '') {
			$this->db->like('tbl_buku.thn_buku', $thn_buku);
		}

		// Pengelompokan data buku
		$this->db->group_by([
			'tbl_buku.title',
			'tbl_buku.id_kategori',
			'tbl_kategori.nama_kategori',
			'tbl_buku.penerbit',
			'tbl_buku.thn_buku'
		]);

		$this->db->order_by('tbl_buku.title', 'ASC');

		$jumlahBuku = $this->db->get()->result_array();

		// ==========================================
		// MEMBUAT PDF
		// ==========================================

		$document = new PDF([
			'mode' => 'utf-8',
			'format' => 'A4',
			'margin_header' => 3,
			'margin_top' => 10,
			'margin_bottom' => 10,
			'margin_footer' => 2,
			'default_font_size' => 12,
			'default_font' => 'sans-serif'
		]);

		$document->SetTitle('DATABASE JUMLAH BUKU');

		// CSS PDF
		$stylesheet = file_get_contents('cetak.css');

		$document->WriteHTML(
			$stylesheet,
			\Mpdf\HTMLParserMode::HEADER_CSS
		);

		// ==========================================
		// JUDUL LAPORAN
		// ==========================================

		$document->WriteHTML("
        <h4 class='text-center'>
            DATA JUMLAH BUKU PERPUSTAKAAN SDN GETASKEREP 01
        </h4>
    ");

		// ==========================================
		// INFORMASI FILTER
		// ==========================================

		$filterInfo = '';

		if ($title !== '') {
			$filterInfo .= '<b>Judul:</b> ' . html_escape($title) . ' &nbsp; ';
		}

		if ($kategori !== '') {
			$filterInfo .= '<b>Kategori:</b> ' . html_escape($kategori) . ' &nbsp; ';
		}

		if ($penerbit !== '') {
			$filterInfo .= '<b>Penerbit:</b> ' . html_escape($penerbit) . ' &nbsp; ';
		}

		if ($thn_buku !== '') {
			$filterInfo .= '<b>Tahun:</b> ' . html_escape($thn_buku) . ' &nbsp; ';
		}

		if ($filterInfo !== '') {
			$document->WriteHTML("
            <p style='font-size:10px;'>
                Filter: {$filterInfo}
            </p>
        ");
		}

		// ==========================================
		// HEADER TABEL
		// ==========================================

		$html = "
        <table class='table-border' width='100%'>
            <thead>
                <tr>
                    <th class='table-border' width='5%'>No.</th>
                    <th class='table-border' width='35%'>Judul</th>
                    <th class='table-border' width='15%'>Kategori</th>
                    <th class='table-border' width='15%'>Penerbit</th>
                    <th class='table-border' width='15%'>Tahun Buku</th>
                    <th class='table-border' width='15%'>Jumlah Total Buku</th>
                </tr>
            </thead>
            <tbody>
    ";

		// ==========================================
		// ISI TABEL
		// ==========================================

		$no = 1;
		$totalSeluruhBuku = 0;

		if (!empty($jumlahBuku)) {

			foreach ($jumlahBuku as $isi) {

				$judul = !empty($isi['title'])
					? html_escape($isi['title'])
					: '-';

				$kategoriNama = !empty($isi['nama_kategori'])
					? html_escape($isi['nama_kategori'])
					: '-';

				$namaPenerbit = !empty($isi['penerbit'])
					? html_escape($isi['penerbit'])
					: '-';

				$tahun = !empty($isi['thn_buku'])
					? html_escape($isi['thn_buku'])
					: '-';

				$jumlahTotal = (int) $isi['jumlah_total'];

				$totalSeluruhBuku += $jumlahTotal;

				$html .= "
                <tr>
                    <td class='table-border' align='center'>
                        {$no}
                    </td>

                    <td class='table-border'>
                        {$judul}
                    </td>

                    <td class='table-border'>
                        {$kategoriNama}
                    </td>

                    <td class='table-border'>
                        {$namaPenerbit}
                    </td>

                    <td class='table-border' align='center'>
                        {$tahun}
                    </td>

                    <td class='table-border' align='center'>
                        {$jumlahTotal}
                    </td>
                </tr>
            ";

				$no++;
			}

		} else {

			$html .= "
            <tr>
                <td class='table-border' colspan='6' align='center'>
                    Data jumlah buku tidak ditemukan.
                </td>
            </tr>
        ";
		}

		$html .= "
            </tbody>
        </table>
    ";

		$document->WriteHTML($html);

		// ==========================================
		// TOTAL KESELURUHAN
		// ==========================================

		$totalJudul = count($jumlahBuku);

		$document->WriteHTML("
        <p style='margin-top:10px; font-size:11px;'>
            <b>Total Judul Buku: {$totalJudul}</b><br>
            <b>Total Seluruh Eksemplar Buku: {$totalSeluruhBuku}</b>
        </p>
    ");

		// ==========================================
		// OUTPUT PDF
		// ==========================================

		$document->Output('Data Jumlah Buku.pdf', 'I');
	}




	public function buku()
	{
		// ==========================================
		// AMBIL PARAMETER FILTER
		// ==========================================
		$kode_buku = trim($this->input->get('kode_buku', TRUE));
		$isbn = trim($this->input->get('isbn', TRUE));
		$title = trim($this->input->get('title', TRUE));
		$penerbit = trim($this->input->get('penerbit', TRUE));
		$thn_buku = trim($this->input->get('thn_buku', TRUE));
		$kondisi = trim($this->input->get('kondisi', TRUE));
		$tanggal_mulai = trim($this->input->get('tanggal_mulai', TRUE));
		$tanggal_selesai = trim($this->input->get('tanggal_selesai', TRUE));

		// ==========================================
		// QUERY DATA BUKU DAN KATEGORI
		// ==========================================
		$this->db->select('
        tbl_buku.*,
        tbl_kategori.nama_kategori
    ');

		$this->db->from('tbl_buku');
		$this->db->join(
			'tbl_kategori',
			'tbl_kategori.id_kategori = tbl_buku.id_kategori',
			'left'
		);

		if ($kode_buku !== '') {
			$this->db->like('tbl_buku.kode_buku', $kode_buku);
		}

		if ($isbn !== '') {
			$this->db->like('tbl_buku.isbn', $isbn);
		}

		if ($title !== '') {
			$this->db->like('tbl_buku.title', $title);
		}

		if ($penerbit !== '') {
			$this->db->like('tbl_buku.penerbit', $penerbit);
		}

		if ($thn_buku !== '') {
			$this->db->like('tbl_buku.thn_buku', $thn_buku);
		}

		if ($kondisi !== '') {
			$this->db->where('tbl_buku.kondisi', $kondisi);
		}

		// Validasi format tanggal sebelum digunakan.
		if (
			$tanggal_mulai !== '' &&
			preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_mulai)
		) {
			$this->db->where('tbl_buku.tgl_masuk >=', $tanggal_mulai);
		}

		if (
			$tanggal_selesai !== '' &&
			preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_selesai)
		) {
			$this->db->where('tbl_buku.tgl_masuk <=', $tanggal_selesai);
		}

		$this->db->order_by('tbl_buku.kode_buku', 'ASC');

		$buku = $this->db->get()->result_array();

		// ==========================================
		// MEMBUAT DOKUMEN PDF
		// ==========================================
		$document = new PDF([
			'mode' => 'utf-8',
			'format' => 'A4-L',
			'margin_header' => 3,
			'margin_top' => 10,
			'margin_bottom' => 10,
			'margin_footer' => 2,
			'default_font_size' => 12,
			'default_font' => 'sans-serif'
		]);

		$document->SetTitle('DATABASE BUKU');

		$stylesheet = file_get_contents('cetak.css');

		if ($stylesheet !== false) {
			$document->WriteHTML(
				$stylesheet,
				\Mpdf\HTMLParserMode::HEADER_CSS
			);
		}

		// ==========================================
		// INFORMASI FILTER LAPORAN
		// ==========================================
		$filterHtml = "
        <table width='100%' style='font-size:10px; margin-bottom:10px;'>
            <tr>
                <td width='50%'>
                    <b>Filter Laporan:</b><br>
                    Kode Buku: " . htmlspecialchars($kode_buku, ENT_QUOTES, 'UTF-8') . "<br>
                    ISBN: " . htmlspecialchars($isbn, ENT_QUOTES, 'UTF-8') . "<br>
                    Judul: " . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . "<br>
                    Penerbit: " . htmlspecialchars($penerbit, ENT_QUOTES, 'UTF-8') . "
                </td>
                <td width='50%'>
                    Tahun Buku: " . htmlspecialchars($thn_buku, ENT_QUOTES, 'UTF-8') . "<br>
                    Kondisi: " . htmlspecialchars($kondisi, ENT_QUOTES, 'UTF-8') . "<br>
                    Tanggal Masuk: " . htmlspecialchars($tanggal_mulai, ENT_QUOTES, 'UTF-8') . "
                    s/d " . htmlspecialchars($tanggal_selesai, ENT_QUOTES, 'UTF-8') . "
                </td>
            </tr>
        </table>
    ";

		// ==========================================
		// JUDUL LAPORAN
		// ==========================================
		$document->WriteHTML("
        <h4 style='text-align:center;'>
            DATA BUKU PERPUSTAKAAN SDN GETASKEREP 01
        </h4>
    ");

		$document->WriteHTML($filterHtml);

		// ==========================================
		// HEADER TABEL
		// ==========================================
		$html = "
        <table class='table-border' width='100%'>
            <thead>
                <tr>
                    <th class='table-border' width='5%'>No.</th>
                    <th class='table-border' width='10%'>Kode</th>
                    <th class='table-border' width='20%'>ISBN</th>
                    <th class='table-border' width='20%'>Judul</th>
                    <th class='table-border' width='13%'>Kategori</th>
                    <th class='table-border' width='15%'>Penerbit</th>
                    <th class='table-border' width='7%'>Thn</th>
                    <th class='table-border' width='10%'>Kondisi</th>
                </tr>
            </thead>
            <tbody>
    ";

		// ==========================================
		// ISI TABEL
		// ==========================================
		if (!empty($buku)) {
			$no = 1;

			foreach ($buku as $row) {
				$html .= "
                <tr>
                    <td class='table-border'>" . $no . "</td>
                    <td class='table-border'>" .
					htmlspecialchars($row['kode_buku'], ENT_QUOTES, 'UTF-8') .
					"</td>
                    <td class='table-border'>" .
					htmlspecialchars($row['isbn'], ENT_QUOTES, 'UTF-8') .
					"</td>
                    <td class='table-border'>" .
					htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') .
					"</td>
                    <td class='table-border'>" .
					htmlspecialchars($row['nama_kategori'] ?? '-', ENT_QUOTES, 'UTF-8') .
					"</td>
                    <td class='table-border'>" .
					htmlspecialchars($row['penerbit'], ENT_QUOTES, 'UTF-8') .
					"</td>
                    <td class='table-border'>" .
					htmlspecialchars($row['thn_buku'], ENT_QUOTES, 'UTF-8') .
					"</td>
                    <td class='table-border'>" .
					htmlspecialchars($row['kondisi'], ENT_QUOTES, 'UTF-8') .
					"</td>
                </tr>
            ";

				$no++;
			}
		} else {
			$html .= "
            <tr>
                <td class='table-border' colspan='8' style='text-align:center;'>
                    Data buku tidak ditemukan.
                </td>
            </tr>
        ";
		}

		$html .= "
            </tbody>
        </table>

        <p style='text-align:right; font-size:11px;'>
            Total Data Buku: " . count($buku) . "
        </p>
    ";

		// ==========================================
		// OUTPUT PDF
		// ==========================================
		$document->WriteHTML($html);
		$document->Output('Data Buku.pdf', 'I');
	}


	public function kategori()
	{
		// Create the mPDF document
		$document = new PDF([
			'mode' => 'utf-8',
			'format' => 'A4',
			'margin_header' => '3',
			'margin_top' => '10',
			'margin_bottom' => '10',
			'margin_footer' => '2',
			'default_font_size' => 12,
			'default_font' => 'sans-serif'
		]);
		$documentFileName = "Data Kategori.pdf";
		// Set some header informations for output
		$header = [
			'Content-Type' => 'application/pdf',
			'Content-Disposition' => 'inline; filename="' . $documentFileName . '"'
		];


		$kategori = $this->db->query("SELECT * FROM tbl_kategori ORDER BY kode_kategori")->result_array();





		// Write some simple Content
		$stylesheet = file_get_contents('cetak.css');
		$document->SetTitle("DATABASE KATEGORI");
		$document->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
		$document->WriteHTML("
		<h4 class='text-center'>DATA KATEGORI BUKU PERPUSTAKAAN SDN GETASKEREP 01</h4>
	");


		$headerTable = "<table class='table-border' width='100%'>
	<thead>
		<tr>

			<th class='table-border' width='5%'>No.</th>
			<th class='table-border' width='30'>Kode</th>
			<th class='table-border' width='65%'>Kategori</th>
		</tr>
	</thead>
	<tbody>";
		$footerTable = "
			</tbody>
		</table>
			";

		$dataTable = "";

		$no = 1;
		for ($i = 0; $i < count($kategori); $i++) {



			$tbody = "
				<tr>
				
					<td class='table-border'>{$no}</td>
					<td class='table-border'>{$kategori[$i]['kode_kategori']}</td>
					<td class='table-border'>{$kategori[$i]['nama_kategori']}</td>
				</tr>
				";

			$dataTable .= $tbody;

			$no++;
		}

		$html = $headerTable . $dataTable . $footerTable;

		$document->WriteHTML("{$html}");
		$document->Output("Data Kategori.pdf", "I");
	}
	// 

	public function buku_digital()
	{
		// ==========================================
		// FILTER DATA BUKU DIGITAL
		// ==========================================

		$kode_buku = trim($this->input->get('kode_buku', TRUE));
		$isbn = trim($this->input->get('isbn', TRUE));
		$title = trim($this->input->get('title', TRUE));
		$penerbit = trim($this->input->get('penerbit', TRUE));
		$thn_buku = trim($this->input->get('thn_buku', TRUE));
		$tanggal_mulai = trim($this->input->get('tanggal_mulai', TRUE));
		$tanggal_selesai = trim($this->input->get('tanggal_selesai', TRUE));

		// ==========================================
		// QUERY DATA BUKU DIGITAL
		// ==========================================

		$this->db->select('
        tbl_buku_digital.*,
        tbl_kategori.nama_kategori
    ');

		$this->db->from('tbl_buku_digital');

		$this->db->join(
			'tbl_kategori',
			'tbl_kategori.id_kategori = tbl_buku_digital.id_kategori',
			'left'
		);

		// Filter kode buku
		if ($kode_buku != '') {
			$this->db->like('tbl_buku_digital.kode_buku', $kode_buku);
		}

		// Filter ISBN
		if ($isbn != '') {
			$this->db->like('tbl_buku_digital.isbn', $isbn);
		}

		// Filter judul
		if ($title != '') {
			$this->db->like('tbl_buku_digital.title', $title);
		}

		// Filter penerbit
		if ($penerbit != '') {
			$this->db->like('tbl_buku_digital.penerbit', $penerbit);
		}

		// Filter tahun buku
		if ($thn_buku != '') {
			$this->db->like('tbl_buku_digital.thn_buku', $thn_buku);
		}

		// Validasi format tanggal YYYY-MM-DD
		$validTanggalMulai = (
			$tanggal_mulai != '' &&
			preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_mulai)
		);

		$validTanggalSelesai = (
			$tanggal_selesai != '' &&
			preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_selesai)
		);

		// Filter tanggal masuk mulai
		if ($validTanggalMulai) {
			$this->db->where(
				'tbl_buku_digital.tgl_masuk >=',
				$tanggal_mulai
			);
		}

		// Filter tanggal masuk sampai
		if ($validTanggalSelesai) {
			$this->db->where(
				'tbl_buku_digital.tgl_masuk <',
				date('Y-m-d', strtotime($tanggal_selesai . ' +1 day'))
			);
		}

		// Urutkan berdasarkan kode buku
		$this->db->order_by('tbl_buku_digital.kode_buku', 'ASC');

		$digital = $this->db->get()->result_array();

		// ==========================================
		// MEMBUAT DOKUMEN PDF
		// ==========================================

		$document = new PDF([
			'mode' => 'utf-8',
			'format' => 'A4-L',
			'margin_header' => '3',
			'margin_top' => '10',
			'margin_bottom' => '10',
			'margin_footer' => '2',
			'default_font_size' => 12,
			'default_font' => 'sans-serif'
		]);

		$documentFileName = "Data Buku Digital.pdf";

		$header = [
			'Content-Type' => 'application/pdf',
			'Content-Disposition' => 'inline; filename="' . $documentFileName . '"'
		];

		// ==========================================
		// CSS DAN JUDUL LAPORAN
		// ==========================================

		$stylesheet = file_get_contents('cetak.css');

		$document->SetTitle("DATABASE BUKU DIGITAL");

		$document->WriteHTML(
			$stylesheet,
			\Mpdf\HTMLParserMode::HEADER_CSS
		);

		$document->WriteHTML("
        <h4 class='text-center'>
            DATA BUKU DIGITAL PERPUSTAKAAN SDN GETASKEREP 01
        </h4>
    ");

		// ==========================================
		// INFORMASI FILTER YANG DIGUNAKAN
		// ==========================================

		$filterInfo = '';

		if ($kode_buku != '') {
			$filterInfo .= '<b>Kode Buku:</b> ' . html_escape($kode_buku) . ' &nbsp; ';
		}

		if ($isbn != '') {
			$filterInfo .= '<b>ISBN:</b> ' . html_escape($isbn) . ' &nbsp; ';
		}

		if ($title != '') {
			$filterInfo .= '<b>Judul:</b> ' . html_escape($title) . ' &nbsp; ';
		}

		if ($penerbit != '') {
			$filterInfo .= '<b>Penerbit:</b> ' . html_escape($penerbit) . ' &nbsp; ';
		}

		if ($thn_buku != '') {
			$filterInfo .= '<b>Tahun:</b> ' . html_escape($thn_buku) . ' &nbsp; ';
		}

		if ($validTanggalMulai) {
			$filterInfo .= '<b>Dari:</b> ' . html_escape($tanggal_mulai) . ' &nbsp; ';
		}

		if ($validTanggalSelesai) {
			$filterInfo .= '<b>Sampai:</b> ' . html_escape($tanggal_selesai) . ' &nbsp; ';
		}

		if ($filterInfo != '') {
			$document->WriteHTML("
            <p style='font-size:10px;'>
                Filter: {$filterInfo}
            </p>
        ");
		}

		// ==========================================
		// HEADER TABEL
		// ==========================================

		$headerTable = "
        <table class='table-border' width='100%'>
            <thead>
                <tr>
                    <th class='table-border' width='5%'>No.</th>
                    <th class='table-border' width='10%'>Kode</th>
                    <th class='table-border' width='15%'>ISBN</th>
                    <th class='table-border' width='25%'>Judul</th>
                    <th class='table-border' width='13%'>Kategori</th>
                    <th class='table-border' width='25%'>Penerbit</th>
                    <th class='table-border' width='7%'>Thn</th>
                </tr>
            </thead>
            <tbody>
    ";

		$dataTable = '';

		// ==========================================
		// ISI TABEL
		// ==========================================

		$no = 1;

		foreach ($digital as $isi) {

			$kategori = !empty($isi['nama_kategori'])
				? $isi['nama_kategori']
				: '-';

			$dataTable .= "
            <tr>
                <td class='table-border'>{$no}</td>
                <td class='table-border'>" . html_escape($isi['kode_buku']) . "</td>
                <td class='table-border'>" . html_escape($isi['isbn']) . "</td>
                <td class='table-border'>" . html_escape($isi['title']) . "</td>
                <td class='table-border'>" . html_escape($kategori) . "</td>
                <td class='table-border'>" . html_escape($isi['penerbit']) . "</td>
                <td class='table-border'>" . html_escape($isi['thn_buku']) . "</td>
            </tr>
        ";

			$no++;
		}

		// Jika data tidak ditemukan
		if (count($digital) == 0) {
			$dataTable .= "
            <tr>
                <td class='table-border' colspan='7' align='center'>
                    Data buku digital tidak ditemukan.
                </td>
            </tr>
        ";
		}

		$footerTable = "
            </tbody>
        </table>
    ";

		// ==========================================
		// TOTAL DATA
		// ==========================================

		$totalData = count($digital);

		$html = $headerTable . $dataTable . $footerTable;

		$document->WriteHTML($html);

		$document->WriteHTML("
        <p style='margin-top:10px; font-size:11px;'>
            <b>Total Buku Digital: {$totalData} buku</b>
        </p>
    ");

		// ==========================================
		// OUTPUT PDF
		// ==========================================

		$document->Output("Data Buku Digital.pdf", "I");
	}



	public function alat()
	{
		// Create the mPDF document
		$document = new PDF([
			'mode' => 'utf-8',
			'format' => 'A4',
			'margin_header' => '3',
			'margin_top' => '10',
			'margin_bottom' => '10',
			'margin_footer' => '2',
			'default_font_size' => 12,
			'default_font' => 'sans-serif'
		]);
		$documentFileName = "Data Alat.pdf";
		// Set some header informations for output
		$header = [
			'Content-Type' => 'application/pdf',
			'Content-Disposition' => 'inline; filename="' . $documentFileName . '"'
		];


		$alat = $this->db->query("SELECT * FROM tbl_alat ORDER BY kode_alat")->result_array();





		// Write some simple Content
		$stylesheet = file_get_contents('cetak.css');
		$document->SetTitle("DATABASE ALAT PERAGA");
		$document->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
		$document->WriteHTML("
		<h4 class='text-center'>DATA ALAT PERAGA PERPUSTAKAAN SDN GETASKEREP 01</h4>
	");


		$headerTable = "<table class='table-border' width='100%'>
	<thead>
		<tr>

			<th class='table-border' width='5%'>No.</th>
			<th class='table-border' width='15%'>Kode</th>
			<th class='table-border' width='45%'>Nama</th>
			<th class='table-border' width='20%'>Kategori</th>
			<th class='table-border' width='15%'>Kondisi</th>
		</tr>
	</thead>
	<tbody>";
		$footerTable = "
			</tbody>
		</table>
			";

		$dataTable = "";

		$no = 1;
		for ($i = 0; $i < count($alat); $i++) {

			$kategori = $this->db->query("SELECT * FROM tbl_kategori_alat WHERE `id_kategori` = " . $alat[$i]['id_kategori'])->result_array();

			$tbody = "
				<tr>
				
					<td class='table-border'>{$no}</td>
					<td class='table-border'>{$alat[$i]['kode_alat']}</td>
					<td class='table-border'>{$alat[$i]['nama_alat']}</td>
					<td class='table-border'>{$kategori[0]['nama_kategori']}</td>
					<td class='table-border'>{$alat[$i]['kondisi']}</td>
				</tr>
				";

			$dataTable .= $tbody;

			$no++;
		}

		$html = $headerTable . $dataTable . $footerTable;

		$document->WriteHTML("{$html}");
		$document->Output("Data Alat Peraga.pdf", "I");
	}

	public function jumlah_alat()
	{
		// Create the mPDF document
		$document = new PDF([
			'mode' => 'utf-8',
			'format' => 'A4',
			'margin_header' => '3',
			'margin_top' => '10',
			'margin_bottom' => '10',
			'margin_footer' => '2',
			'default_font_size' => 12,
			'default_font' => 'sans-serif'
		]);
		$documentFileName = "Data Alat.pdf";
		// Set some header informations for output
		$header = [
			'Content-Type' => 'application/pdf',
			'Content-Disposition' => 'inline; filename="' . $documentFileName . '"'
		];


		$jumlahAlat = $this->db->query("SELECT * FROM tbl_alat GROUP BY nama_alat ORDER BY nama_alat ASC")->result_array();




		// Write some simple Content
		$stylesheet = file_get_contents('cetak.css');
		$document->SetTitle("DATABASE JUMLAH ALAT PERAGA");
		$document->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
		$document->WriteHTML("
		<h4 class='text-center'>DATA JUMLAH ALAT PERAGA PERPUSTAKAAN SDN GETASKEREP 01</h4>
	");


		$headerTable = "<table class='table-border' width='100%'>
	<thead>
		<tr>

			<th class='table-border' width='5%'>No.</th>
			<th class='table-border' width='45%'>Nama</th>
			<th class='table-border' width='15%'>Kategori</th>
			<th class='table-border' width='10%'>Jumlah Total Alat</th>
		</tr>
	</thead>
	<tbody>";
		$footerTable = "
			</tbody>
		</table>
			";

		$dataTable = "";

		$no = 1;
		for ($i = 0; $i < count($jumlahAlat); $i++) {

			$kategori = $this->db->query("SELECT * FROM tbl_kategori_alat WHERE `id_kategori` = " . $jumlahAlat[$i]['id_kategori'])->result_array();
			$jumlah = $this->db->query('SELECT sum(jumlah) FROM `tbl_alat` WHERE nama_alat = "' . $jumlahAlat[$i]['nama_alat'] . '"')->result_array();

			$tbody = "
				<tr>
				
					<td class='table-border'>{$no}</td>
					<td class='table-border'>{$jumlahAlat[$i]['nama_alat']}</td>
					<td class='table-border'>{$kategori[0]['nama_kategori']}</td>
					<td class='table-border'>{$jumlah[0]['sum(jumlah)']}</td>
				</tr>
				";

			$dataTable .= $tbody;

			$no++;
		}

		$html = $headerTable . $dataTable . $footerTable;

		$document->WriteHTML("{$html}");
		$document->Output("Data Alat Peraga.pdf", "I");
	}

	public function kategori_alat()
	{
		// Create the mPDF document
		$document = new PDF([
			'mode' => 'utf-8',
			'format' => 'A4',
			'margin_header' => '3',
			'margin_top' => '10',
			'margin_bottom' => '10',
			'margin_footer' => '2',
			'default_font_size' => 12,
			'default_font' => 'sans-serif'
		]);
		$documentFileName = "Data Kategori.pdf";
		// Set some header informations for output
		$header = [
			'Content-Type' => 'application/pdf',
			'Content-Disposition' => 'inline; filename="' . $documentFileName . '"'
		];


		$kategori = $this->db->query("SELECT * FROM tbl_kategori_alat ORDER BY kode_kategori")->result_array();





		// Write some simple Content
		$stylesheet = file_get_contents('cetak.css');
		$document->SetTitle("DATABASE KATEGORI ALAT");
		$document->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
		$document->WriteHTML("
		<h4 class='text-center'>DATA KATEGORI ALAT PERAGA PERPUSTAKAAN SDN GETASKEREP 01</h4>
	");


		$headerTable = "<table class='table-border' width='100%'>
	<thead>
		<tr>

			<th class='table-border' width='5%'>No.</th>
			<th class='table-border' width='30'>Kode</th>
			<th class='table-border' width='65%'>Kategori</th>
		</tr>
	</thead>
	<tbody>";
		$footerTable = "
			</tbody>
		</table>
			";

		$dataTable = "";

		$no = 1;
		for ($i = 0; $i < count($kategori); $i++) {



			$tbody = "
				<tr>
				
					<td class='table-border'>{$no}</td>
					<td class='table-border'>{$kategori[$i]['kode_kategori']}</td>
					<td class='table-border'>{$kategori[$i]['nama_kategori']}</td>
				</tr>
				";

			$dataTable .= $tbody;

			$no++;
		}

		$html = $headerTable . $dataTable . $footerTable;

		$document->WriteHTML("{$html}");
		$document->Output("Data Kategori.pdf", "I");
	}


	public function kehadiran()
	{
		// Create the mPDF document
		$document = new PDF([
			'mode' => 'utf-8',
			'format' => 'A4',
			'margin_header' => '3',
			'margin_top' => '10',
			'margin_bottom' => '10',
			'margin_footer' => '2',
			'default_font_size' => 12,
			'default_font' => 'sans-serif'
		]);
		$documentFileName = "Data Kehadiran.pdf";
		// Set some header informations for output
		$header = [
			'Content-Type' => 'application/pdf',
			'Content-Disposition' => 'inline; filename="' . $documentFileName . '"'
		];


		$kehadiran = $this->db->query("SELECT * FROM `tbl_siswa` ORDER BY kode_anggota")->result_array();




		// Write some simple Content
		$stylesheet = file_get_contents('cetak.css');
		$document->SetTitle("DATABASE JUMLAH KEHADIRAN ANGGOTA");
		$document->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);
		$document->WriteHTML("
		<h4 class='text-center'>DATA JUMLAH KEHADIRAN ANGGOTA PERPUSTAKAAN SDN GETASKEREP 01</h4>
	");


		$headerTable = "<table class='table-border' width='100%'>
	<thead>
		<tr>

			<th class='table-border' width='5%'>No.</th>
			<th class='table-border' width='15%'>Kode Anggota</th>
			<th class='table-border' width='55%'>Nama</th>
			<th class='table-border' width='15%'>Jenis Kelamin</th>
			<th class='table-border' width='10%'>Jumlah Kehadiran</th>
		</tr>
	</thead>
	<tbody>";
		$footerTable = "
			</tbody>
		</table>
			";

		$dataTable = "";

		$no = 1;
		for ($i = 0; $i < count($kehadiran); $i++) {

			$jumlah = $this->db->query("SELECT COUNT(tgl_kehadiran) FROM `tbl_kehadiran` WHERE siswa_id LIKE '%" . $kehadiran[$i]['id_siswa'] . "%'")->result_array();

			$tbody = "
				<tr>
				
					<td class='table-border'>{$no}</td>
					<td class='table-border'>{$kehadiran[$i]['kode_anggota']}</td>
					<td class='table-border'>{$kehadiran[$i]['nama']}</td>
					<td class='table-border'>{$kehadiran[$i]['jenkel']}</td>
					<td class='table-border'>{$jumlah[0]['COUNT(tgl_kehadiran)']}</td>
				</tr>
				";

			$dataTable .= $tbody;

			$no++;
		}

		$html = $headerTable . $dataTable . $footerTable;

		$document->WriteHTML("{$html}");
		$document->Output("Data kehadiran.pdf", "I");
	}



	public function pinjam_buku()
	{
		// FILTER
		$pinjam_id = trim($this->input->get('pinjam_id', TRUE));
		$anggota_id = trim($this->input->get('anggota_id', TRUE));
		$nama = trim($this->input->get('nama', TRUE));
		$kode = trim($this->input->get('kode', TRUE));
		$status = trim($this->input->get('status', TRUE));
		$tanggal_mulai = trim($this->input->get('tanggal_mulai', TRUE));
		$tanggal_selesai = trim($this->input->get('tanggal_selesai', TRUE));

		// Validasi tanggal
		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_mulai)) {
			$tanggal_mulai = '';
		}

		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_selesai)) {
			$tanggal_selesai = '';
		}

		// QUERY
		$this->db->select('
        p.pinjam_id,
        p.anggota_id,
        p.kode,
        p.status,
        p.tgl_pinjam,
        p.lama_pinjam,
        p.tgl_balik,
        p.tgl_kembali,
        s.nama
    ');

		$this->db->from('tbl_pinjam p');
		$this->db->join(
			'tbl_siswa s',
			's.kode_anggota = p.anggota_id',
			'left'
		);

		if ($pinjam_id !== '') {
			$this->db->like('p.pinjam_id', $pinjam_id);
		}

		if ($anggota_id !== '') {
			$this->db->like('p.anggota_id', $anggota_id);
		}

		if ($nama !== '') {
			$this->db->like('s.nama', $nama);
		}

		if ($kode !== '') {
			$this->db->like('p.kode', $kode);
		}

		if ($status !== '') {
			$this->db->where('p.status', $status);
		}

		if ($tanggal_mulai !== '') {
			$this->db->where('p.tgl_pinjam >=', $tanggal_mulai);
		}

		if ($tanggal_selesai !== '') {
			$this->db->where(
				'p.tgl_pinjam <',
				date('Y-m-d', strtotime($tanggal_selesai . ' +1 day'))
			);
		}

		$this->db->order_by('p.id_pinjam', 'DESC');

		$data = $this->db->get()->result_array();

		// PDF
		$document = new PDF([
			'mode' => 'utf-8',
			'format' => 'A4-L',
			'margin_header' => 3,
			'margin_top' => 10,
			'margin_bottom' => 10,
			'margin_footer' => 2,
			'default_font_size' => 12,
			'default_font' => 'sans-serif'
		]);

		$document->SetTitle('DATA RIWAYAT PEMINJAMAN BUKU');

		$stylesheet = file_get_contents('cetak.css');
		$document->WriteHTML(
			$stylesheet,
			\Mpdf\HTMLParserMode::HEADER_CSS
		);

		$document->WriteHTML("
        <h4 class='text-center'>
            DATA PEMINJAMAN BUKU PERPUSTAKAAN SDN GETASKEREP 01
        </h4>
    ");

		// INFORMASI FILTER
		$filter = '';

		$filters = [
			'Nomor Pinjam' => $pinjam_id,
			'Kode Anggota' => $anggota_id,
			'Nama Anggota' => $nama,
			'Kode Buku' => $kode,
			'Status' => $status,
			'Tanggal Mulai' => $tanggal_mulai,
			'Tanggal Selesai' => $tanggal_selesai
		];

		foreach ($filters as $label => $value) {
			if ($value !== '') {
				$filter .= '<b>' . $label . ':</b> '
					. html_escape($value) . ' &nbsp; ';
			}
		}

		if ($filter !== '') {
			$document->WriteHTML(
				"<p style='font-size:10px;'>Filter: {$filter}</p>"
			);
		}

		// HEADER TABEL
		$html = "
        <table class='table-border' width='100%'>
            <thead>
                <tr>
                    <th class='table-border' width='5%'>No.</th>
                    <th class='table-border' width='10%'>No. Pinjam</th>
                    <th class='table-border' width='10%'>Kode Anggota</th>
                    <th class='table-border' width='23%'>Nama</th>
                    <th class='table-border' width='10%'>Kode Buku</th>
                    <th class='table-border' width='10%'>Pinjam</th>
                    <th class='table-border' width='10%'>Balik</th>
                    <th class='table-border' width='12%'>Status</th>
                    <th class='table-border' width='10%'>Kembali</th>
                </tr>
            </thead>
            <tbody>
    ";

		$no = 1;

		foreach ($data as $isi) {

			$namaAnggota = !empty($isi['nama'])
				? html_escape($isi['nama'])
				: '-';

			$tglKembali = ($isi['tgl_kembali'] == '0' || empty($isi['tgl_kembali']))
				? 'Belum dikembalikan'
				: html_escape($isi['tgl_kembali']);

			$html .= "
            <tr>
                <td class='table-border'>{$no}</td>
                <td class='table-border'>" . html_escape($isi['pinjam_id']) . "</td>
                <td class='table-border'>" . html_escape($isi['anggota_id']) . "</td>
                <td class='table-border'>{$namaAnggota}</td>
                <td class='table-border'>" . html_escape($isi['kode']) . "</td>
                <td class='table-border'>" . html_escape($isi['tgl_pinjam']) . "</td>
                <td class='table-border'>" . html_escape($isi['tgl_balik']) . "</td>
                <td class='table-border'>" . html_escape($isi['status']) . "</td>
                <td class='table-border'>{$tglKembali}</td>
            </tr>
        ";

			$no++;
		}

		if (empty($data)) {
			$html .= "
            <tr>
                <td class='table-border' colspan='9' align='center'>
                    Data peminjaman tidak ditemukan.
                </td>
            </tr>
        ";
		}

		$html .= "</tbody></table>";

		$document->WriteHTML($html);

		$total = count($data);

		$document->WriteHTML("
        <p style='margin-top:10px;font-size:11px;'>
            <b>Total Data Peminjaman: {$total}</b>
        </p>
    ");

		$document->Output('Data Pinjam Buku.pdf', 'I');
	}

	public function total_pinjam_buku()
	{
		// FILTER
		$pinjam_id = trim($this->input->get('pinjam_id', TRUE));
		$anggota_id = trim($this->input->get('anggota_id', TRUE));
		$nama = trim($this->input->get('nama', TRUE));
		$kode = trim($this->input->get('kode', TRUE));
		$status = trim($this->input->get('status', TRUE));
		$tanggal_mulai = trim($this->input->get('tanggal_mulai', TRUE));
		$tanggal_selesai = trim($this->input->get('tanggal_selesai', TRUE));

		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_mulai)) {
			$tanggal_mulai = '';
		}

		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_selesai)) {
			$tanggal_selesai = '';
		}

		// QUERY REKAP
		$this->db->select('
        p.anggota_id,
        s.nama,
        COUNT(p.id_pinjam) AS total
    ');

		$this->db->from('tbl_pinjam p');
		$this->db->join(
			'tbl_siswa s',
			's.kode_anggota = p.anggota_id',
			'left'
		);

		if ($pinjam_id !== '') {
			$this->db->like('p.pinjam_id', $pinjam_id);
		}

		if ($anggota_id !== '') {
			$this->db->like('p.anggota_id', $anggota_id);
		}

		if ($nama !== '') {
			$this->db->like('s.nama', $nama);
		}

		if ($kode !== '') {
			$this->db->like('p.kode', $kode);
		}

		if ($status !== '') {
			$this->db->where('p.status', $status);
		}

		if ($tanggal_mulai !== '') {
			$this->db->where('p.tgl_pinjam >=', $tanggal_mulai);
		}

		if ($tanggal_selesai !== '') {
			$this->db->where(
				'p.tgl_pinjam <',
				date('Y-m-d', strtotime($tanggal_selesai . ' +1 day'))
			);
		}

		$this->db->group_by([
			'p.anggota_id',
			's.nama'
		]);

		$this->db->order_by('total', 'DESC');

		$data = $this->db->get()->result_array();

		// PDF
		$document = new PDF([
			'mode' => 'utf-8',
			'format' => 'A4',
			'margin_header' => 3,
			'margin_top' => 10,
			'margin_bottom' => 10,
			'margin_footer' => 2,
			'default_font_size' => 12,
			'default_font' => 'sans-serif'
		]);

		$document->SetTitle('DATA TOTAL PEMINJAMAN BUKU');

		$stylesheet = file_get_contents('cetak.css');
		$document->WriteHTML(
			$stylesheet,
			\Mpdf\HTMLParserMode::HEADER_CSS
		);

		$document->WriteHTML("
        <h4 class='text-center'>
            DATA TOTAL PEMINJAMAN BUKU
            PERPUSTAKAAN SDN GETASKEREP 01
        </h4>
    ");

		// FILTER
		$filter = '';

		$filters = [
			'Nomor Pinjam' => $pinjam_id,
			'Kode Anggota' => $anggota_id,
			'Nama Anggota' => $nama,
			'Kode Buku' => $kode,
			'Status' => $status,
			'Tanggal Mulai' => $tanggal_mulai,
			'Tanggal Selesai' => $tanggal_selesai
		];

		foreach ($filters as $label => $value) {
			if ($value !== '') {
				$filter .= '<b>' . $label . ':</b> '
					. html_escape($value) . ' &nbsp; ';
			}
		}

		if ($filter !== '') {
			$document->WriteHTML(
				"<p style='font-size:10px;'>Filter: {$filter}</p>"
			);
		}

		// TABEL
		$html = "
        <table class='table-border' width='100%'>
            <thead>
                <tr>
                    <th class='table-border' width='10%'>No.</th>
                    <th class='table-border' width='25%'>Kode Anggota</th>
                    <th class='table-border' width='50%'>Nama</th>
                    <th class='table-border' width='15%'>Total Buku</th>
                </tr>
            </thead>
            <tbody>
    ";

		$no = 1;
		$totalSeluruh = 0;

		foreach ($data as $isi) {

			$jumlah = (int) $isi['total'];
			$totalSeluruh += $jumlah;

			$namaAnggota = !empty($isi['nama'])
				? html_escape($isi['nama'])
				: '-';

			$html .= "
            <tr>
                <td class='table-border'>{$no}</td>
                <td class='table-border'>" . html_escape($isi['anggota_id']) . "</td>
                <td class='table-border'>{$namaAnggota}</td>
                <td class='table-border' align='center'>{$jumlah}</td>
            </tr>
        ";

			$no++;
		}

		if (empty($data)) {
			$html .= "
            <tr>
                <td class='table-border' colspan='4' align='center'>
                    Data peminjaman tidak ditemukan.
                </td>
            </tr>
        ";
		}

		$html .= "</tbody></table>";

		$document->WriteHTML($html);

		$document->WriteHTML("
        <p style='margin-top:10px;font-size:11px;'>
            <b>Total Anggota: " . count($data) . "</b><br>
            <b>Total Seluruh Buku Dipinjam: {$totalSeluruh}</b>
        </p>
    ");

		$document->Output('Data Total Pinjam Buku.pdf', 'I');
	}

	public function pinjam_alat()
	{
		$pinjam_id = trim($this->input->get('pinjam_id', TRUE));
		$anggota_id = trim($this->input->get('anggota_id', TRUE));
		$nama = trim($this->input->get('nama', TRUE));
		$kode = trim($this->input->get('kode', TRUE));
		$status = trim($this->input->get('status', TRUE));
		$tanggal_mulai = trim($this->input->get('tanggal_mulai', TRUE));
		$tanggal_selesai = trim($this->input->get('tanggal_selesai', TRUE));

		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_mulai)) {
			$tanggal_mulai = '';
		}

		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_selesai)) {
			$tanggal_selesai = '';
		}

		// QUERY
		$this->db->select('
        p.pinjam_id,
        p.anggota_id,
        p.kode,
        p.status,
        p.tgl_pinjam,
        p.lama_pinjam,
        p.tgl_balik,
        p.tgl_kembali,
        s.nama
    ');

		$this->db->from('tbl_pinjam_alat p');
		$this->db->join(
			'tbl_siswa s',
			's.kode_anggota = p.anggota_id',
			'left'
		);

		if ($pinjam_id !== '') {
			$this->db->like('p.pinjam_id', $pinjam_id);
		}

		if ($anggota_id !== '') {
			$this->db->like('p.anggota_id', $anggota_id);
		}

		if ($nama !== '') {
			$this->db->like('s.nama', $nama);
		}

		if ($kode !== '') {
			$this->db->like('p.kode', $kode);
		}

		if ($status !== '') {
			$this->db->where('p.status', $status);
		}

		if ($tanggal_mulai !== '') {
			$this->db->where('p.tgl_pinjam >=', $tanggal_mulai);
		}

		if ($tanggal_selesai !== '') {
			$this->db->where(
				'p.tgl_pinjam <',
				date('Y-m-d', strtotime($tanggal_selesai . ' +1 day'))
			);
		}

		$this->db->order_by('p.id_pinjam', 'DESC');

		$data = $this->db->get()->result_array();

		// PDF
		$document = new PDF([
			'mode' => 'utf-8',
			'format' => 'A4-L',
			'margin_header' => 3,
			'margin_top' => 10,
			'margin_bottom' => 10,
			'margin_footer' => 2,
			'default_font_size' => 12,
			'default_font' => 'sans-serif'
		]);

		$document->SetTitle('DATA PEMINJAMAN ALAT PERAGA');

		$stylesheet = file_get_contents('cetak.css');
		$document->WriteHTML(
			$stylesheet,
			\Mpdf\HTMLParserMode::HEADER_CSS
		);

		$document->WriteHTML("
        <h4 class='text-center'>
            DATA PEMINJAMAN ALAT PERAGA
            PERPUSTAKAAN SDN GETASKEREP 01
        </h4>
    ");

		// TABEL
		$html = "
        <table class='table-border' width='100%'>
            <thead>
                <tr>
                    <th class='table-border'>No.</th>
                    <th class='table-border'>No. Pinjam</th>
                    <th class='table-border'>Kode Anggota</th>
                    <th class='table-border'>Nama</th>
                    <th class='table-border'>Kode Alat</th>
                    <th class='table-border'>Pinjam</th>
                    <th class='table-border'>Balik</th>
                    <th class='table-border'>Status</th>
                    <th class='table-border'>Kembali</th>
                </tr>
            </thead>
            <tbody>
    ";

		$no = 1;

		foreach ($data as $isi) {

			$namaAnggota = !empty($isi['nama'])
				? html_escape($isi['nama'])
				: '-';

			$tglKembali = ($isi['tgl_kembali'] == '0' || empty($isi['tgl_kembali']))
				? 'Belum dikembalikan'
				: html_escape($isi['tgl_kembali']);

			$html .= "
            <tr>
                <td class='table-border'>{$no}</td>
                <td class='table-border'>" . html_escape($isi['pinjam_id']) . "</td>
                <td class='table-border'>" . html_escape($isi['anggota_id']) . "</td>
                <td class='table-border'>{$namaAnggota}</td>
                <td class='table-border'>" . html_escape($isi['kode']) . "</td>
                <td class='table-border'>" . html_escape($isi['tgl_pinjam']) . "</td>
                <td class='table-border'>" . html_escape($isi['tgl_balik']) . "</td>
                <td class='table-border'>" . html_escape($isi['status']) . "</td>
                <td class='table-border'>{$tglKembali}</td>
            </tr>
        ";

			$no++;
		}

		if (empty($data)) {
			$html .= "
            <tr>
                <td class='table-border' colspan='9' align='center'>
                    Data peminjaman alat peraga tidak ditemukan.
                </td>
            </tr>
        ";
		}

		$html .= "</tbody></table>";

		$document->WriteHTML($html);

		$document->WriteHTML("
        <p style='margin-top:10px;font-size:11px;'>
            <b>Total Data Peminjaman Alat: " . count($data) . "</b>
        </p>
    ");

		$document->Output('Data Pinjam Alat.pdf', 'I');
	}

	public function pinjam_digital()
	{
		// FILTER
		$anggota_id = trim($this->input->get('anggota_id', TRUE));
		$nama = trim($this->input->get('nama', TRUE));
		$kode = trim($this->input->get('kode', TRUE));
		$title = trim($this->input->get('title', TRUE));
		$tanggal_mulai = trim($this->input->get('tanggal_mulai', TRUE));
		$tanggal_selesai = trim($this->input->get('tanggal_selesai', TRUE));

		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_mulai)) {
			$tanggal_mulai = '';
		}

		if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_selesai)) {
			$tanggal_selesai = '';
		}

		// QUERY
		$this->db->select('
        pd.*,
        s.nama,
        s.kode_anggota,
        b.kode_buku,
        b.title
    ');

		$this->db->from('tbl_pinjam_digital pd');

		$this->db->join(
			'tbl_siswa s',
			's.id_siswa = pd.id_siswa',
			'left'
		);

		$this->db->join(
			'tbl_buku_digital b',
			'b.id_buku = pd.id_buku',
			'left'
		);

		if ($anggota_id !== '') {
			$this->db->like('s.kode_anggota', $anggota_id);
		}

		if ($nama !== '') {
			$this->db->like('s.nama', $nama);
		}

		if ($kode !== '') {
			$this->db->like('b.kode_buku', $kode);
		}

		if ($title !== '') {
			$this->db->like('b.title', $title);
		}

		if ($tanggal_mulai !== '') {
			$this->db->where('pd.tgl_baca >=', $tanggal_mulai);
		}

		if ($tanggal_selesai !== '') {
			$this->db->where(
				'pd.tgl_baca <',
				date('Y-m-d', strtotime($tanggal_selesai . ' +1 day'))
			);
		}

		$this->db->order_by('pd.tgl_baca', 'DESC');

		$data = $this->db->get()->result_array();

		// PDF
		$document = new PDF([
			'mode' => 'utf-8',
			'format' => 'A4-L',
			'margin_header' => 3,
			'margin_top' => 10,
			'margin_bottom' => 10,
			'margin_footer' => 2,
			'default_font_size' => 12,
			'default_font' => 'sans-serif'
		]);

		$document->SetTitle('DATA BACA BUKU DIGITAL');

		$stylesheet = file_get_contents('cetak.css');
		$document->WriteHTML(
			$stylesheet,
			\Mpdf\HTMLParserMode::HEADER_CSS
		);

		$document->WriteHTML("
        <h4 class='text-center'>
            DATA BACA BUKU DIGITAL
            PERPUSTAKAAN SDN GETASKEREP 01
        </h4>
    ");

		// FILTER
		$filter = '';

		$filters = [
			'Kode Anggota' => $anggota_id,
			'Nama Anggota' => $nama,
			'Kode Buku' => $kode,
			'Judul Buku' => $title,
			'Tanggal Mulai' => $tanggal_mulai,
			'Tanggal Selesai' => $tanggal_selesai
		];

		foreach ($filters as $label => $value) {
			if ($value !== '') {
				$filter .= '<b>' . $label . ':</b> '
					. html_escape($value) . ' &nbsp; ';
			}
		}

		if ($filter !== '') {
			$document->WriteHTML(
				"<p style='font-size:10px;'>Filter: {$filter}</p>"
			);
		}

		// TABEL
		$html = "
        <table class='table-border' width='100%'>
            <thead>
                <tr>
                    <th class='table-border' width='5%'>No.</th>
                    <th class='table-border' width='15%'>Kode Anggota</th>
                    <th class='table-border' width='25%'>Nama</th>
                    <th class='table-border' width='15%'>Kode Buku</th>
                    <th class='table-border' width='25%'>Judul</th>
                    <th class='table-border' width='15%'>Tanggal Baca</th>
                </tr>
            </thead>
            <tbody>
    ";

		$no = 1;

		foreach ($data as $isi) {

			$namaAnggota = !empty($isi['nama'])
				? html_escape($isi['nama'])
				: '-';

			$kodeAnggota = !empty($isi['kode_anggota'])
				? html_escape($isi['kode_anggota'])
				: '-';

			$kodeBuku = !empty($isi['kode_buku'])
				? html_escape($isi['kode_buku'])
				: '-';

			$judulBuku = !empty($isi['title'])
				? html_escape($isi['title'])
				: '-';

			$html .= "
            <tr>
                <td class='table-border'>{$no}</td>
                <td class='table-border'>{$kodeAnggota}</td>
                <td class='table-border'>{$namaAnggota}</td>
                <td class='table-border'>{$kodeBuku}</td>
                <td class='table-border'>{$judulBuku}</td>
                <td class='table-border'>" . html_escape($isi['tgl_baca']) . "</td>
            </tr>
        ";

			$no++;
		}

		if (empty($data)) {
			$html .= "
            <tr>
                <td class='table-border' colspan='6' align='center'>
                    Data baca buku digital tidak ditemukan.
                </td>
            </tr>
        ";
		}

		$html .= "</tbody></table>";

		$document->WriteHTML($html);

		$document->WriteHTML("
        <p style='margin-top:10px;font-size:11px;'>
            <b>Total Aktivitas Membaca: " . count($data) . "</b>
        </p>
    ");

		$document->Output('Data Baca Buku Digital.pdf', 'I');
	}




	public function kembali_alat()
	{
		if ($this->session->userdata('level') != 'Petugas') {
			redirect(base_url());
			return;
		}

		$pinjam_id = $this->input->get('pinjam_id', TRUE);
		$anggota_id = $this->input->get('anggota_id', TRUE);
		$nama = $this->input->get('nama', TRUE);
		$kode = $this->input->get('kode', TRUE);
		$tanggal_mulai = $this->input->get('tanggal_mulai', TRUE);
		$tanggal_selesai = $this->input->get('tanggal_selesai', TRUE);

		$this->db->select('
        tbl_pinjam_alat.pinjam_id,
        tbl_pinjam_alat.anggota_id,
        tbl_pinjam_alat.kode,
        tbl_pinjam_alat.status,
        tbl_pinjam_alat.tgl_pinjam,
        tbl_pinjam_alat.lama_pinjam,
        tbl_pinjam_alat.tgl_balik,
        tbl_pinjam_alat.tgl_kembali,
        tbl_siswa.nama
    ');

		$this->db->from('tbl_pinjam_alat');

		$this->db->join(
			'tbl_siswa',
			'tbl_siswa.kode_anggota = tbl_pinjam_alat.anggota_id',
			'left'
		);

		$this->db->where('tbl_pinjam_alat.status', 'Di Kembalikan');

		if (!empty($pinjam_id)) {
			$this->db->like('tbl_pinjam_alat.pinjam_id', $pinjam_id);
		}

		if (!empty($anggota_id)) {
			$this->db->like('tbl_pinjam_alat.anggota_id', $anggota_id);
		}

		if (!empty($nama)) {
			$this->db->like('tbl_siswa.nama', $nama);
		}

		if (!empty($kode)) {
			$this->db->like('tbl_pinjam_alat.kode', $kode);
		}

		if (
			!empty($tanggal_mulai) &&
			preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_mulai)
		) {
			$this->db->where(
				'tbl_pinjam_alat.tgl_kembali >=',
				$tanggal_mulai
			);
		}

		if (
			!empty($tanggal_selesai) &&
			preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_selesai)
		) {
			$this->db->where(
				'tbl_pinjam_alat.tgl_kembali <',
				date('Y-m-d', strtotime($tanggal_selesai . ' +1 day'))
			);
		}

		$this->db->order_by('tbl_pinjam_alat.id_pinjam', 'DESC');

		$data = $this->db->get()->result_array();

		// Siapkan PDF
		$pdf = new PDF([
			'mode' => 'utf-8',
			'format' => 'A4-L',
			'margin_top' => 10,
			'margin_bottom' => 10,
			'margin_left' => 10,
			'margin_right' => 10
		]);

		$pdf->AddPage('L');

		$stylesheet = file_get_contents('cetak.css');
		$pdf->WriteHTML($stylesheet, \Mpdf\HTMLParserMode::HEADER_CSS);

		$html = '
    <h3 style="text-align:center;">RIWAYAT PENGEMBALIAN ALAT</h3>
    <p style="text-align:center;">Data Pengembalian Alat Perpustakaan</p>

    <table width="100%" border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr style="background-color:#eeeeee;">
                <th width="4%">No</th>
                <th>No Pinjam</th>
                <th>Kode Anggota</th>
                <th>Nama Anggota</th>
                <th>Kode Alat</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Batas Kembali</th>
                <th>Status</th>
                <th>Tanggal Pengembalian</th>
            </tr>
        </thead>
        <tbody>';

		if (count($data) > 0) {
			$no = 1;

			foreach ($data as $row) {
				$html .= '
            <tr>
                <td align="center">' . $no++ . '</td>
                <td>' . html_escape($row['pinjam_id']) . '</td>
                <td>' . html_escape($row['anggota_id']) . '</td>
                <td>' . html_escape($row['nama'] ?? '-') . '</td>
                <td>' . html_escape($row['kode']) . '</td>
                <td>' . html_escape($row['tgl_pinjam']) . '</td>
                <td>' . html_escape($row['tgl_balik']) . '</td>
                <td>' . html_escape($row['status']) . '</td>
                <td>' . html_escape($row['tgl_kembali']) . '</td>
            </tr>';
			}
		} else {
			$html .= '
        <tr>
            <td colspan="9" align="center">
                Tidak ada data pengembalian alat.
            </td>
        </tr>';
		}

		$html .= '
        </tbody>
    </table>

    <br>
    <p style="text-align:right;">
        Dicetak pada: ' . date('d-m-Y H:i:s') . '
    </p>';

		$pdf->WriteHTML($html);

		$pdf->Output('Riwayat_Pengembalian_Alat.pdf', 'I');
	}



}
