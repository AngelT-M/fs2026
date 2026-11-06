<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKanbanTables extends Migration
{
    public function up(): void
    {
        foreach (['usuarios', 'proyectos', 'categorias', 'tareas'] as $table) {
            if ($this->db->tableExists($table)) {
                throw new \RuntimeException(
                    'La tabla "' . $table . '" ya existe; no ejecutes esta migración sobre un esquema existente.'
                );
            }
        }

        if (! $this->db->tableExists('usuarios')) {
            $this->forge->addField([
                'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'nombre'       => ['type' => 'VARCHAR', 'constraint' => 100],
                'correo'       => ['type' => 'VARCHAR', 'constraint' => 150],
                'password_hash'=> ['type' => 'VARCHAR', 'constraint' => 255],
                'iniciales'    => ['type' => 'VARCHAR', 'constraint' => 5, 'null' => true],
                'avatar_url'   => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
                'fecha_creacion' => ['type' => 'TIMESTAMP', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('correo');
            $this->forge->createTable('usuarios');
        }

        if (! $this->db->tableExists('proyectos')) {
            $this->forge->addField([
                'id'          => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'nombre'      => ['type' => 'VARCHAR', 'constraint' => 150],
                'descripcion' => ['type' => 'TEXT', 'null' => true],
                'creado_por'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
                'fecha_creacion' => ['type' => 'TIMESTAMP', 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('creado_por');
            $this->forge->addForeignKey('creado_por', 'usuarios', 'id', 'CASCADE', 'SET NULL');
            $this->forge->createTable('proyectos');
        }

        if (! $this->db->tableExists('categorias')) {
            $this->forge->addField([
                'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'nombre'     => ['type' => 'VARCHAR', 'constraint' => 80],
                'color_hex'  => ['type' => 'VARCHAR', 'constraint' => 7, 'null' => true],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('nombre', false, true);
            $this->forge->createTable('categorias');
        }

        if (! $this->db->tableExists('tareas')) {
            $this->forge->addField([
                'id'                 => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
                'proyecto_id'        => ['type' => 'INT', 'unsigned' => true],
                'categoria_id'       => ['type' => 'INT', 'unsigned' => true, 'null' => true],
                'titulo'             => ['type' => 'VARCHAR', 'constraint' => 200],
                'descripcion'        => ['type' => 'TEXT', 'null' => true],
                'estado'             => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'PENDIENTE', 'null' => true],
                'prioridad'          => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'MEDIA', 'null' => true],
                'asignado_a'         => ['type' => 'INT', 'unsigned' => true, 'null' => true],
                'fecha_vencimiento'  => ['type' => 'DATE', 'null' => true],
                'fecha_creacion'     => ['type' => 'TIMESTAMP', 'null' => true],
                'orden'              => ['type' => 'INT', 'unsigned' => true, 'null' => true, 'default' => 0],
            ]);
            $this->forge->addKey('id', true);
            $this->forge->addKey('proyecto_id');
            $this->forge->addKey('categoria_id');
            $this->forge->addKey('asignado_a');
            $this->forge->addForeignKey('proyecto_id', 'proyectos', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('categoria_id', 'categorias', 'id', 'CASCADE', 'SET NULL');
            $this->forge->addForeignKey('asignado_a', 'usuarios', 'id', 'CASCADE', 'SET NULL');
            $this->forge->createTable('tareas');
        }
    }

    public function down(): void
    {
        $this->forge->dropTable('tareas', true);
        $this->forge->dropTable('categorias', true);
        $this->forge->dropTable('proyectos', true);
        $this->forge->dropTable('usuarios', true);
    }
}
