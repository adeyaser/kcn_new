<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup_master_gate extends CI_Controller {

    public function index() {
        $this->load->database();
        
        $menu_url = 'master/gate';
        
        // Find parent 'Master Data' or 'Master'
        $this->db->like('menu_name', 'Master');
        $this->db->where('parent_id', 0);
        $parent = $this->db->get('acl_menus')->row();
        $parent_id = $parent ? $parent->id : 0;

        // Check if menu exists
        $this->db->where('menu_url', $menu_url);
        $menu = $this->db->get('acl_menus')->row();
        
        if (!$menu) {
            $this->db->insert('acl_menus', [
                'menu_name' => 'Master Gate',
                'menu_url' => $menu_url,
                'menu_icon' => 'fas fa-door-open',
                'parent_id' => $parent_id
            ]);
            $menu_id = $this->db->insert_id();
            echo "Menu Master Gate ditambahkan ke Database. <br>";
        } else {
            $menu_id = $menu->id;
        }

        // Add permission for Role 1 (Admin)
        $this->db->where('role_id', 1);
        $this->db->where('menu_id', $menu_id);
        $perm = $this->db->get('acl_permissions')->row();
        
        if (!$perm) {
            $this->db->insert('acl_permissions', [
                'role_id' => 1,
                'menu_id' => $menu_id,
                'can_view' => 1,
                'can_create' => 1,
                'can_edit' => 1,
                'can_delete' => 1
            ]);
            echo "Hak akses Master Gate DIBERIKAN untuk Admin.<br>";
        } else {
            $this->db->where('id', $perm->id)->update('acl_permissions', [
                'can_view' => 1,
                'can_create' => 1,
                'can_edit' => 1,
                'can_delete' => 1
            ]);
            echo "Hak akses Master Gate DIPERBARUI untuk Admin.<br>";
        }
        
        echo "<b>Selesai! Anda sekarang memiliki akses ke menu master/gate. Silakan kembali dan refresh.</b>";
    }
}
