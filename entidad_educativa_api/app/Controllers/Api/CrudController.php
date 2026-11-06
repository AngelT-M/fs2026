<?php

namespace App\Controllers\Api;

use App\Models\AlumnosModel;
use App\Models\MaestrosModel;
use App\Models\PersonalAdministrativosModel;
use App\Models\UsuariosModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Model;

class CrudController extends ApiController
{
    private const RESOURCE_FIELDS = [
        'usuarios' => ['nom_usuario', 'password', 'rol_id', 'estado'],
        'alumnos' => ['carne', 'nombre', 'apellido', 'fecha_nacimiento', 'email', 'nom_encargado', 'tel_personal', 'tel_encargado'],
        'maestros' => ['carne_maestro', 'nombre', 'apellido', 'fecha_nacimiento', 'email', 'tel_personal', 'tel_casa'],
        'personal' => ['nombre', 'apellido', 'cargo', 'email', 'telefono'],
    ];

    public function index(): ResponseInterface
    {
        $paginacion = $this->parametrosPaginacion();
        if ($paginacion === null) {
            return $this->respuestaError('Parámetros de paginación inválidos.', 400);
        }

        $resource = $this->resourceName();
        $usuario = $this->usuarioActual();
        if ((int) ($usuario['rol_id'] ?? 0) === 5 && $resource !== 'alumnos') {
            return $this->respuestaError('No tienes permiso para consultar este recurso.', 403);
        }

        $data = match ($resource) {
            'usuarios' => model(UsuariosModel::class, false)->listarApi(
                $paginacion['pagina'],
                $paginacion['por_pagina'],
                $usuario
            ),
            'alumnos' => model(AlumnosModel::class, false)->listarApi(
                $paginacion['pagina'],
                $paginacion['por_pagina'],
                $usuario
            ),
            'maestros' => model(MaestrosModel::class, false)->listarApi(
                $paginacion['pagina'],
                $paginacion['por_pagina'],
                $usuario
            ),
            'personal' => model(PersonalAdministrativosModel::class, false)->listarApi(
                $paginacion['pagina'],
                $paginacion['por_pagina'],
                $usuario
            ),
            default => null,
        };

        return $data === null
            ? $this->respuestaError('Recurso no encontrado.', 404)
            : $this->respuestaExitosa($data);
    }

    public function show(string $id): ResponseInterface
    {
        $id = $this->validarId($id);
        if ($id === null) {
            return $this->respuestaError('Identificador inválido.', 400);
        }

        $resource = $this->resourceName();
        $usuario = $this->usuarioActual();
        if ((int) ($usuario['rol_id'] ?? 0) === 5 && $resource !== 'alumnos') {
            return $this->respuestaError('No tienes permiso para consultar este recurso.', 403);
        }

        $data = $this->buscarRegistro($resource, $id, $usuario);

        return $data === null
            ? $this->respuestaError('No se encontró el registro solicitado.', 404)
            : $this->respuestaExitosa($data);
    }

    public function create(): ResponseInterface
    {
        $resource = $this->resourceName();
        $datos = $this->datosEntrada();
        if ($datos === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }
        if (in_array($resource, ['usuarios', 'alumnos', 'maestros', 'personal'], true)
            && (! is_string($datos['nom_usuario'] ?? null) || ! is_string($datos['password'] ?? null))) {
            return $this->respuestaError('El nombre de usuario y la contraseña deben ser texto.', 422);
        }
        if (! $this->validateData($datos, $this->createRules($resource))) {
            return $this->errorValidacion();
        }

        $perfil = array_intersect_key($datos, array_flip(self::RESOURCE_FIELDS[$resource] ?? []));
        $cuenta = [
            'nom_usuario' => trim($datos['nom_usuario']),
            'password' => $datos['password'],
        ];
        $result = match ($resource) {
            'usuarios' => model(UsuariosModel::class, false)->crearApi($datos),
            'alumnos' => model(AlumnosModel::class, false)->registrarAlumnoConUsuario($perfil, $cuenta),
            'maestros' => model(MaestrosModel::class, false)->registrarMaestroConUsuario($perfil, $cuenta),
            'personal' => model(PersonalAdministrativosModel::class, false)->registrarPersonalConUsuario($perfil, $cuenta),
            default => false,
        };

        return $result === false
            ? $this->respuestaError('No fue posible crear el registro.', 409)
            : $this->respuestaExitosa($result, 201, 'Registro creado.');
    }

    public function update(string $id): ResponseInterface
    {
        $resource = $this->resourceName();
        $id = $this->validarId($id);
        if ($id === null) {
            return $this->respuestaError('Identificador inválido.', 400);
        }
        if ($this->buscarRegistro($resource, $id, []) === null) {
            return $this->respuestaError('No se encontró el registro solicitado.', 404);
        }

        $datos = $this->datosEntrada();
        if ($datos === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }
        $datos = array_intersect_key($datos, array_flip(self::RESOURCE_FIELDS[$resource] ?? []));
        if ($datos === []) {
            return $this->respuestaError('No se enviaron campos editables.', 422);
        }
        if (! $this->validateData($datos, $this->updateRules($resource, $id, $datos))) {
            return $this->errorValidacion();
        }

        $result = match ($resource) {
            'usuarios' => model(UsuariosModel::class, false)->actualizarYObtenerApi($id, $datos),
            'alumnos' => model(AlumnosModel::class, false)->actualizarYObtenerApi($id, $datos),
            'maestros' => model(MaestrosModel::class, false)->actualizarYObtenerApi($id, $datos),
            'personal' => model(PersonalAdministrativosModel::class, false)->actualizarYObtenerApi($id, $datos),
            default => false,
        };

        return $result === false
            ? $this->respuestaError('No fue posible actualizar el registro.', 409)
            : $this->respuestaExitosa($result);
    }

    public function delete(string $id): ResponseInterface
    {
        $resource = $this->resourceName();
        $id = $this->validarId($id);
        if ($id === null) {
            return $this->respuestaError('Identificador inválido.', 400);
        }
        if ($this->buscarRegistro($resource, $id, []) === null) {
            return $this->respuestaError('No se encontró el registro solicitado.', 404);
        }

        $deleted = match ($resource) {
            'usuarios' => model(UsuariosModel::class, false)->desactivarApi($id),
            'alumnos' => model(AlumnosModel::class, false)->desactivarApi($id),
            'maestros' => model(MaestrosModel::class, false)->desactivarApi($id),
            'personal' => model(PersonalAdministrativosModel::class, false)->desactivarApi($id),
            default => false,
        };

        return $deleted
            ? $this->respuestaExitosa(null, 200, 'Registro desactivado.')
            : $this->respuestaError('No fue posible desactivar el registro.', 409);
    }

    public function cambiarPassword(string $id): ResponseInterface
    {
        $id = $this->validarId($id);
        $datos = $this->datosEntrada();
        if ($id === null || $datos === null) {
            return $this->respuestaError('Identificador o cuerpo JSON inválido.', 400);
        }
        if (
            ! is_string($datos['password_nuevo'] ?? null)
            || (isset($datos['password_actual']) && ! is_string($datos['password_actual']))
        ) {
            return $this->respuestaError('Las contraseñas deben enviarse como texto.', 422);
        }

        $usuario = $this->usuarioActual();
        $esAdministrador = (int) ($usuario['rol_id'] ?? 0) === 1;
        if (! $esAdministrador && (int) ($usuario['id_usuario'] ?? 0) !== $id) {
            return $this->respuestaError('No tienes permiso para cambiar esta contraseña.', 403);
        }

        $reglas = ['password_nuevo' => 'required|min_length[8]|max_length[255]'];
        if (! $esAdministrador) {
            $reglas['password_actual'] = 'required';
        }
        if ($error = $this->validarEntrada($datos, $reglas)) {
            return $error;
        }

        $passwordActual = $esAdministrador ? null : $datos['password_actual'];
        $actualizado = model(UsuariosModel::class, false)->cambiarPassword(
            $id,
            $datos['password_nuevo'],
            $passwordActual
        );
        return $actualizado
            ? $this->respuestaExitosa(null, 200, 'Contraseña actualizada. Inicia sesión nuevamente.')
            : $this->respuestaError('La contraseña actual es incorrecta o no fue posible actualizarla.', 422);
    }

    private function buscarRegistro(string $resource, int $id, array $usuario): ?array
    {
        return match ($resource) {
            'usuarios' => model(UsuariosModel::class, false)->buscarApi($id, $usuario),
            'alumnos' => model(AlumnosModel::class, false)->buscarApi($id, $usuario),
            'maestros' => model(MaestrosModel::class, false)->buscarApi($id, $usuario),
            'personal' => model(PersonalAdministrativosModel::class, false)->buscarApi($id, $usuario),
            default => null,
        };
    }

    private function resourceName(): string
    {
        return (string) $this->request->getUri()->getSegment(2);
    }

    private function createRules(string $resource): array
    {
        if ($resource === 'usuarios') {
            return [
                'nom_usuario' => 'required|max_length[50]|is_unique[usuarios.nom_usuario]',
                'password' => 'required|min_length[8]|max_length[255]',
                'rol_id' => 'required|integer|is_not_unique[roles.rol_id]',
                'estado' => 'permit_empty|in_list[ACTIVO,INACTIVO]',
            ];
        }

        $rules = [
            'nom_usuario' => 'required|max_length[50]|is_unique[usuarios.nom_usuario]',
            'password' => 'required|min_length[8]|max_length[255]',
            'nombre' => 'required|max_length[50]',
            'apellido' => 'required|max_length[50]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'tel_personal' => 'permit_empty|max_length[20]',
        ];

        return match ($resource) {
            'alumnos' => $rules + [
                'carne' => 'required|max_length[50]|is_unique[alumnos.carne]',
                'fecha_nacimiento' => 'required|valid_date[Y-m-d]',
                'nom_encargado' => 'required|max_length[100]',
                'tel_encargado' => 'required|max_length[20]',
            ],
            'maestros' => $rules + [
                'carne_maestro' => 'required|max_length[50]|is_unique[maestros.carne_maestro]',
                'fecha_nacimiento' => 'required|valid_date[Y-m-d]',
                'tel_casa' => 'permit_empty|max_length[20]',
            ],
            'personal' => $rules + [
                'cargo' => 'required|in_list[DIRECTOR,SUBDIRECTOR,SECRETARIA,CONTADOR,ADMIN]',
                'email' => 'required|valid_email|max_length[100]',
                'telefono' => 'permit_empty|max_length[25]',
            ],
            default => [],
        };
    }

    private function updateRules(string $resource, int $id, array $datos): array
    {
        $rules = [];
        foreach (array_keys($datos) as $field) {
            $rules[$field] = match ($field) {
                'password' => 'required|min_length[8]|max_length[255]',
                'nom_usuario' => "required|max_length[50]|is_unique[usuarios.nom_usuario,usuario_id,{$id}]",
                'rol_id' => 'required|integer|is_not_unique[roles.rol_id]',
                'estado' => 'required|in_list[ACTIVO,INACTIVO]',
                'email' => $resource === 'personal' ? 'required|valid_email|max_length[100]' : 'permit_empty|valid_email|max_length[100]',
                'fecha_nacimiento' => 'permit_empty|valid_date[Y-m-d]',
                'nombre' => 'required|max_length[50]',
                'apellido' => 'required|max_length[50]',
                'carne' => "required|max_length[50]|is_unique[alumnos.carne,alumno_id,{$id}]",
                'carne_maestro' => "required|max_length[50]|is_unique[maestros.carne_maestro,maestro_id,{$id}]",
                'nom_encargado' => 'required|max_length[100]',
                'tel_encargado' => 'required|max_length[20]',
                'tel_personal' => 'permit_empty|max_length[20]',
                'tel_casa' => 'permit_empty|max_length[20]',
                'telefono' => 'permit_empty|max_length[25]',
                'cargo' => 'required|in_list[DIRECTOR,SUBDIRECTOR,SECRETARIA,CONTADOR,ADMIN]',
                default => 'required|max_length[255]',
            };
        }

        return $rules;
    }

    private function errorValidacion(): ResponseInterface
    {
        return $this->respuestaError(
            'Los datos enviados no son válidos: ' . implode('; ', service('validation')->getErrors()),
            422
        );
    }
}
