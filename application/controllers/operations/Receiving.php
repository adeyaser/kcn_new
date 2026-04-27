<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receiving extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('operations/receiving', 'can_view');
        $this->load->model('Acl_model');
    }

    public function index() {
        $this->data['page_title'] = 'Receiving & Delivery Monitoring';
        $this->data['jobs'] = $this->db->order_by('id', 'DESC')->limit(10)->get('opr_job_orders')->result();
        $this->render('operations/receiving/index');
    }

    public function ajax_save() {
        $data = $this->input->post();
        $data['job_no'] = 'JOB-' . date('Ymd') . '-' . rand(1000, 9999);
        $this->db->insert('opr_job_orders', $data);
        $this->json_response(['status' => true, 'message' => 'Job Order saved']);
    }
}
