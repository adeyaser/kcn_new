<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Weather extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('monitoring/vessel', 'can_view');
    }

    public function index() {
        $this->data['page_title'] = 'Weather Monitoring & Operations Interruption';
        
        // Fetch recent interruptions
        $this->db->select('i.*, v.vessel_name');
        $this->db->from('opr_interruptions i');
        $this->db->join('mst_vessels v', 'v.id = i.vessel_id', 'left');
        $this->db->order_by('i.start_time', 'DESC');
        $this->data['interruptions'] = $this->db->get()->result();

        $this->render('monitoring/weather/index');
    }

    public function ajax_log_weather_delay() {
        $data = [
            'vessel_id' => $this->input->post('vessel_id'),
            'interruption_type' => 'WEATHER',
            'start_time' => date('Y-m-d H:i:s'),
            'remarks' => $this->input->post('remarks')
        ];
        $this->db->insert('opr_interruptions', $data);
        $this->json_response(['status' => true]);
    }
}
