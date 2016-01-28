<?php


include_once APPPATH.'controllers/rencanaPembangunan/c_baseRencanaPembangunan.php';

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class C_rancangan_rpjm_desa extends C_baseRencanaPembangunan {
    function __construct() {
        parent::__construct('Rancangan RPJM Desa', 'v_rencanaPembangunan');
        
        $this->load->helper('flexigrid_helper');
    }
    
    public function index(){
        
        $session['hasil'] = $this->session->userdata('logged_in');
        $role = $session['hasil']->role;
        
        
        if($role != 'Perencana Pembangunan'){
            redirect('c_login', 'refresh');
        }
        
        $this->lists();
    }
    
    public function lists(){
        $colModel['id_rpjmd'] = array('ID', 30, TRUE, 'center', 0);
        $colModel['program'] = array('Program Kegiatan', 220, TRUE, 'left', 2);
        $colModel['kondisi_awal'] = array('Kondisi Awal', 220, TRUE, 'left', 2);
        $colModel['target'] = array('Target', 220, TRUE, 'left', 2);
        $colModel['id_tahun_anggaran'] = array('Tahun Anggaran', 120, TRUE, 'center', 2);
        $colModel['aksi'] = array('AKSI', 120, FALSE, 'center', 0);

        //Populate flexigrid buttons..
        $buttons[] = array('Select All', 'check', 'btn');
        $buttons[] = array('separator');
        $buttons[] = array('DeSelect All', 'uncheck', 'btn');
        $buttons[] = array('separator');
        $buttons[] = array('Add', 'add', 'btn');
        $buttons[] = array('separator');
        $buttons[] = array('Delete Selected Items', 'delete', 'btn');
        $buttons[] = array('separator');

        $gridParams = array(
            'height' => 300,
            'rp' => 10,
            'rpOptions' => '[10,20,30,40]',
            'pagestat' => 'Displaying: {from} to {to} of {total} items.',
            'blockOpacity' => 0.5,
            'title' => '',
            'showTableToggleBtn' => false
        );

        $grid_js = build_grid_js('flex1', site_url('rencanaPembangunan/c_rpjmd/load_data'), $colModel, 'id_rpjmd', 'asc', $gridParams, $buttons);

        $this->set('js_grid', $grid_js);
        $this->set('deskripsi_title', 'Rencana Pembangunan Jangka Menengah Daerah');
    }
}