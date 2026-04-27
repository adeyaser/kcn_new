<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stacking extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('operations/stacking', 'can_view');
        $this->load->model('Acl_model');
    }

    public function index() {
        $this->data['page_title'] = 'Container Stacking Operations';
        $this->render('operations/stacking/index');
    }
}
