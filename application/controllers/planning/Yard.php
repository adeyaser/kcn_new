<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('planning/yard', 'can_view');
        $this->load->model('Vessel_model');
        $this->load->model('Planning_model');
    }

    public function index() {
        $this->data['page_title'] = 'Yard Stowage Planning';
        
        $this->data['extra_js'] = [
            'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js',
            'https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js',
            'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js',
            'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js'
        ];

        // Get approved planning requests (ship calls)
        $this->db->select('p.id, p.request_no, v.vessel_name, p.voyage_in, p.voyage_out');
        $this->db->from('opr_planning_requests p');
        $this->db->join('mst_vessels v', 'v.id = p.vessel_id');
        $this->db->where_in('p.status', ['APPROVED', 'OPERATING']);
        $this->data['planning_requests'] = $this->db->get()->result();

        // Real Yard Blocks from Database
        $this->data['yard_blocks'] = $this->db->where('is_active', 1)->get('mst_yard_blocks')->result();

        $this->render('planning/yard/index');
    }

    public function get_block_data() {
        $block_id = $this->input->get('block_id');
        $planning_id = $this->input->get('planning_id');
        
        $profile_row = $this->db->where('id', $block_id)->get('mst_yard_blocks')->row();
        if (!$profile_row) {
            $this->json_response(['status' => 'error', 'message' => 'Block not found']);
            return;
        }

        $profile = [
            'id' => $profile_row->id,
            'name' => $profile_row->block_name,
            'bays' => $profile_row->max_bay,
            'rows' => $profile_row->max_row,
            'tiers' => $profile_row->max_tier
        ];
        
        $containers = $this->db->where('block_id', $block_id)->get('opr_yard_inventory')->result();
        
        // Get unplanned containers for yard
        $unplanned = [];
        if($planning_id) {
            $unplanned = $this->Planning_model->get_unplanned_yard_containers($planning_id);
        }

        // Get planned equipments
        $equipments = [];
        if($planning_id) {
            $equipments = $this->Planning_model->get_equipments_by_planning_id($planning_id);
        }

        $this->json_response([
            'status' => 'success', 
            'profile' => $profile,
            'data' => $containers,
            'unplanned' => $unplanned,
            'equipments' => $equipments
        ]);
    }

    public function ajax_save_yard_stowage() {
        $manifest_id = $this->input->post('id');
        $bay = $this->input->post('bay');
        $row = $this->input->post('row');
        $tier = $this->input->post('tier');
        $block_id = $this->input->post('block');

        // Fetch manifest to get container_no
        $manifest = $this->db->where('id', $manifest_id)->get('opr_manifests')->row();
        if (!$manifest) {
            $this->json_response(['status' => 'error', 'message' => 'Manifest not found']);
            return;
        }

        $data = [
            'block_id' => $block_id,
            'bay' => $bay,
            'row' => $row,
            'tier' => $tier,
            'container_no' => $manifest->container_no,
            'last_update' => date('Y-m-d H:i:s')
        ];

        // Upsert into opr_yard_inventory
        $this->db->where('container_no', $manifest->container_no);
        $exists = $this->db->get('opr_yard_inventory')->row();

        if ($exists) {
            $this->db->where('id', $exists->id);
            $this->db->update('opr_yard_inventory', $data);
        } else {
            $this->db->insert('opr_yard_inventory', $data);
        }

        $this->json_response(['status' => 'success', 'message' => 'Container planned to yard position']);
    }

    public function ajax_cancel_yard_stowage() {
        $manifest_id = $this->input->post('id');
        
        $manifest = $this->db->where('id', $manifest_id)->get('opr_manifests')->row();
        if ($manifest) {
            $this->db->where('container_no', $manifest->container_no);
            $this->db->delete('opr_yard_inventory');
        }
        
        $this->json_response(['status' => 'success', 'message' => 'Yard placement cancelled']);
    }
}
