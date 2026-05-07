<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Planning_model extends CI_Model {

    var $table = 'opr_planning_requests';
    var $column_order = array('p.id', 'p.request_no', 'v.vessel_name', 'p.voyage_in', 'p.service_type', 'p.eta', 'p.status', null); 
    var $column_search = array('p.request_no', 'p.voyage_in', 'p.voyage_out'); 
    var $order = array('p.id' => 'desc'); 

    public function __construct()
    {
        parent::__construct();
    }

    private function _get_datatables_query()
    {
        $this->db->select('p.*, v.vessel_name');
        $this->db->from('opr_planning_requests p');
        $this->db->join('mst_vessels v', 'v.id = p.vessel_id', 'left');

        $i = 0;
        foreach ($this->column_search as $item) 
        {
            if(isset($_POST['search']['value']) && $_POST['search']['value'] != '') 
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
        if(isset($_POST['length']) && $_POST['length'] != -1)
        $this->db->limit($_POST['length'], isset($_POST['start']) ? $_POST['start'] : 0);
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
        $this->db->select('p.*, v.vessel_name, v.loa, v.beam, v.draft');
        $this->db->from('opr_planning_requests p');
        $this->db->join('mst_vessels v', 'v.id = p.vessel_id', 'left');
        $this->db->where('p.id',$id);
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

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    public function get_vessel_schedules() {
        $this->db->select('s.*, v.vessel_name, v.vessel_code');
        $this->db->from('opr_vessel_schedules s');
        $this->db->join('mst_vessels v', 'v.id = s.vessel_id', 'left');
        // Include more statuses to be safe
        $this->db->where_in('s.status', ['PLANNED', 'ARRIVED', 'BERTHED', 'DOCKING']);
        $query = $this->db->get();
        return $query->result();
    }

    public function generate_request_no($service_type = 'VSL') {
        $prefix = $service_type . '-' . date('Ymd') . '-';
        $this->db->select('request_no');
        $this->db->like('request_no', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            $last_no = $query->row()->request_no;
            $number = intval(substr($last_no, -4)) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function get_manifest_by_planning_id($planning_id) {
        $this->db->from('opr_manifests');
        $this->db->where('planning_id', $planning_id);
        return $this->db->get()->result();
    }

    public function get_unplanned_containers($planning_id) {
        $this->db->from('opr_manifests');
        $this->db->where('planning_id', $planning_id);
        $this->db->where('bay IS NULL');
        return $this->db->get()->result();
    }

    public function get_containers_on_vessel($planning_id) {
        // For DISCHARGE: Containers that have bay assigned
        // For LOADING: Containers that have bay assigned (already loaded)
        $this->db->from('opr_manifests');
        $this->db->where('planning_id', $planning_id);
        $this->db->where('bay IS NOT NULL');
        return $this->db->get()->result();
    }

    public function get_containers_to_load($planning_id) {
        // Containers in manifest for LOD that are in Yard (ready to be loaded)
        $this->db->select('m.*, i.block_id, i.bay as yard_bay, i.row as yard_row, i.tier as yard_tier');
        $this->db->from('opr_manifests m');
        $this->db->join('opr_yard_inventory i', 'i.container_no = m.container_no', 'inner');
        $this->db->where('m.planning_id', $planning_id);
        $this->db->where('m.bay IS NULL'); // Not yet loaded to vessel
        return $this->db->get()->result();
    }

    public function get_unplanned_yard_containers($planning_id) {
        // Containers in manifest that are NOT in yard inventory yet
        $this->db->select('m.*');
        $this->db->from('opr_manifests m');
        $this->db->join('opr_yard_inventory i', 'i.container_no = m.container_no', 'left');
        $this->db->where('m.planning_id', $planning_id);
        $this->db->where('i.container_no IS NULL');
        return $this->db->get()->result();
    }

    public function update_stowage($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('opr_manifests', $data);
    }

    public function get_vessel_profile_by_planning_id($planning_id) {
        $this->db->select('vp.*');
        $this->db->from('opr_planning_requests pr');
        $this->db->join('mst_vessel_profiles vp', 'vp.vessel_id = pr.vessel_id');
        $this->db->where('pr.id', $planning_id);
        return $this->db->get()->row();
    }

    public function get_all_equipments() {
        return $this->db->where('is_active', 1)->get('mst_equipments')->result();
    }

    public function get_all_vessels() {
        return $this->db->get('mst_vessels')->result();
    }

    public function get_active_schedules() {
        return $this->get_vessel_schedules();
    }

    public function get_equipments_by_planning_id($planning_id) {
        $this->db->select('pe.*, e.equipment_name, e.equipment_type');
        $this->db->from('opr_planning_equipments pe');
        $this->db->join('mst_equipments e', 'e.id = pe.equipment_id');
        $this->db->where('pe.planning_id', $planning_id);
        return $this->db->get()->result();
    }
}
