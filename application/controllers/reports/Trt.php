<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Trt extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('operations/gate', 'can_view');
        $this->load->model('Gate_model');
    }

    public function index() {
        $this->data['page_title'] = 'Truck Turnaround Time (TRT) Report';
        $this->render('reports/truck/index');
    }

    public function print_report() {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        
        $this->db->from('opr_gate_transactions');
        if($start_date) $this->db->where('DATE(gate_in_time) >=', $start_date);
        if($end_date) $this->db->where('DATE(gate_in_time) <=', $end_date);
        $this->db->order_by('gate_in_time', 'DESC');
        $data['activities'] = $this->db->get()->result();

        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        
        $this->load->model('Setting_model');
        $data['terminal_name'] = $this->Setting_model->get_value('terminal_name');

        $this->load->view('reports/truck_print', $data);
    }
}
