<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Init_lift extends CI_Controller {

    public function index() {
        $this->load->dbforge();
        echo "Initializing Lift Operations Table...\n";

        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'gate_transaction_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ],
            'activity_type' => [
                'type' => 'ENUM("LIFT ON", "LIFT OFF")',
                'null' => FALSE
            ],
            'container_no' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'equipment_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'operator_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE
            ],
            'location_block' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => TRUE
            ],
            'location_slot' => [
                'type' => 'VARCHAR',
                'constraint' => 5,
                'null' => TRUE
            ],
            'location_row' => [
                'type' => 'VARCHAR',
                'constraint' => 5,
                'null' => TRUE
            ],
            'location_tier' => [
                'type' => 'VARCHAR',
                'constraint' => 5,
                'null' => TRUE
            ],
            'activity_time' => [
                'type' => 'DATETIME',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('opr_lift_activities', TRUE);

        echo "Table 'opr_lift_activities' created or already exists.\n";
        $this->db->query("ALTER TABLE opr_lift_activities ADD INDEX (gate_transaction_id)");
        echo "Database initialization completed.\n";
    }
}
