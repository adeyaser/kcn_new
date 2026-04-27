<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Queue extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('operations/gate', 'can_view');
        $this->load->model('Gate_model');
    }

    public function index() {
        $this->data['page_title'] = 'Gate Queue & Truck Monitoring';
        
        // Fetch active trucks (Checked In but not yet Out)
        $this->db->where('status', 'CHECKED_IN');
        $this->data['active_trucks'] = $this->db->get('opr_gate_transactions')->result();
        
        // Group by Gate
        $gates = ['GATE-01' => 0, 'GATE-02' => 0, 'GATE-03' => 0];
        foreach($this->data['active_trucks'] as $t) {
            if(isset($gates[$t->gate_no])) $gates[$t->gate_no]++;
        }
        $this->data['gate_queues'] = $gates;

        $this->render('monitoring/queue/index');
    }
}
