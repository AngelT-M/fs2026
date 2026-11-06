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
`POST`, `PUT /{id}` y `DELETE /{id}`. Al crear un perfil se envían también
`nom_usuario` y `password`; la cuenta y el perfil se crean en una transacción.
La eliminación desactiva la cuenta y revoca sus tokens para conservar las
relaciones históricas.

## Estructura académica

- `GET /academico/niveles`
- `GET /academico/grados`
- `GET /academico/secciones`
- `GET /academico/cursos`
- `GET /academico/grados-secciones`
- `POST /academico/grados-secciones`
- `POST /academico/asignaciones/alumnos`
- `POST /academico/asignaciones/maestros`

## Evaluaciones y asistencia

- `GET|POST /cursos/{id_acm}/actividades`
- `POST /calificaciones` — recibe una calificación o una lista
  bajo `calificaciones`.
- `GET /alumnos/{id}/boleta`
- `POST /asistencia`
- `POST /examenes` — acepta `archivo_url` o un archivo multipart `archivo`
  (PDF/DOC/DOCX; máximo 10 MB).
- `PUT /examenes/{id}/autorizar` — JSON: `{"estado":"APROBADO"}` o
  `{"estado":"RECHAZADO"}`.

## Finanzas y calendario

- `GET /alumnos/{id}/cargos`
- `POST /pagos/realizar` — JSON: `{"cargo_id":1,"monto_pagado":100.00}`.
  Admite abonos; el monto no puede superar el saldo pendiente.
- `GET|POST /calendario`

La asistencia se asocia a un alumno y aula; el esquema actual no incluye una
columna para identificar materia en el registro de asistencia.

## Roles iniciales

Los permisos se aplican por `rol_id`: ADMIN (1), DIRECTOR (2), SECRETARIA (3),
MAESTRO (4), ALUMNO (5). Los alumnos solo consultan sus propios datos, boleta,
cargos y eventos de su nivel. Los maestros pueden operar sus asignaciones.

El esquema conectado no contiene una tabla `acceso`; por eso el login no
registra auditoría de entradas. El token se almacena en `token_acceso` como hash.
