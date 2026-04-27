<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lift_model extends CI_Model {

    public function get_pending_lifts() {
        // Trucks that have performed Gate In but NOT yet Lifted
        // For simplicity, we assume status 'GATE_IN' means waiting for lift
        $this->db->select('t.*');
        $this->db->from('opr_gate_transactions t');
        $this->db->where('t.status', 'GATE_IN');
        $this->db->order_by('t.gate_in_time', 'ASC');
        return $this->db->get()->result();
    }

    public function get_recent_activities($limit = 10) {
        $this->db->select('l.*, e.equipment_code');
        $this->db->from('opr_lift_activities l');
        $this->db->join('mst_equipments e', 'e.id = l.equipment_id', 'left');
        $this->db->order_by('l.activity_time', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function save_activity($data) {
        $this->db->trans_start();
        
        // 1. Insert activity
        $this->db->insert('opr_lift_activities', $data);
        
        // 2. Update gate transaction status
        if (isset($data['gate_transaction_id'])) {
            $this->db->where('id', $data['gate_transaction_id']);
            $this->db->update('opr_gate_transactions', ['status' => 'LIFTED']);
        }
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_stats() {
        $today = date('Y-m-d');
        return [
            'total_today' => $this->db->where('DATE(activity_time)', $today)->count_all_results('opr_lift_activities'),
            'active_equipment' => $this->db->where('is_active', 1)->count_all_results('mst_equipments')
        ];
    }
}
