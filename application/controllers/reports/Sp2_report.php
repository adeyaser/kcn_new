<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sp2_report extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('operations/gate', 'can_view');
        $this->load->model('Gate_model');
        $this->load->model('Planning_model');
    }

    public function print_sp2($id) {
        $data['gate'] = $this->Gate_model->get_by_id($id);
        if (!$data['gate']) {
            show_404();
        }
        
        // SP2 is for Delivery mostly
        $planning = $this->Planning_model->get_by_id($data['gate']->planning_id);
        $data['vessel_name'] = $planning ? $planning->vessel_name : 'N/A';
        $data['voyage'] = $planning ? $planning->voyage_in : 'N/A';
        
        // Generate SP2 Number if not exist (simulation)
        $data['sp2_no'] = 'SP2-' . strtoupper(substr(md5($id), 0, 8));
        
        $this->load->view('reports/sp2_print', $data);
    }
}
