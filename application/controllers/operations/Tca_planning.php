<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tca_planning extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('operations/gate', 'can_view');
        $this->load->model('Tca_model');
        $this->load->model('Gate_model');
        $this->load->model('Planning_model');
    }

    public function index() {
        $this->data['page_title'] = 'TCA - Truck Assignment Planning';
        
        $this->data['extra_css'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css'
        ];
        $this->data['extra_js'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
        ];

        $this->render('operations/tca_planning/index');
    }

    public function ajax_list() {
        $list = $this->Tca_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = '<input type="checkbox" class="form-check-input row-check" value="'.$item->id.'">';
            $row[] = '<strong>'.$item->assignment_no.'</strong>';
            $row[] = $item->request_no;
            $row[] = $item->container_no;
            $row[] = $item->police_number;
            $row[] = date('d/m/Y H:i', strtotime($item->estimated_arrival));
            
            $status_class = 'bg-primary';
            if ($item->status == 'CHECKED_IN') $status_class = 'bg-success';
            if ($item->status == 'CANCELLED') $status_class = 'bg-danger';
            
            $row[] = '<span class="badge '.$status_class.'">'.$item->status.'</span>';

            $action = '<button class="btn btn-sm btn-info me-1" onclick="print_pass('.$item->id.')" title="Print Pass"><i class="fas fa-print"></i></button>';
            $action .= '<button class="btn btn-sm btn-dark" onclick="view_assignment('.$item->id.')" title="View"><i class="fas fa-eye"></i></button>';
            
            $row[] = $action;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Tca_model->count_all(),
            "recordsFiltered" => $this->Tca_model->count_filtered(),
            "data" => $data,
        );
        $this->json_response($output);
    }

    public function create() {
        $this->data['page_title'] = 'Create Truck Assignment';
        $this->data['plannings'] = $this->Gate_model->get_active_plannings();
        $this->data['gates'] = $this->Gate_model->get_gates();
        $this->data['assignment_no'] = $this->Tca_model->generate_assignment_no();
        
        $this->data['extra_css'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css'
        ];
        $this->data['extra_js'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
        ];

        $this->render('operations/tca_planning/create');
    }

    public function ajax_get_truck_info() {
        $search = $this->input->get('term');
        $trucks = $this->Tca_model->get_trucks($search);
        $results = [];
        foreach ($trucks as $t) {
            $results[] = [
                'id' => $t->id,
                'text' => $t->police_number,
                'driver_name' => $t->driver_name,
                'driver_phone' => $t->driver_phone,
                'truck_company' => $t->truck_company,
                'rfid_tag' => $t->rfid_tag
            ];
        }
        echo json_encode($results);
    }

    public function ajax_get_planning_containers() {
        $planning_id = $this->input->get('planning_id');
        
        $this->db->select('id, container_no, size, type');
        $this->db->from('opr_manifests');
        $this->db->where('planning_id', $planning_id);
        $containers = $this->db->get()->result();
        
        // If empty, let's see if we can get ANY containers to test if it's a filtering issue
        $all_count = $this->db->count_all('opr_manifests');
        
        $this->json_response([
            'status' => 'success', 
            'data' => $containers,
            'debug' => [
                'planning_id' => $planning_id,
                'count' => count($containers),
                'total_manifests' => $all_count
            ]
        ]);
    }

    public function ajax_save() {
        $data = [
            'assignment_no' => $this->input->post('assignment_no'),
            'planning_id' => $this->input->post('planning_id'),
            'manifest_id' => $this->input->post('manifest_id'),
            'truck_id' => $this->input->post('truck_id'),
            'gate_id' => $this->input->post('gate_id'),
            'estimated_arrival' => $this->input->post('est_date') . ' ' . $this->input->post('est_time'),
            'qr_code_token' => bin2hex(random_bytes(10)),
            'status' => 'PLANNED',
            'created_by' => $this->session->userdata('user_id')
        ];

        $id = $this->Tca_model->save($data);
        $this->json_response(['status' => true, 'id' => $id, 'redirect' => site_url('operations/tca_planning')]);
    }

    public function print_pass($id) {
        $this->data['data'] = $this->Tca_model->get_by_id($id);
        $this->load->view('operations/tca_planning/print_pass', $this->data);
    }

    public function ajax_view($id) {
        $data = $this->Tca_model->get_by_id($id);
        if(!$data) die("Assignment not found");
        
        ?>
        <div class="p-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <h6 class="text-muted small text-uppercase fw-bold mb-3">Truck & Driver</h6>
                    <table class="table table-sm table-borderless text-white">
                        <tr><td width="120">Police No</td><td>: <span class="fw-bold text-info"><?= $data->police_number ?></span></td></tr>
                        <tr><td>Truck Co.</td><td>: <?= $data->truck_company ?></td></tr>
                        <tr><td>Driver</td><td>: <?= $data->driver_name ?></td></tr>
                        <tr><td>Phone</td><td>: <?= $data->driver_phone ?></td></tr>
                        <tr><td>RFID Tag</td><td>: <code class="text-warning"><?= $data->rfid_tag ?></code></td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted small text-uppercase fw-bold mb-3">Planning & Container</h6>
                    <table class="table table-sm table-borderless text-white">
                        <tr><td width="120">Assignment No</td><td>: <span class="fw-bold text-info"><?= $data->assignment_no ?></span></td></tr>
                        <tr><td>Request No</td><td>: <?= $data->request_no ?></td></tr>
                        <tr><td>Container No</td><td>: <span class="fw-bold text-success"><?= $data->container_no ?></span></td></tr>
                        <tr><td>Size / Type</td><td>: <?= $data->container_size ?>' / <?= $data->container_type ?></td></tr>
                        <tr><td>Arrival</td><td>: <span class="text-danger"><?= date('d M Y, H:i', strtotime($data->estimated_arrival)) ?></span></td></tr>
                    </table>
                </div>
                <div class="col-12">
                    <div class="bg-secondary bg-opacity-25 p-3 rounded text-center">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= $data->assignment_no ?>|<?= $data->qr_code_token ?>" class="mb-2" alt="QR Code">
                        <p class="mb-0 small text-muted">Scan token: <?= $data->qr_code_token ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function bulk_print() {
        $ids_str = $this->input->get('ids');
        if(!$ids_str) die("No IDs provided");
        
        $ids = explode(',', $ids_str);
        $assignments = [];
        foreach($ids as $id) {
            $data = $this->Tca_model->get_by_id($id);
            if($data) $assignments[] = $data;
        }
        
        if(empty($assignments)) die("No data found");
        
        $this->data['assignments'] = $assignments;
        $this->load->view('operations/tca_planning/print_bulk', $this->data);
    }
    public function bulk_create() {
        $this->db->select('p.*, v.vessel_name');
        $this->db->from('opr_planning_requests p');
        $this->db->join('mst_vessels v', 'v.id = p.vessel_id');
        $this->db->where_in('p.status', ['APPROVED', 'OPERATING']);
        $plannings = $this->db->get()->result();

        $gates = $this->db->get('mst_gates')->result();
        
        $this->json_response([
            'plannings' => $plannings,
            'gates' => $gates
        ]);
    }

    public function ajax_get_manifest_bulk() {
        $planning_id = $this->input->get('planning_id');
        
        $this->db->select('m.*, t.assignment_no, tr.police_number, t.estimated_arrival, t.status as tca_status, t.truck_id, t.gate_id');
        $this->db->from('opr_manifests m');
        $this->db->join('opr_tca_assignments t', 't.manifest_id = m.id', 'left');
        $this->db->join('mst_trucks tr', 'tr.id = t.truck_id', 'left');
        $this->db->where('m.planning_id', $planning_id);
        $data = $this->db->get()->result();
        
        $this->json_response(['status' => 'success', 'data' => $data]);
    }

    public function ajax_save_bulk() {
        $planning_id = $this->input->post('planning_id');
        $assignments = $this->input->post('assignments');
        
        $count = 0;
        foreach($assignments as $a) {
            if(empty($a['est_arrival']) && empty($a['truck_id'])) continue;
            
            $exists = $this->db->where('manifest_id', $a['manifest_id'])->get('opr_tca_assignments')->row();
            
            $data = [
                'planning_id' => $planning_id,
                'manifest_id' => $a['manifest_id'],
                'gate_id' => $a['gate_id'],
                'truck_id' => !empty($a['truck_id']) ? $a['truck_id'] : ($exists ? $exists->truck_id : NULL),
                'estimated_arrival' => !empty($a['est_arrival']) ? $a['est_arrival'] : ($exists ? $exists->estimated_arrival : date('Y-m-d H:i:s')),
                'status' => 'PLANNED',
                'created_by' => $this->session->userdata('user_id')
            ];
            
            if($exists) {
                $this->db->where('id', $exists->id)->update('opr_tca_assignments', $data);
            } else {
                $data['assignment_no'] = 'TCA-' . date('Ymd') . '-' . sprintf('%04d', rand(1, 9999));
                $data['qr_code_token'] = bin2hex(random_bytes(10));
                $this->db->insert('opr_tca_assignments', $data);
            }
            $count++;
        }
        
        $this->json_response(['status' => 'success', 'message' => "$count assignments updated"]);
    }
}
