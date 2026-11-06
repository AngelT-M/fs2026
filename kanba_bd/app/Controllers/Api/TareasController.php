<?php

namespace App\Controllers\Api;

use App\Models\CategoriaModel;
use App\Models\ProyectoModel;
use App\Models\TareaModel;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;

class TareasController extends ApiController
{
    private const ESTADOS = ['PENDIENTE', 'EN_PROCESO', 'COMPLETADA'];

    public function index(): ResponseInterface
    {
        return $this->safely(function (): ResponseInterface {
            $rawProjectId = $this->request->getGet('proyecto_id');
            $projectId = null;

            if ($rawProjectId !== null) {
                $projectId = filter_var($rawProjectId, FILTER_VALIDATE_INT);

                if ($projectId === false || $projectId < 1) {
                    return $this->fail('El parámetro proyecto_id debe ser un entero positivo.', 400);
                }
            }

            return $this->respond([
                'data' => $this->taskRows($projectId),
            ]);
        });
    }

    public function porProyecto(int $proyectoId): ResponseInterface
    {
        return $this->safely(function () use ($proyectoId): ResponseInterface {
            if ((new ProyectoModel())->find($proyectoId) === null) {
                return $this->failNotFound('El proyecto no existe.');
            }

            $grouped = array_fill_keys(self::ESTADOS, []);

            foreach ($this->taskRows($proyectoId) as $row) {
                $state = $row['estado'];
                if (isset($grouped[$state])) {
                    $grouped[$state][] = $row;
                }
            }

            return $this->respond([
                'proyecto_id' => $proyectoId,
                'tareas'      => $grouped,
            ]);
        });
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function taskRows(?int $projectId = null): array
    {
        $builder = db_connect()->table('tareas t')
            ->select(
                't.id, t.proyecto_id, t.categoria_id, t.asignado_a, t.titulo, t.descripcion, ' .
                't.estado, t.prioridad, t.fecha_vencimiento, ' .
                'c.nombre AS categoria_nombre, c.color_hex AS categoria_color_hex, ' .
                'u.nombre AS usuario_nombre, u.iniciales AS usuario_iniciales, u.avatar_url AS usuario_avatar_url'
            )
            ->join('categorias c', 'c.id = t.categoria_id', 'left')
            ->join('usuarios u', 'u.id = t.asignado_a', 'left');

        if ($projectId !== null) {
            $builder->where('t.proyecto_id', $projectId);
        }

        $rows = $builder
            ->orderBy('t.proyecto_id', 'ASC')
            ->orderBy('t.id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($rows as &$row) {
            $row['categoria'] = $row['categoria_id'] === null ? null : [
                'id'        => $row['categoria_id'],
                'nombre'    => $row['categoria_nombre'],
                'color_hex' => $row['categoria_color_hex'],
            ];
            $row['usuario_asignado'] = $row['asignado_a'] === null ? null : [
                'id'         => $row['asignado_a'],
                'nombre'     => $row['usuario_nombre'],
                'iniciales'  => $row['usuario_iniciales'],
                'avatar_url' => $row['usuario_avatar_url'],
            ];
            unset(
                $row['categoria_nombre'],
                $row['categoria_color_hex'],
                $row['usuario_nombre'],
                $row['usuario_iniciales'],
                $row['usuario_avatar_url']
            );
        }
        unset($row);

        return $rows;
    }

    public function create(): ResponseInterface
    {
        return $this->safely(function (): ResponseInterface {
            $body = $this->jsonBody();

            if ($body === null) {
                return $this->fail('El cuerpo debe ser un JSON válido.', 400);
            }

            $task = $this->validatedTask($body);
            if ($task instanceof ResponseInterface) {
                return $task;
            }

            $model = new TareaModel();
            $id = $model->insert($task, true);

            if ($id === false) {
                return $this->failServerError('No se pudo crear la tarea.');
            }

            return $this->respondCreated([
                'message' => 'Tarea creada correctamente.',
                'data'    => $model->find($id),
            ]);
        });
    }

    public function cambiarEstado(int $id): ResponseInterface
    {
        return $this->safely(function () use ($id): ResponseInterface {
            $body = $this->jsonBody();
            $state = $body['estado'] ?? null;

            if (! is_string($state) || ! in_array($state, self::ESTADOS, true)) {
                return $this->fail('El estado debe ser PENDIENTE, EN_PROCESO o COMPLETADA.', 400);
            }

            $model = new TareaModel();
            if ($model->find($id) === null) {
                return $this->failNotFound('La tarea no existe.');
            }

            if (! $model->update($id, ['estado' => $state])) {
                return $this->failServerError('No se pudo actualizar el estado de la tarea.');
            }

            return $this->respond([
                'message' => 'Estado actualizado correctamente.',
                'data'    => $model->find($id),
            ]);
        });
    }

    public function update($id = null): ResponseInterface
    {
        return $this->safely(function () use ($id): ResponseInterface {
            $id = (int) $id;
            $body = $this->jsonBody();

            if ($body === null) {
                return $this->fail('El cuerpo debe ser un JSON válido.', 400);
            }

            $model = new TareaModel();
            if ($model->find($id) === null) {
                return $this->failNotFound('La tarea no existe.');
            }

            $task = $this->validatedTask($body);
            if ($task instanceof ResponseInterface) {
                return $task;
            }

            if (! $model->update($id, $task)) {
                return $this->failServerError('No se pudo actualizar la tarea.');
            }

            return $this->respond([
                'message' => 'Tarea actualizada correctamente.',
                'data'    => $model->find($id),
            ]);
        });
    }

    public function delete($id = null): ResponseInterface
    {
        return $this->safely(function () use ($id): ResponseInterface {
            $id = (int) $id;
            $model = new TareaModel();

            if ($model->find($id) === null) {
                return $this->failNotFound('La tarea no existe.');
            }

            if (! $model->delete($id)) {
                return $this->failServerError('No se pudo eliminar la tarea.');
            }

            return $this->respond(['message' => 'Tarea eliminada correctamente.']);
        });
    }

    /**
     * @param array<string, mixed> $input
     * @return array<string, mixed>|ResponseInterface
     */
    private function validatedTask(array $input): array|ResponseInterface
    {
        $rawTitle = $input['titulo'] ?? null;
        $title = is_string($rawTitle) ? trim($rawTitle) : '';
        $rawProjectId = $input['proyecto_id'] ?? null;
        $projectId = is_int($rawProjectId) || is_string($rawProjectId)
            ? filter_var($rawProjectId, FILTER_VALIDATE_INT)
            : false;
        $categoryId = $this->nullableId($input['categoria_id'] ?? null);
        $assignedId = $this->nullableId($input['asignado_a'] ?? $input['usuario_id'] ?? null);
        $state = $input['estado'] ?? 'PENDIENTE';
        $priority = $input['prioridad'] ?? 'MEDIA';
        $dueDate = $input['fecha_vencimiento'] ?? null;

        if ($title === '' || mb_strlen($title) > 200 || $projectId === false || $projectId < 1) {
            return $this->fail('Se requiere un título (máximo 200 caracteres) y un proyecto válido.', 400);
        }

        if ($categoryId === false || $assignedId === false) {
            return $this->fail('Los identificadores de categoría y asignado deben ser números válidos o null.', 400);
        }

        if (! is_string($state) || ! in_array($state, self::ESTADOS, true)) {
            return $this->fail('El estado debe ser PENDIENTE, EN_PROCESO o COMPLETADA.', 400);
        }

        if (! is_string($priority) || ! in_array($priority, ['BAJA', 'MEDIA', 'ALTA'], true)) {
            return $this->fail('La prioridad debe ser BAJA, MEDIA o ALTA.', 400);
        }

        if ($dueDate !== null && (! is_string($dueDate) || ! $this->isValidDate($dueDate))) {
            return $this->fail('La fecha de vencimiento debe tener el formato YYYY-MM-DD.', 400);
        }

        if ((new ProyectoModel())->find($projectId) === null) {
            return $this->fail('El proyecto indicado no existe.', 400);
        }

        if ($categoryId !== null && (new CategoriaModel())->find($categoryId) === null) {
            return $this->fail('La categoría indicada no existe.', 400);
        }

        if ($assignedId !== null && (new UsuarioModel())->find($assignedId) === null) {
            return $this->fail('El usuario asignado indicado no existe.', 400);
        }

        $description = $input['descripcion'] ?? null;
        if ($description !== null && ! is_string($description)) {
            return $this->fail('La descripción debe ser texto o null.', 400);
        }

        return [
            'proyecto_id'       => $projectId,
            'categoria_id'      => $categoryId,
            'asignado_a'        => $assignedId,
            'titulo'            => $title,
            'descripcion'       => $description,
            'estado'            => $state,
            'prioridad'         => $priority,
            'fecha_vencimiento' => $dueDate,
        ];
    }

    private function nullableId(mixed $value): int|false|null
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (! is_int($value) && ! is_string($value)) {
            return false;
        }

        $id = filter_var($value, FILTER_VALIDATE_INT);

        return $id !== false && $id > 0 ? $id : false;
    }

    private function isValidDate(string $date): bool
    {
        $parsed = \DateTime::createFromFormat('!Y-m-d', $date);

        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    }
}
