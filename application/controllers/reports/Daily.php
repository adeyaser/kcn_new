<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daily extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('monitoring/vessel', 'can_view'); // Reuse monitoring permission
        $this->load->model('Gate_model');
        $this->load->model('Tally_model');
    }

    public function index() {
        $this->data['page_title'] = 'Daily Operations Report';
        $this->render('reports/daily/index');
    }

    public function print_report() {
        $date = $this->input->get('date') ? $this->input->get('date') : date('Y-m-d');
        
        // Fetch Gate Activities for the day
        $this->db->where('DATE(gate_in_time)', $date);
        $data['gate_in'] = $this->db->get('opr_gate_transactions')->result();

        $this->db->where('DATE(gate_out_time)', $date);
        $data['gate_out'] = $this->db->get('opr_gate_transactions')->result();

        // Fetch Tally Activities
        $this->db->select('t.*, v.vessel_name');
        $this->db->from('opr_tally_activities t');
        $this->db->join('mst_vessels v', 'v.id = t.vessel_id', 'left');
        $this->db->where('DATE(activity_time)', $date);
        $data['tally'] = $this->db->get()->result();

        $data['report_date'] = $date;
        $this->load->model('Setting_model');
        $data['terminal_name'] = $this->Setting_model->get_value('terminal_name');

        $this->load->view('reports/daily_print', $data);
    }
}
