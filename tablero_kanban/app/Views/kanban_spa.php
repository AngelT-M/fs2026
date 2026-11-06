<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f5f6fa">
    <title>Kanban - Espacio de trabajo</title>
    <link rel="stylesheet" href="<?= esc(base_url('kanban/app.css')) ?>">
</head>
<body>
    <main id="login-screen" class="login-screen">
        <section class="login-card">
            <div class="brand-mark">K</div>
            <p class="eyebrow">ESPACIO DE TRABAJO</p>
            <h1>Organiza el trabajo.<br><span>Avanza en equipo.</span></h1>
            <p class="muted">Inicia sesión para abrir tus proyectos y tareas.</p>
            <div id="login-error" class="notice error" role="alert" hidden></div>
            <form id="login-form" class="stack">
                <label for="correo">Correo electrónico</label>
                <input id="correo" name="correo" type="email" autocomplete="username"
                       placeholder="admin@prueba.com" required>
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" autocomplete="current-password"
                       placeholder="Tu contraseña" required>
                <button class="button primary full-width" type="submit">Iniciar sesión</button>
            </form>
            <p class="login-hint">Prueba: <strong>admin@prueba.com</strong> / <strong>password123</strong></p>
        </section>
        <div class="login-decoration decoration-one"></div>
        <div class="login-decoration decoration-two"></div>
    </main>

    <div id="workspace" class="workspace" hidden>
        <aside class="sidebar">
            <a class="brand" href="#" aria-label="Kanban inicio">
                <span class="brand-mark small">K</span><span>kanban<span class="brand-dot">.</span></span>
            </a>
            <div class="sidebar-label">ESPACIO DE TRABAJO</div>
            <div class="workspace-chip"><span class="workspace-icon">⌘</span><span>Mi equipo</span></div>
            <div class="sidebar-label projects-label">PROYECTOS</div>
            <div id="project-list" class="project-list"></div>
            <div class="sidebar-user">
                <div id="user-avatar" class="avatar">U</div>
                <div class="sidebar-user-info"><strong id="user-name">Usuario</strong><span>Sesión de prueba</span></div>
                <button id="logout-button" class="icon-button logout-button" type="button" title="Cerrar sesión" aria-label="Cerrar sesión">↪</button>
            </div>
        </aside>

        <section class="main-panel">
            <header class="topbar">
                <div class="breadcrumbs"><span>Proyectos</span><span class="crumb-separator">/</span><strong id="current-project-name">Cargando...</strong></div>
                <div class="topbar-actions">
                    <div id="member-avatars" class="avatar-stack" aria-label="Miembros del equipo"></div>
                    <button id="new-task-button" class="button primary" type="button"><span>＋</span> Nueva tarea</button>
                </div>
            </header>
            <div id="notice" class="notice" role="status" hidden></div>
            <div class="board-heading">
                <div>
                    <p class="eyebrow">TABLERO DEL PROYECTO</p>
                    <h1 id="board-title">Cargando proyecto...</h1>
                    <p id="board-description" class="muted"></p>
                </div>
                <label class="project-picker-label" for="project-picker">Proyecto
                    <select id="project-picker" aria-label="Seleccionar proyecto"></select>
                </label>
            </div>
            <div class="board-toolbar">
                <div class="board-view-label"><span class="view-icon">▦</span> Tablero</div>
                <span id="task-count" class="task-count">0 tareas</span>
            </div>
            <section id="board" class="board" aria-label="Columnas de tareas"></section>
        </section>
    </div>

    <dialog id="task-dialog" class="task-dialog">
        <form id="task-form" class="task-form">
            <div class="dialog-heading">
                <div><p class="eyebrow">DETALLES</p><h2 id="dialog-title">Nueva tarea</h2></div>
                <button id="close-dialog" class="icon-button close-button" type="button" aria-label="Cerrar">×</button>
            </div>
            <input id="task-id" type="hidden">
            <label for="task-title">Título</label>
            <input id="task-title" name="titulo" maxlength="200" required placeholder="¿Qué hay que hacer?">
            <label for="task-description">Descripción</label>
            <textarea id="task-description" name="descripcion" rows="3" placeholder="Añade algunos detalles..."></textarea>
            <div class="form-grid">
                <div><label for="task-category">Categoría</label><select id="task-category" name="categoria_id"></select></div>
                <div><label for="task-assignee">Asignado a</label><select id="task-assignee" name="asignado_a"></select></div>
                <div><label for="task-state">Estado</label><select id="task-state" name="estado">
                    <option value="PENDIENTE">Pendiente</option>
                    <option value="EN_PROCESO">En proceso</option>
                    <option value="COMPLETADA">Completada</option>
                </select></div>
                <div><label for="task-priority">Prioridad</label><select id="task-priority" name="prioridad">
                    <option value="BAJA">Baja</option><option value="MEDIA">Media</option><option value="ALTA">Alta</option>
                </select></div>
                <div class="form-wide"><label for="task-due-date">Fecha de vencimiento</label><input id="task-due-date" name="fecha_vencimiento" type="date"></div>
            </div>
            <div class="dialog-actions">
                <button id="delete-task-button" class="button danger-button" type="button" hidden>Eliminar</button>
                <span class="dialog-spacer"></span>
                <button id="cancel-task" class="button secondary" type="button">Cancelar</button>
                <button class="button primary" type="submit">Guardar tarea</button>
            </div>
        </form>
    </dialog>

    <script>
        window.KANBAN_API_BASE = <?= json_encode($apiBaseUrl, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    </script>
    <script src="<?= esc(base_url('kanban/app.js')) ?>" defer></script>
</body>
</html>
