<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//session_start();



class C_baseRencanaPembangunan extends CI_Controller {

    protected $_data_view = array();
    protected $_action;
    protected $_page_title;

    function __construct($page_title = 'Rencana Pembangunan', $menu = 'v_rencanaPembangunan') {
        parent::__construct();

        $full_name = get_class($this);
        $this->_name = strtolower($full_name);

        $this->load->helper('form');
        $this->load->model('m_user');
        //$this->load->model('m_kalkulasi');     
        //$this->load->model('statistik/m_kk');	
        $this->_set_logo_and_menu($page_title, $menu);
        unset($full_name);
    }

    private function _set_logo_and_menu($page_title, $menu) {

        $this->load->model('m_logo');
        $this->set('konten_logo', $this->m_logo->getLogo());

        $this->set('page_title', $page_title);

        $menu = 'menu/' . $menu;
        $this->set('menu', $this->load->view($menu, array(), TRUE));
    }

    public function _remap($method, $params = array()) {
        if ($this->session->userdata('logged_in')) {

            if (method_exists($this, $method)) {
                $this->_action = $method;
                return call_user_func_array(array($this, $method), $params);
            }
            show_404();
        } else {
            redirect('c_login', 'refresh');
        }
    }

    public function _output($output) {
        $out = $output;
//        var_dump($output == NULL);exit;
        if ($output == NULL) {
            $this->configure_view();
            $out = $this->output->get_output();
        }
        $this->output->_write_cache($out);
        echo $out;
    }

    protected function configure_view() {
        $view_template = 'v_' . $this->_action;
        $this->render($view_template);
    }

    protected function render($view, $path = NULL, $layout = NULL) {
        if ($path == NULL) {
            $path = substr($this->_name, 2);
        }

        $dir = $this->router->fetch_directory();

        $act_view = $this->_get_view_path($dir . $path . '/' . $view);

        if (!$act_view) {
            show_error('Unable to find view for ' . $this->_name, 500);
        }

        $content = $this->load_view($act_view, $this->_data_view, TRUE);
        $this->render_layout($content, $layout);
    }

    function load_view($file, $data = array(), $return = FALSE) {
        if (!$return) {
            $this->load->view($file, $data, $return);
        }

        return $this->load->view($file, $data, TRUE);
    }

    protected function render_layout($content, $layout = NULL) {
        $data_for_layout = array_merge($this->_data_view, array(
            'content' => $content,
            'page_title' => $this->_page_title
        ));

        if ($layout === NULL) {
            $layout = $this->_layout;
        }
        $layout_view = $this->_get_view_path('utama');

        if (!$layout_view) {
            show_error('Unable to find template for ' . $layout, 500);
        }
        $this->load_view($layout_view, $data_for_layout);
    }

    private function _get_view_path($view) {
        $act_view = $view . EXT;
        $path_view = APPPATH . '/views/' . $act_view;

        if (!file_exists($path_view)) {
            return FALSE;
        }
        return $act_view;
    }

    public function set($key, $value) {
        $this->_data_view[$key] = $value;
    }

    public function get_data_view($key = FALSE) {
        if ($key && array_key_exists($key, $this->_data_view)) {
            return $this->_data_view[$key];
        }
        return FALSE;
    }

    protected function set_header($type) {
        $this->output->set_content_type($type);
    }

}
