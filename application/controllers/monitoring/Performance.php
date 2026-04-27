<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Performance extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('monitoring/vessel', 'can_view');
        $this->load->model('Dashboard_model');
    }

    public function index() {
        $this->data['page_title'] = 'Terminal Performance Center';
        
        // Mock Performance Data
        $this->data['kpis'] = [
            'gcr' => 28.5, // Moves/Hour
            'vessel_productivity' => 55.2, // Moves/Vessel/Hour
            'trt' => 42.0, // Minutes
            'yard_utilization' => 68.4, // %
            'gate_efficiency' => 92.5 // %
        ];

        $this->data['extra_js'] = [
            'https://cdn.jsdelivr.net/npm/chart.js'
        ];

        $this->render('monitoring/performance/index');
    }
}
