<?php

namespace Tests\Support\Database\Migrations;

use CodeIgniter\Database\Migration;

class ApiWriteSchema extends Migration
{
    protected $DBGroup = 'tests';

    public function up(): void
    {
        $this->create('roles', 'rol_id', [
            'nom_rol' => $this->varchar(50),
            'descripcion' => $this->text(true),
        ]);
        $this->create('usuarios', 'usuario_id', [
            'nom_usuario' => $this->varchar(50),
            'password' => $this->varchar(255),
            'rol_id' => $this->integer(),
            'estado' => $this->varchar(10),
        ], ['nom_usuario']);
        $this->create('token_acceso', 'token_id', [
            'usuario_id' => $this->integer(),
            'token' => $this->varchar(500),
            'fecha_creacion' => $this->datetime(),
            'fecha_expiracion' => $this->datetime(),
            'activo' => $this->integer(),
        ]);
        $this->create('alumnos', 'alumno_id', [
            'carne' => $this->varchar(50),
            'nombre' => $this->varchar(50),
            'apellido' => $this->varchar(50),
            'fecha_nacimiento' => $this->date(),
            'email' => $this->varchar(100, true),
            'nom_encargado' => $this->varchar(100),
            'tel_personal' => $this->varchar(20, true),
            'tel_encargado' => $this->varchar(20),
            'usuario_id' => $this->integer(),
        ], ['carne', 'usuario_id']);
        $this->create('maestros', 'maestro_id', [
            'carne_maestro' => $this->varchar(50),
            'nombre' => $this->varchar(50),
            'apellido' => $this->varchar(50),
            'fecha_nacimiento' => $this->date(),
            'email' => $this->varchar(100, true),
            'tel_personal' => $this->varchar(20, true),
            'tel_casa' => $this->varchar(20, true),
            'usuario_id' => $this->integer(true),
        ], ['carne_maestro', 'usuario_id']);
        $this->create('personal_administrativos', 'personal_id', [
            'usuario_id' => $this->integer(),
            'nombre' => $this->varchar(50),
            'apellido' => $this->varchar(50),
            'cargo' => $this->varchar(20),
            'email' => $this->varchar(100),
            'telefono' => $this->varchar(25, true),
        ], ['usuario_id']);
        $this->create('niveles_educativos', 'nivel_id', ['nom_nivel' => $this->varchar(50)]);
        $this->create('grados', 'grado_id', [
            'nom_grado' => $this->varchar(50),
            'nivel_id' => $this->integer(),
        ]);
        $this->create('secciones', 'seccion_id', ['nom_seccion' => $this->varchar(20)]);
        $this->create('cursos', 'curso_id', [
            'nom_curso' => $this->varchar(100),
            'descripcion' => $this->text(true),
        ]);
        $this->create('grados_secciones', 'grado_seccion_id', [
            'grado_id' => $this->integer(),
            'seccion_id' => $this->integer(),
            'cantidad_estudiantes' => $this->integer(true),
            'ciclo' => $this->integer(),
        ]);
        $this->create('asignacion_grados', 'asignacion_grado_id', [
            'alumno_id' => $this->integer(),
            'grado_seccion_id' => $this->integer(),
        ]);
        $this->create('asignacion_curso_maestro', 'asignacion_curso_maestro_id', [
            'maestro_id' => $this->integer(),
            'grado_seccion_id' => $this->integer(),
            'curso_id' => $this->integer(),
        ]);
        $this->create('actividades_curso', 'actividad_curso_id', [
            'asignacion_curso_maestro_id' => $this->integer(),
            'bimestre' => $this->varchar(30),
            'nom_actividad' => $this->varchar(100),
            'punteo_maximo' => $this->decimal(5, 2),
            'descripcion' => $this->text(true),
        ]);
        $this->create('calificaciones', 'calificacion_id', [
            'asignacion_grado_id' => $this->integer(),
            'actividad_curso_id' => $this->integer(),
            'punteo_obtenido' => $this->decimal(5, 2),
            'fecha_registro' => $this->datetime(),
        ]);
        $this->create('asistencia', 'asistencia_id', [
            'usuario_id' => $this->integer(),
            'asignacion_grado_id' => $this->integer(),
            'fecha' => $this->date(),
            'estado' => $this->varchar(10),
        ]);
        $this->create('pagos', 'pago_id', [
            'descripcion' => $this->varchar(150),
            'cantidad_pagos' => $this->integer(),
            'monto_base' => $this->decimal(10, 2),
            'grado_id' => $this->integer(),
        ]);
        $this->create('cargos_alumnos', 'cargo_id', [
            'asignacion_grado_id' => $this->integer(),
            'pago_id' => $this->integer(),
            'monto_cobrar' => $this->decimal(10, 2),
            'fecha_vencimiento' => $this->date(),
            'estado' => $this->varchar(20),
        ]);
        $this->create('pagos_realizados', 'pago_realizado_id', [
            'personal_id' => $this->integer(),
            'cargo_id' => $this->integer(),
            'monto_pagado' => $this->decimal(10, 2),
            'fecha_pago' => $this->datetime(),
        ]);
        $this->create('examen_enviados', 'examen_id', [
            'asignacion_curso_maestro_id' => $this->integer(),
            'titulo' => $this->varchar(150),
            'mensaje' => $this->text(true),
            'archivo_url' => $this->varchar(255),
            'estado' => $this->varchar(20),
            'fecha_envio' => $this->datetime(),
            'fecha_autorizacion' => $this->datetime(true),
            'personal_id' => $this->integer(true),
        ]);
        $this->create('calendario_actividades', 'actividad_id', [
            'titulo' => $this->varchar(150),
            'descripcion' => $this->text(true),
            'fecha_inicio' => $this->datetime(),
            'fecha_final' => $this->datetime(),
            'tipo_evento' => $this->varchar(20),
            'usuario_id' => $this->integer(),
            'nivel_id' => $this->integer(true),
        ]);
    }

    public function down(): void
    {
        foreach ([
            'calendario_actividades',
            'examen_enviados',
            'pagos_realizados',
            'cargos_alumnos',
            'pagos',
            'asistencia',
            'calificaciones',
            'actividades_curso',
            'asignacion_curso_maestro',
            'asignacion_grados',
            'grados_secciones',
            'cursos',
            'secciones',
            'grados',
            'niveles_educativos',
            'personal_administrativos',
            'maestros',
            'alumnos',
            'token_acceso',
            'usuarios',
            'roles',
        ] as $table) {
            $this->forge->dropTable($table, true);
        }
    }

    private function create(string $table, string $primaryKey, array $fields, array $uniqueKeys = []): void
    {
        $this->forge->addField([
            $primaryKey => ['type' => 'INTEGER', 'auto_increment' => true],
            ...$fields,
        ]);
        $this->forge->addKey($primaryKey, true);
        foreach ($uniqueKeys as $key) {
            $this->forge->addUniqueKey($key);
        }
        $this->forge->createTable($table);
    }

    private function varchar(int $length, bool $nullable = false): array
    {
        return ['type' => 'VARCHAR', 'constraint' => $length, 'null' => $nullable];
    }

    private function text(bool $nullable = false): array
    {
        return ['type' => 'TEXT', 'null' => $nullable];
    }

    private function integer(bool $nullable = false): array
    {
        return ['type' => 'INTEGER', 'null' => $nullable];
    }

    private function decimal(int $precision, int $scale): array
    {
        return ['type' => 'DECIMAL', 'constraint' => "{$precision},{$scale}"];
    }

    private function date(): array
    {
        return ['type' => 'DATE'];
    }

    private function datetime(bool $nullable = false): array
    {
        return ['type' => 'DATETIME', 'null' => $nullable];
    }
}
