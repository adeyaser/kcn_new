<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Port extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('master/vessel', 'can_view'); // Using vessel permission for now
        $this->load->model('Port_model');
    }

    public function index() {
        $this->data['page_title'] = 'Master Port (POD/FPOD)';
        $this->render('master/port/index');
    }

    public function ajax_list() {
        $list = $this->Port_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $p) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<span class="fw-bold text-primary">'.$p->port_code.'</span>';
            $row[] = $p->port_name;
            $row[] = $p->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';

            $action = '<button class="btn btn-sm btn-sm-action me-1" onclick="edit_port('.$p->id.')" title="Edit"><i class="fas fa-edit"></i></button>';
            $action .= '<button class="btn btn-sm btn-sm-action btn-danger" onclick="delete_port('.$p->id.')" title="Delete"><i class="fas fa-trash"></i></button>';
            
            $row[] = $action;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Port_model->count_all(),
            "recordsFiltered" => $this->Port_model->count_filtered(),
            "data" => $data,
        );
        $this->json_response($output);
    }

    public function ajax_edit($id) {
        $data = $this->Port_model->get_by_id($id);
        $this->json_response($data);
    }

    public function ajax_add() {
        $data = array(
            'port_code' => strtoupper($this->input->post('port_code')),
            'port_name' => strtoupper($this->input->post('port_name')),
            'is_active' => $this->input->post('is_active')
        );
        $insert = $this->Port_model->save($data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_update() {
        $data = array(
            'port_code' => strtoupper($this->input->post('port_code')),
            'port_name' => strtoupper($this->input->post('port_name')),
            'is_active' => $this->input->post('is_active')
        );
        $this->Port_model->update(array('id' => $this->input->post('id')), $data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_delete($id) {
        $this->Port_model->delete_by_id($id);
        $this->json_response(array("status" => TRUE));
    }
}
