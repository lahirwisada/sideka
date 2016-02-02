<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class M_master_rancangan_rpjm_desa extends CI_Model {

    private $ci;
    private $_table = 'tbl_rp_m_rancangan_rpjm_desa';

    function __construct() {
        parent::__construct();
        $this->ci = get_instance();
    }

    public function getFlexigrid() {
        //Build contents query

        $select = $this->_table.'.id_m_rancangan_rpjm_desa, ' .
        $this->_table.'.tahun_awal, '.
        $this->_table.'.tahun_akhir, '.
        $this->_table.'.tahun_anggaran, '.
        $this->_table.'.nama_file, '.
        $this->_table.'.total_bidang_1, '.
        $this->_table.'.total_bidang_2, '.
        $this->_table.'.total_bidang_3, '.
        $this->_table.'.total_bidang_4, '.
        $this->_table.'.total_keseluruhan, '.
        $this->_table.'.tanggal_disusun, '.
        $this->_table.'.disusun_oleh, '.
        $this->_table.'.kepala_desa, '.
        $this->_table.'.id_desa, '.
        $this->_table.'.id_kecamatan, '.
        $this->_table.'.id_kab_kota, '.
        $this->_table.'.id_provinsi, '.
        'ref_desa.nama_desa, '.
        'ref_kecamatan.nama_kecamatan, '.
        'ref_kab_kota.nama_kab_kota, '.
        'ref_provinsi.nama_provinsi ';

        $this->db->select($select)
                ->from($this->_table);
        
        $this->db->join('ref_desa', 'ref_desa.id_desa = '.$this->_table.'.id_desa');
        $this->db->join('ref_kecamatan', 'ref_kecamatan.id_kecamatan = '.$this->_table.'.id_kecamatan');
        $this->db->join('ref_kab_kota', 'ref_kab_kota.id_kab_kota = '.$this->_table.'.id_kab_kota');
        $this->db->join('ref_provinsi', 'ref_provinsi.id_provinsi = '.$this->_table.'.id_provinsi');
        //$this->db->where('tbl_rp_rpjmd.id_rpjmd !=', 0);
        //$this->db->join('tbl_rp_rpjmd as a1','a1.id_parent_rpjmd = tbl_rp_rpjmd.id_rpjmd', 'left');
        $this->ci->flexigrid->build_query();

        //Get contents
        $return['records'] = $this->db->get();

        //Build count query
        $this->db->select("count(".$this->_table.".id_m_rancangan_rpjm_desa) as record_count")->from($this->_table);

        $this->ci->flexigrid->build_query(FALSE);
        $record_count = $this->db->get();
        $row = $record_count->row();

        //Get Record Count
        $return['record_count'] = $row->record_count;

        //Return all
        return $return;
    }
}