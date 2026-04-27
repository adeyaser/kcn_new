<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('setup/permissions', 'can_view');
        $this->load->model('Acl_model');
    }

    public function index() {
        $this->data['page_title'] = 'Access Permissions';
        $this->data['roles'] = $this->Acl_model->get_roles();
        $this->data['menus'] = $this->db->where('parent_id', 0)->order_by('menu_order', 'ASC')->get('acl_menus')->result();
        
        foreach ($this->data['menus'] as &$m) {
            $m->children = $this->db->where('parent_id', $m->id)->order_by('menu_order', 'ASC')->get('acl_menus')->result();
        }

        $this->render('setup/permissions/index');
    }

    public function get_role_permissions($role_id) {
        $perms = $this->db->where('role_id', $role_id)->get('acl_permissions')->result();
        $this->json_response($perms);
    }

    public function update_permission() {
        $role_id = $this->input->post('role_id');
        $menu_id = $this->input->post('menu_id');
        $action = $this->input->post('action');
        $value = $this->input->post('value');

        $where = ['role_id' => $role_id, 'menu_id' => $menu_id];
        $exists = $this->db->where($where)->get('acl_permissions')->row();

        if ($exists) {
            $this->db->where($where)->update('acl_permissions', [$action => $value]);
        } else {
            $data = $where;
            $data[$action] = $value;
            $this->db->insert('acl_permissions', $data);
        }

        $this->json_response(['status' => true]);
    }
}
