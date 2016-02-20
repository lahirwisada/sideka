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

        $this->load->helper(array('flexigrid_helper', 'common_helper'));
        $this->config->load('rp_rancangan_rpjm_desa');
        $this->load->model(array(
            'rencanaPembangunan/m_rancangan_rpjm_desa',
            'rencanaPembangunan/m_master_rancangan_rpjm_desa',
            'rencanaPembangunan/m_sumber_dana_desa',
            'rencanaPembangunan/m_bidang'));
    }

    public function detail($id) {

        $session['hasil'] = $this->session->userdata('logged_in');
        $role = $session['hasil']->role;


        if ($role != 'Perencana Pembangunan') {
            redirect('c_login', 'refresh');
        }

        $this->lists($id);

        $attention_message = $this->session->flashdata('attention_message');
        $this->set('attention_message', $attention_message);
    }

    public function load_detail($id) {
        $this->load->library('flexigrid');
        $this->load->helper('common_helper');
        $valid_fields = array('id_rancangan_rpjm_desa');

        $this->flexigrid->validate_post('id_m_rancangan_rpjm_desa', 'ASC', $valid_fields);

        $records = $this->m_rancangan_rpjm_desa->getFlexigrid($id);

        $this->output->set_header($this->config->item('json_header'));

        $record_items = array();

        foreach ($records['records']->result() as $row) {
            $record_items[] = array(
                $row->id_rancangan_rpjm_desa,
                $row->id_rancangan_rpjm_desa,
                $row->bidang,
                $row->sub_bidang,
                $row->jenis_kegiatan,
                $row->lokasi_rt_rw,
                $row->prakiraan_volume,
                $row->sasaran_manfaat,
                ($row->tahun_pelaksanaan_1 != 0 ? '<i class="fa fa-check"></i>' : ' '),
                ($row->tahun_pelaksanaan_2 != 0 ? '<i class="fa fa-check"></i>' : ' '),
                ($row->tahun_pelaksanaan_3 != 0 ? '<i class="fa fa-check"></i>' : ' '),
                ($row->tahun_pelaksanaan_4 != 0 ? '<i class="fa fa-check"></i>' : ' '),
                ($row->tahun_pelaksanaan_5 != 0 ? '<i class="fa fa-check"></i>' : ' '),
                ($row->tahun_pelaksanaan_6 != 0 ? '<i class="fa fa-check"></i>' : ' '),
                rupiah_display($row->jumlah_biaya),
                $row->sumber_biaya,
                ($row->swakelola != 0 ? '<i class="fa fa-check"></i>' : ' '),
                ($row->kerjasama_antar_desa != 0 ? '<i class="fa fa-check"></i>' : ' '),
                ($row->kerjasama_pihak_ketiga != 0 ? '<i class="fa fa-check"></i>' : ' ')
            );
        }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items));
    }

    public function index() {

        $session['hasil'] = $this->session->userdata('logged_in');
        $role = $session['hasil']->role;


        if ($role != 'Perencana Pembangunan') {
            redirect('c_login', 'refresh');
        }

        $r_m_rpjm_desa_config = $this->config->item('rp_master_rancangan_rpjm_desa');

        $colModelM = $r_m_rpjm_desa_config['colModel'];
        $buttons = $r_m_rpjm_desa_config['buttons'];
        $gridParams = $r_m_rpjm_desa_config['gridParams'];

        $grid_js = build_grid_js('flex1', site_url('rencanaPembangunan/c_rancangan_rpjm_desa/load_data_master'), $colModelM, 'id_m_rancangan_rpjm_desa', 'desc', $gridParams, $buttons);

        $attention_message = $this->session->flashdata('attention_message');
        $this->set('attention_message', $attention_message);
        $this->set('js_grid', $grid_js);
        $this->set('deskripsi_title', 'Master Rencana Pembangunan Jangka Menengah Daerah');
    }

    public function load_data_master() {
        $this->load->library('flexigrid');
        $valid_fields = array('id_m_rancangan_rpjm_desa');

        $this->load->model('rencanaPembangunan/m_master_rancangan_rpjm_desa');
        $this->flexigrid->validate_post('id_m_rancangan_rpjm_desa', 'ASC', $valid_fields);
        $records = $this->m_master_rancangan_rpjm_desa->getFlexigrid();

        $this->output->set_header($this->config->item('json_header'));

        $record_items = array();

        foreach ($records['records']->result() as $row) {
            $record_items[] = array(
                $row->id_m_rancangan_rpjm_desa,
                $row->id_m_rancangan_rpjm_desa,
                $row->tahun_awal,
                $row->tahun_akhir,
                $row->tahun_anggaran,
                $row->nama_file,
                rupiah_display($row->total_bidang_1),
                rupiah_display($row->total_bidang_2),
                rupiah_display($row->total_bidang_3),
                rupiah_display($row->total_bidang_4),
                rupiah_display($row->total_keseluruhan),
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
                '<button id="anchor_detail_' . $row->id_m_rancangan_rpjm_desa . '" type="button" class="btn btn-primary btn-xs btn_add_detail" onclick="add_detail(this);" title="Tambah Detail RPJM" />' .
                '<i class="fa fa-plus"></i>' .
                '</button>&nbsp;' .
                '<button type="submit" class="btn btn-info btn-xs" title="Tampil Detil RPJM" onclick="show_detail_program(\'' . $row->id_m_rancangan_rpjm_desa . '\')"/>' .
                '<i class="fa fa-list-alt"></i>' .
                '</button>&nbsp;' .
                /**
                 * @todo Buat generate excel untuk rpjm
                 */
                ($row->nama_file != '' && $row->nama_file != NULL ? '<a  title="Download Excel" href="' . base_url() . 'uploads/temp_upload_excel/rpjm/' . $row->nama_file . '" class="btn btn-success btn-xs"><i class="fa fa-file-excel-o"></i></a>' : '')
            );
        }
        //Print please
        $this->output->set_output($this->flexigrid->json_build($records['record_count'], $record_items));
    }

    public function lists($id) {


        $r_rpjm_desa_config = $this->config->item('rp_rancangan_rpjm_desa');

        $colModel = $r_rpjm_desa_config['colModel'];
        $buttons = $r_rpjm_desa_config['buttons'];
        $gridParams = $r_rpjm_desa_config['gridParams'];

        $grid_js = build_grid_js('flex1', site_url('rencanaPembangunan/c_rancangan_rpjm_desa/load_detail/' . $id), $colModel, 'id_rancangan_rpjm_desa', 'asc', $gridParams, $buttons);

//        var_dump($grid_js);exit;
        $this->set('js_grid', $grid_js);
        $this->set('deskripsi_title', 'Rencana Pembangunan Jangka Menengah Daerah');
    }

    public function execute_import_excel() {
        $config['upload_path'] = "./uploads/temp_upload_excel/rpjm/";
        $config['allowed_types'] = "xls|xlsx";

        $this->load->library('upload', $config);

//        var_dump($this->upload->validate_upload_path());exit;


        if ($this->session->userdata('logged_in')) {
            $this->load->library('rencanaPembangunan/l_read_rancangan_rpjm_desa');

            $is_upload_ok = $this->l_read_rancangan_rpjm_desa->upload_excel('file_excel');

            $save_content_status = $this->l_read_rancangan_rpjm_desa->save_content_excel();

            if ($save_content_status) {
                $this->session->set_flashdata('attention_message', 'Import Excel Sukses.');
                redirect('rencanaPembangunan/c_rancangan_rpjm_desa', 'refresh');
            } else {
                $this->session->set_flashdata('attention_message', 'Import Excel Gagal dilakukan, cek kembali template dan konten data anda.');
                redirect('rencanaPembangunan/c_rancangan_rpjm_desa/add', 'refresh');
            }
        } else {
            redirect('c_login', 'refresh');
        }
    }

    public function import_excel() {
        $attention_message = $this->session->flashdata('attention_message');
        $this->set('attention_message', $attention_message);
    }

    public function data_not_found() {
        $this->session->set_flashdata('attention_message', 'Maaf, Data tidak ditemukan.');
        redirect('rencanaPembangunan/c_rancangan_rpjm_desa', 'refresh');
    }

    public function add_detail($id_m_rancangan_rpjm_desa = FALSE, $id_rancangan_rpjm_desa = FALSE) {
        
        $post_data = array();
        $attention_message = "";


        if (!$id_m_rancangan_rpjm_desa || !$this->m_master_rancangan_rpjm_desa->isIdValid($id_m_rancangan_rpjm_desa)) {
            $this->session->set_flashdata('attention_message', 'Maaf, RPJM tidak ditemukan.');
            redirect('rencanaPembangunan/c_rancangan_rpjm_desa', 'refresh');
        }

        if (count($_POST) > 0 && $this->m_rancangan_rpjm_desa->getPostData($id_m_rancangan_rpjm_desa)) {
            
            $detail_master_rpjm = $this->m_master_rancangan_rpjm_desa->getDetail($id_m_rancangan_rpjm_desa);
            $this->m_rancangan_rpjm_desa->calculateTahunPelaksanaan($detail_master_rpjm->tahun_awal);
            unset($detail_master_rpjm);
            
            $response = $this->m_rancangan_rpjm_desa->save($id_rancangan_rpjm_desa);
            
            $this->m_master_rancangan_rpjm_desa->setSubTotal($id_m_rancangan_rpjm_desa);
            
            if($response["error_number"] != '0'){
               $sub_total = $this->m_rancangan_rpjm_desa->reCalculateSubTotal($id_m_rancangan_rpjm_desa, $response["post_data"]["id_bidang"]);

               if($sub_total){
                   $this->m_master_rancangan_rpjm_desa->setSubTotal($id_m_rancangan_rpjm_desa, $response["post_data"]["id_bidang"], $sub_total);
               }
            }
            
            $attention_message = $response["message_error"];
            if ($response["error_number"] != '0' && $id_rancangan_rpjm_desa) {
                redirect('rencanaPembangunan/c_rancangan_rpjm_desa');
            } elseif ($response["error_number"] != '0' && !$id_rancangan_rpjm_desa) {
                redirect('rencanaPembangunan/c_rancangan_rpjm_desa/add_detail');
            }
            $post_data = $response["post_data"];
        } elseif (count($_POST) == 0 && $id_rancangan_rpjm_desa) {
            $post_data = $this->m_rancangan_rpjm_desa->getDetail($id_rancangan_rpjm_desa, TRUE);

            if (!$post_data || empty($post_data)) {
                $this->session->set_flashdata('attention_message', 'Maaf, Data tidak ditemukan.');
                redirect('rencanaPembangunan/c_rancangan_rpjm_desa', 'refresh');
            }
        }

        $id_master_not_found = $this->session->flashdata('id_master_not_found');
        if (empty($post_data) && $id_master_not_found) {
            $this->session->set_flashdata('attention_message', 'Maaf, Data tidak ditemukan.');
            redirect('rencanaPembangunan/c_rancangan_rpjm_desa', 'refresh');
        }

        $this->set('js_general_helper', $this->load->view('rencanaPembangunan/rancangan_rpjm_desa/js/general_helper', array(), TRUE));
        $this->set('deskripsi_title', 'Formulir Detail RPJM Desa');
        $this->set('attention_message', $attention_message);
        $this->set('json_jenis_kegiatan', $this->select_jenis_kegiatan(TRUE));
        $this->set('json_sumber_dana', $this->select_sumber_dana(TRUE));
        $this->set('id_m_rancangan_rpjm_desa', $id_m_rancangan_rpjm_desa);
    }

    public function add($id_m_rancangan_rpjm_desa = FALSE) {
        $post_data = array();
        $attention_message = "";
        if (count($_POST) > 0) {
            $this->m_master_rancangan_rpjm_desa->getPostData();
            $response = $this->m_master_rancangan_rpjm_desa->save($id_m_rancangan_rpjm_desa);
            $attention_message = $response["message_error"];
            if ($response["error_number"] != '0' && $id_m_rancangan_rpjm_desa) {
                redirect('rencanaPembangunan/c_rancangan_rpjm_desa');
            } elseif ($response["error_number"] != '0' && !$id_m_rancangan_rpjm_desa) {
                redirect('rencanaPembangunan/c_rancangan_rpjm_desa/add_detail');
            }
            $post_data = $response["post_data"];
        } elseif (count($_POST) == 0 && $id_m_rancangan_rpjm_desa) {
            $post_data = $this->m_master_rancangan_rpjm_desa->getDetail($id_m_rancangan_rpjm_desa, TRUE);

            if (!$post_data || empty($post_data)) {
                $this->session->set_flashdata('attention_message', 'Maaf, Data tidak ditemukan.');
                redirect('rencanaPembangunan/c_rancangan_rpjm_desa', 'refresh');
            }
        }

        $this->load->model('m_provinsi');

        $arr_provinsi = $this->m_provinsi->getArray();
        $this->set('arr_provinsi', $arr_provinsi);
        $this->set('post_data', $post_data);
        $this->set('attention_message', $attention_message);
        $this->set('id_m_rancangan_rpjm_desa', $id_m_rancangan_rpjm_desa);
    }

}
