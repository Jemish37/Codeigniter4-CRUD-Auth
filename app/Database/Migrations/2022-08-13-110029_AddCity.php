<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCity extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 255,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],           
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('cities');
    }

    public function down()
    {
        $this->forge->dropTable('cities');
    }
}
