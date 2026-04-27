<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('setup/roles', 'can_view');
        $this->load->model('Acl_model');
    }

    public function index() {
        $this->data['page_title'] = 'Role Management';
        $this->data['roles'] = $this->Acl_model->get_roles();
        $this->render('setup/roles/index');
    }

    public function ajax_save() {
        $id = $this->input->post('id');
        $data = [
            'role_name' => $this->input->post('role_name'),
            'role_code' => strtoupper($this->input->post('role_code')),
            'is_active' => $this->input->post('is_active')
        ];

        if ($id) {
            $this->db->where('id', $id)->update('acl_roles', $data);
        } else {
            $this->db->insert('acl_roles', $data);
        }
        $this->json_response(['status' => true, 'message' => 'Role saved successfully']);
    }

    public function ajax_delete($id) {
        $this->db->where('id', $id)->delete('acl_roles');
        $this->json_response(['status' => true, 'message' => 'Role deleted successfully']);
    }
}
