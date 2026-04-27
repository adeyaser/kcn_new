<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menus extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('setup/menus', 'can_view');
        $this->load->model('Acl_model');
    }

    public function index() {
        $this->data['page_title'] = 'Menu Management';
        $this->data['all_menus'] = $this->db->order_by('menu_order', 'ASC')->get('acl_menus')->result();
        $this->render('setup/menus/index');
    }

    public function ajax_save() {
        $id = $this->input->post('id');
        $data = [
            'menu_name' => $this->input->post('menu_name'),
            'menu_url' => $this->input->post('menu_url'),
            'menu_icon' => $this->input->post('menu_icon'),
            'parent_id' => $this->input->post('parent_id') ?: 0,
            'menu_order' => $this->input->post('menu_order'),
            'is_active' => $this->input->post('is_active')
        ];

        if ($id) {
            $this->db->where('id', $id)->update('acl_menus', $data);
        } else {
            $this->db->insert('acl_menus', $data);
        }
        $this->json_response(['status' => true, 'message' => 'Menu saved successfully']);
    }

    public function ajax_delete($id) {
        $this->db->where('id', $id)->delete('acl_menus');
        $this->json_response(['status' => true, 'message' => 'Menu deleted successfully']);
    }
}
