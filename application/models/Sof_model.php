<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sof_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_sof_data($planning_id) {
        $this->db->select('p.*, v.vessel_name, v.call_sign, v.loa, v.flag');
        $this->db->from('opr_planning_requests p');
        $this->db->join('mst_vessels v', 'v.id = p.vessel_id');
        $this->db->where('p.id', $planning_id);
        $planning = $this->db->get()->row();

        if (!$planning) return null;

        // Get Cargo Commenced (First Tally)
        $this->db->select_min('activity_time', 'commence_time');
        $this->db->where('planning_id', $planning_id);
        $commence = $this->db->get('opr_tally_activities')->row()->commence_time;

        // Get Cargo Completed (Last Tally)
        $this->db->select_max('activity_time', 'complete_time');
        $this->db->where('planning_id', $planning_id);
        $complete = $this->db->get('opr_tally_activities')->row()->complete_time;

        // Get Interruptions
        $this->db->where('vessel_id', $planning->vessel_id);
        $this->db->order_by('start_time', 'ASC');
        $interruptions = $this->db->get('opr_interruptions')->result();

        return [
            'planning' => $planning,
            'commence' => $commence,
            'complete' => $complete,
            'interruptions' => $interruptions
        ];
    }
}
