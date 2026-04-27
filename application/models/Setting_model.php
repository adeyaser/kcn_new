<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting_model extends CI_Model {

    var $table = 'sys_settings';

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->get($this->table)->result();
    }

    public function get_by_group($group) {
        return $this->db->where('setting_group', $group)->get($this->table)->result();
    }

    public function get_value($key) {
        $row = $this->db->where('setting_key', $key)->get($this->table)->row();
        return $row ? $row->setting_value : null;
    }

    public function update_batch($data) {
        foreach ($data as $key => $value) {
            $this->db->where('setting_key', $key);
            $this->db->update($this->table, ['setting_value' => $value]);
        }
        return true;
    }
}
