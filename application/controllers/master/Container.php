<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Container extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('master/vessel', 'can_view'); // Reuse general master permission
    }

    public function index() {
        $this->data['page_title'] = 'Master Container Database';
        $this->render('master/container/index');
    }

    public function ajax_list() {
        $this->db->from('mst_containers');
        $list = $this->db->get()->result();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $c) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<strong>'.$c->container_no.'</strong>';
            $row[] = $c->size . "' " . $c->type;
            $row[] = $c->iso_code;
            $row[] = $c->status == 'FULL' ? '<span class="badge bg-primary">FULL</span>' : '<span class="badge bg-secondary">EMPTY</span>';
            $row[] = $c->last_position ? $c->last_position : '<span class="text-muted">Unknown</span>';
            
            $action = '<button class="btn btn-sm btn-sm-action me-1" title="History"><i class="fas fa-history"></i></button>';
            $action .= '<button class="btn btn-sm btn-sm-action" title="Edit"><i class="fas fa-edit"></i></button>';
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
