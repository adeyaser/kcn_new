<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Acl_model');
    }

    public function index() {
        $this->data['page_title'] = 'Terminal Profile';
        // Get all app settings to show terminal info
        $this->data['settings'] = $this->Acl_model->get_settings();
        
        $this->render('setup/profile/index');
    }

    public function update() {
        $post_data = $this->input->post();
        
        // Handle Logo Upload if any
        if (!empty($_FILES['logo_file']['name'])) {
            $config['upload_path']   = './assets/img/';
            $config['allowed_types'] = 'gif|jpg|png|webp|jpeg';
            $config['max_size']      = 2048;
            $config['file_name']     = 'terminal_logo_' . time();
            
            $this->load->library('upload', $config);
            
            if (!$this->upload->do_upload('logo_file')) {
                $error = $this->upload->display_errors('', '');
                $this->session->set_flashdata('error', 'Gagal mengunggah logo: ' . $error);
            } else {
                $upload_data = $this->upload->data();
                $logo_path = 'assets/img/' . $upload_data['file_name'];
                
                // Update database
                $this->db->where('setting_key', 'terminal_logo')->update('app_settings', ['setting_value' => $logo_path]);
                $this->session->set_flashdata('success', 'Logo berhasil diperbarui');
            }
        }

        foreach ($post_data as $key => $value) {
            // Update app_settings
            $this->db->where('setting_key', $key)->update('app_settings', ['setting_value' => $value]);
        }
        
        $this->session->set_flashdata('success', 'Terminal profile and configuration updated successfully');
        redirect('setup/profile');
    }
}
