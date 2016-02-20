<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once APPPATH . 'controllers/rencanaPembangunan/c_baseRencanaPembangunan.php';

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class C_rkp extends C_baseRencanaPembangunan {

    function __construct() {
        parent::__construct('RKP Desa', 'v_rencanaPembangunan');
        $this->load->helper(array('flexigrid_helper', 'common_helper'));
        $this->config->load('rp_rkp_desa');
        $this->load->model(array(
            'rencanaPembangunan/m_rancangan_rpjm_desa',
            'rencanaPembangunan/m_master_rkp',
            'rencanaPembangunan/m_rkp',
            'rencanaPembangunan/m_bidang',
            'rencanaPembangunan/m_master_rancangan_rpjm_desa'));
    }

    function index() {
        $session['hasil'] = $this->session->userdata('logged_in');
        $role = $session['hasil']->role;


        if ($role != 'Perencana Pembangunan') {
            redirect('c_login', 'refresh');
        }

        $r_m_rpjm_desa_config = $this->config->item('rp_master_rkp_desa');

        $colModelM = $r_m_rpjm_desa_config['colModel'];
        $buttons = $r_m_rpjm_desa_config['buttons'];
        $gridParams = $r_m_rpjm_desa_config['gridParams'];

        $grid_js = build_grid_js('flex1', site_url('rencanaPembangunan/c_rkp/load_data_master'), $colModelM, 'id_m_rkp', 'desc', $gridParams, $buttons);

        $attention_message = $this->session->flashdata('attention_message');
        $this->set('attention_message', $attention_message);
        $this->set('js_grid', $grid_js);
        $this->set('deskripsi_title', 'Master RKP');
    }

    public function detail($id) {

        $session['hasil'] = $this->session->userdata('logged_in');
        $role = $session['hasil']->role;


        if ($role != 'Perencana Pembangunan') {
            redirect('c_login', 'refresh');
        }

        $r_m_rpjm_desa_config = $this->config->item('rp_rkp_desa');

        $colModelM = $r_m_rpjm_desa_config['colModel'];
        $buttons = $r_m_rpjm_desa_config['buttons'];
        $gridParams = $r_m_rpjm_desa_config['gridParams'];

        $grid_js = build_grid_js('flex1', site_url('rencanaPembangunan/c_rkp/load_data_detail/'.$id), $colModelM, 'id_rkp', 'desc', $gridParams, $buttons);

        $attention_message = $this->session->flashdata('attention_message');
        $this->set('attention_message', $attention_message);
        $this->set('js_grid', $grid_js);
        $this->set('deskripsi_title', 'Detail RKP');
    }

    public function load_data_detail($id_m_rkp = FALSE) {
        $this->load->library('flexigrid');
        $valid_fields = array('id_rkp');

        $this->flexigrid->validate_post('id_rkp', 'ASC', $valid_fields);
        $records = $this->m_rkp->getFlexigrid($id_m_rkp);

        $this->output->set_header($this->config->item('json_header'));

        $record_items = array();

        if ($records['records']) {
            foreach ($records['records']->result() as $row) {
                $record_items[] = array(
                    $row->id_rkp,
                    $row->id_rkp,
                    $row->id_m_rancangan_rpjm_desa,
                    $row->rkp_tahun,
                    $row->bidang,
                    $row->jenis_kegiatan,
                    $row->lokasi,
                    $row->volume,
                    $row->sasaran_manfaat,
                    $row->waktu_pelaksanaan,
                    rupiah_display($row->jumlah_biaya),
                    ($row->swakelola != 0 ? '<i class="fa fa-check"></i>' : ' '),
                    ($row->kerjasama_antar_desa != 0 ? '<i class="fa fa-check"></i>' : ' '),
                    ($row->kerjasama_pihak_ketiga != 0 ? '<i class="fa fa-check"></i>' : ' '),
                    $row->rencana_pelaksanaan_kegiatan
                );
            }
        }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items));
    }

    public function load_data_master() {
        $this->load->library('flexigrid');
        $valid_fields = array('id_m_rkp');

        $this->flexigrid->validate_post('id_m_rkp', 'ASC', $valid_fields);
        $records = $this->m_master_rkp->getFlexigrid();

        $this->output->set_header($this->config->item('json_header'));

        $record_items = array();

        foreach ($records['records']->result() as $row) {
            $record_items[] = array(
                $row->id_m_rkp,
                $row->id_m_rkp,
                $row->id_m_rancangan_rpjm_desa,
                $row->tahun_awal,
                $row->tahun_akhir,
                $row->tahun_anggaran,
                rupiah_display($row->total_bidang_1),
                rupiah_display($row->total_bidang_2),
                rupiah_display($row->total_bidang_3),
                rupiah_display($row->total_bidang_4),
                rupiah_display($row->total_keseluruhan),
                $row->rkp_tahun,
                $row->tanggal_disusun,
                $row->disusun_oleh,
                $row->kepala_desa,
                $row->id_desa,
                $row->nama_desa,
                $row->id_kecamatan,
                $row->nama_kecamatan,
                $row->id_kab_kota,
                $row->nama_kab_kota,
                $row->id_provinsi,
                $row->nama_provinsi,
                '<button id="anchor_detail_' . $row->id_m_rkp . '" type="button" class="btn btn-primary btn-xs btn_add_detail" onclick="add_detail(this);" title="Tambah Detail RKP" />' .
                '<i class="fa fa-plus"></i>' .
                '</button>&nbsp;' .
                '<button type="submit" class="btn btn-info btn-xs" title="Tampil Detil RKP" onclick="show_detail_program(\'' . $row->id_m_rkp . '\')"/>' .
                '<i class="fa fa-list-alt"></i>' .
                '</button>&nbsp;' .
                /**
                 * @todo Buat generate excel untuk rkp
                 */
                ($row->nama_file != '' && $row->nama_file != NULL ? '<a  title="Download Excel" href="' . base_url() . 'uploads/temp_upload_excel/rkp/' . $row->nama_file . '" class="btn btn-success btn-xs"><i class="fa fa-file-excel-o"></i></a>' : '')
            );
        }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items));
    }

    function add($id_m_rkp = FALSE) {
        $master_rpjm = $this->m_master_rancangan_rpjm_desa->getArray(date('Y') - 2);

        $post_data = array();
        $attention_message = "";
        if (count($_POST) > 0) {
            $this->m_master_rkp->getPostData();
            $response = $this->m_master_rkp->save($id_m_rkp);
            $attention_message = $response["message_error"];
            if ($response["error_number"] != '0' && $id_m_rkp) {
                redirect('rencanaPembangunan/c_rkp');
            } elseif ($response["error_number"] != '0' && !$id_m_rkp) {
                redirect('rencanaPembangunan/c_rkp/add_detail');
            }
            $post_data = $response["post_data"];
        } elseif (count($_POST) == 0 && $id_m_rkp) {
            $post_data = $this->m_master_rkp->getDetail($id_m_rkp, TRUE);

            if (!$post_data || empty($post_data)) {
                $this->session->set_flashdata('attention_message', 'Maaf, Data tidak ditemukan.');
                redirect('rencanaPembangunan/c_rkp', 'refresh');
            }
        }

        $this->load->model('m_provinsi');

        $arr_provinsi = $this->m_provinsi->getArray();
        $this->set('arr_provinsi', $arr_provinsi);
        $this->set('post_data', $post_data);
        $this->set('attention_message', $attention_message);
        $this->set('id_m_rkp', $id_m_rkp);


        $this->set('js_general_helper', $this->load->view('rencanaPembangunan/rancangan_rpjm_desa/js/general_helper', array(), TRUE));
        $this->set('master_rpjm', $master_rpjm);
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

    function add_detail($id_m_rkp = FALSE, $id_rkp = FALSE) {

        $post_data = array();
        $attention_message = "";


        if (!$id_m_rkp || !$this->m_master_rkp->isIdValid($id_m_rkp)) {
            $this->session->set_flashdata('attention_message', 'Maaf, RPJM tidak ditemukan.');
            redirect('rencanaPembangunan/c_rkp', 'refresh');
        }

        $detail_master_rkp = $this->m_master_rkp->getDetail($id_m_rkp);
        if (count($_POST) > 0 && $this->m_rkp->getPostData($id_m_rkp)) {

            $response = $this->m_rkp->save($id_rkp);

            $this->m_master_rkp->setSubTotal($id_m_rkp);

            if ($response["error_number"] != '0') {
                $sub_total = $this->m_rkp->reCalculateSubTotal($id_m_rkp, $response["post_data"]["id_bidang"]);
//var_dump($sub_total);exit;
                if ($sub_total) {
                    $this->m_master_rkp->setSubTotal($id_m_rkp, $response["post_data"]["id_bidang"], $sub_total);
                }
            }

            $attention_message = $response["message_error"];
            if ($response["error_number"] != '0' && $id_rkp) {
                redirect('rencanaPembangunan/c_rkp');
            } elseif ($response["error_number"] != '0' && !$id_rkp) {
                redirect('rencanaPembangunan/c_rkp/add_detail');
            }
            $post_data = $response["post_data"];
        } elseif (count($_POST) == 0 && $id_rkp) {
            $post_data = $this->m_rkp->getDetail($id_rkp, TRUE);

            if (!$post_data || empty($post_data)) {
                $this->session->set_flashdata('attention_message', 'Maaf, Data tidak ditemukan.');
                redirect('rencanaPembangunan/c_rkp', 'refresh');
            }
        }

        $id_master_not_found = $this->session->flashdata('id_master_not_found');
        if (empty($post_data) && $id_master_not_found) {
            $this->session->set_flashdata('attention_message', 'Maaf, Data tidak ditemukan.');
            redirect('rencanaPembangunan/c_rkp', 'refresh');
        }

        $this->load->model(array(
            'rencanaPembangunan/m_bidang'));

        $bidang = $this->m_coa->getDeskripsiBidangFromConfig();

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
        $this->set('id_m_rkp', $id_m_rkp);

        $this->set('bidang', $bidang);
        $this->set('deskripsi_title', 'Detail RKP Desa');

        $this->set('js_general_helper', $this->load->view('rencanaPembangunan/rancangan_rpjm_desa/js/general_helper', array(), TRUE));
    }

}
