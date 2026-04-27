<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yard_block extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('setup/yard_block', 'can_view');
        $this->load->model('Yard_model');
    }

    public function index() {
        $this->data['page_title'] = 'Master Yard Blocks';
        $this->render('setup/yard_block/index');
    }

    public function ajax_list() {
        $list = $this->Yard_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $block) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<strong>'.$block->block_name.'</strong>';
            $row[] = $block->block_type;
            $row[] = $block->max_bay . ' Bays';
            $row[] = $block->max_row . ' Rows';
            $row[] = $block->max_tier . ' Tiers';
            
            $status = $block->is_active 
                ? '<span class="badge badge-status badge-active">Active</span>' 
                : '<span class="badge badge-status badge-inactive">Inactive</span>';
            $row[] = $status;

            $action = '';
            if ($this->Acl_model->has_permission($this->data['current_user']->role_id, 'setup/yard_block', 'can_edit')) {
                $action .= '<button class="btn btn-sm btn-sm-action me-1" onclick="edit_block('.$block->id.')" title="Edit"><i class="fas fa-edit"></i></button>';
            }
            if ($this->Acl_model->has_permission($this->data['current_user']->role_id, 'setup/yard_block', 'can_delete')) {
                $action .= '<button class="btn btn-sm btn-sm-action btn-danger" onclick="delete_block('.$block->id.')" title="Delete"><i class="fas fa-trash"></i></button>';
            }
            $row[] = $action;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Yard_model->count_all(),
            "recordsFiltered" => $this->Yard_model->count_filtered(),
            "data" => $data,
        );
        $this->json_response($output);
    }

    public function ajax_edit($id) {
        $data = $this->Yard_model->get_by_id($id);
        $this->json_response($data);
    }

    public function ajax_add() {
        $this->_validate();
        $data = array(
            'block_name' => $this->input->post('block_name'),
            'block_type' => $this->input->post('block_type'),
            'max_bay'    => $this->input->post('max_bay'),
            'max_row'    => $this->input->post('max_row'),
            'max_tier'   => $this->input->post('max_tier'),
            'is_active'  => $this->input->post('is_active'),
        );
        $this->Yard_model->save($data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_update() {
        $this->_validate();
        $data = array(
            'block_name' => $this->input->post('block_name'),
            'block_type' => $this->input->post('block_type'),
            'max_bay'    => $this->input->post('max_bay'),
            'max_row'    => $this->input->post('max_row'),
            'max_tier'   => $this->input->post('max_tier'),
            'is_active'  => $this->input->post('is_active'),
        );
        $this->Yard_model->update(array('id' => $this->input->post('id')), $data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_delete($id) {
        $this->Yard_model->delete_by_id($id);
        $this->json_response(array("status" => TRUE));
    }

    private function _validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if($this->input->post('block_name') == '') {
            $data['inputerror'][] = 'block_name';
            $data['error_string'][] = 'Block name is required';
            $data['status'] = FALSE;
        }

        if($data['status'] === FALSE) {
            $this->json_response($data);
            exit();
        }
    }
}
