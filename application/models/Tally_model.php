<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tally_model extends CI_Model {

    var $table = 'opr_tally_activities';
    var $column_order = array('id', 'container_no', 'activity_type', 'vessel_id', 'activity_time', 'status', null); 
    var $column_search = array('container_no', 'activity_type', 'remarks'); 
    var $order = array('id' => 'desc'); 

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->select('t.*, v.vessel_name, e.equipment_name, pr.request_no, g.gate_no');
        $this->db->from('opr_tally_activities t');
        $this->db->join('mst_vessels v', 'v.id = t.vessel_id', 'left');
        $this->db->join('mst_equipments e', 'e.id = t.equipment_id', 'left');
        $this->db->join('opr_planning_requests pr', 'pr.id = t.planning_id', 'left');
        $this->db->join('opr_gate_transactions g', 'g.planning_id = t.planning_id AND g.container_no = t.container_no', 'left');

        // Custom Filters
        if($this->input->post('filter_planning')) {
            $this->db->where('t.planning_id', $this->input->post('filter_planning'));
        }
        if($this->input->post('filter_gate_no')) {
            $this->db->like('g.gate_no', $this->input->post('filter_gate_no'));
        }
        if($this->input->post('filter_container')) {
            $this->db->like('t.container_no', $this->input->post('filter_container'));
        }

        $i = 0;
        foreach ($this->column_search as $item) 
        {
            if($_POST['search']['value']) 
            {
                if($i===0) 
                {
                    $this->db->group_start(); 
                    $this->db->like('t.'.$item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like('t.'.$item, $_POST['search']['value']);
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

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function get_equipments() {
        return $this->db->where('is_active', 1)->where('status', 'READY')->get('mst_equipments')->result();
    }
}
