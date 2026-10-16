<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Selfservice extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper(array('url', 'security'));
        $this->load->library('session');

        // Validasi login
        if ($this->session->userdata('masuk_perpus') != TRUE) {
            redirect('login');
        }
    }

    /**
     * Halaman utama Self Service.
     * Tidak menggunakan header/sidebar admin.
     */
    public function index()
    {
        $data['title_web'] = 'Self Service Perpustakaan';

        $this->load->view('selfservice/index', $data);
    }

    /**
     * Mengirim respons JSON.
     */
    private function json($data, $httpCode = 200)
    {
        return $this->output
            ->set_status_header($httpCode)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($data));
    }

    /**
     * Mengambil ID anggota dari request.
     */
    private function inputAnggota()
    {
        return trim((string) $this->input->post('id_anggota', TRUE));
    }

    /**
     * Mencari anggota.
     *
     * Mendukung ID siswa maupun kode anggota.
     */
    private function cariAnggota($id)
    {
        if ($id === '') {
            return NULL;
        }

        return $this->db
            ->group_start()
                ->where('kode_anggota', $id)
                ->or_where('id_siswa', $id)
            ->group_end()
            ->limit(1)
            ->get('tbl_siswa')
            ->row();
    }

    /**
     * Mencari buku berdasarkan kode buku.
     */
    private function cariBuku($kode)
    {
        if ($kode === '') {
            return NULL;
        }

        return $this->db
            ->group_start()
                ->where('kode_buku', $kode)
                ->or_where('buku_id', $kode)
                ->or_where('isbn', $kode)
            ->group_end()
            ->limit(1)
            ->get('tbl_buku')
            ->row();
    }

    /**
     * Mengirimkan informasi anggota.
     * POST: id_anggota
     */
    public function cek_anggota()
    {
        $id = $this->inputAnggota();
        $anggota = $this->cariAnggota($id);

        if (!$anggota) {
            return $this->json(array(
                'status' => false,
                'message' => 'Anggota tidak ditemukan.'
            ));
        }

        return $this->json(array(
            'status' => true,
            'id_anggota' => $anggota->id_siswa,
            'kode_anggota' => $anggota->kode_anggota,
            'nama' => $anggota->nama
        ));
    }

    /**
     * Memeriksa buku dan menentukan tindakan.
     *
     * POST:
     * id_anggota
     * kode_buku
     *
     * aksi = kembali jika buku sedang dipinjam
     * aksi = pinjam jika buku tersedia
     */
    public function cek_buku()
    {
        $id = $this->inputAnggota();
        $kode = trim((string) $this->input->post('kode_buku', TRUE));

        if ($id === '' || $kode === '') {
            return $this->json(array(
                'status' => false,
                'message' => 'ID anggota dan kode buku wajib diisi.'
            ));
        }

        $anggota = $this->cariAnggota($id);

        if (!$anggota) {
            return $this->json(array(
                'status' => false,
                'message' => 'Anggota tidak ditemukan.'
            ));
        }

        $buku = $this->cariBuku($kode);

        if (!$buku) {
            return $this->json(array(
                'status' => false,
                'message' => 'Buku tidak ditemukan.'
            ));
        }

        // Cari transaksi buku yang masih aktif.
        $pinjaman = $this->db
            ->where('kode', $buku->kode_buku)
            ->where('status', 'Dipinjam')
            ->order_by('id_pinjam', 'DESC')
            ->limit(1)
            ->get('tbl_pinjam')
            ->row();

        if ($pinjaman) {
            // Buku sedang dipinjam anggota lain.
            if ((string) $pinjaman->anggota_id !==
                (string) $anggota->id_siswa) {

                return $this->json(array(
                    'status' => false,
                    'message' => 'Buku sedang dipinjam anggota lain.'
                ));
            }

            // Buku dipinjam anggota yang sedang diperiksa.
            return $this->json(array(
                'status' => true,
                'aksi' => 'kembali',
                'judul' => $buku->title,
                'kode_buku' => $buku->kode_buku,
                'message' => 'Buku dapat dikembalikan.'
            ));
        }

        // Buku tidak memiliki transaksi aktif.
        return $this->json(array(
            'status' => true,
            'aksi' => 'pinjam',
            'judul' => $buku->title,
            'kode_buku' => $buku->kode_buku,
            'message' => 'Buku tersedia untuk dipinjam.'
        ));
    }

    /**
     * Menampilkan daftar buku yang sedang dipinjam anggota.
     *
     * POST: id_anggota
     */
    public function pinjaman()
    {
        $id = $this->inputAnggota();
        $anggota = $this->cariAnggota($id);

        if (!$anggota) {
            return $this->json(array(
                'status' => false,
                'message' => 'Anggota tidak ditemukan.',
                'data' => array()
            ));
        }

        $rows = $this->db
            ->select(
                'p.id_pinjam,
                 p.pinjam_id,
                 p.anggota_id,
                 p.kode,
                 p.status,
                 p.tgl_pinjam,
                 p.tgl_balik,
                 b.title AS judul'
            )
            ->from('tbl_pinjam p')
            ->join(
                'tbl_buku b',
                'b.kode_buku = p.kode',
                'left'
            )
            ->where('p.anggota_id', $anggota->id_siswa)
            ->where('p.status', 'Dipinjam')
            ->order_by('p.id_pinjam', 'DESC')
            ->get()
            ->result();

        $data = array();

        foreach ($rows as $row) {
            $terlambat = (
                !empty($row->tgl_balik) &&
                $row->tgl_balik !== '0000-00-00' &&
                $row->tgl_balik < date('Y-m-d')
            );

            $data[] = array(
                'id_pinjam' => $row->id_pinjam,
                'pinjam_id' => $row->pinjam_id,
                'judul' => $row->judul ?: '-',
                'title' => $row->judul ?: '-',
                'kode' => $row->kode,
                'kode_buku' => $row->kode,
                'status' => $row->status,
                'status_kembali' => $terlambat
                    ? 'Terlambat'
                    : 'Dipinjam',
                'tgl_pinjam' => $row->tgl_pinjam,
                'tgl_balik' => $row->tgl_balik,
                'terlambat' => $terlambat
            );
        }

        return $this->json(array(
            'status' => true,
            'nama' => $anggota->nama,
            'data' => $data
        ));
    }

    /**
     * Proses peminjaman atau pengembalian buku.
     *
     * POST:
     * id_anggota
     * kode_buku
     * aksi = pinjam / kembali
     */
    public function proses()
    {
        $id = $this->inputAnggota();
        $kode = trim((string) $this->input->post('kode_buku', TRUE));
        $aksi = trim((string) $this->input->post('aksi', TRUE));

        if ($id === '' || $kode === '') {
            return $this->json(array(
                'status' => false,
                'message' => 'ID anggota dan kode buku wajib diisi.'
            ));
        }

        if (!in_array($aksi, array('pinjam', 'kembali'), TRUE)) {
            return $this->json(array(
                'status' => false,
                'message' => 'Tindakan transaksi tidak valid.'
            ));
        }

        $anggota = $this->cariAnggota($id);

        if (!$anggota) {
            return $this->json(array(
                'status' => false,
                'message' => 'Anggota tidak ditemukan.'
            ));
        }

        $buku = $this->cariBuku($kode);

        if (!$buku) {
            return $this->json(array(
                'status' => false,
                'message' => 'Buku tidak ditemukan.'
            ));
        }

        $this->db->trans_begin();

        // Kunci transaksi aktif buku untuk mengurangi
        // kemungkinan peminjaman bersamaan.
        $pinjaman = $this->db
            ->where('kode', $buku->kode_buku)
            ->where('status', 'Dipinjam')
            ->order_by('id_pinjam', 'DESC')
            ->limit(1)
            ->get('tbl_pinjam')
            ->row();

        if ($aksi === 'pinjam') {
            if ($pinjaman) {
                $this->db->trans_rollback();

                return $this->json(array(
                    'status' => false,
                    'message' => 'Buku sedang dipinjam.'
                ));
            }

            $tanggal = date('Y-m-d');
            $lamaPinjam = 7;
            $tanggalKembali = date(
                'Y-m-d',
                strtotime('+' . $lamaPinjam . ' days', strtotime($tanggal))
            );

            $data = array(
                'pinjam_id' => $this->buatKodePinjam(),
                'anggota_id' => $anggota->id_siswa,
                'kode' => $buku->kode_buku,
                'status' => 'Dipinjam',
                'tgl_pinjam' => $tanggal,
                'lama_pinjam' => $lamaPinjam,
                'tgl_balik' => $tanggalKembali,
                'tgl_kembali' => '0'
            );

            $berhasil = $this->db->insert('tbl_pinjam', $data);

            $pesan = 'Buku ' . $buku->title .
                ' berhasil dipinjam. Batas pengembalian: ' .
                $tanggalKembali . '.';
        } else {
            if (!$pinjaman) {
                $this->db->trans_rollback();

                return $this->json(array(
                    'status' => false,
                    'message' => 'Buku tidak sedang dipinjam.'
                ));
            }

            if ((string) $pinjaman->anggota_id !==
                (string) $anggota->id_siswa) {

                $this->db->trans_rollback();

                return $this->json(array(
                    'status' => false,
                    'message' => 'Buku ini dipinjam anggota lain.'
                ));
            }

            $berhasil = $this->db
                ->where('id_pinjam', $pinjaman->id_pinjam)
                ->where('status', 'Dipinjam')
                ->update('tbl_pinjam', array(
                    'status' => 'Di Kembalikan',
                    'tgl_kembali' => date('Y-m-d')
                ));

            $pesan = 'Buku ' . $buku->title .
                ' berhasil dikembalikan.';
        }

        if (!$berhasil || $this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();

            return $this->json(array(
                'status' => false,
                'message' => 'Transaksi gagal disimpan.'
            ));
        }

        $this->db->trans_commit();

        return $this->json(array(
            'status' => true,
            'aksi' => $aksi,
            'judul' => $buku->title,
            'message' => $pesan
        ));
    }

    /**
     * Membuat nomor transaksi pinjam.
     */
    private function buatKodePinjam()
    {
        $this->db
            ->select('pinjam_id')
            ->from('tbl_pinjam')
            ->like('pinjam_id', 'PJ', 'after')
            ->order_by('id_pinjam', 'DESC')
            ->limit(1);

        $row = $this->db->get()->row();

        $nomor = 1;

        if ($row && preg_match('/^PJ(\d+)$/', $row->pinjam_id, $match)) {
            $nomor = ((int) $match[1]) + 1;
        }

        do {
            $kode = 'PJ' . str_pad($nomor, 6, '0', STR_PAD_LEFT);

            $ada = $this->db
                ->where('pinjam_id', $kode)
                ->count_all_results('tbl_pinjam') > 0;

            if ($ada) {
                $nomor++;
            }
        } while ($ada);

        return $kode;
    }
}