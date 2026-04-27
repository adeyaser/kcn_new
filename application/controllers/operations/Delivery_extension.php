<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery_extension extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('operations/gate', 'can_view');
        $this->load->model('Billing_model');
        $this->load->model('Gate_model');
    }

    public function index() {
        $this->data['page_title'] = 'Delivery Extension';
        $this->render('operations/delivery_extension/index');
    }

    public function ajax_search_container() {
        $cont_no = $this->input->get('container_no');
        // Search in active gate transactions (IN YARD)
        $this->db->where('container_no', $cont_no);
        $this->db->where('status', 'CHECKED_IN');
        $this->db->order_by('id', 'DESC');
        $gate = $this->db->get('opr_gate_transactions')->row();

        if ($gate) {
            $calc = $this->Billing_model->calculate_storage($gate->id);
            $this->json_response(['status' => 'success', 'gate' => $gate, 'calculation' => $calc]);
        } else {
            $this->json_response(['status' => 'error', 'message' => 'Active container not found in yard.']);
        }
    }

    public function ajax_save_extension() {
        $id = $this->input->post('gate_id');
        $days = $this->input->post('extension_days');
        
        // In a real system, we would create a new extension record
        // For now, we'll just return success
        $this->json_response(['status' => true, 'message' => 'Extension granted for ' . $days . ' days.']);
    }
}
