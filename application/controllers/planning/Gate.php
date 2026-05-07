<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gate extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('planning/gate', 'can_view');
        $this->load->model('Planning_model');
        $this->load->model('Gate_model');
    }

    public function index() {
        $this->data['page_title'] = 'Receiving & Delivery Planning';
        
        $this->data['extra_css'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css'
        ];
        $this->data['extra_js'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
        ];

        $this->db->select('p.*, v.vessel_name');
        $this->db->from('opr_planning_requests p');
        $this->db->join('mst_vessels v', 'v.id = p.vessel_id');
        $this->db->where_in('p.status', ['APPROVED', 'OPERATING']);
        $this->data['plannings'] = $this->db->get()->result();

        $this->data['gates'] = $this->db->get('mst_gates')->result();
        
        $this->render('planning/gate/index');
    }

    public function get_manifest_gate_data() {
        $planning_id = $this->input->get('planning_id');
        
        $this->db->select('m.*, t.assignment_no, tr.police_number, t.estimated_arrival, t.status as tca_status, t.truck_id');
        $this->db->from('opr_manifests m');
        $this->db->join('opr_tca_assignments t', 't.manifest_id = m.id', 'left');
        $this->db->join('mst_trucks tr', 'tr.id = t.truck_id', 'left');
        $this->db->where('m.planning_id', $planning_id);
        $data = $this->db->get()->result();
        
        $this->json_response(['status' => 'success', 'data' => $data]);
    }

    public function ajax_save_bulk_assignment() {
        $planning_id = $this->input->post('planning_id');
        $assignments = $this->input->post('assignments'); // Array of {manifest_id, est_arrival, gate_id}
        
        $count = 0;
        foreach($assignments as $a) {
            if(empty($a['est_arrival']) && empty($a['truck_id'])) continue;
            
            // Check if already exists
            $exists = $this->db->where('manifest_id', $a['manifest_id'])->get('opr_tca_assignments')->row();
            
            $data = [
                'planning_id' => $planning_id,
                'manifest_id' => $a['manifest_id'],
                'gate_id' => $a['gate_id'],
                'truck_id' => !empty($a['truck_id']) ? $a['truck_id'] : ($exists ? $exists->truck_id : NULL),
                'estimated_arrival' => !empty($a['est_arrival']) ? $a['est_arrival'] : ($exists ? $exists->estimated_arrival : date('Y-m-d H:i:s')),
                'status' => 'PLANNED',
                'created_by' => $this->session->userdata('user_id')
            ];
            
            if($exists) {
                $this->db->where('id', $exists->id)->update('opr_tca_assignments', $data);
            } else {
                $data['assignment_no'] = 'TCA-' . date('Ymd') . '-' . sprintf('%04d', rand(1, 9999));
                $data['qr_code_token'] = bin2hex(random_bytes(10));
                $this->db->insert('opr_tca_assignments', $data);
            }
            $count++;
        }
        
        $this->json_response(['status' => 'success', 'message' => "$count assignments updated"]);
    }
}
