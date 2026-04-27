<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vessel_log extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('monitoring/vessel', 'can_view');
        $this->load->model('Schedule_model');
    }

    public function index() {
        $this->data['page_title'] = 'Vessel Movement Log (Book)';
        
        // Fetch historical and active movements
        $this->db->select('s.*, v.vessel_name, b.berth_name');
        $this->db->from('opr_vessel_schedules s');
        $this->db->join('mst_vessels v', 'v.id = s.vessel_id');
        $this->db->join('mst_berths b', 'b.id = s.berth_id', 'left');
        $this->db->order_by('s.eta', 'DESC');
        $this->data['logs'] = $this->db->get()->result();

        $this->render('monitoring/vessel_log/index');
    }

    public function update_status() {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $field = '';
        
        if($status == 'ARRIVED') $field = 'eta'; // Usually we'd have ATA
        if($status == 'BERTHED') $field = 'etb'; // Usually ATB
        if($status == 'DEPARTED') $field = 'etd'; // Usually ATD
        
        $data = ['status' => $status];
        if($field) $data[$field] = date('Y-m-d H:i:s');

        $this->Schedule_model->update(['id' => $id], $data);
        $this->json_response(['status' => true]);
    }
}
