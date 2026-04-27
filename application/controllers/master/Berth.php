<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berth extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('master/berth', 'can_view');
        $this->load->model('Berth_model');
    }

    public function index() {
        $this->data['page_title'] = 'Master Berth / Dermaga';
        $this->data['extra_css'] = [
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'
        ];
        $this->data['extra_js'] = [
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
        ];
        $this->render('master/berth/index');
    }

    public function ajax_list() {
        $list = $this->Berth_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $berth) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $berth->berth_code;
            $row[] = $berth->berth_name;
            $row[] = $berth->length;
            $row[] = $berth->draft_max;
            
            $status = $berth->is_active 
                ? '<span class="badge badge-status badge-active">Active</span>' 
                : '<span class="badge badge-status badge-inactive">Inactive</span>';
            $row[] = $status;

            $action = '';
            if ($this->Acl_model->has_permission($this->data['current_user']->role_id, 'master/berth', 'can_edit')) {
                $action .= '<button class="btn btn-sm btn-sm-action me-1" onclick="edit_berth('.$berth->id.')" title="Edit"><i class="fas fa-edit"></i></button>';
            }
            if ($this->Acl_model->has_permission($this->data['current_user']->role_id, 'master/berth', 'can_delete')) {
                $action .= '<button class="btn btn-sm btn-sm-action btn-danger" onclick="delete_berth('.$berth->id.')" title="Delete"><i class="fas fa-trash"></i></button>';
            }
            $row[] = $action;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Berth_model->count_all(),
            "recordsFiltered" => $this->Berth_model->count_filtered(),
            "data" => $data,
        );
        $this->json_response($output);
    }

    public function ajax_edit($id) {
        $data = $this->Berth_model->get_by_id($id);
        $this->json_response($data);
    }

    public function ajax_add() {
        $this->_validate();
        
        $data = array(
            'berth_code' => $this->input->post('berth_code'),
            'berth_name' => $this->input->post('berth_name'),
            'length'     => $this->input->post('length'),
            'draft_max'  => $this->input->post('draft_max'),
            'coordinate_polygon' => $this->input->post('coordinate_polygon'),
            'is_active'  => $this->input->post('is_active'),
        );

        $this->Berth_model->save($data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_update() {
        $this->_validate();
        
        $data = array(
            'berth_code' => $this->input->post('berth_code'),
            'berth_name' => $this->input->post('berth_name'),
            'length'     => $this->input->post('length'),
            'draft_max'  => $this->input->post('draft_max'),
            'coordinate_polygon' => $this->input->post('coordinate_polygon'),
            'is_active'  => $this->input->post('is_active'),
        );

        $this->Berth_model->update(array('id' => $this->input->post('id')), $data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_delete($id) {
        $this->Berth_model->delete_by_id($id);
        $this->json_response(array("status" => TRUE));
    }

    private function _validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('berth_code') == '') {
            $data['inputerror'][] = 'berth_code';
            $data['error_string'][] = 'Berth code is required';
            $data['status'] = FALSE;
        }

        if($this->input->post('berth_name') == '') {
            $data['inputerror'][] = 'berth_name';
            $data['error_string'][] = 'Berth name is required';
            $data['status'] = FALSE;
        }

        if($data['status'] === FALSE) {
            $this->json_response($data);
            exit();
        }
    }
}
