<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {

    public function get_stats() {
        // Return dummy data for now
        return [
            'total_vessels' => 12,
            'active_vessels' => 3,
            'container_in' => 450,
            'container_out' => 380,
            'trucks_active' => 45,
            'yard_occupancy' => 65
        ];
    }

    public function get_active_vessels() {
        $this->db->select('s.*, v.vessel_name as name, v.loa, b.berth_name, b.coordinate_polygon');
        $this->db->from('opr_vessel_schedules s');
        $this->db->join('mst_vessels v', 's.vessel_id = v.id');
        $this->db->join('mst_berths b', 's.berth_id = b.id', 'left');
        $this->db->where_in('s.status', ['ARRIVED', 'BERTHED']);
        return $this->db->get()->result_array();
    }

    public function get_recent_activities() {
        return [
            ['time' => '10:45', 'desc' => 'Container CMAU1234567 Gated In', 'type' => 'gate_in'],
            ['time' => '10:30', 'desc' => 'Lift On Container TGHU7654321', 'type' => 'lift_on'],
            ['time' => '10:15', 'desc' => 'MV. OCEAN NAVIGATOR started discharging', 'type' => 'vessel'],
            ['time' => '09:50', 'desc' => 'Truck B 1234 CD registered in TCA', 'type' => 'truck'],
        ];
    }

    public function get_trt_cycles($vessel_id) {
        // Dummy TRT Data (Turn Around Time / Truck Round Trip)
        $labels = [];
        $data = [];
        for ($i=1; $i<=10; $i++) {
            $labels[] = "Cycle $i";
            $data[] = rand(15, 45); // TRT in minutes
        }
        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'TRT (Minutes)',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59,130,246,0.2)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4
                ]
            ]
        ];
    }
}
