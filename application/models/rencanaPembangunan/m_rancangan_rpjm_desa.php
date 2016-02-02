<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_rancangan_rpjm_desa extends CI_Model {

    private $ci;
    private $_table = 'tbl_rp_rancangan_rpjm_desa';

    function __construct() {
        parent::__construct();
        $this->ci = get_instance();
    }

    public function getFlexigrid($master_id) {
        //Build contents query

        $select = $this->_table.'.id_rancangan_rpjm_desa, ' .
        $this->_table.'.bidang, '.
        $this->_table.'.sub_bidang, '.
        $this->_table.'.jenis_kegiatan, '.
        $this->_table.'.lokasi_rt_rw, '.
        $this->_table.'.prakiraan_volume, '.
        $this->_table.'.sasaran_manfaat, '.
        $this->_table.'.tahun_pelaksanaan_1, '.
        $this->_table.'.tahun_pelaksanaan_2, '.
        $this->_table.'.tahun_pelaksanaan_3, '.
        $this->_table.'.tahun_pelaksanaan_4, '.
        $this->_table.'.tahun_pelaksanaan_5, '.
        $this->_table.'.tahun_pelaksanaan_6, '.
        $this->_table.'.jumlah_biaya, '.
        $this->_table.'.sumber_dana, '.
        $this->_table.'.swakelola, '.
        $this->_table.'.kerjasama_antar_desa, '.
        $this->_table.'.kerjasama_pihak_ketiga ';

        $this->db->select($select)
                ->from($this->_table);
        $this->db->where($this->_table.'.id_m_rancangan_rpjm_desa', $master_id);
        //$this->db->join('tbl_rp_rpjmd as a1','a1.id_parent_rpjmd = tbl_rp_rpjmd.id_rpjmd', 'left');
        $this->ci->flexigrid->build_query(FALSE);

        //Get contents
        $return['records'] = $this->db->get();

        //Build count query
        $this->db->select("count(".$this->_table.".id_rancangan_rpjm_desa) as record_count")->from($this->_table);
        $this->db->where($this->_table.'.id_m_rancangan_rpjm_desa', $master_id);

        $this->ci->flexigrid->build_query(FALSE);
        $record_count = $this->db->get();
        $row = $record_count->row();

        //Get Record Count
        $return['record_count'] = $row->record_count;

        //Return all
        return $return;
    }
    
    public function insert_data($data = FALSE){
        if($datas){
            $this->db->insert($this->_table, $data);
        }
    }

}
