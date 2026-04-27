<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acl_model extends CI_Model {

    public function get_user($id) {
        return $this->db->select('u.*, r.role_name, r.role_code')
            ->from('acl_users u')
            ->join('acl_roles r', 'r.id = u.role_id')
            ->where('u.id', $id)
            ->get()->row();
    }

    public function get_user_by_username($username) {
        return $this->db->select('u.*, r.role_name, r.role_code')
            ->from('acl_users u')
            ->join('acl_roles r', 'r.id = u.role_id')
            ->where('u.username', $username)
            ->where('u.is_active', 1)
            ->get()->row();
    }

    public function get_user_menus($role_id) {
        // Get parent menus
        $parents = $this->db->select('m.*')
            ->from('acl_menus m')
            ->join('acl_permissions p', 'p.menu_id = m.id')
            ->where('p.role_id', $role_id)
            ->where('p.can_view', 1)
            ->where('m.parent_id', 0)
            ->where('m.is_active', 1)
            ->order_by('m.menu_order', 'ASC')
            ->get()->result();

        foreach ($parents as &$parent) {
            $parent->children = $this->db->select('m.*')
                ->from('acl_menus m')
                ->join('acl_permissions p', 'p.menu_id = m.id')
                ->where('p.role_id', $role_id)
                ->where('p.can_view', 1)
                ->where('m.parent_id', $parent->id)
                ->where('m.is_active', 1)
                ->order_by('m.menu_order', 'ASC')
                ->get()->result();
        }
        return $parents;
    }

    public function has_permission($role_id, $menu_url, $action = 'can_view') {
        $menu = $this->db->where('menu_url', $menu_url)->get('acl_menus')->row();
        if (!$menu) return false;
        
        $perm = $this->db->where(['role_id' => $role_id, 'menu_id' => $menu->id])
            ->get('acl_permissions')->row();
        if (!$perm) return false;
        
        return (bool)$perm->$action;
    }

    public function get_settings() {
        $result = [];
        $rows = $this->db->get('app_settings')->result();
        foreach ($rows as $row) {
            $result[$row->setting_key] = $row->setting_value;
        }
        return $result;
    }

    public function update_last_login($user_id) {
        $this->db->where('id', $user_id)->update('acl_users', ['last_login' => date('Y-m-d H:i:s')]);
    }

    // ---- CRUD Users ----
    public function get_users_dt($search, $start, $length, $order_col, $order_dir) {
        $this->db->select('u.*, r.role_name')->from('acl_users u')
            ->join('acl_roles r', 'r.id = u.role_id');
        if ($search) {
            $this->db->group_start()
                ->like('u.username', $search)->or_like('u.full_name', $search)->or_like('u.email', $search)
                ->group_end();
        }
        $cols = ['u.id', 'u.username', 'u.full_name', 'u.email', 'r.role_name', 'u.is_active'];
        if (isset($cols[$order_col])) $this->db->order_by($cols[$order_col], $order_dir);
        return $this->db->limit($length, $start)->get()->result();
    }

    public function count_users($search = '') {
        $this->db->from('acl_users u');
        if ($search) {
            $this->db->group_start()
                ->like('u.username', $search)->or_like('u.full_name', $search)->or_like('u.email', $search)
                ->group_end();
        }
        return $this->db->count_all_results();
    }

    public function get_roles() {
        return $this->db->where('is_active', 1)->get('acl_roles')->result();
    }

    public function save_user($data, $id = null) {
        if ($id) {
            return $this->db->where('id', $id)->update('acl_users', $data);
        }
        return $this->db->insert('acl_users', $data);
    }

    public function delete_user($id) {
        return $this->db->where('id', $id)->delete('acl_users');
    }
}
