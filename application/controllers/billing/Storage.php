<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Storage extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('billing/storage', 'can_view');
        $this->load->model('Billing_model');
        $this->load->model('Gate_model');
    }

    public function index() {
        $this->data['page_title'] = 'Container Storage Billing';
        $this->data['billables'] = $this->Billing_model->get_billable_containers();
        $this->render('billing/storage/index');
    }

    public function ajax_calculate() {
        $id = $this->input->get('id');
        $calculation = $this->Billing_model->calculate_storage($id);
        if ($calculation) {
            $this->json_response(['status' => 'success', 'data' => $calculation]);
        } else {
            $this->json_response(['status' => 'error', 'message' => 'Calculation failed']);
        }
    }
}
