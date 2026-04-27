<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tally extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('monitoring/vessel', 'can_view');
        $this->load->model('Planning_model');
        $this->load->model('Tally_model');
    }

    public function index() {
        $this->data['page_title'] = 'Vessel Tally & Productivity Report';
        $this->data['vessels'] = $this->Planning_model->get_datatables();
        $this->render('reports/tally/index');
    }

    public function print_productivity($planning_id) {
        $data['planning'] = $this->Planning_model->get_by_id($planning_id);
        if (!$data['planning']) show_404();

        // Get Summary per Equipment
        $this->db->select('e.equipment_code, e.equipment_name, COUNT(*) as total_moves');
        $this->db->from('opr_tally_activities t');
        $this->db->join('mst_equipments e', 'e.id = t.equipment_id');
        $this->db->where('t.planning_id', $planning_id);
        $this->db->group_by('t.equipment_id');
        $data['productivity'] = $this->db->get()->result();

        // Get Full Log
        $this->db->select('t.*, e.equipment_code');
        $this->db->from('opr_tally_activities t');
        $this->db->join('mst_equipments e', 'e.id = t.equipment_id');
        $this->db->where('t.planning_id', $planning_id);
        $this->db->order_by('t.activity_time', 'ASC');
        $data['logs'] = $this->db->get()->result();

        $this->load->model('Setting_model');
        $data['terminal_name'] = $this->Setting_model->get_value('terminal_name');

        $this->load->view('reports/tally_print', $data);
    }
}
