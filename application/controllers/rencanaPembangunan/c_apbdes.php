<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once APPPATH . 'controllers/rencanaPembangunan/c_baseRencanaPembangunan.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class C_apbdes extends C_baseRencanaPembangunan {

    function __construct() {
        parent::__construct('APBDesa', 'v_rencanaPembangunan');
        $this->load->helper(array('flexigrid_helper', 'common_helper'));
        $this->config->load('rp_apb_desa');
        $this->load->model(array(
            'rencanaPembangunan/m_rancangan_rpjm_desa',
            'rencanaPembangunan/m_master_rkp',
            'rencanaPembangunan/m_apbdes',
            'rencanaPembangunan/m_master_apbdes',
            'rencanaPembangunan/m_rkp',
            'rencanaPembangunan/m_master_rancangan_rpjm_desa'));
    }

    function index() {
        $session['hasil'] = $this->session->userdata('logged_in');
        $role = $session['hasil']->role;

        if ($role != 'Perencana Pembangunan') {
            redirect('c_login', 'refresh');
        }

        $r_m_apb_desa_config = $this->config->item('rp_master_apb_desa');

        $colModelM = $r_m_apb_desa_config['colModel'];
        $buttons = $r_m_apb_desa_config['buttons'];
        $gridParams = $r_m_apb_desa_config['gridParams'];

        $grid_js = build_grid_js('flex1', site_url('rencanaPembangunan/c_apbdes/load_data_master'), $colModelM, 'id_m_apbdes', 'desc', $gridParams, $buttons);

        $attention_message = $this->session->flashdata('attention_message');
        $this->set('attention_message', $attention_message);
        $this->set('js_grid', $grid_js);
        $this->set('deskripsi_title', '');
    }

    public function detail($id) {

        $session['hasil'] = $this->session->userdata('logged_in');
        $role = $session['hasil']->role;

        if ($role != 'Perencana Pembangunan') {
            redirect('c_login', 'refresh');
        }

        $r_m_rpjm_desa_config = $this->config->item('rp_apb_desa');

        $colModelM = $r_m_rpjm_desa_config['colModel'];
        $buttons = $r_m_rpjm_desa_config['buttons'];
        $gridParams = $r_m_rpjm_desa_config['gridParams'];

        $grid_js = build_grid_js('flex1', site_url('rencanaPembangunan/c_apbdes/load_data_detail/' . $id), $colModelM, 'id_coa', 'asc', $gridParams, $buttons);

        $attention_message = $this->session->flashdata('attention_message');
        $this->set('attention_message', $attention_message);
        $this->set('js_grid', $grid_js);
        $this->set('deskripsi_title', 'Detail RKP');
    }

    public function load_data_detail($id_m_apbdes = FALSE) {
        $this->load->library('flexigrid');
        $valid_fields = array('id_apbdes');

        $this->flexigrid->validate_post('id_apbdes', 'ASC', $valid_fields);
        $records = $this->m_apbdes->getFlexigrid($id_m_apbdes);

        $this->output->set_header($this->config->item('json_header'));

        $record_items = array();

        if ($records['records']) {
            foreach ($records['records']->result() as $row) {
                $record_items[] = array(
                    $row->id_apbdes,
                    $row->id_apbdes,
                    $row->id_m_apbdes,
                    $row->id_coa,
                    $row->kode_rekening,
                    $row->deskripsi,
                    rupiah_display($row->anggaran),
                    $row->keterangan,
                    '<a  title="Ubah Data" href="' . base_url() . 'rencanaPembangunan/c_apbdes/add_detail/' . $id_m_apbdes . '/' . $row->id_apbdes . '" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>&nbsp;'
                );
            }
        }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items));
    }

    public function load_data_master() {
        $this->load->library('flexigrid');
        $valid_fields = array('id_m_apbdes');

        $this->flexigrid->validate_post('id_m_apbdes', 'ASC', $valid_fields);
        $records = $this->m_master_apbdes->getFlexigrid();

        $this->output->set_header($this->config->item('json_header'));

        $record_items = array();

        foreach ($records['records']->result() as $row) {
            $record_items[] = array(
                $row->id_m_apbdes,
                $row->id_m_apbdes,
                $row->id_m_rkp,
                $row->rkp_tahun,
                rupiah_display($row->total_pendapatan),
                rupiah_display($row->total_belanja),
                rupiah_display($row->total_pembiayaan),
                $row->tanggal_disetujui,
                $row->disetujui_oleh,
                '<button id="anchor_detail_' . $row->id_m_apbdes . '" type="button" class="btn btn-primary btn-xs btn_add_detail" onclick="add_detail(this);" title="Tambah Detail APB Desa" />' .
                '<i class="fa fa-plus"></i>' .
                '</button>&nbsp;' .
                '<button type="submit" class="btn btn-info btn-xs" title="Tampil Detil APB Desa" onclick="show_detail_program(\'' . $row->id_m_apbdes . '\')"/>' .
                '<i class="fa fa-list-alt"></i>' .
                '</button>&nbsp;' .
                '<a  title="Ubah Data" href="' . base_url() . 'rencanaPembangunan/c_apbdes/add/' . $row->id_m_apbdes . '" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i></a>&nbsp;'
                    //('<a  title="Download Excel" href="#" onclick="download_excel(\'' . $row->id_m_apbdesa . '\')" class="btn btn-success btn-xs"><i class="fa fa-file-excel-o"></i></a>')
            );
        }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items));
    }

    function add($id_m_apbdes = FALSE) {
        $master_rkp = $this->m_master_rkp->getArray(2016, 2089, array());
//        $master_rkp = $this->m_master_rkp->getArray(date('Y') - 2, FALSE, array());

        $post_data = array();
        $attention_message = "";
        if (count($_POST) > 0) {
            $this->m_master_apbdes->getPostData();
            $response = $this->m_master_apbdes->save($id_m_apbdes);
            $attention_message = $response["message_error"];
            if ($response["error_number"] != '0' && $id_m_apbdes) {
                redirect('rencanaPembangunan/c_apbdes');
            } elseif ($response["error_number"] != '0' && !$id_m_apbdes) {
                redirect('rencanaPembangunan/c_apbdes/add_detail');
            }
            $post_data = $response["post_data"];
        } elseif (count($_POST) == 0 && $id_m_apbdes) {
            $post_data = $this->m_master_apbdes->getDetail($id_m_apbdes, TRUE);

            if (!$post_data || empty($post_data)) {
                $this->session->set_flashdata('attention_message', 'Maaf, Data tidak ditemukan.');
                redirect('rencanaPembangunan/c_apbdes', 'refresh');
            }
        }

        $this->set('post_data', $post_data);
        $this->set('attention_message', $attention_message);
        $this->set('id_m_apbdes', $id_m_apbdes);

        $this->set('js_general_helper', $this->load->view('rencanaPembangunan/rancangan_rpjm_desa/js/general_helper', array(), TRUE));
        $this->set('master_rkp', $master_rkp);
    }

    function get_cost() {
        $id_bidang = $this->input->post('id_bidang');
        $id_rancangan_rpjm_desa = $this->input->post('id_rancangan_rpjm_desa');
//var_dump($id_bidang, $id_rancangan_rpjm_desa);exit;
        $rs = $this->m_rkp->sum_cost_by_id_bidang_and_id_rancangan_rpjm_desa($id_bidang, $id_rancangan_rpjm_desa);
        if ($rs) {
            echo $rs->total_jumlah_biaya;
        } else {
            echo '0';
        }
        exit;
    }

    function add_detail($id_m_apbdes = FALSE, $id_apbdes = FALSE) {
        $top_level_coa = $this->m_coa->getTopLevelCoa();
        $post_data = array();
        $attention_message = "";


        if (!$id_m_apbdes || !$this->m_master_apbdes->isIdValid($id_m_apbdes)) {
            $this->session->set_flashdata('attention_message', 'Maaf, APBDES tidak ditemukan.');
            redirect('rencanaPembangunan/c_apbdes', 'refresh');
        }

        $detail_master_rkp = $this->m_master_rkp->getDetail($id_m_apbdes);
        if (count($_POST) > 0 && $this->m_apbdes->getPostData($id_m_apbdes)) {

            $response = $this->m_apbdes->save($id_apbdes);

//            $this->m_master_apbdes->setSubTotal($id_m_apbdes);

            if ($response["error_number"] != '0') {
                $sub_total = $this->m_apbdes->reCalculateSubTotal($id_m_apbdes, $response["post_data"]["id_top_coa"]);
                if ($sub_total) {
                    $this->m_master_apbdes->setSubTotal($id_m_apbdes, $response["post_data"]["id_top_coa"], $sub_total);
                }
            }

            $attention_message = $response["message_error"];
            if ($response["error_number"] != '0' && $id_apbdes) {
                redirect('rencanaPembangunan/c_apbdes');
            } elseif ($response["error_number"] != '0' && !$id_apbdes) {
                redirect('rencanaPembangunan/c_apbdes/add_detail');
            }
            $post_data = $response["post_data"];
        } elseif (count($_POST) == 0 && $id_apbdes) {
            $post_data = $this->m_apbdes->getDetail($id_apbdes, TRUE);

            if (!$post_data || empty($post_data)) {
                $this->session->set_flashdata('attention_message', 'Maaf, Data tidak ditemukan.');
                redirect('rencanaPembangunan/c_apbdes', 'refresh');
            }
        }

        $id_master_not_found = $this->session->flashdata('id_master_not_found');
        if (empty($post_data) && $id_master_not_found) {
            $this->session->set_flashdata('attention_message', 'Maaf, Data tidak ditemukan.');
            redirect('rencanaPembangunan/c_apbdes', 'refresh');
        }

        $master_rpjm_desa = $this->m_master_rancangan_rpjm_desa->getDetail($detail_master_rkp->id_m_rancangan_rpjm_desa);
        $and_tahun_pelaksanaan = FALSE;
        if ($master_rpjm_desa) {
            $tahun_ke = (intval($detail_master_rkp->rkp_tahun) - intval($master_rpjm_desa->tahun_awal)) + 1;
            $tahun_pelaksanaan = "tahun_pelaksanaan_" . $tahun_ke;

            $and_tahun_pelaksanaan = array($tahun_pelaksanaan => $detail_master_rkp->rkp_tahun);
        }

        unset($master_rpjm_desa);

        $rpjm_grouped_by_bidang = $this->m_rancangan_rpjm_desa->getByIdMasterRpjm($detail_master_rkp->id_m_rancangan_rpjm_desa, TRUE, $and_tahun_pelaksanaan);

        $this->set('js_rkp_add_detail', $this->load->view('rencanaPembangunan/rkp/js/rkp_detail', array("rpjm_grouped_by_bidang" => json_encode($rpjm_grouped_by_bidang)), TRUE));
        $this->set('js_general_helper', $this->load->view('rencanaPembangunan/rancangan_rpjm_desa/js/general_helper', array(), TRUE));
        $this->set('deskripsi_title', 'Formulir Detail RPJM Desa');
        $this->set('attention_message', $attention_message);
        $this->set('id_m_apbdes', $id_m_apbdes);
        $this->set('id_apbdes', $id_apbdes);
        $this->set('post_data', $post_data);

        $this->set('top_level_coa', $top_level_coa);
        $this->set('deskripsi_title', 'Detail RKP Desa');

        $this->set('js_general_helper', $this->load->view('rencanaPembangunan/rancangan_rpjm_desa/js/general_helper', array(), TRUE));
    }

}
