<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stacking extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('operations/stacking', 'can_view');
        $this->load->model('Gate_model');
        $this->load->model('Yard_model');
    }

    public function index() {
        $this->data['page_title'] = 'Container Stacking Operations';
        $this->data['blocks'] = $this->db->get('mst_yard_blocks')->result(); // Fetch all blocks safely
        
        $this->data['extra_css'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css'
        ];
        $this->data['extra_js'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
        ];

        $this->render('operations/stacking/index');
    }

    public function ajax_search_container() {
        $term = $this->input->get('term');
        
        // Find containers that are GATED_IN but not yet STACKED
        // For simplicity, we search in opr_gate_transactions
        $this->db->select('g.container_no, g.id as gate_id, m.size, m.type, pr.request_no');
        $this->db->from('opr_gate_transactions g');
        $this->db->join('opr_manifests m', 'm.container_no = g.container_no', 'left');
        $this->db->join('opr_planning_requests pr', 'pr.id = g.planning_id', 'left');
        $this->db->where('g.status', 'GATED_IN');
        $this->db->where('pr.loosing_type', 'TRUCK_NON_LOOSING');
        if($term) {
            $this->db->like('g.container_no', $term);
        }
        $this->db->limit(10);
        $query = $this->db->get();
        
        $results = [];
        foreach($query->result() as $row) {
            $results[] = [
                'id' => $row->container_no,
                'text' => $row->container_no . ' (' . $row->size . ' / ' . $row->type . ') - ' . $row->request_no,
                'size' => $row->size,
                'type' => $row->type,
                'request_no' => $row->request_no
            ];
        }
        
        echo json_encode($results);
    }

    public function ajax_save() {
        $this->check_permission('operations/stacking', 'can_create');
        
        $container_no = $this->input->post('container_no');
        $block_id = $this->input->post('block_id');
        $bay = $this->input->post('bay');
        $row = $this->input->post('row');
        $tier = $this->input->post('tier');
        $reason = $this->input->post('reason');
        
        $this->db->trans_start();
        
        // 1. Insert into yard inventory
        $inv_data = [
            'block_id' => $block_id,
            'bay' => $bay,
            'row' => $row,
            'tier' => $tier,
            'container_no' => $container_no
        ];
        $this->db->insert('opr_yard_inventory', $inv_data);
        
        // 2. Update gate transaction status
        $this->db->where('container_no', $container_no);
        $this->db->where('status', 'GATED_IN');
        $this->db->update('opr_gate_transactions', ['status' => 'STACKED']);
        
        // 3. Update manifest status
        $this->db->where('container_no', $container_no);
        $this->db->update('opr_manifests', ['status' => 'STACKED']);
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            $this->json_response(['status' => false, 'message' => 'Failed to save movement']);
        } else {
            $this->json_response(['status' => true, 'message' => 'Stacking movement recorded successfully']);
        }
    }
}
