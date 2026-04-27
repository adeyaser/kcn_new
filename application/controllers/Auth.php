<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Acl_model');
    }

    public function index() {
        redirect('auth/login');
    }

    public function login() {
        if ($this->session->userdata('user_id')) {
            redirect('dashboard');
        }

        if ($this->input->method() === 'post') {
            $username = $this->input->post('username', true);
            $password = $this->input->post('password');

            $user = $this->Acl_model->get_user_by_username($username);

            if ($user && password_verify($password, $user->password)) {
                $this->session->set_userdata([
                    'user_id'   => $user->id,
                    'username'  => $user->username,
                    'full_name' => $user->full_name,
                    'role_id'   => $user->role_id,
                    'role_code' => $user->role_code
                ]);
                $this->Acl_model->update_last_login($user->id);
                
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'success', 'redirect' => site_url('dashboard')]);
                    return;
                }
                redirect('dashboard');
            } else {
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'error', 'message' => 'Username atau password salah!']);
                    return;
                }
                $this->session->set_flashdata('error', 'Username atau password salah!');
                redirect('auth/login');
            }
        }

        // Get app settings for login page
        $data['settings'] = $this->Acl_model->get_settings();
        $this->load->view('auth/login', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
