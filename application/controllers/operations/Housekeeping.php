<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Housekeeping extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('operations/housekeeping', 'can_view');
        $this->load->model('Tally_model');
        $this->load->model('Equipment_model');
    }

    public function index() {
        $this->data['page_title'] = 'Yard Housekeeping & Transfer';
        $this->data['equipments'] = $this->Equipment_model->get_datatables(); 
        
        $this->render('operations/housekeeping/index');
    }

    public function ajax_save_transfer() {
        $this->check_permission('operations/housekeeping', 'can_create');
        
        $data = array(
            'planning_id' => 0, // General housekeeping
            'container_no' => strtoupper($this->input->post('container_no')),
            'activity_type' => 'RE-STOW',
            'equipment_id' => $this->input->post('equipment_id'),
            'bay' => $this->input->post('new_bay'),
            'row' => $this->input->post('new_row'),
            'tier' => $this->input->post('new_tier'),
            'location_type' => 'YARD',
            'tally_operator_id' => $this->session->userdata('user_id'),
            'activity_time' => date('Y-m-d H:i:s'),
            'status' => 'COMPLETED',
            'remarks' => 'Transfer from ' . $this->input->post('old_location')
        );

        $this->Tally_model->save($data);
        $this->json_response(array("status" => TRUE));
    }
}
