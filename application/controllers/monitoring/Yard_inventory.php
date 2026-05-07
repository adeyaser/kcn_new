<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Yard_inventory extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('monitoring/yard_inventory', 'can_view');
    }

    public function index() {
        $this->data['page_title'] = 'Yard Monitoring';
        
        $this->db->select('i.*, b.block_name, b.block_type, m.size, m.type, m.consignee');
        $this->db->from('opr_yard_inventory i');
        $this->db->join('mst_yard_blocks b', 'b.id = i.block_id');
        $this->db->join('opr_manifests m', 'm.container_no = i.container_no', 'left');
        $this->db->order_by('b.block_name', 'ASC');
        $this->db->order_by('i.bay', 'ASC');
        $this->db->order_by('i.row', 'ASC');
        $this->db->order_by('i.tier', 'ASC');
        
        $this->data['inventory'] = $this->db->get()->result();
        
        $this->render('operations/yard_inventory/index');
    }
}
