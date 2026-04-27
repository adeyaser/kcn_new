<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_model extends CI_Model {

    // Default Rates (Simulation)
    private $rates = [
        '20' => 30000, // IDR per day for 20ft
        '40' => 60000, // IDR per day for 40ft
        '45' => 75000  // IDR per day for 45ft
    ];

    private $free_days = 3; // Standard free storage days

    public function __construct() {
        parent::__construct();
    }

    public function calculate_storage($gate_id) {
        $this->db->from('opr_gate_transactions');
        $this->db->where('id', $gate_id);
        $gate = $this->db->get()->row();

        if (!$gate || !$gate->gate_in_time) return null;

        $start_date = new DateTime($gate->gate_in_time);
        $end_date = $gate->gate_out_time ? new DateTime($gate->gate_out_time) : new DateTime(); // Use current time if not gate out yet

        $interval = $start_date->diff($end_date);
        $total_days = $interval->days + 1; // Include start day

        $chargeable_days = max(0, $total_days - $this->free_days);
        
        $rate = isset($this->rates[$gate->container_size]) ? $this->rates[$gate->container_size] : 30000;
        $total_amount = $chargeable_days * $rate;

        return [
            'total_days' => $total_days,
            'free_days' => $this->free_days,
            'chargeable_days' => $chargeable_days,
            'rate_per_day' => $rate,
            'total_amount' => $total_amount,
            'currency' => 'IDR'
        ];
    }

    public function get_billable_containers() {
        // Fetch containers that have gate in but not necessarily gate out
        $this->db->from('opr_gate_transactions');
        $this->db->where('activity_type', 'DELIVERY'); // Delivery containers are most relevant for billing
        $this->db->order_by('gate_in_time', 'DESC');
        return $this->db->get()->result();
    }
}
