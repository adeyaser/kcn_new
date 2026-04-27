<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('setup/users', 'can_view');
        $this->load->model('Acl_model');
    }

    public function index() {
        $this->data['page_title'] = 'User Management';
        $this->data['roles'] = $this->Acl_model->get_roles();
        $this->render('setup/users/index');
    }

    public function ajax_list() {
        $search = $this->input->post('search')['value'];
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $order_col = $this->input->post('order')[0]['column'];
        $order_dir = $this->input->post('order')[0]['dir'];

        $list = $this->Acl_model->get_users_dt($search, $start, $length, $order_col, $order_dir);
        $total = $this->Acl_model->count_users();
        $filtered = $this->Acl_model->count_users($search);

        $data = [];
        foreach ($list as $u) {
            $row = [];
            $row[] = $u->id;
            $row[] = $u->username;
            $row[] = $u->full_name;
            $row[] = $u->email;
            $row[] = '<span class="badge bg-primary">'.$u->role_name.'</span>';
            $row[] = $u->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            $row[] = '
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary" onclick="editUser('.$u->id.')"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteUser('.$u->id.')"><i class="fas fa-trash"></i></button>
                </div>';
            $data[] = $row;
        }

        $output = [
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $total,
            "recordsFiltered" => $filtered,
            "data" => $data,
        ];
        $this->json_response($output);
    }

    public function ajax_edit($id) {
        $data = $this->Acl_model->get_user($id);
        unset($data->password); // Security
        $this->json_response($data);
    }

    public function ajax_save() {
        $id = $this->input->post('id');
        $data = [
            'username' => $this->input->post('username'),
            'full_name' => $this->input->post('full_name'),
            'email' => $this->input->post('email'),
            'role_id' => $this->input->post('role_id'),
            'is_active' => $this->input->post('is_active'),
        ];

        if ($this->input->post('password')) {
            $data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        }

        $this->Acl_model->save_user($data, $id);
        $this->json_response(['status' => true, 'message' => 'User saved successfully']);
    }

    public function ajax_delete($id) {
        $this->Acl_model->delete_user($id);
        $this->json_response(['status' => true, 'message' => 'User deleted successfully']);
    }
}
