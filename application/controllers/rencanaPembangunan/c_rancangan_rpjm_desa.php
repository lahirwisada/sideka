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
        $this->config->load('rp_rancangan_rpjm_desa');
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
        $this->load->model('rencanaPembangunan/m_rancangan_rpjm_desa');

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

        $grid_js = build_grid_js('flex1', site_url('rencanaPembangunan/c_rancangan_rpjm_desa/load_data_master'), $colModelM, 'id_m_rancangan_rpjm_desa', 'asc', $gridParams, $buttons);

        $attention_message = $this->session->flashdata('attention_message');
        $this->set('attention_message', $attention_message);
        $this->set('js_grid', $grid_js);
        $this->set('deskripsi_title', 'Master Rencana Pembangunan Jangka Menengah Daerah');
    }

    public function load_data_master() {
        $this->load->library('flexigrid');
        $this->load->helper('common_helper');
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
                '<button type="submit" class="btn btn-info btn-xs" title="Tampil Detil Program" onclick="show_detail_program(\'' . $row->id_m_rancangan_rpjm_desa . '\')"/>
				<i class="fa fa-eye"></i>
				</button>'
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

    public function import_excel() {
        $config['upload_path'] = "./uploads/temp_upload_excel/rpjm/";
        $config['allowed_types'] = "xls|xlsx";

        $this->load->library('upload', $config);

//        var_dump($this->upload->validate_upload_path());exit;


        if ($this->session->userdata('logged_in')) {
            $this->load->library('rencanaPembangunan/l_read_rancangan_rpjm_desa');

            $is_upload_ok = $this->l_read_rancangan_rpjm_desa->upload_excel('file_excel');

            /**
             * @todo Simpan Master untuk rancangan rpjm desa
             * @todo Simpan informasi Desa, Kecamatan, Kabupaten, dan Provinsi pada Master
             */
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

    public function add() {
        $attention_message = $this->session->flashdata('attention_message');
        $this->set('attention_message', $attention_message);
    }

}
