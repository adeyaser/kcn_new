<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Equipment extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('master/equipment', 'can_view');
        $this->load->model('Equipment_model');
    }

    public function index() {
        $this->data['page_title'] = 'Master Equipment';
        $this->render('master/equipment/index');
    }

    public function ajax_list() {
        $list = $this->Equipment_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $equipment) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $equipment->equipment_code;
            $row[] = $equipment->equipment_name;
            $row[] = $equipment->equipment_type;
            $row[] = $equipment->capacity;
            
            // Status badge (Condition)
            $condition = '';
            if ($equipment->status == 'READY') {
                $condition = '<span class="badge badge-status badge-active">Ready</span>';
            } elseif ($equipment->status == 'MAINTENANCE') {
                $condition = '<span class="badge badge-status bg-warning text-dark">Maintenance</span>';
            } else {
                $condition = '<span class="badge badge-status badge-inactive">Broken</span>';
            }
            $row[] = $condition;

            // Is Active Badge
            $status = $equipment->is_active 
                ? '<span class="badge badge-status badge-active">Active</span>' 
                : '<span class="badge badge-status badge-inactive">Inactive</span>';
            $row[] = $status;

            $action = '';
            if ($this->Acl_model->has_permission($this->data['current_user']->role_id, 'master/equipment', 'can_edit')) {
                $action .= '<button class="btn btn-sm btn-sm-action me-1" onclick="edit_equipment('.$equipment->id.')" title="Edit"><i class="fas fa-edit"></i></button>';
            }
            if ($this->Acl_model->has_permission($this->data['current_user']->role_id, 'master/equipment', 'can_delete')) {
                $action .= '<button class="btn btn-sm btn-sm-action btn-danger" onclick="delete_equipment('.$equipment->id.')" title="Delete"><i class="fas fa-trash"></i></button>';
            }
            $row[] = $action;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Equipment_model->count_all(),
            "recordsFiltered" => $this->Equipment_model->count_filtered(),
            "data" => $data,
        );
        $this->json_response($output);
    }

    public function ajax_edit($id) {
        $data = $this->Equipment_model->get_by_id($id);
        $this->json_response($data);
    }

    public function ajax_add() {
        $this->_validate();
        
        $data = array(
            'equipment_code' => $this->input->post('equipment_code'),
            'equipment_name' => $this->input->post('equipment_name'),
            'equipment_type' => $this->input->post('equipment_type'),
            'capacity'       => $this->input->post('capacity'),
            'status'         => $this->input->post('status'),
            'is_active'      => $this->input->post('is_active'),
        );

        $this->Equipment_model->save($data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_update() {
        $this->_validate();
        
        $data = array(
            'equipment_code' => $this->input->post('equipment_code'),
            'equipment_name' => $this->input->post('equipment_name'),
            'equipment_type' => $this->input->post('equipment_type'),
            'capacity'       => $this->input->post('capacity'),
            'status'         => $this->input->post('status'),
            'is_active'      => $this->input->post('is_active'),
        );

        $this->Equipment_model->update(array('id' => $this->input->post('id')), $data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_delete($id) {
        $this->Equipment_model->delete_by_id($id);
        $this->json_response(array("status" => TRUE));
    }

    private function _validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('equipment_code') == '') {
            $data['inputerror'][] = 'equipment_code';
            $data['error_string'][] = 'Equipment code is required';
            $data['status'] = FALSE;
        }

        if($this->input->post('equipment_name') == '') {
            $data['inputerror'][] = 'equipment_name';
            $data['error_string'][] = 'Equipment name is required';
            $data['status'] = FALSE;
        }

        if($data['status'] === FALSE) {
            $this->json_response($data);
            exit();
        }
    }
}
