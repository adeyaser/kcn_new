<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vessel extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('monitoring/vessel', 'can_view');
        $this->load->model('Planning_model');
        $this->load->model('Tally_model');
    }

    public function index() {
        $this->data['page_title'] = 'Live Vessel Monitoring';
        
        // Get vessels that are currently in OPERATING status
        $this->db->where('p.status', 'OPERATING');
        $this->data['operating_vessels'] = $this->Planning_model->get_datatables();
        
        $this->render('monitoring/vessel/index');
    }

    public function detail($id) {
        $this->data['page_title'] = 'Vessel Monitoring Detail';
        $this->data['vessel_plan'] = $this->Planning_model->get_by_id($id);
        
        if (!$this->data['vessel_plan']) {
            show_404();
        }

        // 1. Get Real Stats from Tally Activities
        $planning_id = $id;
        
        $this->db->where('planning_id', $planning_id);
        $total_manifest = $this->db->count_all_results('opr_manifests');

        $this->db->where(['planning_id' => $planning_id, 'activity_type' => 'DISCHARGE']);
        $discharged = $this->db->count_all_results('opr_tally_activities');

        $this->db->where(['planning_id' => $planning_id, 'activity_type' => 'LOAD']);
        $loaded = $this->db->count_all_results('opr_tally_activities');

        // 2. Get Recent Movements (Join with equipment)
        $this->db->select('t.*, e.equipment_code');
        $this->db->from('opr_tally_activities t');
        $this->db->join('mst_equipments e', 'e.id = t.equipment_id', 'left');
        $this->db->where('t.planning_id', $planning_id);
        $this->db->order_by('t.activity_time', 'DESC');
        $this->db->limit(10);
        $this->data['recent_movements'] = $this->db->get()->result();

        // 3. Calculate KPI (GCR & Productivity)
        $this->db->select_min('activity_time', 'start');
        $this->db->where('planning_id', $planning_id);
        $first_activity = $this->db->get('opr_tally_activities')->row();
        
        $commence = $first_activity && $first_activity->start ? $first_activity->start : null;
        $gcr = 0;
        $vprod = 0;

        if ($commence) {
            $start_time = strtotime($commence);
            $current_time = time();
            $hours = ($current_time - $start_time) / 3600;
            if ($hours < 0.1) $hours = 0.1; // Prevent div by zero or too high spike

            $total_moves = $discharged + $loaded;
            $vprod = round($total_moves / $hours, 1);
            
            // Assume 2 cranes if not defined to get a realistic GCR
            $cranes = $this->db->where('planning_id', $planning_id)->count_all_results('opr_planning_equipments') ?: 2;
            $gcr = round($vprod / $cranes, 1);
        }

        $this->data['stats'] = [
            'total_manifest' => $total_manifest,
            'discharged' => $discharged,
            'loaded' => $loaded,
            'gcr' => $gcr,
            'vessel_productivity' => $vprod,
            'commence_work' => $commence ? date('d/m/Y H:i', strtotime($commence)) : '-',
            'discharge_pct' => $total_manifest > 0 ? round(($discharged / $total_manifest) * 100) : 0,
            'load_pct' => $total_manifest > 0 ? round(($loaded / $total_manifest) * 100) : 0,
        ];

        // Fetch interruptions
        $this->data['interruptions'] = $this->db->where('vessel_id', $this->data['vessel_plan']->vessel_id)->get('opr_interruptions')->result();

        $this->render('monitoring/vessel/detail');
    }

    public function ajax_save_interruption() {
        $data = [
            'vessel_id' => $this->input->post('vessel_id'),
            'interruption_type' => $this->input->post('type'),
            'start_time' => $this->input->post('start_time'),
            'remarks' => $this->input->post('remarks'),
            'created_by' => $this->session->userdata('user_id')
        ];
        $this->db->insert('opr_interruptions', $data);
        $this->json_response(['status' => true]);
    }
}
