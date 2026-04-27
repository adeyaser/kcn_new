<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Berth_schedule extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('monitoring/vessel', 'can_view');
        $this->load->model('Schedule_model');
        $this->load->model('Berth_model');
    }

    public function index() {
        $this->data['page_title'] = 'Vessel Berth Schedule (Gantt Chart)';
        
        // Fetch all berths
        $this->data['berths'] = $this->Berth_model->get_datatables();
        
        // Fetch schedules for next 7 days
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime('+7 days'));
        
        $this->db->select('s.*, v.vessel_name');
        $this->db->from('opr_vessel_schedules s');
        $this->db->join('mst_vessels v', 'v.id = s.vessel_id');
        $this->db->where('s.eta >=', $start_date);
        $this->db->where('s.eta <=', $end_date);
        $this->db->order_by('s.eta', 'ASC');
        $this->data['schedules'] = $this->db->get()->result();

        $this->render('monitoring/berth_schedule/index');
    }
}
