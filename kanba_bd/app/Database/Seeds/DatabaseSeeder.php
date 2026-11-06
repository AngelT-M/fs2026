<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->db->transStart();

        $users = [];
        foreach ([
            ['nombre' => 'Administrador de Prueba', 'correo' => 'admin@prueba.com', 'iniciales' => 'AP', 'avatar_url' => 'https://ui-avatars.com/api/?name=Admin+Prueba'],
            ['nombre' => 'Ana García', 'correo' => 'ana@kanban.test', 'iniciales' => 'AG', 'avatar_url' => 'https://i.pravatar.cc/120?img=47'],
            ['nombre' => 'Carlos López', 'correo' => 'carlos@kanban.test', 'iniciales' => 'CL', 'avatar_url' => 'https://i.pravatar.cc/120?img=12'],
            ['nombre' => 'María Torres', 'correo' => 'maria@kanban.test', 'iniciales' => 'MT', 'avatar_url' => 'https://i.pravatar.cc/120?img=44'],
        ] as $user) {
            $existing = $this->db->table('usuarios')->where('correo', $user['correo'])->get()->getRowArray();
            $user['password_hash'] = password_hash('password123', PASSWORD_DEFAULT);

            if ($existing === null) {
                $this->db->table('usuarios')->insert($user);
                $user['id'] = $this->db->insertID();
            } else {
                $this->db->table('usuarios')->where('id', $existing['id'])->update($user);
                $user['id'] = $existing['id'];
            }
            $users[] = $user;
        }

        $categories = [];
        foreach ([
            ['nombre' => 'Diseño', 'color_hex' => '#8B5CF6'],
            ['nombre' => 'Desarrollo', 'color_hex' => '#3B82F6'],
            ['nombre' => 'Marketing', 'color_hex' => '#F59E0B'],
        ] as $category) {
            $existing = $this->db->table('categorias')->where('nombre', $category['nombre'])->get()->getRowArray();
            if ($existing === null) {
                $this->db->table('categorias')->insert($category);
                $category['id'] = $this->db->insertID();
            } else {
                $this->db->table('categorias')->where('id', $existing['id'])->update($category);
                $category['id'] = $existing['id'];
            }
            $categories[] = $category;
        }

        $projects = [];
        foreach ([
            ['nombre' => 'Rediseño de producto', 'descripcion' => 'Actualización de la experiencia principal de la aplicación.'],
            ['nombre' => 'Lanzamiento campaña Q4', 'descripcion' => 'Planificación de contenidos y materiales de campaña.'],
        ] as $project) {
            $existing = $this->db->table('proyectos')->where('nombre', $project['nombre'])->get()->getRowArray();
            if ($existing === null) {
                $this->db->table('proyectos')->insert($project);
                $project['id'] = $this->db->insertID();
            } else {
                $this->db->table('proyectos')->where('id', $existing['id'])->update($project);
                $project['id'] = $existing['id'];
            }
            $projects[] = $project;
        }

        $tasks = [
            ['proyecto_id' => $projects[0]['id'], 'categoria_id' => $categories[0]['id'], 'asignado_a' => $users[0]['id'], 'titulo' => 'Definir guía visual', 'descripcion' => 'Acordar tipografía, colores y componentes base.', 'estado' => 'PENDIENTE', 'prioridad' => 'ALTA', 'fecha_vencimiento' => '2026-11-05'],
            ['proyecto_id' => $projects[0]['id'], 'categoria_id' => $categories[1]['id'], 'asignado_a' => $users[1]['id'], 'titulo' => 'Preparar estructura del tablero', 'descripcion' => 'Crear la estructura inicial de navegación.', 'estado' => 'EN_PROCESO', 'prioridad' => 'MEDIA', 'fecha_vencimiento' => '2026-11-08'],
            ['proyecto_id' => $projects[0]['id'], 'categoria_id' => $categories[0]['id'], 'asignado_a' => $users[2]['id'], 'titulo' => 'Revisar prototipo', 'descripcion' => 'Validar el prototipo con el equipo.', 'estado' => 'COMPLETADA', 'prioridad' => 'BAJA', 'fecha_vencimiento' => '2026-10-20'],
            ['proyecto_id' => $projects[1]['id'], 'categoria_id' => $categories[2]['id'], 'asignado_a' => $users[0]['id'], 'titulo' => 'Redactar mensajes de campaña', 'descripcion' => 'Preparar copys para los distintos canales.', 'estado' => 'PENDIENTE', 'prioridad' => 'MEDIA', 'fecha_vencimiento' => '2026-11-12'],
            ['proyecto_id' => $projects[1]['id'], 'categoria_id' => $categories[1]['id'], 'asignado_a' => $users[1]['id'], 'titulo' => 'Publicar página de lanzamiento', 'descripcion' => 'Integrar contenido y preparar la publicación.', 'estado' => 'EN_PROCESO', 'prioridad' => 'ALTA', 'fecha_vencimiento' => '2026-11-15'],
        ];

        foreach ($tasks as $task) {
            $existing = $this->db->table('tareas')
                ->where('proyecto_id', $task['proyecto_id'])
                ->where('titulo', $task['titulo'])
                ->get()
                ->getRowArray();

            if ($existing === null) {
                $this->db->table('tareas')->insert($task);
            } else {
                $this->db->table('tareas')->where('id', $existing['id'])->update($task);
            }
        }

        $this->db->transComplete();
    }
}
