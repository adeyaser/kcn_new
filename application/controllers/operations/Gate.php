<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gate extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('operations/gate', 'can_view');
        $this->load->model('Gate_model');
        $this->load->model('Truck_model');
        $this->load->model('Planning_model');
        $this->load->model('Tca_model');
    }

    public function index() {
        $this->data['page_title'] = 'Gate Operations (TCA)';
        $this->data['plannings'] = $this->Gate_model->get_active_plannings();
        
        $this->data['extra_css'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css'
        ];
        $this->data['extra_js'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
        ];

        $this->render('operations/gate/index');
    }

    public function ajax_list() {
        $list = $this->Gate_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $gate) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<div class="fw-bold">'.$gate->gate_no.'</div><div class="small text-muted">Req: '.($gate->request_no ?: 'Manual').'</div>';
            $row[] = $gate->police_number;
            $row[] = $gate->container_no ? $gate->container_no : '<span class="text-muted">N/A</span>';
            $row[] = $gate->activity_type;
            $row[] = $gate->gate_in_time ? date('d-m-Y H:i', strtotime($gate->gate_in_time)) : '-';
            
            // Status badge
            $status_class = 'bg-secondary';
            if ($gate->status == 'CHECKED_IN') $status_class = 'bg-primary';
            if ($gate->status == 'IN_YARD') $status_class = 'bg-info text-dark';
            if ($gate->status == 'CHECKED_OUT') $status_class = 'bg-success';
            
            $row[] = '<span class="badge badge-status '.$status_class.'">'.$gate->status.'</span>';

            $action = '';
            if ($gate->status == 'CHECKED_IN' || $gate->status == 'IN_YARD') {
                $action .= '<button class="btn btn-sm btn-sm-action btn-success me-1" onclick="gate_out('.$gate->id.')" title="Gate Out"><i class="fas fa-sign-out-alt"></i></button>';
            }
            $action .= '<a href="'.site_url('reports/gate_report/print_pass/'.$gate->id).'" target="_blank" class="btn btn-sm btn-sm-action btn-info me-1" title="Print Pass"><i class="fas fa-print"></i></a>';
            if ($gate->activity_type == 'DELIVERY') {
                $action .= '<a href="'.site_url('reports/sp2_report/print_sp2/'.$gate->id).'" target="_blank" class="btn btn-sm btn-sm-action btn-warning me-1" title="Print SP2"><i class="fas fa-file-invoice"></i></a>';
            }
            $action .= '<button class="btn btn-sm btn-sm-action" onclick="view_gate('.$gate->id.')" title="View"><i class="fas fa-eye"></i></button>';
            
            $row[] = $action;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Gate_model->count_all(),
            "recordsFiltered" => $this->Gate_model->count_filtered(),
            "data" => $data,
        );
        $this->json_response($output);
    }

    public function gate_in() {
        $this->check_permission('operations/gate', 'can_create');
        $this->data['page_title'] = 'Gate In - TCA Registration';
        $this->data['plannings'] = $this->Gate_model->get_active_plannings();
        $this->data['gates'] = $this->Gate_model->get_gates();
        $this->data['gate_no'] = $this->Gate_model->generate_gate_no();
        
        $this->data['extra_css'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css'
        ];
        $this->data['extra_js'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
            'https://unpkg.com/html5-qrcode'
        ];

        $this->render('operations/gate/gate_in');
    }

    public function ajax_check_rfid() {
        $rfid = $this->input->get('rfid');
        
        // 1. Get Truck Info
        $truck = $this->Gate_model->get_truck_by_rfid($rfid);
        if (!$truck) {
            $this->json_response(['status' => 'error', 'message' => 'RFID not registered in TCA system.']);
        }

        // 2. Check if there is an active TCA Assignment for this truck
        $this->db->select('id');
        $this->db->where('truck_id', $truck->id);
        $this->db->where('status', 'PLANNED');
        $this->db->order_by('id', 'DESC');
        $assignment = $this->db->get('opr_tca_assignments')->row();

        if ($assignment) {
            $data = $this->Tca_model->get_by_id($assignment->id);
            $this->json_response([
                'status' => 'success',
                'type' => 'TCA_FULL',
                'data' => [
                    'assignment_no' => $data->assignment_no,
                    'planning_id' => $data->planning_id,
                    'request_no' => $data->request_no,
                    'gate_id' => $data->gate_id,
                    'truck_id' => $data->truck_id,
                    'police_number' => $data->police_number,
                    'driver_name' => $data->driver_name,
                    'rfid_tag' => $data->rfid_tag,
                    'container_no' => $data->container_no,
                    'container_size' => $data->container_size,
                    'container_type' => $data->container_type,
                    'activity_type' => 'RECEIVING'
                ]
            ]);
        } else {
            // Just truck info
            $this->json_response(['status' => 'success', 'type' => 'TRUCK_ONLY', 'data' => $truck]);
        }
    }

    public function ajax_check_container() {
        $container_no = $this->input->get('container_no');
        
        // Check in TCA Assignments first
        $this->db->select('a.id');
        $this->db->from('opr_tca_assignments a');
        $this->db->join('opr_manifests m', 'm.id = a.manifest_id');
        $this->db->where('m.container_no', $container_no);
        $this->db->where('a.status', 'PLANNED');
        $assignment = $this->db->get()->row();

        if ($assignment) {
            $data = $this->Tca_model->get_by_id($assignment->id);
            $this->json_response([
                'status' => 'success',
                'type' => 'TCA_FULL',
                'data' => [
                    'assignment_no' => $data->assignment_no,
                    'planning_id' => $data->planning_id,
                    'request_no' => $data->request_no,
                    'gate_id' => $data->gate_id,
                    'truck_id' => $data->truck_id,
                    'police_number' => $data->police_number,
                    'driver_name' => $data->driver_name,
                    'rfid_tag' => $data->rfid_tag,
                    'container_no' => $data->container_no,
                    'container_size' => $data->container_size,
                    'container_type' => $data->container_type,
                    'activity_type' => 'RECEIVING'
                ]
            ]);
        } else {
            // Fallback to basic container search
            $container = $this->Gate_model->get_container_by_no($container_no);
            if ($container) {
                $this->json_response(['status' => 'success', 'type' => 'CONT_ONLY', 'data' => $container]);
            } else {
                $this->json_response(['status' => 'error', 'message' => 'Container not found in planning/manifest.']);
            }
        }
    }

    public function ajax_save_gate_in() {
        $this->check_permission('operations/gate', 'can_create');
        
        $data = array(
            'gate_no' => $this->input->post('gate_no'),
            'gate_id' => $this->input->post('gate_id'),
            'planning_id' => $this->input->post('planning_id'),
            'truck_id' => $this->input->post('truck_id'),
            'police_number' => $this->input->post('police_number'),
            'driver_name' => $this->input->post('driver_name'),
            'rfid_tag' => $this->input->post('rfid_tag'),
            'container_no' => $this->input->post('container_no'),
            'container_size' => $this->input->post('container_size'),
            'container_type' => $this->input->post('container_type'),
            'activity_type' => $this->input->post('activity_type'),
            'transaction_type' => 'GATE_IN',
            'gate_in_time' => date('Y-m-d H:i:s'),
            'gate_in_operator' => $this->session->userdata('user_id'),
            'status' => 'CHECKED_IN'
        );

        $insert_id = $this->Gate_model->save($data);
        $this->json_response(array("status" => TRUE, "redirect" => site_url('operations/gate')));
    }

    public function ajax_gate_out($id) {
        $this->check_permission('operations/gate', 'can_edit');
        
        $data = array(
            'gate_out_time' => date('Y-m-d H:i:s'),
            'gate_out_operator' => $this->session->userdata('user_id'),
            'status' => 'CHECKED_OUT'
        );

        $this->Gate_model->update(array('id' => $id), $data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_check_qr() {
        $code = $this->input->get('code');
        if(!$code) $this->json_response(['status' => 'error', 'message' => 'Invalid QR Code']);

        // Format is AssignmentNo|Token
        $parts = explode('|', $code);
        $assignment_no = $parts[0];
        
        $this->db->select('id');
        $this->db->where('assignment_no', $assignment_no);
        $assignment = $this->db->get('opr_tca_assignments')->row();

        if ($assignment) {
            $data = $this->Tca_model->get_by_id($assignment->id);
            
            // Check if already used
            if ($data->status === 'CHECKED_IN') {
                $this->json_response(['status' => 'error', 'message' => 'This pass has already been used.']);
            }

            $this->json_response([
                'status' => 'success',
                'data' => [
                    'id' => $data->id,
                    'assignment_no' => $data->assignment_no,
                    'planning_id' => $data->planning_id,
                    'request_no' => $data->request_no,
                    'gate_id' => $data->gate_id,
                    'truck_id' => $data->truck_id,
                    'police_number' => $data->police_number,
                    'driver_name' => $data->driver_name,
                    'rfid_tag' => $data->rfid_tag,
                    'container_no' => $data->container_no,
                    'container_size' => $data->container_size,
                    'container_type' => $data->container_type,
                    'activity_type' => 'RECEIVING' // Default or based on planning
                ]
            ]);
        } else {
            $this->json_response(['status' => 'error', 'message' => 'TCA Assignment not found.']);
        }
    }

    public function ajax_view($id) {
        $this->db->select('g.*, pr.request_no, pr.vessel_id, v.vessel_name, gt.gate_name');
        $this->db->from('opr_gate_transactions g');
        $this->db->join('opr_planning_requests pr', 'pr.id = g.planning_id', 'left');
        $this->db->join('mst_vessels v', 'v.id = pr.vessel_id', 'left');
        $this->db->join('mst_gates gt', 'gt.id = g.gate_id', 'left');
        $this->db->where('g.id', $id);
        $data = $this->db->get()->row();

        if ($data) {
            $this->json_response(['status' => 'success', 'data' => $data]);
        } else {
            $this->json_response(['status' => 'error', 'message' => 'Transaction not found.']);
        }
    }
}
