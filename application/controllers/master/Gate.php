<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gate extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('master/gate', 'can_view');
        $this->load->model('Gate_model');
    }

    public function index() {
        $this->data['page_title'] = 'Gate Master Data';
        $this->render('master/gate/index');
    }

    public function ajax_list() {
        $gates = $this->Gate_model->get_gates(false);
        $data = array();
        $no = 0;
        foreach ($gates as $g) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $g->gate_name;
            $row[] = $g->gate_type;
            $row[] = $g->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            $row[] = '<button class="btn btn-sm btn-info me-1" onclick="edit_gate('.$g->id.')"><i class="fas fa-edit"></i></button>
                      <button class="btn btn-sm btn-danger" onclick="delete_gate('.$g->id.')"><i class="fas fa-trash"></i></button>';
            $data[] = $row;
        }
        $this->json_response(['data' => $data]);
    }

    public function ajax_add() {
        $data = [
            'gate_name' => $this->input->post('gate_name'),
            'gate_type' => $this->input->post('gate_type'),
            'is_active' => $this->input->post('is_active') ? 1 : 0
        ];
        $this->db->insert('mst_gates', $data);
        $this->json_response(['status' => true]);
    }

    public function ajax_edit($id) {
        $data = $this->db->where('id', $id)->get('mst_gates')->row();
        $this->json_response($data);
    }

    public function ajax_update() {
        $data = [
            'gate_name' => $this->input->post('gate_name'),
            'gate_type' => $this->input->post('gate_type'),
            'is_active' => $this->input->post('is_active') ? 1 : 0
        ];
        $this->db->where('id', $this->input->post('id'))->update('mst_gates', $data);
        $this->json_response(['status' => true]);
    }

    public function ajax_delete($id) {
        $this->db->where('id', $id)->delete('mst_gates');
        $this->json_response(['status' => true]);
    }
}
