<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $data = [];

    public function __construct() {
        parent::__construct();
        
        // Check authentication
        if (!$this->session->userdata('user_id')) {
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'error', 'message' => 'Session expired']);
                exit;
            }
            redirect('auth/login');
        }

        $this->load->model('Acl_model');
        $this->_init_data();
    }

    private function _init_data() {
        $user_id = $this->session->userdata('user_id');
        $this->data['current_user'] = $this->Acl_model->get_user($user_id);
        $this->data['sidebar_menus'] = $this->Acl_model->get_user_menus($this->data['current_user']->role_id);
        $this->data['app_settings'] = $this->Acl_model->get_settings();
        $this->data['page_title'] = 'Dashboard';
    }

    protected function render($view, $data = []) {
        $this->data = array_merge($this->data, $data);
        $this->load->view('layouts/header', $this->data);
        $this->load->view('layouts/sidebar', $this->data);
        $this->load->view($view, $this->data);
        $this->load->view('layouts/footer', $this->data);
    }

    protected function json_response($data, $status = 200) {
        $this->output
            ->set_status_header($status)
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    protected function check_permission($menu_url, $action = 'can_view') {
        $role_id = $this->data['current_user']->role_id;
        if (!$this->Acl_model->has_permission($role_id, $menu_url, $action)) {
            if ($this->input->is_ajax_request()) {
                $this->json_response(['status' => 'error', 'message' => 'Access denied'], 403);
                return false;
            }
            show_error('You do not have permission to access this page.', 403);
        }
        return true;
    }
}
