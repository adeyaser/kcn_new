<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tally extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('operations/tally', 'can_view');
        $this->load->model('Tally_model');
        $this->load->model('Planning_model');
    }

    public function index() {
        $this->data['page_title'] = 'Tally Operations';
        $this->load->model('Gate_model');
        $this->data['plannings'] = $this->Gate_model->get_active_plannings();
        $this->data['equipments'] = $this->Tally_model->get_equipments();
        
        $this->data['extra_css'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css'
        ];
        $this->data['extra_js'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
        ];

        $this->render('operations/tally/index');
    }

    public function ajax_list() {
        $list = $this->Tally_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $tally) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<div class="fw-bold">'.$tally->container_no.'</div><div class="small text-muted">Gate: '.($tally->gate_no ?: '-').'</div>';
            $row[] = $tally->activity_type;
            $row[] = '<div class="fw-bold">'.($tally->vessel_name ?: 'Yard Only').'</div><div class="small text-muted">Req: '.($tally->request_no ?: '-').'</div>';
            $row[] = $tally->equipment_name;
            $row[] = date('d-m-Y H:i', strtotime($tally->activity_time));
            
            $status_class = $tally->status == 'COMPLETED' ? 'bg-success' : 'bg-warning';
            $row[] = '<span class="badge badge-status '.$status_class.'">'.$tally->status.'</span>';

            $action = '<button class="btn btn-sm btn-sm-action" onclick="view_tally('.$tally->id.')" title="View"><i class="fas fa-eye"></i></button>';
            $row[] = $action;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Tally_model->count_all(),
            "recordsFiltered" => $this->Tally_model->count_filtered(),
            "data" => $data,
        );
        $this->json_response($output);
    }

    public function ajax_save() {
        $this->check_permission('operations/tally', 'can_create');
        
        // Auto fetch vessel_id from planning
        $planning = $this->Planning_model->get_by_id($this->input->post('planning_id'));
        
        $data = array(
            'planning_id' => $this->input->post('planning_id'),
            'vessel_id' => $planning->vessel_id,
            'container_no' => strtoupper($this->input->post('container_no')),
            'activity_type' => $this->input->post('activity_type'),
            'equipment_id' => $this->input->post('equipment_id'),
            'bay' => $this->input->post('bay'),
            'row' => $this->input->post('row'),
            'tier' => $this->input->post('tier'),
            'location_type' => $this->input->post('location_type'),
            'tally_operator_id' => $this->session->userdata('user_id'),
            'activity_time' => date('Y-m-d H:i:s'),
            'status' => 'COMPLETED',
            'remarks' => $this->input->post('remarks')
        );

        $insert_id = $this->Tally_model->save($data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_get_containers($planning_id) {
        $this->db->select('m.*, pr.request_type, v.vessel_name, pr.voyage_in');
        $this->db->from('opr_manifests m');
        $this->db->join('opr_planning_requests pr', 'pr.id = m.planning_id');
        $this->db->join('mst_vessels v', 'v.id = pr.vessel_id');
        $this->db->where('m.planning_id', $planning_id);
        $containers = $this->db->get()->result();
        
        // Fetch assigned equipment for this planning
        $this->db->select('equipment_id');
        $this->db->where('planning_id', $planning_id);
        $equipment = $this->db->get('opr_planning_equipments')->row();
        $equipment_id = $equipment ? $equipment->equipment_id : null;

        // Map request_type to activity_type for Tally
        foreach($containers as &$c) {
            $rt = strtoupper($c->request_type);
            if ($rt == 'IMPORT' || $rt == 'INBOUND') $c->planning_activity = 'DISCHARGE';
            elseif ($rt == 'EXPORT' || $rt == 'OUTBOUND') $c->planning_activity = 'LOAD';
            elseif ($rt == 'RECEIVING') $c->planning_activity = 'LIFT_OFF';
            elseif ($rt == 'DELIVERY') $c->planning_activity = 'LIFT_ON';
            else $c->planning_activity = 'DISCHARGE'; // Default
        }

        $this->json_response([
            'status' => 'success', 
            'data' => $containers,
            'equipment_id' => $equipment_id
        ]);
    }
}
