<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lift extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('operations/lift', 'can_view');
        $this->load->model('Lift_model');
        $this->load->model('Equipment_model');
    }

    public function index() {
        $this->data['page_title'] = 'Lift On / Lift Off Operations';
        
        // Trucks that have performed Gate In but NOT yet Lifted
        $this->db->select('t.*, i.block_id as planned_block, i.bay as planned_bay, i.row as planned_row, i.tier as planned_tier');
        $this->db->from('opr_gate_transactions t');
        $this->db->join('opr_yard_inventory i', 'i.container_no = t.container_no', 'left');
        $this->db->where('t.status', 'GATE_IN');
        $this->db->order_by('t.gate_in_time', 'ASC');
        $this->data['pending_lifts'] = $this->db->get()->result();
        $this->data['recent_activities'] = $this->Lift_model->get_recent_activities();
        $this->data['equipments'] = $this->db->where('is_active', 1)->get('mst_equipments')->result();
        $this->data['stats'] = $this->Lift_model->get_stats();
        
        $this->render('operations/lift/index');
    }

    public function ajax_save() {
        $gate_id = $this->input->post('gate_transaction_id');
        
        // Fetch gate info to get container_no
        $gate = $this->db->where('id', $gate_id)->get('opr_gate_transactions')->row();
        
        if (!$gate) {
            $this->json_response(['status' => false, 'message' => 'Gate transaction not found']);
            return;
        }

        $data = [
            'gate_transaction_id' => $gate_id,
            'activity_type' => $gate->transaction_type == 'IN' ? 'LIFT OFF' : 'LIFT ON', // Receiving = Lift Off, Delivery = Lift On
            'container_no' => $gate->container_no,
            'equipment_id' => $this->input->post('equipment_id'),
            'operator_id' => $this->session->userdata('user_id'),
            'location_block' => strtoupper($this->input->post('block')),
            'location_slot' => $this->input->post('slot'),
            'location_row' => $this->input->post('row'),
            'location_tier' => $this->input->post('tier'),
            'activity_time' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($this->Lift_model->save_activity($data)) {
            $this->json_response(['status' => true, 'message' => 'Operation recorded successfully']);
        } else {
            $this->json_response(['status' => false, 'message' => 'Failed to record operation']);
        }
    }
}
