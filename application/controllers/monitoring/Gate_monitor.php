<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gate_monitor extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('monitoring/gate_monitor', 'can_view');
    }

    public function index() {
        $this->data['page_title'] = 'Gate Transaction Monitor';

        $today = date('Y-m-d');

        $this->data['stats'] = [
            'receiving_in'   => $this->db->where('activity_type', 'RECEIVING')->where('DATE(gate_in_time)', $today)->count_all_results('opr_gate_transactions'),
            'delivery_out'   => $this->db->where('activity_type', 'DELIVERY')->where('DATE(gate_out_time)', $today)->count_all_results('opr_gate_transactions'),
            'in_yard'        => $this->db->where('status', 'IN_YARD')->count_all_results('opr_gate_transactions'),
            'pending_tca'    => $this->db->where('status', 'PLANNED')->count_all_results('opr_tca_assignments'),
        ];

        $this->db->select('p.id, p.request_no, v.vessel_name, p.operation_type, p.eta');
        $this->db->from('opr_planning_requests p');
        $this->db->join('mst_vessels v', 'v.id = p.vessel_id', 'left');
        $this->db->where_in('p.status', ['APPROVED', 'OPERATING']);
        $this->data['plannings'] = $this->db->get()->result();

        $this->data['extra_css'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css'
        ];
        $this->data['extra_js'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
        ];

        $this->render('monitoring/gate_monitor/index');
    }

    public function ajax_list() {
        ob_start();
        $type     = $this->input->post('activity_type') ?: '';
        $status   = $this->input->post('status') ?: '';
        $date     = $this->input->post('date') ?: '';
        $planning = $this->input->post('planning_id') ?: '';

        $this->db->select('g.*, t.police_number as truck_plate, t.driver_name, pr.request_no, v.vessel_name');
        $this->db->from('opr_gate_transactions g');
        $this->db->join('mst_trucks t', 't.id = g.truck_id', 'left');
        $this->db->join('opr_planning_requests pr', 'pr.id = g.planning_id', 'left');
        $this->db->join('mst_vessels v', 'v.id = pr.vessel_id', 'left');

        if ($type)     $this->db->where('g.activity_type', $type);
        if ($status)   $this->db->where('g.status', $status);
        if ($date)     $this->db->where('DATE(g.gate_in_time)', $date);
        if ($planning) $this->db->where('g.planning_id', $planning);

        $this->db->order_by('g.id', 'DESC');
        $records = $this->db->get()->result();

        $data = [];
        foreach ($records as $i => $r) {
            $status_map = [
                'CHECKED_IN'  => ['bg' => '#3b82f6', 'label' => 'GATE IN'],
                'IN_YARD'     => ['bg' => '#f59e0b', 'label' => 'IN YARD'],
                'CHECKED_OUT' => ['bg' => '#10b981', 'label' => 'GATE OUT'],
                'CANCELLED'   => ['bg' => '#ef4444', 'label' => 'CANCELLED'],
            ];
            $s = $status_map[$r->status] ?? ['bg' => '#64748b', 'label' => $r->status];

            $type_map = [
                'RECEIVING'  => ['bg' => '#3b82f6', 'label' => 'RECEIVING'],
                'DELIVERY'   => ['bg' => '#8b5cf6', 'label' => 'DELIVERY'],
                'EMPTY_IN'   => ['bg' => '#64748b', 'label' => 'EMPTY IN'],
                'EMPTY_OUT'  => ['bg' => '#64748b', 'label' => 'EMPTY OUT'],
            ];
            $at = $type_map[$r->activity_type] ?? ['bg' => '#64748b', 'label' => $r->activity_type];

            $duration = '-';
            if ($r->gate_in_time && $r->gate_out_time) {
                $diff = (strtotime($r->gate_out_time) - strtotime($r->gate_in_time)) / 60;
                $duration = round($diff) . ' min';
            } elseif ($r->gate_in_time) {
                $diff = (time() - strtotime($r->gate_in_time)) / 60;
                $duration = round($diff) . ' min (ongoing)';
            }

            $data[] = [
                'no'       => $i + 1,
                'gate_no'  => $r->gate_no,
                'container'=> $r->container_no,
                'size_type'=> $r->container_size . '\' ' . $r->container_type,
                'truck'    => $r->truck_plate ?? $r->police_number,
                'driver'   => $r->driver_name ?? '-',
                'activity' => '<span class="badge px-2" style="background:' . $at['bg'] . '20;border:1px solid ' . $at['bg'] . '40;color:' . $at['bg'] . ';border-radius:6px;">' . $at['label'] . '</span>',
                'planning' => $r->request_no ? ($r->request_no . '<br><small class="text-muted">' . ($r->vessel_name ?? '') . '</small>') : '<span class="text-muted">-</span>',
                'gate_in'  => $r->gate_in_time  ? date('d/m H:i', strtotime($r->gate_in_time))  : '-',
                'gate_out' => $r->gate_out_time ? date('d/m H:i', strtotime($r->gate_out_time)) : '-',
                'duration' => $duration,
                'status'   => '<span class="badge px-2 py-1" style="background:' . $s['bg'] . '20;border:1px solid ' . $s['bg'] . '40;color:' . $s['bg'] . ';border-radius:6px;">' . $s['label'] . '</span>',
            ];
        }

        ob_clean();
        $this->json_response(['status' => 'success', 'data' => $data, 'total' => count($data)]);
    }

    public function ajax_stats() {
        $today = date('Y-m-d');
        $stats = [
            'receiving_today' => $this->db->where('activity_type', 'RECEIVING')->where('DATE(gate_in_time)', $today)->count_all_results('opr_gate_transactions'),
            'delivery_today'  => $this->db->where('activity_type', 'DELIVERY')->where('DATE(gate_in_time)', $today)->count_all_results('opr_gate_transactions'),
            'in_yard'         => $this->db->where('status', 'IN_YARD')->count_all_results('opr_gate_transactions'),
            'checked_out'     => $this->db->where('status', 'CHECKED_OUT')->where('DATE(gate_out_time)', $today)->count_all_results('opr_gate_transactions'),
            'pending_tca'     => $this->db->where('status', 'PLANNED')->count_all_results('opr_tca_assignments'),
        ];
        $this->json_response($stats);
    }
}
