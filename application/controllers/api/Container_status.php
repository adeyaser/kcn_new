<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Container_status extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Simple API Key simulation
        $api_key = $this->input->get_request_header('X-API-KEY');
        if ($api_key !== 'KCN-SECRET-2026') {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
            exit;
        }
    }

    public function get($container_no) {
        $this->db->select('container_no, size, type, status, last_position, updated_at');
        $this->db->where('container_no', $container_no);
        $container = $this->db->get('mst_containers')->row();

        header('Content-Type: application/json');
        if ($container) {
            echo json_encode(['status' => 'success', 'data' => $container]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Container not found.']);
        }
    }

    public function history($container_no) {
        // Return simplified history for external tracking
        $this->db->select('gate_in_time, gate_out_time, activity_type, status');
        $this->db->where('container_no', $container_no);
        $this->db->order_by('gate_in_time', 'DESC');
        $history = $this->db->get('opr_gate_transactions')->result();

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $history]);
    }
}
