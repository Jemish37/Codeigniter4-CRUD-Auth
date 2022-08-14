<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUser extends Migration
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
            'email' => [
                'type' => 'VARCHAR',
                'unique' => true,
                'constraint' => '255',
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'is_admin' => [
                'type' => 'INT',
                'default' => 0,
                'constraint' => '255',
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true
            ],
            'contact_number' => [
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => true
            ],
            'post_code' => [
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => true
            ],
            'images' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'hobbies' => [
                'type' => 'TEXT',
                'null' => true
            ],  
            'gender' => [
                'type' => 'INT',
                'null' => true,
                'constraint' => '11',
                'comment' => "0 => Male, 1 => Female, 2 => Rather not Say"
            ],
            'city' => [
                'type' => 'INT',
                'null' => true,
                'constraint' => '11',
            ],            
         
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('users');
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
