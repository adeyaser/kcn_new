<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gate_model extends CI_Model {

    var $table = 'opr_gate_transactions';
    var $column_order = array('id', 'gate_no', 'police_number', 'container_no', 'activity_type', 'gate_in_time', 'status', null); 
    var $column_search = array('gate_no', 'police_number', 'container_no', 'driver_name'); 
    var $order = array('id' => 'desc'); 

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->select('g.*, pr.request_no');
        $this->db->from($this->table . ' g');
        $this->db->join('opr_planning_requests pr', 'pr.id = g.planning_id', 'left');

        // Custom Filters
        if($this->input->post('filter_planning')) {
            $this->db->where('g.planning_id', $this->input->post('filter_planning'));
        }
        if($this->input->post('filter_container')) {
            $this->db->like('g.container_no', $this->input->post('filter_container'));
        }
        if($this->input->post('filter_truck')) {
            $this->db->like('g.police_number', $this->input->post('filter_truck'));
        }
        if($this->input->post('filter_gate_no')) {
            $this->db->like('g.gate_no', $this->input->post('filter_gate_no'));
        }

        $i = 0;
        foreach ($this->column_search as $item) 
        {
            if($_POST['search']['value']) 
            {
                if($i===0) 
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }
        
        if(isset($_POST['order'])) 
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id',$id);
        $query = $this->db->get();
        return $query->row();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function generate_gate_no() {
        $prefix = 'GAT-' . date('Ymd') . '-';
        $this->db->select('gate_no');
        $this->db->like('gate_no', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            $last_no = $query->row()->gate_no;
            $number = intval(substr($last_no, -4)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function get_truck_by_rfid($rfid) {
        return $this->db->where('rfid_tag', $rfid)->where('is_active', 1)->get('mst_trucks')->row();
    }

    public function get_active_plannings() {
        $this->db->select('p.*, v.vessel_name');
        $this->db->from('opr_planning_requests p');
        $this->db->join('mst_vessels v', 'v.id = p.vessel_id', 'left');
        $this->db->where_in('p.status', ['APPROVED', 'OPERATING']);
        return $this->db->get()->result();
    }

    public function get_container_by_no($container_no) {
        $this->db->select('m.*, pr.request_no, pr.id as planning_id');
        $this->db->from('opr_manifests m');
        $this->db->join('opr_planning_requests pr', 'pr.id = m.planning_id', 'left');
        $this->db->where('m.container_no', $container_no);
        $this->db->order_by('m.id', 'DESC');
        return $this->db->get()->row();
    }

    public function get_gates($active_only = true) {
        if ($active_only) $this->db->where('is_active', 1);
        return $this->db->get('mst_gates')->result();
    }

    public function get_gate_by_id($id) {
        return $this->db->where('id', $id)->get('mst_gates')->row();
    }
}
