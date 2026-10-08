<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Selfservice extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper(array('url', 'date'));
        $this->load->library('session');
    }


    /**
     * =========================================================
     * HALAMAN SELF SERVICE
     * =========================================================
     */
    public function index()
    {
        $data = array(
            'title_web' => 'Self Service Perpustakaan'
        );

        $this->load->view('selfservice/index', $data);
    }


    /**
     * =========================================================
     * CEK ANGGOTA
     * =========================================================
     *
     * POST:
     * id_anggota
     *
     * Response:
     * {
     *     status: true,
     *     id_siswa: 1,
     *     kode_anggota: "AG001",
     *     nama: "Budi"
     * }
     */
    public function cek_anggota()
    {
        $kode_anggota = trim(
            $this->input->post('id_anggota', true)
        );


        if ($kode_anggota == '') {

            return $this->json_response(array(
                'status' => false,
                'message' => 'ID anggota belum diisi.'
            ));

        }


        $anggota = $this->db
            ->where('kode_anggota', $kode_anggota)
            ->get('tbl_siswa')
            ->row();


        if (!$anggota) {

            return $this->json_response(array(
                'status' => false,
                'message' => 'Anggota tidak ditemukan.'
            ));

        }


        /*
         * Simpan sementara anggota yang sedang
         * melakukan transaksi.
         */

        $this->session->set_userdata(
            'selfservice_anggota',
            $anggota->kode_anggota
        );


        /*
         * Ambil jumlah buku yang sedang dipinjam.
         */

        $jumlah_pinjam = $this->jumlah_pinjaman_aktif(
            $anggota->kode_anggota
        );


        return $this->json_response(array(

            'status' => true,

            'id_siswa' =>
                $anggota->id_siswa,

            'kode_anggota' =>
                $anggota->kode_anggota,

            'nama' =>
                $anggota->nama,

            'jumlah_pinjam' =>
                $jumlah_pinjam

        ));
    }


    /**
     * =========================================================
     * CEK BUKU
     * =========================================================
     *
     * POST:
     * id_anggota
     * kode_buku
     *
     * Fungsi ini menentukan otomatis:
     *
     * jika buku sedang dipinjam anggota
     *      => KEMBALI
     *
     * jika belum
     *      => PINJAM
     */
    public function cek_buku()
    {
        $id_anggota = trim(
            $this->input->post('id_anggota', true)
        );

        $kode_buku = trim(
            $this->input->post('kode_buku', true)
        );


        if ($id_anggota == '') {

            return $this->json_response(array(
                'status' => false,
                'message' => 'ID anggota belum diisi.'
            ));

        }


        if ($kode_buku == '') {

            return $this->json_response(array(
                'status' => false,
                'message' => 'Kode buku belum diisi.'
            ));

        }


        /*
         * Pastikan anggota memang ada.
         */

        $anggota = $this->db
            ->where(
                'kode_anggota',
                $id_anggota
            )
            ->get('tbl_siswa')
            ->row();


        if (!$anggota) {

            return $this->json_response(array(
                'status' => false,
                'message' => 'Anggota tidak ditemukan.'
            ));

        }


        /*
         * Cari buku.
         */

        $buku = $this->db
            ->where(
                'kode_buku',
                $kode_buku
            )
            ->get('tbl_buku')
            ->row();


        if (!$buku) {

            return $this->json_response(array(
                'status' => false,
                'message' => 'Buku tidak ditemukan.'
            ));

        }


        /*
         * Cek apakah buku sedang dipinjam
         * oleh anggota tersebut.
         */

        $pinjaman_anggota = $this->get_pinjaman_aktif(
            $id_anggota,
            $kode_buku
        );


        if ($pinjaman_anggota) {

            /*
             * Buku milik anggota ini.
             * Maka otomatis KEMBALI.
             */

            return $this->json_response(array(

                'status' => true,

                'aksi' => 'kembali',

                'id_siswa' =>
                    $anggota->id_siswa,

                'anggota' =>
                    $anggota->nama,

                'kode_anggota' =>
                    $anggota->kode_anggota,

                'kode' =>
                    $buku->kode_buku,

                'judul' =>
                    $buku->title,

                'tgl_pinjam' =>
                    $pinjaman_anggota->tgl_pinjam,

                'tgl_balik' =>
                    $pinjaman_anggota->tgl_balik,

                'message' =>
                    'Buku ini sedang dipinjam oleh ' .
                    $anggota->nama .
                    '. Buku akan dikembalikan.'

            ));

        }


        /*
         * Cek apakah buku sedang dipinjam
         * oleh orang lain.
         */

        $pinjaman_orang_lain =
            $this->get_pinjaman_buku_aktif(
                $kode_buku
            );


        if ($pinjaman_orang_lain) {

            return $this->json_response(array(

                'status' => false,

                'aksi' => 'ditolak',

                'kode' =>
                    $buku->kode_buku,

                'judul' =>
                    $buku->title,

                'message' =>
                    'Buku sedang dipinjam oleh anggota lain.'

            ));

        }


        /*
         * Buku tersedia.
         * Maka otomatis PINJAM.
         */

        return $this->json_response(array(

            'status' => true,

            'aksi' => 'pinjam',

            'id_siswa' =>
                $anggota->id_siswa,

            'anggota' =>
                $anggota->nama,

            'kode_anggota' =>
                $anggota->kode_anggota,

            'kode' =>
                $buku->kode_buku,

            'judul' =>
                $buku->title,

            'message' =>
                'Buku tersedia dan siap dipinjam.'

        ));
    }


    /**
     * =========================================================
     * PROSES PINJAM / KEMBALI
     * =========================================================
     */
    public function proses()
    {
        $id_anggota = trim(
            $this->input->post('id_anggota', true)
        );

        $kode_buku = trim(
            $this->input->post('kode_buku', true)
        );


        if ($id_anggota == '') {

            return $this->json_response(array(
                'status' => false,
                'message' => 'ID anggota belum diisi.'
            ));

        }


        if ($kode_buku == '') {

            return $this->json_response(array(
                'status' => false,
                'message' => 'Kode buku belum diisi.'
            ));

        }


        /*
         * =====================================================
         * VALIDASI ANGGOTA
         * =====================================================
         */

        $anggota = $this->db
            ->where(
                'kode_anggota',
                $id_anggota
            )
            ->get('tbl_siswa')
            ->row();


        if (!$anggota) {

            return $this->json_response(array(
                'status' => false,
                'message' => 'Anggota tidak ditemukan.'
            ));

        }


        /*
         * =====================================================
         * VALIDASI BUKU
         * =====================================================
         */

        $buku = $this->db
            ->where(
                'kode_buku',
                $kode_buku
            )
            ->get('tbl_buku')
            ->row();


        if (!$buku) {

            return $this->json_response(array(
                'status' => false,
                'message' => 'Buku tidak ditemukan.'
            ));

        }


        /*
         * =====================================================
         * CEK APAKAH BUKU SEDANG DIPINJAM ANGGOTA
         * =====================================================
         */

        $pinjaman_anggota =
            $this->get_pinjaman_aktif(
                $id_anggota,
                $kode_buku
            );


        /*
         * =====================================================
         * JIKA ADA => PENGEMBALIAN
         * =====================================================
         */

        if ($pinjaman_anggota) {

            return $this->proses_kembali(
                $pinjaman_anggota,
                $anggota,
                $buku
            );

        }


        /*
         * =====================================================
         * CEK BUKU DIPINJAM ORANG LAIN
         * =====================================================
         */

        $pinjaman_orang_lain =
            $this->get_pinjaman_buku_aktif(
                $kode_buku
            );


        if ($pinjaman_orang_lain) {

            return $this->json_response(array(

                'status' => false,

                'aksi' => 'ditolak',

                'message' =>
                    'Buku sedang dipinjam oleh anggota lain.'

            ));

        }


        /*
         * =====================================================
         * PINJAM
         * =====================================================
         */

        return $this->proses_pinjam(
            $anggota,
            $buku
        );
    }


    /**
     * =========================================================
     * PROSES PEMINJAMAN
     * =========================================================
     */
    private function proses_pinjam($anggota, $buku)
    {
        /*
         * Lama pinjam.
         *
         * Untuk sementara kita gunakan 7 hari.
         *
         * Nanti angka ini kita ambil dari
         * pengaturan sistem.
         */

        $lama_pinjam = 7;


        $tgl_pinjam =
            date('Y-m-d');


        $tgl_balik =
            date(
                'Y-m-d',
                strtotime(
                    '+' . $lama_pinjam . ' days'
                )
            );


        /*
         * Generate ID transaksi.
         */

        $pinjam_id =
            'PS' .
            date('YmdHis') .
            rand(100, 999);


        /*
         * Cek ulang sebelum INSERT.
         *
         * Ini untuk menghindari double scan.
         */

        $cek =
            $this->get_pinjaman_buku_aktif(
                $buku->kode_buku
            );


        if ($cek) {

            return $this->json_response(array(

                'status' => false,

                'message' =>
                    'Buku baru saja dipinjam oleh anggota lain.'

            ));

        }


        $data = array(

            'pinjam_id' =>
                $pinjam_id,

            'anggota_id' =>
                $anggota->kode_anggota,

            'kode' =>
                $buku->kode_buku,

            'status' =>
                'Dipinjam',

            'tgl_pinjam' =>
                $tgl_pinjam,

            'lama_pinjam' =>
                $lama_pinjam,

            'tgl_balik' =>
                $tgl_balik,

            'tgl_kembali' =>
                null

        );


        /*
         * Gunakan transaction database.
         */

        $this->db->trans_begin();


        $this->db->insert(
            'tbl_pinjam',
            $data
        );


        if (
            $this->db->trans_status() === false
        ) {

            $this->db->trans_rollback();


            return $this->json_response(array(

                'status' => false,

                'message' =>
                    'Gagal menyimpan transaksi peminjaman.'

            ));

        }


        $this->db->trans_commit();


        return $this->json_response(array(

            'status' => true,

            'aksi' => 'pinjam',

            'nama' =>
                $anggota->nama,

            'judul' =>
                $buku->title,

            'kode' =>
                $buku->kode_buku,

            'tgl_pinjam' =>
                $tgl_pinjam,

            'tgl_balik' =>
                $tgl_balik,

            'message' =>
                '<b>' .
                html_escape($buku->title) .
                '</b><br>' .
                'berhasil dipinjam oleh <b>' .
                html_escape($anggota->nama) .
                '</b>.<br><br>' .
                'Harap dikembalikan sebelum ' .
                '<b>' .
                date('d-m-Y', strtotime($tgl_balik)) .
                '</b>.'

        ));
    }


    /**
     * =========================================================
     * PROSES PENGEMBALIAN
     * =========================================================
     */
    private function proses_kembali(
        $pinjaman,
        $anggota,
        $buku
    ) {

        $tgl_kembali =
            date('Y-m-d');


        /*
         * Untuk sementara denda belum dihitung.
         *
         * Nanti kita tambahkan:
         *
         * - harian
         * - mingguan
         * - bulanan
         * - maksimal
         */

        $denda = 0;


        $this->db->trans_begin();


        $data = array(

            'status' =>
                'Dikembalikan',

            'tgl_kembali' =>
                $tgl_kembali

        );


        $this->db
            ->where(
                'id_pinjam',
                $pinjaman->id_pinjam
            )
            ->update(
                'tbl_pinjam',
                $data
            );


        if (
            $this->db->trans_status() === false
        ) {

            $this->db->trans_rollback();


            return $this->json_response(array(

                'status' => false,

                'message' =>
                    'Gagal menyimpan pengembalian.'

            ));

        }


        $this->db->trans_commit();


        /*
         * Hitung apakah terlambat.
         */

        $terlambat =
            0;


        if (
            strtotime($tgl_kembali) >
            strtotime($pinjaman->tgl_balik)
        ) {

            $selisih =
                strtotime($tgl_kembali) -
                strtotime($pinjaman->tgl_balik);


            $terlambat =
                floor(
                    $selisih / 86400
                );

        }


        return $this->json_response(array(

            'status' => true,

            'aksi' => 'kembali',

            'nama' =>
                $anggota->nama,

            'judul' =>
                $buku->title,

            'kode' =>
                $buku->kode_buku,

            'tgl_pinjam' =>
                $pinjaman->tgl_pinjam,

            'tgl_balik' =>
                $pinjaman->tgl_balik,

            'tgl_kembali' =>
                $tgl_kembali,

            'terlambat' =>
                $terlambat,

            'denda' =>
                $denda,

            'message' =>
                '<b>' .
                html_escape($buku->title) .
                '</b><br>' .
                'berhasil dikembalikan oleh <b>' .
                html_escape($anggota->nama) .
                '</b>.'

        ));
    }


    /**
     * =========================================================
     * DAFTAR BUKU YANG SEDANG DIPINJAM
     * =========================================================
     *
     * POST:
     * id_anggota
     */
    public function pinjaman()
    {
        $id_anggota = trim(
            $this->input->post(
                'id_anggota',
                true
            )
        );


        if ($id_anggota == '') {

            return $this->json_response(array(

                'status' => false,

                'message' =>
                    'ID anggota belum diisi.',

                'data' => array()

            ));

        }


        /*
         * Join dengan tbl_buku.
         */

        $this->db
            ->select(
                'tbl_pinjam.id_pinjam,
                 tbl_pinjam.pinjam_id,
                 tbl_pinjam.anggota_id,
                 tbl_pinjam.kode,
                 tbl_pinjam.status,
                 tbl_pinjam.tgl_pinjam,
                 tbl_pinjam.lama_pinjam,
                 tbl_pinjam.tgl_balik,
                 tbl_pinjam.tgl_kembali,
                 tbl_buku.title'
            );


        $this->db
            ->from('tbl_pinjam');


        $this->db
            ->join(
                'tbl_buku',
                'tbl_buku.kode_buku = tbl_pinjam.kode',
                'left'
            );


        $this->db
            ->where(
                'tbl_pinjam.anggota_id',
                $id_anggota
            );


        /*
         * Status aktif.
         *
         * Karena database lama mungkin menggunakan
         * variasi status, kita cek status Dipinjam.
         */

        $this->db
            ->where(
                'tbl_pinjam.status',
                'Dipinjam'
            );


        $this->db
            ->order_by(
                'tbl_pinjam.tgl_pinjam',
                'DESC'
            );


        $query =
            $this->db->get();


        $hasil =
            array();


        foreach (
            $query->result() as $row
        ) {

            $hasil[] = array(

                'id_pinjam' =>
                    $row->id_pinjam,

                'kode' =>
                    $row->kode,

                'judul' =>
                    $row->title,

                'tanggal' =>
                    $this->format_tanggal(
                        $row->tgl_pinjam
                    ),

                'tgl_balik' =>
                    $this->format_tanggal(
                        $row->tgl_balik
                    ),

                'status' =>
                    $row->status

            );

        }


        return $this->json_response(array(

            'status' => true,

            'data' =>
                $hasil,

            'jumlah' =>
                count($hasil)

        ));
    }


    /**
     * =========================================================
     * GET PINJAMAN AKTIF ANGGOTA + BUKU
     * =========================================================
     */
    private function get_pinjaman_aktif(
        $anggota_id,
        $kode_buku
    ) {

        return $this->db
            ->where(
                'anggota_id',
                $anggota_id
            )
            ->where(
                'kode',
                $kode_buku
            )
            ->where(
                'status',
                'Dipinjam'
            )
            ->order_by(
                'id_pinjam',
                'DESC'
            )
            ->get(
                'tbl_pinjam'
            )
            ->row();
    }


    /**
     * =========================================================
     * GET BUKU YANG SEDANG DIPINJAM SIAPA PUN
     * =========================================================
     */
    private function get_pinjaman_buku_aktif(
        $kode_buku
    ) {

        return $this->db
            ->where(
                'kode',
                $kode_buku
            )
            ->where(
                'status',
                'Dipinjam'
            )
            ->order_by(
                'id_pinjam',
                'DESC'
            )
            ->get(
                'tbl_pinjam'
            )
            ->row();
    }


    /**
     * =========================================================
     * JUMLAH PINJAMAN AKTIF
     * =========================================================
     */
    private function jumlah_pinjaman_aktif(
        $anggota_id
    ) {

        return $this->db
            ->where(
                'anggota_id',
                $anggota_id
            )
            ->where(
                'status',
                'Dipinjam'
            )
            ->count_all_results(
                'tbl_pinjam'
            );
    }


    /**
     * =========================================================
     * FORMAT TANGGAL
     * =========================================================
     */
    private function format_tanggal($tanggal)
    {
        if (
            empty($tanggal) ||
            $tanggal == '0000-00-00'
        ) {

            return '-';

        }


        $time =
            strtotime($tanggal);


        if (!$time) {

            return $tanggal;

        }


        return date(
            'd-m-Y',
            $time
        );
    }


    /**
     * =========================================================
     * JSON RESPONSE
     * =========================================================
     */
    private function json_response($data)
    {
        $this->output
            ->set_content_type(
                'application/json'
            )
            ->set_output(
                json_encode(
                    $data,
                    JSON_UNESCAPED_UNICODE
                )
            );
    }
}
