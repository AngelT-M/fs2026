# API de entidad educativa

Base URL: `/api`. Excepto el inicio de sesión, las rutas requieren
`Authorization: Bearer <token>`.

Las respuestas usan `status`, `error`, `message` y `data`. Los errores incluyen
`data: null`; los errores de validación usan HTTP 422.

## Autenticación

- `POST /auth/login` — JSON: `{"nom_usuario":"...","password":"..."}`
- `POST /auth/logout`
- `GET /auth/me`

## Usuarios y perfiles

`usuarios`, `alumnos`, `maestros` y `personal` tienen `GET`, `GET /{id}`,
`POST`, `PUT /{id}` y `DELETE /{id}`. Para consultar cuentas activas todavía
sin perfil, usa `GET /usuarios/disponibles?recurso=alumnos|maestros|personal`.
Para personal se puede filtrar el rol con `rol_id=1|2|3`. Al crear alumnos,
maestros o personal se envía el `usuario_id` seleccionado; la API valida que
la cuenta esté activa, no asignada y tenga el rol correspondiente. La eliminación
desactiva la cuenta y revoca sus tokens para conservar las relaciones históricas.

## Estructura académica

- `GET /academico/niveles`
- `GET /academico/grados`
- `GET /academico/secciones`
- `GET /academico/cursos`
- `GET /academico/grados-secciones`
- `GET /academico/bimestres?ciclo=2026` — periodos del ciclo; omite `ciclo`
  para consultar todos.
- `POST /academico/bimestres` — roles administrativos; JSON `{"ciclo":2027}`
  crea los cuatro bimestres y Recuperación para ese ciclo.
- `PUT /academico/bimestres/{id}` — roles administrativos; JSON con
  `fecha_inicio` y `fecha_fin` (`YYYY-MM-DD` o `null`). Los periodos del mismo
  ciclo no pueden tener fechas superpuestas.
- `POST /academico/grados-secciones`
- `POST /academico/asignaciones/alumnos`
- `POST /academico/asignaciones/maestros`

## Evaluaciones y asistencia

- `GET|POST /cursos/{id_acm}/actividades`
- `POST /calificaciones` — recibe una calificación o una lista
  bajo `calificaciones`.
- `GET /evaluaciones/alumnos` — directorio activo para consultar boletas;
  docentes solo reciben estudiantes asignados a sus clases. Acepta
  `asignacion_curso_maestro_id` para filtrar por asignatura y aula.
- `GET /evaluaciones/alumnos/{id}/boleta` — el alumno consulta su propia
  boleta y el docente solo puede consultar estudiantes de sus clases.
- `GET /alumnos/{id}/boleta`
- `POST /asistencia`
- `GET /alumnos/{id}/asistencia?mes=2026-11` — historial y resumen del mes
  seleccionado (`YYYY-MM`); omite `mes` para consultar todo el historial.
- `GET /examenes` — exámenes de todos los estados con bimestre y datos de curso,
  grado y sección
  (Administración, Dirección y Secretaría).
- `POST /examenes` — solo docentes; acepta `archivo_url` o un archivo multipart
  `archivo` (PDF/DOC/DOCX; máximo 10 MB). Requiere `bimestre_id` de un periodo
  del mismo ciclo escolar que la clase.
- `PUT /examenes/{id}/autorizar` — JSON: `{"estado":"APROBADO"}` o
  `{"estado":"RECHAZADO"}`.

`actividades_curso` y `examen_enviados` se relacionan con `bimestres` mediante
`bimestre_id`. Los esquemas SQL de instalación incluyen estas relaciones. Los
exámenes históricos sin periodo siguen apareciendo como “Sin especificar”.

## Finanzas y calendario

- `GET /alumnos/{id}/cargos`
- `POST /pagos/realizar` — JSON: `{"cargo_id":1,"monto_pagado":100.00}`.
  Admite abonos; el monto no puede superar el saldo pendiente.
- `GET /pagos/comprobantes` — últimos 100 comprobantes con datos de estudiante
  y concepto para seleccionarlos sin escribir su ID.
- `GET|POST /calendario`

La asistencia se asocia a un alumno y aula; el esquema actual no incluye una
columna para identificar materia en el registro de asistencia.

## Roles iniciales

Los permisos se aplican por `rol_id`: ADMIN (1), DIRECTOR (2), SECRETARIA (3),
MAESTRO (4), ALUMNO (5). Los alumnos solo consultan sus propios datos, boleta,
cargos y eventos de su nivel. Los maestros pueden operar sus asignaciones.

El esquema conectado no contiene una tabla `acceso`; por eso el login no
registra auditoría de entradas. El token se almacena en `token_acceso` como hash.
