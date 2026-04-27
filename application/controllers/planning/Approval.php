<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Approval extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->check_permission('planning/request', 'can_edit');
        $this->load->model('Planning_model');
    }

    public function index() {
        $this->data['page_title'] = 'Planning Approval';
        $this->render('planning/approval/index');
    }

    public function ajax_list() {
        $this->db->select('r.*, v.vessel_name');
        $this->db->from('opr_planning_requests r');
        $this->db->join('mst_vessels v', 'v.id = r.vessel_id');
        $this->db->where_in('r.status', ['REQUESTED', 'APPROVED', 'REJECTED']);
        $this->db->order_by('r.created_at', 'DESC');
        
        $list = $this->db->get()->result();
        $data = array();
        $no = 0;
        foreach ($list as $req) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<span class="fw-bold">'.$req->request_no.'</span>';
            $row[] = $req->vessel_name;
            $row[] = $req->voyage_in . ' / ' . $req->voyage_out;
            $row[] = $req->request_type;
            
            $status_badge = '';
            if($req->status == 'REQUESTED') $status_badge = '<span class="badge bg-warning text-dark">Pending Approval</span>';
            elseif($req->status == 'APPROVED') $status_badge = '<span class="badge bg-success">Approved</span>';
            elseif($req->status == 'REJECTED') $status_badge = '<span class="badge bg-danger">Rejected</span>';
            
            $row[] = $status_badge;
            $row[] = date('d/m/Y H:i', strtotime($req->created_at));

            $action = '<button class="btn btn-sm btn-primary-custom me-1" onclick="view_request('.$req->id.')" title="Review"><i class="fas fa-search-plus me-1"></i>Review</button>';
            
            $row[] = $action;
            $data[] = $row;
        }

        $this->json_response(['data' => $data]);
    }

    public function ajax_approve() {
        $id = $this->input->post('id');
        $note = $this->input->post('note');
        
        $data = [
            'status' => 'APPROVED',
            'approval_note' => $note,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $id)->update('opr_planning_requests', $data);
        $this->json_response(['status' => true, 'message' => 'Request approved successfully']);
    }

    public function ajax_reject() {
        $id = $this->input->post('id');
        $note = $this->input->post('note');
        
        $data = [
            'status' => 'REJECTED',
            'approval_note' => $note,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $id)->update('opr_planning_requests', $data);
        $this->json_response(['status' => true, 'message' => 'Request rejected']);
    }

    public function get_detail($id) {
        $this->db->select('r.*, v.vessel_name');
        $this->db->from('opr_planning_requests r');
        $this->db->join('mst_vessels v', 'v.id = r.vessel_id');
        $this->db->where('r.id', $id);
        $request = $this->db->get()->row();
        
        if($request) {
            $this->db->where('planning_id', $id);
            $manifest = $this->db->get('opr_manifests')->result();
            $this->json_response(['status' => true, 'data' => $request, 'manifest' => $manifest]);
        } else {
            $this->json_response(['status' => false]);
        }
    }
}
