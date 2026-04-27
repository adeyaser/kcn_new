<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('setup/settings', 'can_view');
        $this->load->model('Setting_model');
    }

    public function index() {
        $this->data['page_title'] = 'System Settings & Profile';
        $this->data['settings'] = $this->Setting_model->get_all();
        
        // Group settings for view
        $grouped = [];
        foreach ($this->data['settings'] as $s) {
            $grouped[$s->setting_group][] = $s;
        }
        $this->data['grouped_settings'] = $grouped;

        $this->render('setup/settings/index');
    }

    public function save() {
        $this->check_permission('setup/settings', 'can_edit');
        $post_data = $this->input->post();
        
        if ($this->Setting_model->update_batch($post_data)) {
            $this->session->set_flashdata('success', 'Settings updated successfully');
        } else {
            $this->session->set_flashdata('error', 'Failed to update settings');
        }
        
        redirect('setup/settings');
    }
}
