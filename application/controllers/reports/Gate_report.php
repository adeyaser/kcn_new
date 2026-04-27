<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gate_report extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('operations/gate', 'can_view');
        $this->load->model('Gate_model');
    }

    public function print_pass($id) {
        $data['gate'] = $this->Gate_model->get_by_id($id);
        if (!$data['gate']) {
            show_404();
        }
        
        // Fetch vessel name if linked to planning
        if ($data['gate']->planning_id) {
            $this->load->model('Planning_model');
            $planning = $this->Planning_model->get_by_id($data['gate']->planning_id);
            $data['vessel_name'] = $planning->vessel_name;
            $data['voyage'] = $planning->voyage_in;
        } else {
            $data['vessel_name'] = 'N/A';
            $data['voyage'] = 'N/A';
        }

        $this->load->view('reports/gate_pass_print', $data);
    }
}
