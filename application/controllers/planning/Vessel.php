<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vessel extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('planning/vessel', 'can_view');
        $this->load->model('Vessel_model');
        $this->load->model('Planning_model');
    }

    public function index() {
        $this->data['page_title'] = 'Vessel Stowage Planning';
        
        $this->data['extra_js'] = [];

        // Get approved planning requests (ship calls)
        $this->db->select('p.id, p.request_no, v.vessel_name, p.voyage_in, p.voyage_out, p.status');
        $this->db->from('opr_planning_requests p');
        $this->db->join('mst_vessels v', 'v.id = p.vessel_id');
        $this->db->where_in('p.status', ['APPROVED', 'OPERATING']);
        $this->data['planning_requests'] = $this->db->get()->result();

        $this->render('planning/vessel/index');
    }

    public function get_vessel_stowage_data() {
        $planning_id = $this->input->get('planning_id');
        if (!$planning_id) {
            $this->json_response(['status' => 'error', 'message' => 'Planning ID required']);
            return;
        }

        $planning = $this->Planning_model->get_by_id($planning_id);
        if (!$planning) {
            $this->json_response(['status' => 'error', 'message' => 'Planning not found']);
            return;
        }
        
        $profile = $this->Planning_model->get_vessel_profile_by_planning_id($planning_id);
        if (!$profile) {
            $profile = (object)[
                'bay_count' => 14, 
                'row_count' => 8, 
                'tier_count_under_deck' => 4, 
                'tier_count_on_deck' => 4
            ];
        }

        // 1. Get containers currently PLANNED on vessel
        $manifest_on_vessel = $this->Planning_model->get_containers_on_vessel($planning_id);
        $containers = [];
        
        foreach ($manifest_on_vessel as $m) {
            $color = '#eab308'; // yellow (default for planned)
            if ($m->hz == 'Y') $color = '#dc2626'; // red
            if ($m->type == 'RF') $color = '#ffffff'; // white

            $containers[] = [
                'id' => $m->id,
                'bay' => $m->bay,
                'row' => $m->row,
                'tier' => $m->tier,
                'deck' => $m->deck,
                'container_no' => $m->container_no,
                'size' => $m->size,
                'type' => $m->type,
                'color' => $color,
                'pol' => 'IDJKT',
                'pod' => $m->pod
            ];
        }

        // 2. Get containers for the "Work List" sidebar based on Operation Type
        $unplanned = [];
        if ($planning->operation_type == 'DIS') {
            // For DISCHARGE: Show containers that are NOT YET PLANNED (assuming they are on ship but position unknown)
            // Or usually, for DIS, they are ALL planned first then "dropped"
            $unplanned = $this->Planning_model->get_unplanned_containers($planning_id);
        } else if ($planning->operation_type == 'LOD') {
            // For LOADING: Show containers that are in the YARD and NOT YET PLANNED on vessel
            $unplanned = $this->Planning_model->get_containers_to_load($planning_id);
        } else {
            // FOR VSL (Both): Mix logic (for now just all unplanned)
            $unplanned = $this->Planning_model->get_unplanned_containers($planning_id);
        }

        // Get planned equipments
        $equipments = $this->Planning_model->get_equipments_by_planning_id($planning_id);

        $this->json_response([
            'status' => 'success', 
            'operation_type' => $planning->operation_type,
            'profile' => [
                'bays' => (int)$profile->bay_count, 
                'rows' => (int)$profile->row_count, 
                'tiers_under' => (int)$profile->tier_count_under_deck, 
                'tiers_on' => (int)$profile->tier_count_on_deck
            ],
            'data' => $containers,
            'unplanned' => $unplanned,
            'equipments' => $equipments
        ]);
    }

    public function ajax_save_stowage() {
        $id = $this->input->post('id'); // manifest id
        $data = [
            'bay' => $this->input->post('bay'),
            'row' => $this->input->post('row'),
            'tier' => $this->input->post('tier'),
            'deck' => $this->input->post('deck')
        ];

        $update = $this->Planning_model->update_stowage($id, $data);
        
        if ($update) {
            $this->json_response(['status' => 'success', 'message' => 'Position updated']);
        } else {
            $this->json_response(['status' => 'error', 'message' => 'Failed to update position']);
        }
    }

    public function ajax_cancel_stowage() {
        $id = $this->input->post('id');
        $data = [
            'bay' => NULL,
            'row' => NULL,
            'tier' => NULL,
            'deck' => NULL
        ];

        $update = $this->Planning_model->update_stowage($id, $data);
        
        if ($update) {
            $this->json_response(['status' => 'success', 'message' => 'Placement cancelled']);
        } else {
            $this->json_response(['status' => 'error', 'message' => 'Failed to cancel placement']);
        }
    }

    public function ajax_start_operation() {
        $id = $this->input->post('planning_id');
        if (!$id) {
            $this->json_response(['status' => 'error', 'message' => 'Planning ID required']);
            return;
        }

        $data = [
            'status' => 'OPERATING',
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $id)->update('opr_planning_requests', $data);
        
        if ($this->db->affected_rows() > 0) {
            $this->json_response(['status' => 'success', 'message' => 'Vessel is now OPERATING']);
        } else {
            $this->json_response(['status' => 'error', 'message' => 'Failed to start operation or already operating']);
        }
    }
}
