<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berth_plan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('monitoring/vessel', 'can_view');
        $this->load->model('Berth_model');
        $this->load->model('Schedule_model');
    }

    public function index() {
        $this->data['page_title'] = 'Vessel Berthing Plan (Map)';
        
        // Fetch all berths with coordinates
        $this->data['berths'] = $this->Berth_model->get_datatables();
        
        // Fetch active vessels (BERTHED or ARRIVED)
        $this->db->select('s.*, v.vessel_name, v.loa, b.coordinate_polygon');
        $this->db->from('opr_vessel_schedules s');
        $this->db->join('mst_vessels v', 'v.id = s.vessel_id');
        $this->db->join('mst_berths b', 'b.id = s.berth_id');
        $this->db->where_in('s.status', ['ARRIVED', 'BERTHED']);
        $this->data['active_vessels'] = $this->db->get()->result();

        $this->data['extra_css'] = [
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css'
        ];
        $this->data['extra_js'] = [
            'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js'
        ];

        $this->render('monitoring/berth_plan/index');
    }
}
