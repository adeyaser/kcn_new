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

        $this->db->select('p.id, p.request_no, v.vessel_name, p.voyage_in, p.voyage_out, p.operation_type');
        $this->db->from('opr_planning_requests p');
        $this->db->join('mst_vessels v', 'v.id = p.vessel_id');
        $this->db->where_in('p.status', ['APPROVED', 'OPERATING']);
        $this->data['planning_requests'] = $this->db->get()->result();

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
        
        // 1. Get REAL inventory (joined with manifest for details)
        $this->db->select('i.*, m.type, m.size, m.pod');
        $this->db->from('opr_yard_inventory i');
        $this->db->join('opr_manifests m', 'm.container_no = i.container_no', 'left');
        $this->db->where('i.block_id', $block_id);
        $real_inventory = $this->db->get()->result();
        
        // 2. Get PLANNED inventory from manifest
        $this->db->select('m.*, p.request_no');
        $this->db->from('opr_manifests m');
        $this->db->join('opr_planning_requests p', 'p.id = m.planning_id');
        $this->db->where('m.planned_block_id', $block_id);
        $planned_inventory = $this->db->get()->result();
        
        // Combine them
        $all_data = [];
        foreach($real_inventory as $r) {
            $all_data[] = [
                'id' => $r->id,
                'container_no' => $r->container_no,
                'bay' => $r->bay,
                'row' => $r->row,
                'tier' => $r->tier,
                'type' => $r->type ?? 'GP',
                'size' => $r->size ?? '20',
                'pod' => $r->pod ?? '-',
                'status' => 'PRESENT',
                'color' => '#1e293b' // Dark for real
            ];
        }
        foreach($planned_inventory as $p) {
            // Only add if not already present in real inventory (to avoid duplicates)
            $exists = false;
            foreach($real_inventory as $r) {
                if($r->container_no == $p->container_no) { $exists = true; break; }
            }
            
            if(!$exists) {
                $all_data[] = [
                    'id' => $p->id,
                    'container_no' => $p->container_no,
                    'bay' => $p->planned_bay,
                    'row' => $p->planned_row,
                    'tier' => $p->planned_tier,
                    'status' => 'PLANNED',
                    'request_no' => $p->request_no,
                    'color' => '#3b82f6' // Blue for planned
                ];
            }
        }
        
        // Get unplanned containers for yard
        $unplanned = [];
        if($planning_id) {
            // For Yard Planning, we want containers in manifest that DON'T HAVE planned_block_id AND DON'T HAVE REAL INVENTORY
            $this->db->select('m.*');
            $this->db->from('opr_manifests m');
            $this->db->join('opr_yard_inventory i', 'i.container_no = m.container_no', 'left');
            $this->db->where('m.planning_id', $planning_id);
            $this->db->where('m.planned_block_id IS NULL');
            $this->db->where('i.container_no IS NULL');
            $unplanned = $this->db->get()->result();
        }

        $this->json_response([
            'status' => 'success', 
            'profile' => $profile,
            'data' => $all_data,
            'unplanned' => $unplanned
        ]);
    }

    public function ajax_save_yard_stowage() {
        $manifest_id = $this->input->post('id');
        $bay = $this->input->post('bay');
        $row = $this->input->post('row');
        $tier = $this->input->post('tier');
        $block_id = $this->input->post('block');

        $data = [
            'planned_block_id' => $block_id,
            'planned_bay' => $bay,
            'planned_row' => $row,
            'planned_tier' => $tier
        ];

        $this->db->where('id', $manifest_id);
        $update = $this->db->update('opr_manifests', $data);

        if ($update) {
            $this->json_response(['status' => 'success', 'message' => 'Yard pre-planning saved']);
        } else {
            $this->json_response(['status' => 'error', 'message' => 'Failed to save planning']);
        }
    }

    public function ajax_cancel_yard_stowage() {
        $id = $this->input->post('id'); // manifest id
        
        $data = [
            'planned_block_id' => NULL,
            'planned_bay' => NULL,
            'planned_row' => NULL,
            'planned_tier' => NULL
        ];

        $this->db->where('id', $id);
        $this->db->update('opr_manifests', $data);
        
        $this->json_response(['status' => 'success', 'message' => 'Yard planning cancelled']);
    }
}
