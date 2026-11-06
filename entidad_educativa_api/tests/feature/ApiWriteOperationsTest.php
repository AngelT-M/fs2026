<?php

use App\Models\TokenAccesoModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\TestResponse;

/**
 * @internal
 */
final class ApiWriteOperationsTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    private array $tokens = [];
    private bool $fixtureTransactionOpen = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->db->transBegin();
        $this->fixtureTransactionOpen = true;
        $this->insertFixtures();
    }

    protected function tearDown(): void
    {
        if ($this->fixtureTransactionOpen) {
            $this->db->transRollback();
            $this->fixtureTransactionOpen = false;
        }

        parent::tearDown();
    }

    public function testProfileCreationCreatesAccountAndHashesPassword(): void
    {
        $response = $this->request('post', 'alumnos', [
            'nom_usuario' => 'new.student',
            'password' => 'password123',
            'carne' => 'ST-002',
            'nombre' => 'Nueva',
            'apellido' => 'Estudiante',
            'fecha_nacimiento' => '2012-03-10',
            'nom_encargado' => 'Encargado',
            'tel_encargado' => '55551010',
        ]);
        $body = $this->responseBody($response);

        $this->assertSame(201, $response->response()->getStatusCode());
        $this->assertFalse($body['error']);
        $this->assertSame('ST-002', $body['data']['carne']);

        $account = $this->db->table('usuarios')
            ->where('nom_usuario', 'new.student')
            ->get()
            ->getRowArray();
        $profile = $this->db->table('alumnos')
            ->where('carne', 'ST-002')
            ->get()
            ->getRowArray();

        $this->assertNotNull($account);
        $this->assertNotNull($profile);
        $this->assertSame('ACTIVO', $account['estado']);
        $this->assertSame(5, (int) $account['rol_id']);
        $this->assertTrue(password_verify('password123', $account['password']));
        $this->assertSame((int) $account['usuario_id'], (int) $profile['usuario_id']);
    }

    public function testUserCreationHashesPasswordAndOmitsItFromResponse(): void
    {
        $response = $this->request('post', 'usuarios', [
            'nom_usuario' => 'new.admin',
            'password' => 'new-admin-password',
            'rol_id' => 1,
        ]);
        $body = $this->responseBody($response);
        $account = $this->db->table('usuarios')
            ->where('nom_usuario', 'new.admin')
            ->get()
            ->getRowArray();

        $this->assertSame(201, $response->response()->getStatusCode());
        $this->assertArrayNotHasKey('password', $body['data']);
        $this->assertTrue(password_verify('new-admin-password', $account['password']));
    }

    public function testTeacherAndStaffProfilesCreateTheirAccounts(): void
    {
        $teacher = $this->request('post', 'maestros', [
            'nom_usuario' => 'new.teacher',
            'password' => 'new-teacher-password',
            'carne_maestro' => 'TC-002',
            'nombre' => 'Nuevo',
            'apellido' => 'Docente',
            'fecha_nacimiento' => '1985-04-12',
        ]);
        $teacherBody = $this->responseBody($teacher);
        $staff = $this->request('post', 'personal', [
            'nom_usuario' => 'new.staff',
            'password' => 'new-staff-password',
            'nombre' => 'Nuevo',
            'apellido' => 'Personal',
            'cargo' => 'SECRETARIA',
            'email' => 'new.staff@example.com',
        ]);
        $staffBody = $this->responseBody($staff);

        $this->assertSame(201, $teacher->response()->getStatusCode());
        $this->assertSame(201, $staff->response()->getStatusCode());
        $this->assertSame(4, (int) $this->db->table('usuarios')
            ->where('nom_usuario', 'new.teacher')
            ->get()
            ->getRowArray()['rol_id']);
        $this->assertSame(3, (int) $this->db->table('usuarios')
            ->where('nom_usuario', 'new.staff')
            ->get()
            ->getRowArray()['rol_id']);
        $this->assertSame('TC-002', $teacherBody['data']['carne_maestro']);
        $this->assertSame('SECRETARIA', $staffBody['data']['cargo']);
    }

    public function testUserPutAndDeleteDeactivateTokens(): void
    {
        $update = $this->request('put', 'usuarios/1', [
            'password' => 'updated-password',
        ]);
        $account = $this->db->table('usuarios')->where('usuario_id', 1)->get()->getRowArray();

        $this->assertSame(200, $update->response()->getStatusCode());
        $this->assertTrue(password_verify('updated-password', $account['password']));
        $this->assertSame(0, (int) $this->db->table('token_acceso')
            ->where('usuario_id', 1)
            ->where('activo', 1)
            ->countAllResults());

        $this->tokens[1] = $this->createToken(1);
        $delete = $this->request('delete', 'usuarios/1');
        $account = $this->db->table('usuarios')->where('usuario_id', 1)->get()->getRowArray();

        $this->assertSame(200, $delete->response()->getStatusCode());
        $this->assertSame('INACTIVO', $account['estado']);
        $this->assertSame(0, (int) $this->db->table('token_acceso')
            ->where('usuario_id', 1)
            ->where('activo', 1)
            ->countAllResults());
    }

    public function testProfilePutAndDeleteDeactivateItsAccount(): void
    {
        $update = $this->request('put', 'alumnos/1', ['nombre' => 'Nombre actualizado']);
        $this->assertSame(200, $update->response()->getStatusCode());
        $this->assertSame('Nombre actualizado', $this->responseBody($update)['data']['nombre']);

        $this->tokens[4] = $this->createToken(4);
        $delete = $this->request('delete', 'alumnos/1');
        $account = $this->db->table('usuarios')->where('usuario_id', 4)->get()->getRowArray();

        $this->assertSame(200, $delete->response()->getStatusCode());
        $this->assertSame('INACTIVO', $account['estado']);
        $this->assertSame(0, (int) $this->db->table('token_acceso')
            ->where('usuario_id', 4)
            ->where('activo', 1)
            ->countAllResults());
    }

    public function testStudentsCannotReadTeacherOrStaffProfiles(): void
    {
        $this->tokens[4] = $this->createToken(4);

        foreach (['maestros', 'maestros/1', 'personal', 'personal/1'] as $path) {
            $response = $this->request('get', $path, null, 4);
            $this->assertSame(403, $response->response()->getStatusCode(), $path);
        }
    }

    public function testStudentListOnlyReturnsItsOwnProfile(): void
    {
        $this->db->table('usuarios')->insert([
            'usuario_id' => 5,
            'nom_usuario' => 'another.student',
            'password' => password_hash('student-two-password', PASSWORD_DEFAULT),
            'rol_id' => 5,
            'estado' => 'ACTIVO',
        ]);
        $this->db->table('alumnos')->insert([
            'alumno_id' => 2,
            'carne' => 'ST-002',
            'nombre' => 'Segundo',
            'apellido' => 'Estudiante',
            'fecha_nacimiento' => '2012-02-02',
            'nom_encargado' => 'Otro encargado',
            'tel_encargado' => '55550002',
            'usuario_id' => 5,
        ]);
        $this->tokens[4] = $this->createToken(4);

        $response = $this->request('get', 'alumnos', null, 4);
        $body = $this->responseBody($response);

        $this->assertSame(200, $response->response()->getStatusCode());
        $this->assertCount(1, $body['data']);
        $this->assertSame(1, (int) $body['data'][0]['alumno_id']);
    }

    public function testAcademicWritesOpenRoomAndCreateAssignments(): void
    {
        $room = $this->request('post', 'academico/grados-secciones', [
            'grado_id' => 1,
            'seccion_id' => 1,
            'cantidad_estudiantes' => 25,
            'ciclo' => 2026,
        ]);
        $roomBody = $this->responseBody($room);

        $this->assertSame(201, $room->response()->getStatusCode());
        $newRoomId = (int) $roomBody['data']['grado_seccion_id'];

        $studentAssignment = $this->request('post', 'academico/asignaciones/alumnos', [
            'alumno_id' => 1,
            'grado_seccion_id' => $newRoomId,
        ]);
        $teacherAssignment = $this->request('post', 'academico/asignaciones/maestros', [
            'maestro_id' => 1,
            'grado_seccion_id' => $newRoomId,
            'curso_id' => 1,
        ]);

        $this->assertSame(201, $studentAssignment->response()->getStatusCode());
        $this->assertSame(201, $teacherAssignment->response()->getStatusCode());
        $this->assertSame(1, (int) $this->db->table('asignacion_grados')
            ->where('grado_seccion_id', $newRoomId)
            ->countAllResults());
        $this->assertSame(1, (int) $this->db->table('asignacion_curso_maestro')
            ->where('grado_seccion_id', $newRoomId)
            ->countAllResults());
    }

    public function testActivityAndGradeWrites(): void
    {
        $activity = $this->request('post', 'evaluaciones/cursos/1/actividades', [
            'bimestre' => '1er Bimestre',
            'nom_actividad' => 'Tarea 1',
            'punteo_maximo' => 20,
            'descripcion' => 'Actividad de prueba',
        ]);
        $activityBody = $this->responseBody($activity);
        $this->assertSame(201, $activity->response()->getStatusCode());

        $grade = $this->request('post', 'evaluaciones/calificaciones', [
            'asignacion_grado_id' => 1,
            'actividad_curso_id' => $activityBody['data']['actividad_curso_id'],
            'punteo_obtenido' => 18,
        ]);

        $this->assertSame(201, $grade->response()->getStatusCode());
        $this->assertSame(1, (int) $this->db->table('calificaciones')
            ->where('asignacion_grado_id', 1)
            ->where('actividad_curso_id', $activityBody['data']['actividad_curso_id'])
            ->countAllResults());

        $scores = $this->request(
            'get',
            'evaluaciones/actividades/' . $activityBody['data']['actividad_curso_id'] . '/calificaciones',
            null,
            2
        );
        $this->assertSame(200, $scores->response()->getStatusCode());
        $this->assertEquals(18, (float) $this->responseBody($scores)['data'][0]['punteo_obtenido']);
    }

    public function testAttendanceWriteRetainsClassroomAssignment(): void
    {
        $response = $this->request('post', 'asistencia', [
            'alumno_id' => 1,
            'asignacion_grado_id' => 1,
            'fecha' => '2026-11-04',
            'estado' => 'PRESENTE',
        ]);
        $body = $this->responseBody($response);
        $attendance = $this->db->table('asistencia')
            ->where('asistencia_id', $body['data']['asistencia_id'])
            ->get()
            ->getRowArray();

        $this->assertSame(201, $response->response()->getStatusCode());
        $this->assertSame(1, (int) $attendance['asignacion_grado_id']);
        $this->assertSame(4, (int) $attendance['usuario_id']);
        $this->assertSame('PRESENTE', $attendance['estado']);
    }

    public function testAttendanceReadsReturnClassListAndStudentHistory(): void
    {
        $this->db->table('asistencia')->insert([
            'usuario_id' => 4,
            'asignacion_grado_id' => 1,
            'fecha' => '2026-11-04',
            'estado' => 'TARDE',
        ]);
        $this->tokens[4] = $this->createToken(4);

        $classAttendance = $this->request('get', 'asistencia/grado-seccion/1?fecha=2026-11-04', null, 2);
        $classBody = $this->responseBody($classAttendance);
        $studentHistory = $this->request('get', 'alumnos/1/asistencia', null, 4);
        $historyBody = $this->responseBody($studentHistory);

        $this->assertSame(200, $classAttendance->response()->getStatusCode());
        $this->assertSame('TARDE', $classBody['data'][0]['estado']);
        $this->assertSame(200, $studentHistory->response()->getStatusCode());
        $this->assertSame(1, $historyBody['data']['resumen']['tardes']);
        $this->assertCount(1, $historyBody['data']['historial']);
    }

    public function testExamSubmissionAndAuthorization(): void
    {
        $submission = $this->request('post', 'examenes', [
            'asignacion_curso_maestro_id' => 1,
            'titulo' => 'Examen de prueba',
            'mensaje' => 'Revisión requerida',
            'archivo_url' => 'https://example.com/examen.pdf',
        ], 2);
        $submissionBody = $this->responseBody($submission);
        $this->assertSame(201, $submission->response()->getStatusCode());
        $this->assertSame('PENDIENTE', $submissionBody['data']['estado']);

        $authorization = $this->request('put', 'examenes/' . $submissionBody['data']['examen_id'] . '/autorizar', [
            'estado' => 'APROBADO',
        ], 3);
        $this->assertSame(200, $authorization->response()->getStatusCode());
        $this->assertSame('APROBADO', $this->responseBody($authorization)['data']['estado']);
    }

    public function testExamSubmissionRequiresValidFileUrl(): void
    {
        $response = $this->request('post', 'examenes', [
            'asignacion_curso_maestro_id' => 1,
            'titulo' => 'Examen sin URL válida',
            'archivo_url' => 'no es una URL',
        ], 2);

        $this->assertSame(422, $response->response()->getStatusCode());
        $this->assertSame(0, (int) $this->db->table('examen_enviados')->countAllResults());
    }

    public function testPartialPaymentAndCalendarEventWrites(): void
    {
        $payment = $this->request('post', 'pagos/realizar', [
            'cargo_id' => 1,
            'monto_pagado' => 30,
        ], 3);
        $paymentBody = $this->responseBody($payment);

        $this->assertSame(201, $payment->response()->getStatusCode());
        $this->assertSame('PAGO_PARCIAL', $paymentBody['data']['estado']);
        $this->assertEquals(70, $paymentBody['data']['saldo_pendiente']);
        $this->assertSame('PAGO_PARCIAL', $this->db->table('cargos_alumnos')
            ->where('cargo_id', 1)
            ->get()
            ->getRowArray()['estado']);

        $event = $this->request('post', 'calendario', [
            'titulo' => 'Reunión',
            'descripcion' => 'Reunión de prueba',
            'fecha_inicio' => '2026-11-10 09:00:00',
            'fecha_final' => '2026-11-10 10:00:00',
            'tipo_evento' => 'INSTITUCIONAL',
            'nivel_id' => 1,
        ], 3);

        $this->assertSame(201, $event->response()->getStatusCode());
        $this->assertSame(3, (int) $this->responseBody($event)['data']['usuario_id']);
    }

    public function testPaymentReceiptAndBulkChargeGeneration(): void
    {
        $this->db->table('pagos')->insert([
            'pago_id' => 2,
            'descripcion' => 'Cuota extraordinaria',
            'cantidad_pagos' => 1,
            'monto_base' => 50,
            'grado_id' => 1,
        ]);
        $generated = $this->request('post', 'cargos-alumnos/generar', [
            'pago_id' => 2,
            'grado_id' => 1,
            'fecha_vencimiento' => '2026-12-15',
        ]);
        $generatedBody = $this->responseBody($generated);
        $again = $this->request('post', 'cargos-alumnos/generar', [
            'pago_id' => 2,
            'grado_id' => 1,
            'fecha_vencimiento' => '2026-12-15',
        ]);

        $this->assertSame(201, $generated->response()->getStatusCode());
        $this->assertSame(1, $generatedBody['data']['creados']);
        $this->assertSame(0, $this->responseBody($again)['data']['creados']);

        $payment = $this->request('post', 'pagos/realizar', [
            'cargo_id' => 1,
            'monto_pagado' => 30,
        ], 3);
        $paymentId = $this->responseBody($payment)['data']['pago_realizado_id'];
        $receipt = $this->request('get', 'pagos/comprobante/' . $paymentId, null, 3);
        $receiptBody = $this->responseBody($receipt);

        $this->assertSame(200, $receipt->response()->getStatusCode());
        $this->assertSame('Estudiante', $receiptBody['data']['alumno_nombre']);
        $this->assertSame('Encargado', $receiptBody['data']['nom_encargado']);
        $this->assertSame('Cuota mensual', $receiptBody['data']['concepto']);
    }

    public function testPasswordChangeAndDashboardSummary(): void
    {
        $this->tokens[4] = $this->createToken(4);
        $changed = $this->request('put', 'usuarios/4/password', [
            'password_actual' => 'student-password',
            'password_nuevo' => 'student-password-updated',
        ], 4);
        $account = $this->db->table('usuarios')->where('usuario_id', 4)->get()->getRowArray();

        $this->assertSame(200, $changed->response()->getStatusCode());
        $this->assertTrue(password_verify('student-password-updated', $account['password']));
        $this->assertSame(0, (int) $this->db->table('token_acceso')
            ->where('usuario_id', 4)
            ->where('activo', 1)
            ->countAllResults());

        $dashboard = $this->request('get', 'dashboard/resumen');
        $dashboardData = $this->responseBody($dashboard)['data'];
        $this->assertSame(200, $dashboard->response()->getStatusCode());
        $this->assertSame(1, $dashboardData['alumnos_inscritos']);
        $this->assertSame(1, $dashboardData['docentes_activos']);
    }

    public function testCoursesCanBeCreatedAndUpdatedByAdmin(): void
    {
        $created = $this->request('post', 'academico/cursos', [
            'nom_curso' => 'Ciencias Naturales',
            'descripcion' => 'Curso nuevo',
        ]);
        $createdBody = $this->responseBody($created);
        $this->assertSame(201, $created->response()->getStatusCode());
        $courseId = $createdBody['data']['curso_id'];
        $updated = $this->request('put', 'academico/cursos/' . $courseId, [
            'descripcion' => 'Descripción actualizada',
        ]);

        $this->assertSame(200, $updated->response()->getStatusCode());
        $this->assertSame('Descripción actualizada', $this->responseBody($updated)['data']['descripcion']);
    }

    public function testInvalidWriteIsRejectedWithoutDatabaseChanges(): void
    {
        $response = $this->request('post', 'academico/grados-secciones', [
            'grado_id' => 999,
            'seccion_id' => 1,
            'cantidad_estudiantes' => 25,
            'ciclo' => 2026,
        ]);

        $this->assertSame(422, $response->response()->getStatusCode());
        $this->assertTrue($this->responseBody($response)['error']);
        $this->assertSame(1, (int) $this->db->table('grados_secciones')->countAllResults());
    }

    public function testLoginCreatesTokenForValidCredentials(): void
    {
        $tokensBeforeLogin = (int) $this->db->table('token_acceso')
            ->where('usuario_id', 1)
            ->where('activo', 1)
            ->countAllResults();
        $response = $this->withBodyFormat('json')->post('v1/auth/login', [
            'nom_usuario' => 'admin',
            'password' => 'admin-password',
        ]);
        $body = $this->responseBody($response);

        $this->assertSame(200, $response->response()->getStatusCode());
        $this->assertFalse($body['error']);
        $this->assertSame('Bearer', $body['data']['tipo']);
        $this->assertSame($tokensBeforeLogin + 1, (int) $this->db->table('token_acceso')
            ->where('usuario_id', 1)
            ->where('activo', 1)
            ->countAllResults());
    }

    public function testLogoutRevokesThePresentedToken(): void
    {
        $response = $this->request('post', 'auth/logout');
        $tokenHash = hash('sha256', $this->tokens[1]);

        $this->assertSame(200, $response->response()->getStatusCode());
        $this->assertSame(0, (int) $this->db->table('token_acceso')
            ->where('token', $tokenHash)
            ->where('activo', 1)
            ->countAllResults());
    }

    private function insertFixtures(): void
    {
        foreach ([
            1 => 'ADMIN',
            2 => 'DIRECTOR',
            3 => 'SECRETARIA',
            4 => 'MAESTRO',
            5 => 'ALUMNO',
        ] as $id => $role) {
            $this->db->table('roles')->insert([
                'rol_id' => $id,
                'nom_rol' => $role,
                'descripcion' => $role,
            ]);
        }

        foreach ([
            1 => ['admin', 'admin-password', 1],
            2 => ['teacher', 'teacher-password', 4],
            3 => ['secretary', 'secretary-password', 3],
            4 => ['student', 'student-password', 5],
        ] as $id => [$username, $password, $roleId]) {
            $this->db->table('usuarios')->insert([
                'usuario_id' => $id,
                'nom_usuario' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'rol_id' => $roleId,
                'estado' => 'ACTIVO',
            ]);
        }

        $this->db->table('alumnos')->insert([
            'alumno_id' => 1,
            'carne' => 'ST-001',
            'nombre' => 'Estudiante',
            'apellido' => 'Prueba',
            'fecha_nacimiento' => '2012-01-01',
            'nom_encargado' => 'Encargado',
            'tel_encargado' => '55550000',
            'usuario_id' => 4,
        ]);
        $this->db->table('maestros')->insert([
            'maestro_id' => 1,
            'carne_maestro' => 'TC-001',
            'nombre' => 'Docente',
            'apellido' => 'Prueba',
            'fecha_nacimiento' => '1980-01-01',
            'usuario_id' => 2,
        ]);
        $this->db->table('personal_administrativos')->insert([
            'personal_id' => 1,
            'usuario_id' => 3,
            'nombre' => 'Secretaria',
            'apellido' => 'Prueba',
            'cargo' => 'SECRETARIA',
            'email' => 'secretaria@example.com',
        ]);
        $this->db->table('niveles_educativos')->insert([
            'nivel_id' => 1,
            'nom_nivel' => 'Primaria',
        ]);
        $this->db->table('grados')->insert([
            'grado_id' => 1,
            'nom_grado' => 'Primero',
            'nivel_id' => 1,
        ]);
        $this->db->table('secciones')->insert(['seccion_id' => 1, 'nom_seccion' => 'A']);
        $this->db->table('cursos')->insert(['curso_id' => 1, 'nom_curso' => 'Matemática']);
        $this->db->table('grados_secciones')->insert([
            'grado_seccion_id' => 1,
            'grado_id' => 1,
            'seccion_id' => 1,
            'cantidad_estudiantes' => 30,
            'ciclo' => 2026,
        ]);
        $this->db->table('asignacion_grados')->insert([
            'asignacion_grado_id' => 1,
            'alumno_id' => 1,
            'grado_seccion_id' => 1,
        ]);
        $this->db->table('asignacion_curso_maestro')->insert([
            'asignacion_curso_maestro_id' => 1,
            'maestro_id' => 1,
            'grado_seccion_id' => 1,
            'curso_id' => 1,
        ]);
        $this->db->table('pagos')->insert([
            'pago_id' => 1,
            'descripcion' => 'Cuota mensual',
            'cantidad_pagos' => 10,
            'monto_base' => 100,
            'grado_id' => 1,
        ]);
        $this->db->table('cargos_alumnos')->insert([
            'cargo_id' => 1,
            'asignacion_grado_id' => 1,
            'pago_id' => 1,
            'monto_cobrar' => 100,
            'fecha_vencimiento' => '2026-12-01',
            'estado' => 'PENDIENTE',
        ]);

        $this->tokens[1] = $this->createToken(1);
        $this->tokens[2] = $this->createToken(2);
        $this->tokens[3] = $this->createToken(3);
    }

    private function createToken(int $userId): string
    {
        $token = model(TokenAccesoModel::class)->crearToken($userId);
        $this->assertNotFalse($token);

        return $token;
    }

    private function request(string $method, string $path, ?array $data = null, int $userId = 1): TestResponse
    {
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->tokens[$userId],
            'Accept' => 'application/json',
        ])->withBodyFormat('json');

        return $this->{$method}('v1/' . $path, $data);
    }

    private function responseBody(TestResponse $response): array
    {
        return json_decode($response->response()->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }
}
