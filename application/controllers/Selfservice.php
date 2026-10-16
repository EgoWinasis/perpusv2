<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Selfservice extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->model('M_Admin');
        $this->load->helper(array('url', 'form'));
        $this->load->library('form_validation');
    }

    /**
     * HALAMAN UTAMA SELF SERVICE
     * Tidak memerlukan login.
     */
    public function index()
    {
        $data['title_web'] = 'Self Service Perpustakaan';

        $this->load->view('selfservice/index', $data);
    }

    /**
     * OUTPUT JSON
     */
    private function json_response($data, $http_code = 200)
    {
        return $this->output
            ->set_status_header($http_code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode(
                $data,
                JSON_UNESCAPED_UNICODE
            ));
    }

    /**
     * CEK ANGGOTA
     *
     * Input POST:
     * id_anggota = kode_anggota
     */
    public function cek_anggota()
    {
        $id = trim((string) $this->input->post('id_anggota'));

        if ($id === '') {
            return $this->json_response(array(
                'status'  => false,
                'message' => 'ID anggota wajib diisi.'
            ));
        }

        $anggota = $this->db
            ->where('kode_anggota', $id)
            ->get('tbl_siswa')
            ->row();

        if (!$anggota) {
            return $this->json_response(array(
                'status'  => false,
                'message' => 'Anggota tidak ditemukan.'
            ));
        }

        return $this->json_response(array(
            'status'       => true,
            'message'      => 'Anggota ditemukan.',
            'id_anggota'   => $anggota->kode_anggota,
            'nama'         => $anggota->nama,
            'kode_anggota' => $anggota->kode_anggota
        ));
    }

    /**
     * CEK BUKU
     *
     * Input POST:
     * id_anggota
     * kode_buku
     *
     * Mengembalikan aksi:
     * pinjam  = buku tersedia
     * kembali = buku dipinjam anggota tersebut
     */
    public function cek_buku()
    {
        $id_anggota = trim(
            (string) $this->input->post('id_anggota')
        );

        $kode_buku = trim(
            (string) $this->input->post('kode_buku')
        );

        if ($id_anggota === '' || $kode_buku === '') {
            return $this->json_response(array(
                'status'  => false,
                'message' => 'ID anggota dan kode buku wajib diisi.'
            ));
        }

        // Validasi anggota.
        $anggota = $this->db
            ->where('kode_anggota', $id_anggota)
            ->get('tbl_siswa')
            ->row();

        if (!$anggota) {
            return $this->json_response(array(
                'status'  => false,
                'message' => 'Anggota tidak ditemukan.'
            ));
        }

        // Cari buku menggunakan kode_buku.
        $buku = $this->db
            ->where('kode_buku', $kode_buku)
            ->get('tbl_buku')
            ->row();

        if (!$buku) {
            return $this->json_response(array(
                'status'  => false,
                'message' => 'Kode buku tidak ditemukan.'
            ));
        }

        // Cek apakah buku dipinjam anggota yang sama.
        $pinjaman_anggota = $this->db
            ->where('kode', $kode_buku)
            ->where('anggota_id', $id_anggota)
            ->where('status', 'Dipinjam')
            ->get('tbl_pinjam')
            ->row();

        if ($pinjaman_anggota) {
            return $this->json_response(array(
                'status'  => true,
                'message' => 'Buku sedang dipinjam oleh anggota ini.',
                'judul'   => $buku->title,
                'title'   => $buku->title,
                'kode'    => $buku->kode_buku,
                'aksi'    => 'kembali'
            ));
        }

        // Cek apakah buku dipinjam anggota lain.
        $pinjaman_lain = $this->db
            ->where('kode', $kode_buku)
            ->where('status', 'Dipinjam')
            ->get('tbl_pinjam')
            ->num_rows();

        if ($pinjaman_lain > 0) {
            return $this->json_response(array(
                'status'  => false,
                'message' => 'Buku sedang dipinjam anggota lain.'
            ));
        }

        return $this->json_response(array(
            'status'  => true,
            'message' => 'Buku tersedia dan dapat dipinjam.',
            'judul'   => $buku->title,
            'title'   => $buku->title,
            'kode'    => $buku->kode_buku,
            'aksi'    => 'pinjam'
        ));
    }

    /**
     * DAFTAR BUKU YANG SEDANG DIPINJAM ANGGOTA
     *
     * Input POST:
     * id_anggota
     */
    public function pinjaman()
    {
        /*
         * Jika id_anggota dikirim, tampilkan pinjaman aktif anggota tersebut.
         * Jika kosong, tampilkan seluruh pinjaman aktif dari semua anggota.
         */
        $id = trim((string) $this->input->post('id_anggota'));

        $query = $this->db
            ->select(
                'p.id_pinjam,
                 p.pinjam_id,
                 p.anggota_id,
                 p.kode,
                 p.status,
                 p.tgl_pinjam,
                 p.tgl_balik,
                 b.title,
                 s.nama AS nama_anggota'
            )
            ->from('tbl_pinjam p')
            ->join('tbl_buku b', 'b.kode_buku = p.kode', 'left')
            ->join('tbl_siswa s', 's.kode_anggota = p.anggota_id', 'left')
            ->where('p.status', 'Dipinjam');

        // Filter hanya jika halaman meminta pinjaman anggota tertentu.
        if ($id !== '') {
            $anggota = $this->db
                ->where('kode_anggota', $id)
                ->get('tbl_siswa')
                ->row();

            if (!$anggota) {
                return $this->json_response(array(
                    'status'  => false,
                    'message' => 'Anggota tidak ditemukan.',
                    'data'    => array()
                ));
            }

            $query->where('p.anggota_id', $id);
        }

        $rows = $query
            ->order_by('p.id_pinjam', 'DESC')
            ->get()
            ->result();

        $data = array();
        $today = strtotime(date('Y-m-d'));

        foreach ($rows as $row) {
            $deadline = strtotime((string) $row->tgl_balik);
            $terlambat = (
                $deadline !== false &&
                $today > $deadline
            );

            $data[] = array(
                'id_pinjam'      => $row->id_pinjam,
                'pinjam_id'      => $row->pinjam_id,
                'anggota_id'     => $row->anggota_id,
                'id_anggota'     => $row->anggota_id,
                'nama_anggota'   => $row->nama_anggota ?: '-',
                'nama'           => $row->nama_anggota ?: '-',
                'kode'           => $row->kode,
                'kode_buku'      => $row->kode,
                'judul'          => $row->title,
                'title'          => $row->title,
                'tgl_pinjam'     => $row->tgl_pinjam,
                'tgl_balik'      => $row->tgl_balik,
                'status'         => 'Dipinjam',
                'status_kembali' => $terlambat ? 'Terlambat' : 'Dipinjam',
                'terlambat'      => $terlambat
            );
        }

        return $this->json_response(array(
            'status'  => true,
            'message' => $id !== ''
                ? 'Data pinjaman aktif anggota berhasil dimuat.'
                : 'Seluruh data pinjaman aktif berhasil dimuat.',
            'data'    => $data
        ));
    }

    /**
     * PROSES PEMINJAMAN DAN PENGEMBALIAN
     *
     * Input POST:
     * id_anggota
     * kode_buku
     * aksi = pinjam / kembali
     */
    public function proses()
    {
        $id_anggota = trim(
            (string) $this->input->post('id_anggota')
        );

        $kode_buku = trim(
            (string) $this->input->post('kode_buku')
        );

        $aksi = trim(
            (string) $this->input->post('aksi')
        );

        if (
            $id_anggota === '' ||
            $kode_buku === '' ||
            !in_array($aksi, array('pinjam', 'kembali'), true)
        ) {
            return $this->json_response(array(
                'status'  => false,
                'message' => 'Data transaksi tidak lengkap atau tidak valid.'
            ));
        }

        $anggota = $this->db
            ->where('kode_anggota', $id_anggota)
            ->get('tbl_siswa')
            ->row();

        if (!$anggota) {
            return $this->json_response(array(
                'status'  => false,
                'message' => 'Anggota tidak ditemukan.'
            ));
        }

        $buku = $this->db
            ->where('kode_buku', $kode_buku)
            ->get('tbl_buku')
            ->row();

        if (!$buku) {
            return $this->json_response(array(
                'status'  => false,
                'message' => 'Buku tidak ditemukan.'
            ));
        }

        /*
         * PEMINJAMAN
         */
        if ($aksi === 'pinjam') {
            $sedang_dipinjam = $this->db
                ->where('kode', $kode_buku)
                ->where('status', 'Dipinjam')
                ->count_all_results('tbl_pinjam');

            if ($sedang_dipinjam > 0) {
                return $this->json_response(array(
                    'status'  => false,
                    'message' => 'Buku sedang dipinjam dan belum tersedia.'
                ));
            }

            // Lama pinjam default 7 hari.
            $lama_pinjam = 7;
            $tgl_pinjam = date('Y-m-d');
            $tgl_balik = date(
                'Y-m-d',
                strtotime('+' . $lama_pinjam . ' days', strtotime($tgl_pinjam))
            );

            // Buat nomor transaksi.
            $pinjam_id = $this->M_Admin->buat_kode_pinjam(
                'tbl_pinjam',
                'PJ',
                'id_pinjam',
                'ORDER BY id_pinjam DESC LIMIT 1'
            );

            $data_pinjam = array(
                'pinjam_id'   => $pinjam_id,
                'anggota_id'  => $id_anggota,
                'kode'        => $kode_buku,
                'status'      => 'Dipinjam',
                'tgl_pinjam'  => $tgl_pinjam,
                'lama_pinjam' => $lama_pinjam,
                'tgl_balik'   => $tgl_balik,
                'tgl_kembali' => '0000-00-00'
            );

            $insert = $this->db->insert(
                'tbl_pinjam',
                $data_pinjam
            );

            if (!$insert) {
                return $this->json_response(array(
                    'status'  => false,
                    'message' => 'Gagal menyimpan transaksi peminjaman.'
                ));
            }

            return $this->json_response(array(
                'status'  => true,
                'aksi'    => 'pinjam',
                'message' => 'Buku "' . $buku->title . '" berhasil dipinjam. Batas pengembalian: ' . $tgl_balik . '.'
            ));
        }

        /*
         * PENGEMBALIAN
         */
        $pinjaman = $this->db
            ->where('kode', $kode_buku)
            ->where('anggota_id', $id_anggota)
            ->where('status', 'Dipinjam')
            ->order_by('id_pinjam', 'DESC')
            ->get('tbl_pinjam')
            ->row();

        if (!$pinjaman) {
            return $this->json_response(array(
                'status'  => false,
                'message' => 'Buku ini tidak tercatat sebagai pinjaman aktif anggota.'
            ));
        }

        $tgl_kembali = date('Y-m-d');

        $this->db->trans_begin();

        // Perbarui transaksi pinjam.
        $this->db
            ->where('id_pinjam', $pinjaman->id_pinjam)
            ->update('tbl_pinjam', array(
                'status'      => 'Di Kembalikan',
                'tgl_kembali' => $tgl_kembali
            ));

        // Hitung denda berdasarkan tanggal keterlambatan.
        $jatuh_tempo = strtotime($pinjaman->tgl_balik);
        $tanggal_kembali = strtotime($tgl_kembali);

        $hari_terlambat = 0;

        if (
            $jatuh_tempo !== false &&
            $tanggal_kembali !== false &&
            $tanggal_kembali > $jatuh_tempo
        ) {
            $hari_terlambat = (int) floor(
                ($tanggal_kembali - $jatuh_tempo) / 86400
            );
        }

        $harga_denda = 0;

        if ($hari_terlambat > 0) {
            $aturan_denda = $this->db
                ->where('stat', 'Aktif')
                ->order_by('id_biaya_denda', 'DESC')
                ->get('tbl_biaya_denda')
                ->row();

            if ($aturan_denda) {
                $harga_per_hari = (float) $aturan_denda->harga_denda;

                $maksimal = isset($aturan_denda->maksimal_perhitungan)
                    ? (int) $aturan_denda->maksimal_perhitungan
                    : 0;

                $hari_dihitung = $hari_terlambat;

                if ($maksimal > 0) {
                    $hari_dihitung = min(
                        $hari_terlambat,
                        $maksimal
                    );
                }

                // Berlaku jika jenis denda dihitung per hari.
                $jenis = isset($aturan_denda->jenis_denda)
                    ? strtolower(trim($aturan_denda->jenis_denda))
                    : '';

                if (
                    strpos($jenis, 'hari') !== false ||
                    strpos($jenis, 'terlambat') !== false ||
                    $jenis === ''
                ) {
                    $harga_denda = $harga_per_hari * $hari_dihitung;
                } else {
                    // Untuk jenis denda lain, jangan mengarang
                    // aturan perhitungannya.
                    $harga_denda = 0;
                }
            }
        }

        // Simpan catatan denda jika tabel tersedia.
        $data_denda = array(
            'pinjam_id'  => $pinjaman->pinjam_id,
            'denda'      => $harga_denda,
            'lama_waktu' => $hari_terlambat,
            'tgl_denda'  => $tgl_kembali
        );

        $this->db->insert('tbl_denda', $data_denda);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();

            return $this->json_response(array(
                'status'  => false,
                'message' => 'Gagal memproses pengembalian buku.'
            ));
        }

        $this->db->trans_commit();

        $pesan = 'Buku "' . $buku->title . '" berhasil dikembalikan.';

        if ($hari_terlambat > 0) {
            $pesan .= ' Terlambat ' . $hari_terlambat . ' hari. Denda: Rp ' .
                number_format($harga_denda, 0, ',', '.') . '.';
        }

        return $this->json_response(array(
            'status'        => true,
            'aksi'          => 'kembali',
            'message'       => $pesan,
            'hari_terlambat'=> $hari_terlambat,
            'denda'         => $harga_denda
        ));
    }

}
