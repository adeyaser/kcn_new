<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Truck extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('master/truck', 'can_view');
        $this->load->model('Truck_model');
    }

    public function index() {
        $this->data['page_title'] = 'Master Truck (TCA)';
        $this->render('master/truck/index');
    }

    public function ajax_list() {
        $list = $this->Truck_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $truck) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $truck->police_number;
            $row[] = $truck->truck_company;
            $row[] = $truck->driver_name;
            $row[] = $truck->driver_phone;
            $row[] = $truck->rfid_tag;
            
            $status = $truck->is_active 
                ? '<span class="badge badge-status badge-active">Active</span>' 
                : '<span class="badge badge-status badge-inactive">Inactive</span>';
            $row[] = $status;

            $action = '';
            if ($this->Acl_model->has_permission($this->data['current_user']->role_id, 'master/truck', 'can_edit')) {
                $action .= '<button class="btn btn-sm btn-sm-action me-1" onclick="edit_truck('.$truck->id.')" title="Edit"><i class="fas fa-edit"></i></button>';
            }
            if ($this->Acl_model->has_permission($this->data['current_user']->role_id, 'master/truck', 'can_delete')) {
                $action .= '<button class="btn btn-sm btn-sm-action btn-danger" onclick="delete_truck('.$truck->id.')" title="Delete"><i class="fas fa-trash"></i></button>';
            }
            $row[] = $action;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Truck_model->count_all(),
            "recordsFiltered" => $this->Truck_model->count_filtered(),
            "data" => $data,
        );
        $this->json_response($output);
    }

    public function ajax_edit($id) {
        $data = $this->Truck_model->get_by_id($id);
        $this->json_response($data);
    }

    public function ajax_add() {
        $this->_validate();
        
        $data = array(
            'police_number' => $this->input->post('police_number'),
            'truck_company' => $this->input->post('truck_company'),
            'driver_name'   => $this->input->post('driver_name'),
            'driver_phone'  => $this->input->post('driver_phone'),
            'rfid_tag'      => $this->input->post('rfid_tag'),
            'is_active'     => $this->input->post('is_active'),
        );

        $this->Truck_model->save($data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_update() {
        $this->_validate();
        
        $data = array(
            'police_number' => $this->input->post('police_number'),
            'truck_company' => $this->input->post('truck_company'),
            'driver_name'   => $this->input->post('driver_name'),
            'driver_phone'  => $this->input->post('driver_phone'),
            'rfid_tag'      => $this->input->post('rfid_tag'),
            'is_active'     => $this->input->post('is_active'),
        );

        $this->Truck_model->update(array('id' => $this->input->post('id')), $data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_delete($id) {
        $this->Truck_model->delete_by_id($id);
        $this->json_response(array("status" => TRUE));
    }

    private function _validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('police_number') == '') {
            $data['inputerror'][] = 'police_number';
            $data['error_string'][] = 'Police number is required';
            $data['status'] = FALSE;
        }

        if($data['status'] === FALSE) {
            $this->json_response($data);
            exit();
        }
    }
}
