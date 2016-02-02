<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once APPPATH . 'controllers/rencanaPembangunan/c_baseRencanaPembangunan.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class C_rancangan_rpjm_desa extends C_baseRencanaPembangunan {

    function __construct() {
        parent::__construct('Rancangan RPJM Desa', 'v_rencanaPembangunan');

        $this->load->helper('flexigrid_helper');
    }

    public function index() {

        $session['hasil'] = $this->session->userdata('logged_in');
        $role = $session['hasil']->role;


        if ($role != 'Perencana Pembangunan') {
            redirect('c_login', 'refresh');
        }

        $this->lists();
    }

    public function load_data() {
        $this->load->library('flexigrid');
        $valid_fields = array('id_rpjmd');

        $this->flexigrid->validate_post('id_rancangan_rpjm_desa', 'ASC', $valid_fields);
    }

    public function lists() {

        $this->config->load('rp_rancangan_rpjm_desa');
        $r_rpjm_desa_config = $this->config->item('rp_rancangan_rpjm_desa');

        $colModel = $r_rpjm_desa_config['colModel'];
        $buttons = $r_rpjm_desa_config['buttons'];
        $gridParams = $r_rpjm_desa_config['gridParams'];

        $grid_js = build_grid_js('flex1', site_url('rencanaPembangunan/c_rancangan_rpjm_desa/load_data'), $colModel, 'id_rancangan_rpjm_desa', 'asc', $gridParams, $buttons);

        $this->set('js_grid', $grid_js);
        $this->set('deskripsi_title', 'Rencana Pembangunan Jangka Menengah Daerah');
    }

    public function import_excel() {
        $config['upload_path'] = "./uploads/temp_upload_excel/rpjm/";
        $config['allowed_types'] = "xls|xlsx";

        $this->load->library('upload', $config);

//        var_dump($this->upload->validate_upload_path());exit;


        if ($this->session->userdata('logged_in')) {
            $this->load->library('rencanaPembangunan/l_read_rancangan_rpjm_desa');

            $is_upload_ok = $this->l_read_rancangan_rpjm_desa->upload_excel('file_excel');
            $save_content_status = $this->l_read_rancangan_rpjm_desa->save_content_excel();
            
            if($save_content_status){
                redirect('rencanaPembangunan/c_rancangan_rpjm_desa', 'refresh');
            }else{
                
            }
        } else {
            redirect('c_login', 'refresh');
        }
    }

    public function add() {
        
    }

}
