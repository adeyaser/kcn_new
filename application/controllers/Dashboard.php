<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Dashboard_model');
    }

    public function index() {
        $this->data['page_title'] = 'Dashboard';
        $this->data['stats'] = $this->Dashboard_model->get_stats();
        $this->data['active_vessels'] = $this->Dashboard_model->get_active_vessels();
        $this->data['recent_activities'] = $this->Dashboard_model->get_recent_activities();
        
        // Chart Data Simulation
        $this->data['trt_data'] = [
            'labels' => ['06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00'],
            'values' => [35, 42, 55, 38, 45, 62, 48, 30]
        ];

        $this->data['extra_css'] = [
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'
        ];
        $this->data['extra_js'] = [
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
            'https://cdn.jsdelivr.net/npm/chart.js'
        ];
        $this->render('dashboard/index');
    }

    public function get_trt_data() {
        $vessel_id = $this->input->get('vessel_id');
        $data = $this->Dashboard_model->get_trt_cycles($vessel_id);
        $this->json_response(['status' => 'success', 'data' => $data]);
    }
}
