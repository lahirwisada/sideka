<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_rkp extends CI_Model {

    private $ci;
    private $_table = 'tbl_rp_rkp';
    public $form_field_names = array(
        'id_rancangan_rpjm_desa',
        'id_bidang',
        'jenis_kegiatan',
        'lokasi',
        'volume',
        'sasaran_manfaat',
        'waktu_pelaksanaan',
        'jumlah_biaya',
        'rencana_pelaksanaan_kegiatan',
        'swakelola',
        'kerjasama_antar_desa',
        'kerjasama_pihak_ketiga',
    );
    public $post_data = array();

    function __construct() {
        parent::__construct();
        $this->ci = get_instance();
    }

    private function _resetPostData() {
        $this->post_data = array();
    }

    public function calculateTahunPelaksanaan($tahun_awal) {
        for ($i = 1; $i <= 6; $i++) {
            $field_tahun_pelaksanaan = 'tahun_pelaksanaan_' . $i;

            if (array_key_exists($field_tahun_pelaksanaan, $this->post_data) && $this->post_data[$field_tahun_pelaksanaan] != '') {
                $this->post_data[$field_tahun_pelaksanaan] = $tahun_awal + ($i - 1);
            } else {
                $this->post_data[$field_tahun_pelaksanaan] = 0;
            }
        }
        unset($tahun_awal);
        return;
    }

    public function reCalculateSubTotal($id_m_rkp = FALSE, $id_bidang = FALSE) {

        if ($id_bidang && $id_m_rkp) {
            $this->db->select("sum(" . $this->_table . ".jumlah_biaya) as sub_total");
            $this->db->where($this->_table . ".id_m_rkp = '" . $id_m_rkp . "' and " . $this->_table . ".id_bidang = '" . $id_bidang . "'");
            $q = $this->db->get($this->_table);

            if ($q) {
                $res = $q->row();
                return $res->sub_total;
            }
        }
        return FALSE;
    }

    public function getPostData($id_m_rkp = FALSE) {
        if (!$id_m_rkp) {
            $this->session->set_flashdata('id_master_not_found', TRUE);
            return FALSE;
        }

        $this->_resetPostData();

        $this->post_data['id_m_rkp'] = $id_m_rkp;

        foreach ($this->form_field_names as $key => $field_name) {
            if ($this->input->post($field_name)) {
                $this->post_data[$field_name] = addslashes($this->input->post($field_name));
            }
        }

//        var_dump($this->post_data);exit;

        return TRUE;
    }

    public function getDetail($id_rkp = FALSE, $returnArray = FALSE) {
        if (!$id_rkp) {
            return FALSE;
        }
        $this->_setSelectAndJoin();
        $query = $this->db->get_where($this->_table, array('id_rkp' => $id_rkp));

        $detail = FALSE;
        if ($returnArray) {
            $detail = $query->row_array();
        } else {
            $detail = $query->row();
        }
        return $detail;
    }

    public function sum_cost_by_id_bidang_and_id_rancangan_rpjm_desa($id_bidang = FALSE, $id_rancangan_rpjm_desa = FALSE) {
        if ($id_bidang && $id_rancangan_rpjm_desa) {
            $select = "select sum(" . $this->_table . ".jumlah_biaya) as total_jumlah_biaya";
            $this->db->select($select);
            $this->db->where(array(
                "id_bidang" => $id_bidang,
                "id_rancangan_rpjm_desa" => $id_rancangan_rpjm_desa
            ));
            if ($q) {
                $rs = $q->row();
                return $rs;
            }
        }
        return FALSE;
    }

    public function save($id_rancangan_rpjm_desa = FALSE) {
        /**
         * Error Number :
         * 0 : tidak ada post data sama sekali
         * 1.1 : Sukses Insert data
         * 1.2 : Sukses Update data
         * 2 : data tidak valid
         */
        $response = array(
            "post_data" => $this->post_data,
            "error_message" => "Tidak ada data yang dikirim.",
            "inserted_id" => $id_rancangan_rpjm_desa,
            "error_number" => "0"
        );

        /**
         * @todo kasih validasi disini
         */
        if (count($this->post_data) > 0) {

            $this->db->trans_off();

            $this->db->trans_begin();
            $this->db->trans_strict(FALSE);

            if ($id_rancangan_rpjm_desa) {
                $response["error_message"] = "Perubahan ";
                $response["error_number"] = "1.2";

                $this->db->where($this->_table . '.id_rancangan_rpjm_desa', $id_rancangan_rpjm_desa);
                $this->db->update($this->_table, $this->post_data);
            } else {
                $response["error_message"] = "Data baru ";
                $response["error_number"] = "1.1";

                $this->insert_data($this->post_data);

                $response["inserted_id"] = $this->db->insert_id();
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {

                $response["error_message"] .= "gagal dilakukan.";
                $response["error_number"] = "0";
                $this->db->trans_rollback();
            } else {
                $response["error_message"] .= "berhasil dilakukan.";
            }
            $this->db->trans_commit();
        }



        return $response;
    }

    public function getFlexigrid($master_id) {
        //Build contents query

        $this->_setSelectAndJoin();

        $this->db->from($this->_table);
        $this->db->where($this->_table . '.id_m_rkp', $master_id);
        $this->ci->flexigrid->build_query(FALSE);

        //Get contents
        $return['records'] = $this->db->get();

        //Build count query
        $this->db->select("count(" . $this->_table . ".id_rancangan_rpjm_desa) as record_count")->from($this->_table);
        $this->db->where($this->_table . '.id_m_rkp', $master_id);
        $this->db->join('ref_rp_bidang', 'ref_rp_bidang.id_bidang = ' . $this->_table . '.id_bidang', 'left');
        $this->ci->flexigrid->build_query(FALSE);
        $record_count = $this->db->get();

        $row = FALSE;
        if ($record_count) {
            $row = $record_count->row();
        }

        //Get Record Count
        $return['record_count'] = $row->record_count;

        //Return all
        return $return;
    }

    public function _setSelectAndJoin() {
        $select = $this->_table . '.id_rkp, ' .
                $this->_table . '.id_rancangan_rpjm_desa, ' .
                $this->_table . '.id_bidang, ' .
                'tbl_rp_m_rkp.rkp_tahun, ' .
                'ref_rp_coa.deskripsi as bidang, ' .
                $this->_table . '.jenis_kegiatan, ' .
                $this->_table . '.lokasi, ' .
                $this->_table . '.volume, ' .
                $this->_table . '.sasaran_manfaat, ' .
                $this->_table . '.waktu_pelaksanaan, ' .
                $this->_table . '.jumlah_biaya, ' .
                $this->_table . '.rencana_pelaksanaan_kegiatan, ' .
                $this->_table . '.swakelola, ' .
                $this->_table . '.kerjasama_antar_desa, ' .
                $this->_table . '.kerjasama_pihak_ketiga ';

        $this->db->select($select);
        $this->db->join('tbl_rp_m_rkp', 'tbl_rp_m_rkp.id_m_rkp = ' . $this->_table . '.id_m_rkp');
        $this->db->join('ref_rp_coa', 'ref_rp_coa.id_coa = ' . $this->_table . '.id_bidang');
    }

    public function insert_data($data = FALSE) {
        if ($data) {
            $this->db->insert($this->_table, $data);
        }
    }

}
