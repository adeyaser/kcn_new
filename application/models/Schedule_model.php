<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_model extends CI_Model {

    var $table = 'opr_vessel_schedules';
    var $column_order = array('id', 'vessel_name', 'voyage_in', 'voyage_out', 'berth_name', 'eta', 'etd', 'status', null); 
    var $column_search = array('v.vessel_name', 'voyage_in', 'voyage_out'); 
    var $order = array('eta' => 'asc'); 

    public function __construct() {
        parent::__construct();
    }

    private function _get_datatables_query() {
        $this->db->select('s.*, v.vessel_name, b.berth_name');
        $this->db->from('opr_vessel_schedules s');
        $this->db->join('mst_vessels v', 'v.id = s.vessel_id');
        $this->db->join('mst_berths b', 'b.id = s.berth_id', 'left');

        $i = 0;
        foreach ($this->column_search as $item) {
            if(isset($_POST['search']['value'])) {
                if($i===0) {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i) $this->db->group_end(); 
            }
            $i++;
        }
        if(isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables() {
        $this->_get_datatables_query();
        if(isset($_POST['length']) && $_POST['length'] != -1) $this->db->limit($_POST['length'], isset($_POST['start']) ? $_POST['start'] : 0);
        return $this->db->get()->result();
    }

    function count_filtered() {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    public function count_all() {
        return $this->db->count_all($this->table);
    }

    public function get_by_id($id) {
        return $this->db->where('id', $id)->get($this->table)->row();
    }

    public function save($data) {
        return $this->db->insert($this->table, $data);
    }

    public function update($where, $data) {
        return $this->db->update($this->table, $data, $where);
    }

    public function delete_by_id($id) {
        $this->db->where('id', $id)->delete($this->table);
    }
}
