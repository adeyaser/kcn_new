<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tid extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('master/tid', 'can_view');
        $this->load->model('Acl_model'); // Placeholder, or create a generic model
    }

    public function index() {
        $this->data['page_title'] = 'Master TID (Terminal ID)';
        $this->render('master/tid/index');
    }

    public function ajax_list() {
        $this->db->from('mst_tids');
        $list = $this->db->get()->result();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $t) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<strong>'.$t->tid_number.'</strong>';
            $row[] = $t->company_name;
            $row[] = $t->email;
            $row[] = $t->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
            
            $action = '<button class="btn btn-sm btn-sm-action me-1" title="Edit"><i class="fas fa-edit"></i></button>';
            $action .= '<button class="btn btn-sm btn-sm-action btn-danger" title="Delete"><i class="fas fa-trash"></i></button>';
            $row[] = $action;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($list),
            "recordsFiltered" => count($list),
            "data" => $data,
        );
        $this->json_response($output);
    }
}
