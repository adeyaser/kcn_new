<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trace extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('master/vessel', 'can_view');
    }

    public function index() {
        $this->data['page_title'] = 'Container Trace & Track';
        $this->render('monitoring/trace/index');
    }

    public function ajax_trace() {
        $cont_no = $this->input->get('container_no');
        if (!$cont_no) {
            $this->json_response(['status' => 'error', 'message' => 'Container number is required.']);
            return;
        }
        
        // 1. Get Planning/Manifest History
        $this->db->select('m.*, pr.request_no, pr.request_type, pr.voyage_in, v.vessel_name, pr.created_at as planned_at');
        $this->db->from('opr_manifests m');
        $this->db->join('opr_planning_requests pr', 'pr.id = m.planning_id', 'left');
        $this->db->join('mst_vessels v', 'v.id = pr.vessel_id', 'left');
        $this->db->where('m.container_no', $cont_no);
        $this->db->order_by('m.id', 'DESC');
        $manifest_history = $this->db->get()->result();

        // 2. Get Gate History
        $this->db->where('container_no', $cont_no);
        $this->db->order_by('gate_in_time', 'DESC');
        $gate_history = $this->db->get('opr_gate_transactions')->result();
        
        // 3. Get Tally History
        $this->db->select('t.*, v.vessel_name, e.equipment_code');
        $this->db->from('opr_tally_activities t');
        $this->db->join('mst_vessels v', 'v.id = t.vessel_id', 'left');
        $this->db->join('mst_equipments e', 'e.id = t.equipment_id', 'left');
        $this->db->where('t.container_no', $cont_no);
        $this->db->order_by('t.activity_time', 'DESC');
        $tally_history = $this->db->get()->result();

        if (empty($gate_history) && empty($tally_history) && empty($manifest_history)) {
            $this->json_response(['status' => 'error', 'message' => 'No history found for this container.']);
        } else {
            $this->json_response([
                'status' => 'success', 
                'manifest' => $manifest_history,
                'gate' => $gate_history, 
                'tally' => $tally_history
            ]);
        }
    }
}
