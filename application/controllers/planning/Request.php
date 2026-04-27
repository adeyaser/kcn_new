<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('planning/request', 'can_view');
        $this->load->model('Planning_model');
        $this->load->model('Vessel_model');
    }

    public function index() {
        $this->data['page_title'] = 'Request Planning';
        $this->render('planning/request/index');
    }

    public function create() {
        $this->check_permission('planning/request', 'can_create');
        $this->data['page_title'] = 'Create Request Planning';
        
        $this->data['vessels'] = $this->Planning_model->get_all_vessels();
        $this->data['schedules'] = $this->Planning_model->get_active_schedules();
        $this->load->model('Port_model');
        $this->data['ports'] = $this->Port_model->get_all_ports();
        $this->data['all_equipments'] = $this->Planning_model->get_all_equipments();
        $this->load->model('Port_model');
        $this->data['ports'] = $this->Port_model->get_all_ports();
        
        $this->data['request_no'] = $this->Planning_model->generate_request_no();
        
        $this->data['extra_css'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css'
        ];
        $this->data['extra_js'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
        ];

        $this->render('planning/request/form');
    }

    public function edit($id = NULL) {
        if (!$id) {
            redirect('planning/request');
        }
        $this->check_permission('planning/request', 'can_edit');
        $this->data['page_title'] = 'Edit Request Planning';
        
        $this->data['request'] = $this->Planning_model->get_by_id($id);
        if (!$this->data['request']) {
            show_404();
        }

        // Fetch existing manifest containers
        $this->data['manifest'] = $this->Planning_model->get_manifest_by_planning_id($id);
        $this->data['planned_equipments'] = $this->Planning_model->get_equipments_by_planning_id($id);
        $this->data['all_equipments'] = $this->Planning_model->get_all_equipments();

        $this->data['schedules'] = $this->Planning_model->get_vessel_schedules();
        
        $this->data['extra_css'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
            'https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css'
        ];
        $this->data['extra_js'] = [
            'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
        ];

        $this->render('planning/request/form');
    }

    public function ajax_list() {
        $list = $this->Planning_model->get_datatables();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;
        
        foreach ($list as $req) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $req->request_no;
            $row[] = $req->vessel_name;
            $row[] = $req->voyage_in . ' / ' . $req->voyage_out;
            $row[] = '<span class="badge bg-secondary">'.$req->operation_type.'</span>';
            $row[] = $req->service_type;
            $row[] = $req->request_type;
            $row[] = date('d-m-Y H:i', strtotime($req->eta));
            
            // Status badge
            $status_class = 'bg-secondary';
            if ($req->status == 'REQUESTED') $status_class = 'bg-primary';
            if ($req->status == 'APPROVED') $status_class = 'bg-success';
            if ($req->status == 'REJECTED') $status_class = 'bg-danger';
            if ($req->status == 'OPERATING') $status_class = 'bg-info text-dark';
            
            $row[] = '<span class="badge badge-status '.$status_class.'">'.$req->status.'</span>';

            $action = '';
            if (in_array($req->status, ['DRAFT', 'REQUESTED']) && $this->Acl_model->has_permission($this->data['current_user']->role_id, 'planning/request', 'can_edit')) {
                $action .= '<a href="'.site_url('planning/request/edit/'.$req->id).'" class="btn btn-sm btn-info me-1" title="Edit"><i class="fas fa-edit"></i></a>';
            } else {
                $action .= '<button class="btn btn-sm btn-outline-info me-1" title="View Only" disabled><i class="fas fa-eye"></i></button>';
            }

            if ($req->status == 'DRAFT' && $this->Acl_model->has_permission($this->data['current_user']->role_id, 'planning/request', 'can_delete')) {
                $action .= '<button class="btn btn-sm btn-danger" onclick="delete_request('.$req->id.')" title="Delete"><i class="fas fa-trash"></i></button>';
            }
            
            $row[] = $action;
            $data[] = $row;
        }

        $output = array(
            "draw" => isset($_POST['draw']) ? intval($_POST['draw']) : 0,
            "recordsTotal" => $this->Planning_model->count_all(),
            "recordsFiltered" => $this->Planning_model->count_filtered(),
            "data" => $data,
        );
        $this->json_response($output);
    }

    public function ajax_save() {
        $id = $this->input->post('id');
        $is_edit = !empty($id);
        
        if ($is_edit) {
            $this->check_permission('planning/request', 'can_edit');
        } else {
            $this->check_permission('planning/request', 'can_create');
        }
        
        $data = array(
            'request_no' => $this->input->post('request_no'),
            'schedule_id' => $this->input->post('schedule_id'),
            'vessel_id' => $this->input->post('vessel_id'),
            'voyage_in' => $this->input->post('voyage_in'),
            'voyage_out' => $this->input->post('voyage_out'),
            'operation_type' => $this->input->post('operation_type'),
            'service_type' => $this->input->post('service_type'),
            'eta' => $this->input->post('eta'),
            'etd' => $this->input->post('etd'),
            'open_stack' => $this->input->post('open_stack'),
            'closing_time' => $this->input->post('closing_time'),
            'closing_time_doc' => $this->input->post('closing_time_doc'),
            'start_shift_reefer' => $this->input->post('start_shift_reefer'),
            'end_shift_reefer' => $this->input->post('end_shift_reefer'),
            'booking_limit' => $this->input->post('booking_limit'),
            'pod' => $this->input->post('pod'),
            'fpod' => $this->input->post('fpod'),
            'request_type' => $this->input->post('request_type'),
        );

        if (!$is_edit) {
            $data['status'] = 'REQUESTED';
            $data['created_by'] = $this->session->userdata('user_id');
        }

        // International specific fields
        if ($data['service_type'] == 'International') {
            $data['customs_document_type'] = $this->input->post('customs_document_type');
            $data['doc_bc_1_2'] = $this->input->post('doc_bc_1_2');
            $data['doc_npe'] = $this->input->post('doc_npe');
            $data['doc_pkbe'] = $this->input->post('doc_pkbe');
        }

        $this->db->trans_start();

        if ($is_edit) {
            $this->Planning_model->update(['id' => $id], $data);
            $planning_id = $id;
        } else {
            // Re-check request_no just in case
            $data['request_no'] = $this->Planning_model->generate_request_no();
            $planning_id = $this->Planning_model->save($data);
        }

        // Handle Manifest File Upload & Saving
        if (isset($_FILES['manifest_file']) && $_FILES['manifest_file']['size'] > 0) {
            $file = $_FILES['manifest_file'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            
            if (strtolower($ext) === 'csv') {
                if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {
                    $firstLine = fgets($handle);
                    $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';
                    rewind($handle);
                    $header = fgetcsv($handle, 1000, $delimiter); 
                    
                    if ($is_edit) {
                        $this->db->delete('opr_manifests', ['planning_id' => $planning_id]);
                    }

                    while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                        if (count($row) >= 5) {
                            $manifest_data = [
                                'planning_id' => $planning_id,
                                'container_no' => trim($row[0]),
                                'size' => trim($row[1]),
                                'type' => trim($row[2]),
                                'status' => trim($row[3]),
                                'weight' => trim($row[4]),
                                'pod' => isset($row[12]) ? trim($row[12]) : (isset($row[5]) ? trim($row[5]) : '')
                            ];
                            $this->db->insert('opr_manifests', $manifest_data);
                        }
                    }
                    fclose($handle);
                }
            }
        }
        
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->json_response(array("status" => FALSE, "message" => "Gagal menyimpan data ke database."));
            return;
        }

        // Save equipments
        $equip_ids = $this->input->post('equip_id');
        $equip_qtys = $this->input->post('equip_qty');
        $equip_starts = $this->input->post('equip_start');
        $equip_ends = $this->input->post('equip_end');
        $equip_notes = $this->input->post('equip_notes');

        if ($is_edit) {
            $this->db->delete('opr_planning_equipments', ['planning_id' => $planning_id]);
        }

        if (!empty($equip_ids)) {
            foreach ($equip_ids as $k => $eid) {
                if (!empty($eid)) {
                    $this->db->insert('opr_planning_equipments', [
                        'planning_id' => $planning_id,
                        'equipment_id' => $eid,
                        'quantity' => $equip_qtys[$k],
                        'start_date' => !empty($equip_starts[$k]) ? $equip_starts[$k] : NULL,
                        'end_date' => !empty($equip_ends[$k]) ? $equip_ends[$k] : NULL,
                        'notes' => $equip_notes[$k]
                    ]);
                }
            }
        }

        $this->json_response(array("status" => TRUE, "redirect" => site_url('planning/request')));
    }

    public function ajax_delete($id) {
        $this->check_permission('planning/request', 'can_delete');
        $this->Planning_model->delete_by_id($id);
        $this->json_response(array("status" => TRUE));
    }

    public function get_vessel_info() {
        $schedule_id = $this->input->get('schedule_id');
        $this->load->model('Schedule_model');
        $schedule = $this->Schedule_model->get_by_id($schedule_id);
        
        if ($schedule) {
            $response = [
                'status' => 'success',
                'vessel_id' => $schedule->vessel_id,
                'voyage_in' => $schedule->voyage_in,
                'voyage_out' => $schedule->voyage_out,
                'eta' => date('Y-m-d\TH:i', strtotime($schedule->eta)),
                'etd' => date('Y-m-d\TH:i', strtotime($schedule->etd)),
                'open_stack' => date('Y-m-d\TH:i', strtotime($schedule->eta . ' -3 days')),
                'closing_time' => date('Y-m-d\TH:i', strtotime($schedule->eta . ' -1 days')),
                'closing_time_doc' => date('Y-m-d\TH:i', strtotime($schedule->eta . ' -1 days')),
                'start_shift_reefer' => date('Y-m-d\TH:i', strtotime($schedule->eta)),
                'end_shift_reefer' => date('Y-m-d\TH:i', strtotime($schedule->etd)),
                'pod' => $schedule->pod ? $schedule->pod : 'IDKCN',
                'fpod' => $schedule->fpod ? $schedule->fpod : 'IDKCN'
            ];
            $this->json_response($response);
        } else {
            $this->json_response(['status' => 'error']);
        }
    }

    public function ajax_preview_manifest() {
        log_message('error', 'ajax_preview_manifest hit');
        if (!isset($_FILES['manifest_file'])) {
            $this->json_response(['status' => 'error', 'message' => 'No file uploaded']);
            return;
        }

        $file = $_FILES['manifest_file'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        log_message('error', 'File extension: ' . $ext);
        
        $data = [];
        
        if (strtolower($ext) === 'csv') {
            if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {
                // Detect delimiter
                $firstLine = fgets($handle);
                $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';
                rewind($handle);
                
                log_message('error', 'Detected delimiter: ' . $delimiter);

                $header = fgetcsv($handle, 1000, $delimiter); 
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                    if (count($row) >= 5) {
                        $data[] = [
                            'container_no' => trim($row[0]),
                            'size' => trim($row[1]),
                            'type' => trim($row[2]),
                            'status' => trim($row[3]),
                            'weight' => trim($row[4]),
                            'pod' => isset($row[12]) ? trim($row[12]) : (isset($row[5]) ? trim($row[5]) : '')
                        ];
                    }
                }
                fclose($handle);
                log_message('error', 'Parsed rows: ' . count($data));
            }
        } else {
            $this->json_response(['status' => 'error', 'message' => 'Currently only CSV format is supported for instant preview.']);
            return;
        }

        $this->json_response([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function ajax_delete_container($id) {
        $this->check_permission('planning/request', 'can_edit');
        $delete = $this->db->delete('opr_manifests', ['id' => $id]);
        if ($delete) {
            $this->json_response(['status' => 'success', 'message' => 'Container removed from manifest']);
        } else {
            $this->json_response(['status' => 'error', 'message' => 'Failed to remove container']);
        }
    }
}
