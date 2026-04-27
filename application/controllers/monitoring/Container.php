<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Container extends MY_Controller {

    public function __construct() {
        parent::__construct();
        // Redirect to Trace & Track which is the container monitoring page
        redirect('monitoring/trace');
    }

    public function index() {
        redirect('monitoring/trace');
    }
}
