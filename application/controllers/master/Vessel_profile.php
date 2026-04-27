<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vessel_profile extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('master/vessel_profile', 'can_view');
        $this->load->model('Vessel_model');
    }

    public function index() {
        $this->data['page_title'] = 'Vessel Configuration Profiles';
        $this->data['vessels'] = $this->Vessel_model->get_all();
        $this->render('master/vessel_profile/index');
    }

    public function ajax_save() {
        $data = $this->input->post();
        // Logika simpan profil
        $vessel_id = $data['vessel_id'];
        unset($data['id']);
        
        $exists = $this->db->where('vessel_id', $vessel_id)->get('mst_vessel_profiles')->row();
        if ($exists) {
            $this->db->where('vessel_id', $vessel_id)->update('mst_vessel_profiles', $data);
        } else {
            $this->db->insert('mst_vessel_profiles', $data);
        }
        $this->json_response(['status' => true]);
    }

    public function get_profile($vessel_id) {
        $profile = $this->db->where('vessel_id', $vessel_id)->get('mst_vessel_profiles')->row();
        $this->json_response($profile);
    }
}
