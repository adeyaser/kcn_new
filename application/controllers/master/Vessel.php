<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vessel extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Check permission for master/vessel
        $this->check_permission('master/vessel', 'can_view');
        $this->load->model('Vessel_model');
    }

    public function index() {
        $this->data['page_title'] = 'Master Vessel';
        $this->render('master/vessel/index');
    }

    public function ajax_list() {
        $list = $this->Vessel_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $vessel) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $vessel->vessel_code;
            $row[] = $vessel->vessel_name;
            $row[] = $vessel->call_sign;
            $row[] = $vessel->flag;
            $row[] = $vessel->loa;
            
            // Active badge
            $status = $vessel->is_active 
                ? '<span class="badge badge-status badge-active">Active</span>' 
                : '<span class="badge badge-status badge-inactive">Inactive</span>';
            $row[] = $status;

            // Action buttons
            $action = '';
            if ($this->Acl_model->has_permission($this->data['current_user']->role_id, 'master/vessel', 'can_edit')) {
                $action .= '<button class="btn btn-sm btn-sm-action me-1" onclick="edit_vessel('.$vessel->id.')" title="Edit"><i class="fas fa-edit"></i></button>';
            }
            if ($this->Acl_model->has_permission($this->data['current_user']->role_id, 'master/vessel', 'can_delete')) {
                $action .= '<button class="btn btn-sm btn-sm-action btn-danger" onclick="delete_vessel('.$vessel->id.')" title="Delete"><i class="fas fa-trash"></i></button>';
            }
            $row[] = $action;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Vessel_model->count_all(),
            "recordsFiltered" => $this->Vessel_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        $this->json_response($output);
    }

    public function ajax_edit($id) {
        $data = $this->Vessel_model->get_by_id($id);
        $this->json_response($data);
    }

    public function ajax_add() {
        $this->_validate();
        $data = $this->_get_post_data();
        
        if (!empty($_FILES['vessel_image']['name'])) {
            $data['vessel_image'] = $this->_do_upload();
        }

        $this->Vessel_model->save($data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_update() {
        $this->_validate();
        $data = $this->_get_post_data();

        if (!empty($_FILES['vessel_image']['name'])) {
            $data['vessel_image'] = $this->_do_upload();
        }

        $this->Vessel_model->update(array('id' => $this->input->post('id')), $data);
        $this->json_response(array("status" => TRUE));
    }

    private function _do_upload() {
        $config['upload_path']          = './uploads/vessels/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['encrypt_name']         = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('vessel_image')) {
            return null;
        } else {
            return $this->upload->data('file_name');
        }
    }

    private function _get_post_data() {
        return array(
            'vessel_name' => $this->input->post('vessel_name'),
            'call_sign' => $this->input->post('call_sign'),
            'flag' => $this->input->post('flag'),
            'loa' => $this->input->post('loa'),
            'beam' => $this->input->post('beam'),
            'draft_max' => $this->input->post('draft_max'),
            'grt' => $this->input->post('grt'),
            'vessel_type' => $this->input->post('vessel_type'),
        );
    }

    public function ajax_delete($id) {
        $this->Vessel_model->delete_by_id($id);
        $this->json_response(array("status" => TRUE));
    }

    private function _validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('vessel_code') == '') {
            $data['inputerror'][] = 'vessel_code';
            $data['error_string'][] = 'Vessel code is required';
            $data['status'] = FALSE;
        }

        if($this->input->post('vessel_name') == '') {
            $data['inputerror'][] = 'vessel_name';
            $data['error_string'][] = 'Vessel name is required';
            $data['status'] = FALSE;
        }

        if($data['status'] === FALSE) {
            $this->json_response($data);
            exit();
        }
    }
}
