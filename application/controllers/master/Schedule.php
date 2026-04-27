<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('master/vessel', 'can_view');
        $this->load->model('Schedule_model');
        $this->load->model('Vessel_model');
        $this->load->model('Berth_model');
        $this->load->model('Port_model');
    }

    public function index() {
        $this->data['page_title'] = 'Vessel Scheduler';
        $this->data['vessels'] = $this->Vessel_model->get_datatables(); 
        $this->data['berths'] = $this->Berth_model->get_datatables();
        $this->data['ports'] = $this->Port_model->get_all_ports();
        $this->render('master/schedule/index');
    }

    public function ajax_list() {
        $list = $this->Schedule_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $s) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<strong>'.$s->vessel_name.'</strong>';
            $row[] = $s->voyage_in . ' / ' . $s->voyage_out;
            $row[] = $s->berth_name ? $s->berth_name : '<span class="text-muted">Unassigned</span>';
            $row[] = date('d/m/Y H:i', strtotime($s->eta));
            $row[] = date('d/m/Y H:i', strtotime($s->etd));
            
            $status_class = 'bg-secondary';
            if ($s->status == 'PLANNED') $status_class = 'bg-primary';
            if ($s->status == 'ARRIVED') $status_class = 'bg-info text-dark';
            if ($s->status == 'BERTHED') $status_class = 'bg-success';
            if ($s->status == 'DEPARTED') $status_class = 'bg-dark';
            
            $row[] = '<span class="badge '.$status_class.'">'.$s->status.'</span>';

            $action = '<button class="btn btn-sm btn-sm-action me-1" onclick="edit_schedule('.$s->id.')" title="Edit"><i class="fas fa-edit"></i></button>';
            $action .= '<button class="btn btn-sm btn-sm-action btn-danger" onclick="delete_schedule('.$s->id.')" title="Delete"><i class="fas fa-trash"></i></button>';
            
            $row[] = $action;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Schedule_model->count_all(),
            "recordsFiltered" => $this->Schedule_model->count_filtered(),
            "data" => $data,
        );
        $this->json_response($output);
    }

    public function ajax_edit($id) {
        $data = $this->Schedule_model->get_by_id($id);
        $this->json_response($data);
    }

    public function ajax_add() {
        $data = array(
            'vessel_id' => $this->input->post('vessel_id'),
            'voyage_in' => $this->input->post('voyage_in'),
            'voyage_out' => $this->input->post('voyage_out'),
            'berth_id' => $this->input->post('berth_id'),
            'eta' => $this->input->post('eta'),
            'etd' => $this->input->post('etd'),
            'status' => $this->input->post('status'),
            'pod' => $this->input->post('pod'),
            'fpod' => $this->input->post('fpod'),
            'remarks' => $this->input->post('remarks')
        );
        $this->Schedule_model->save($data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_update() {
        $data = array(
            'vessel_id' => $this->input->post('vessel_id'),
            'voyage_in' => $this->input->post('voyage_in'),
            'voyage_out' => $this->input->post('voyage_out'),
            'berth_id' => $this->input->post('berth_id'),
            'eta' => $this->input->post('eta'),
            'etd' => $this->input->post('etd'),
            'status' => $this->input->post('status'),
            'pod' => $this->input->post('pod'),
            'fpod' => $this->input->post('fpod'),
            'remarks' => $this->input->post('remarks')
        );
        $this->Schedule_model->update(array('id' => $this->input->post('id')), $data);
        $this->json_response(array("status" => TRUE));
    }

    public function ajax_delete($id) {
        $this->Schedule_model->delete_by_id($id);
        $this->json_response(array("status" => TRUE));
    }
}
