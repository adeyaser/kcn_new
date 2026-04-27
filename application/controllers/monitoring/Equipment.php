<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Equipment extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('master/equipment', 'can_view');
        $this->load->model('Equipment_model');
    }

    public function index() {
        $this->data['page_title'] = 'Equipment Status Monitoring';
        
        // Fetch all equipment with their status
        $this->data['equipments'] = $this->Equipment_model->get_datatables();
        
        // Group by type for dashboard feel
        $stats = [
            'QCC' => ['total' => 0, 'ready' => 0],
            'RTG' => ['total' => 0, 'ready' => 0],
            'RS' => ['total' => 0, 'ready' => 0],
            'TRUCK' => ['total' => 0, 'ready' => 0],
        ];
        
        foreach($this->data['equipments'] as $e) {
            if(isset($stats[$e->equipment_type])) {
                $stats[$e->equipment_type]['total']++;
                if($e->status == 'READY') $stats[$e->equipment_type]['ready']++;
            }
        }
        $this->data['type_stats'] = $stats;

        $this->render('monitoring/equipment/index');
    }

    public function ajax_update_status() {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $this->Equipment_model->update(['id' => $id], ['status' => $status]);
        $this->json_response(['status' => true]);
    }
}
