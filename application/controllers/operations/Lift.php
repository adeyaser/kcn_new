<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lift extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('operations/lift', 'can_view');
        $this->load->model('Lift_model');
    }

    public function index() {
        $this->data['page_title'] = 'Lift On / Lift Off Operations';

        // Trucks that have Gate-In (CHECKED_IN) but not yet processed (no lift record)
        $this->db->select('g.*, tk.police_number, tk.driver_name, pr.request_no, v.vessel_name');
        $this->db->from('opr_gate_transactions g');
        $this->db->join('mst_trucks tk', 'tk.id = g.truck_id', 'left');
        $this->db->join('opr_planning_requests pr', 'pr.id = g.planning_id', 'left');
        $this->db->join('mst_vessels v', 'v.id = pr.vessel_id', 'left');
        $this->db->join('opr_lift_activities la', 'la.gate_transaction_id = g.id', 'left');
        $this->db->where('g.status', 'CHECKED_IN');
        $this->db->where('la.id IS NULL', null, false); // Not yet lifted
        $this->db->order_by('g.gate_in_time', 'ASC');
        $this->data['pending_lifts'] = $this->db->get()->result();

        // Recent lift activities
        $this->db->select('la.*, e.equipment_code, e.equipment_name');
        $this->db->from('opr_lift_activities la');
        $this->db->join('mst_equipments e', 'e.id = la.equipment_id', 'left');
        $this->db->order_by('la.activity_time', 'DESC');
        $this->db->limit(15);
        $this->data['recent_activities'] = $this->db->get()->result();

        // Stats today
        $today = date('Y-m-d');
        $this->data['stats'] = [
            'total_today'  => $this->db->where('DATE(activity_time)', $today)->count_all_results('opr_lift_activities'),
            'lift_on'      => $this->db->where('DATE(activity_time)', $today)->where('activity_type', 'LIFT ON')->count_all_results('opr_lift_activities'),
            'lift_off'     => $this->db->where('DATE(activity_time)', $today)->where('activity_type', 'LIFT OFF')->count_all_results('opr_lift_activities'),
            'pending'      => count($this->data['pending_lifts']),
        ];

        $this->data['equipments'] = $this->db->where('is_active', 1)->where('equipment_type', 'RTG')->get('mst_equipments')->result();
        if (empty($this->data['equipments'])) {
            $this->data['equipments'] = $this->db->where('is_active', 1)->get('mst_equipments')->result();
        }

        $this->render('operations/lift/index');
    }

    public function ajax_save() {
        $this->check_permission('operations/lift', 'can_create');
        $gate_id = $this->input->post('gate_transaction_id');

        $gate = $this->db->where('id', $gate_id)->get('opr_gate_transactions')->row();
        if (!$gate) {
            $this->json_response(['status' => false, 'message' => 'Gate transaction not found']);
            return;
        }

        // Determine lift type from activity_type field
        // RECEIVING = truck bringing container IN = LIFT OFF (container lifted off truck to yard)
        // DELIVERY  = truck picking container UP  = LIFT ON  (container lifted on to truck from yard)
        $lift_type = ($gate->activity_type == 'RECEIVING') ? 'LIFT OFF' : 'LIFT ON';

        $data = [
            'gate_transaction_id' => $gate_id,
            'activity_type'       => $lift_type,
            'container_no'        => $gate->container_no,
            'equipment_id'        => $this->input->post('equipment_id'),
            'operator_id'         => $this->session->userdata('user_id'),
            'location_block'      => strtoupper($this->input->post('block')),
            'location_slot'       => $this->input->post('slot'),
            'location_row'        => $this->input->post('row'),
            'location_tier'       => $this->input->post('tier'),
            'activity_time'       => date('Y-m-d H:i:s'),
        ];

        $this->db->insert('opr_lift_activities', $data);

        if ($this->db->affected_rows() > 0) {
            // Update gate transaction status to IN_YARD
            $this->db->where('id', $gate_id)->update('opr_gate_transactions', ['status' => 'IN_YARD']);

            // Update or insert yard inventory
            if ($lift_type == 'LIFT OFF') {
                $existing = $this->db->where('container_no', $gate->container_no)->get('opr_yard_inventory')->row();
                $yard_data = [
                    'container_no' => $gate->container_no,
                    'block_id'     => strtoupper($this->input->post('block')),
                    'bay'          => $this->input->post('slot'),
                    'row'          => $this->input->post('row'),
                    'tier'         => $this->input->post('tier'),
                    'status'       => 'YARD',
                    'updated_at'   => date('Y-m-d H:i:s'),
                ];
                if ($existing) {
                    $this->db->where('container_no', $gate->container_no)->update('opr_yard_inventory', $yard_data);
                } else {
                    $yard_data['created_at'] = date('Y-m-d H:i:s');
                    $this->db->insert('opr_yard_inventory', $yard_data);
                }
            }

            $this->json_response(['status' => true, 'message' => $lift_type . ' recorded. Gate status updated to IN YARD.']);
        } else {
            $this->json_response(['status' => false, 'message' => 'Failed to record operation']);
        }
    }

    public function ajax_stats() {
        $today = date('Y-m-d');
        $this->json_response([
            'total_today' => $this->db->where('DATE(activity_time)', $today)->count_all_results('opr_lift_activities'),
            'lift_on'     => $this->db->where('DATE(activity_time)', $today)->where('activity_type', 'LIFT ON')->count_all_results('opr_lift_activities'),
            'lift_off'    => $this->db->where('DATE(activity_time)', $today)->where('activity_type', 'LIFT OFF')->count_all_results('opr_lift_activities'),
            'pending'     => $this->db->join('opr_lift_activities la', 'la.gate_transaction_id = opr_gate_transactions.id', 'left')->where('opr_gate_transactions.status', 'CHECKED_IN')->where('la.id IS NULL', null, false)->count_all_results('opr_gate_transactions'),
        ]);
    }
}
