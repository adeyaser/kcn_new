<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sof_report extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('monitoring/vessel', 'can_view');
        $this->load->model('Sof_model');
    }

    public function print_sof($id) {
        $data['sof'] = $this->Sof_model->get_sof_data($id);
        if (!$data['sof']) {
            show_404();
        }
        
        $this->load->model('Setting_model');
        $data['terminal'] = [
            'name' => $this->Setting_model->get_value('terminal_name'),
            'address' => $this->Setting_model->get_value('terminal_address')
        ];

        $this->load->view('reports/sof_print', $data);
    }
}
