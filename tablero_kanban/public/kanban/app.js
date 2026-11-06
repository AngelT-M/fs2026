(() => {
    'use strict';

    const API_BASE = String(window.KANBAN_API_BASE || '').replace(/\/+$/, '');
    const STATES = [
        { id: 'PENDIENTE', label: 'Pendiente' },
        { id: 'EN_PROCESO', label: 'En proceso' },
        { id: 'COMPLETADA', label: 'Completada' },
    ];
    const TOKEN_KEY = 'kanban.access_token';
    const USER_KEY = 'kanban.user';
    const state = { token: null, user: null, projects: [], categories: [], users: [], projectId: null, tasks: [] };
    const $ = (selector) => document.querySelector(selector);
    const loginScreen = $('#login-screen');
    const workspace = $('#workspace');
    const taskDialog = $('#task-dialog');

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, (char) => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;',
        })[char]);
    }

    function showNotice(message, isError = false, login = false) {
        const target = login ? $('#login-error') : $('#notice');
        target.textContent = message;
        target.classList.toggle('error', isError);
        target.hidden = !message;
    }

    function setLoggedIn(isLoggedIn) {
        loginScreen.hidden = isLoggedIn;
        workspace.hidden = !isLoggedIn;
    }

    function clearSession(message = '') {
        sessionStorage.removeItem(TOKEN_KEY);
        sessionStorage.removeItem(USER_KEY);
        state.token = null;
        state.user = null;
        state.projects = [];
        state.projectId = null;
        setLoggedIn(false);
        showNotice(message, true, true);
    }

    async function request(path, options = {}, authenticated = true) {
        const headers = new Headers(options.headers || {});
        if (options.body !== undefined) headers.set('Content-Type', 'application/json');
        if (authenticated && state.token) headers.set('Authorization', `Bearer ${state.token}`);

        let response;
        try {
            response = await fetch(`${API_BASE}${path}`, { ...options, headers });
        } catch {
            throw new Error(`No se pudo conectar con la API (${API_BASE}). Verifica que el servidor esté activo y que CORS esté habilitado.`);
        }

        const body = response.status === 204 ? null : await response.json().catch(() => null);
        if (response.status === 401 && authenticated) {
            clearSession('Tu sesión expiró. Inicia sesión nuevamente.');
        }
        if (!response.ok) {
            const message = body?.messages?.error || body?.message || `Error HTTP ${response.status}`;
            throw new Error(message);
        }
        return body;
    }

    async function login(correo, password) {
        const result = await request('/login', {
            method: 'POST',
            body: JSON.stringify({ correo, password }),
        }, false);
        state.token = result.access_token;
        state.user = result.usuario;
        sessionStorage.setItem(TOKEN_KEY, state.token);
        sessionStorage.setItem(USER_KEY, JSON.stringify(state.user));
        $('#user-name').textContent = state.user.nombre;
        const avatar = $('#user-avatar');
        if (state.user.avatar_url) {
            avatar.innerHTML = `<img class="avatar" src="${escapeHtml(state.user.avatar_url)}" alt="">`;
        } else {
            avatar.textContent = state.user.iniciales || state.user.nombre.slice(0, 1);
        }
        setLoggedIn(true);
        showNotice('');
        await loadWorkspace();
    }

    function renderProjectOptions() {
        const picker = $('#project-picker');
        picker.innerHTML = state.projects.map((project) =>
            `<option value="${escapeHtml(project.id)}">${escapeHtml(project.nombre)}</option>`
        ).join('');
        picker.value = String(state.projectId);

        $('#project-list').innerHTML = state.projects.map((project) => `
            <button class="project-link ${String(project.id) === String(state.projectId) ? 'active' : ''}"
                    type="button" data-project-id="${escapeHtml(project.id)}">
                ${escapeHtml(project.nombre)}
            </button>`).join('');
    }

    function renderMembers() {
        $('#member-avatars').innerHTML = state.users.slice(0, 5).map((user) => {
            const fallback = escapeHtml(user.iniciales || user.nombre.slice(0, 1));
            return user.avatar_url
                ? `<img class="avatar" src="${escapeHtml(user.avatar_url)}" alt="${escapeHtml(user.nombre)}" title="${escapeHtml(user.nombre)}">`
                : `<span class="avatar" title="${escapeHtml(user.nombre)}">${fallback}</span>`;
        }).join('');

        $('#task-assignee').innerHTML = '<option value="">Sin asignar</option>' + state.users.map((user) =>
            `<option value="${escapeHtml(user.id)}">${escapeHtml(user.nombre)}</option>`
        ).join('');
        $('#task-category').innerHTML = '<option value="">Sin categoría</option>' + state.categories.map((category) =>
            `<option value="${escapeHtml(category.id)}">${escapeHtml(category.nombre)}</option>`
        ).join('');
    }

    async function loadWorkspace() {
        try {
            const [projectResult, userResult, categoryResult] = await Promise.all([
                request('/proyectos'),
                request('/usuarios'),
                request('/categorias'),
            ]);
            state.projects = projectResult.data || [];
            state.users = userResult.data || [];
            state.categories = categoryResult.data || [];

            if (state.projects.length === 0) {
                renderMembers();
                $('#project-picker').innerHTML = '';
                $('#project-list').innerHTML = '';
                $('#current-project-name').textContent = 'Sin proyectos';
                $('#board-title').textContent = 'No hay proyectos disponibles';
                $('#board-description').textContent = 'Carga datos de prueba para comenzar.';
                $('#board').innerHTML = '';
                showNotice('No se encontraron proyectos en la API.');
                return;
            }

            if (!state.projects.some((project) => String(project.id) === String(state.projectId))) {
                state.projectId = state.projects[0].id;
            }
            renderMembers();
            renderProjectOptions();
            await loadProject();
        } catch (error) {
            if (state.token) showNotice(error.message, true);
        }
    }

    async function loadProject() {
        const project = state.projects.find((item) => String(item.id) === String(state.projectId));
        if (!project) return;

        $('#current-project-name').textContent = project.nombre;
        $('#board-title').textContent = project.nombre;
        $('#board-description').textContent = project.descripcion || 'Organiza y da seguimiento a las tareas del proyecto.';
        renderProjectOptions();

        try {
            const result = await request(`/proyectos/${encodeURIComponent(state.projectId)}/tareas`);
            state.tasks = STATES.flatMap((column) => result.tareas?.[column.id] || []);
            renderBoard(result.tareas || {});
            showNotice('');
        } catch (error) {
            if (state.token) showNotice(error.message, true);
        }
    }

    function renderBoard(groupedTasks) {
        let total = 0;
        $('#board').innerHTML = STATES.map((column) => {
            const tasks = groupedTasks[column.id] || [];
            total += tasks.length;
            return `
                <section class="column" data-state="${column.id}">
                    <header class="column-heading">
                        <div class="column-title"><span class="column-dot"></span>${column.label}</div>
                        <span class="column-count">${tasks.length}</span>
                    </header>
                    <div class="task-list" data-drop-state="${column.id}">
                        ${tasks.length ? tasks.map(renderTask).join('') : '<div class="empty-column">Suelta aquí una tarea</div>'}
                    </div>
                </section>`;
        }).join('');
        $('#task-count').textContent = `${total} ${total === 1 ? 'tarea' : 'tareas'}`;
    }

    function renderTask(task) {
        const category = task.categoria;
        const assignee = task.usuario_asignado;
        const priorityClass = task.prioridad === 'ALTA' ? 'priority-high' : task.prioridad === 'BAJA' ? 'priority-low' : '';
        const avatar = assignee?.avatar_url
            ? `<img class="avatar" src="${escapeHtml(assignee.avatar_url)}" alt="">`
            : `<span class="avatar">${escapeHtml(assignee?.iniciales || '?')}</span>`;
        const date = task.fecha_vencimiento
            ? `<span class="due-date">${escapeHtml(task.fecha_vencimiento)}</span>` : '';

        return `
            <article class="task-card" draggable="true" data-task-id="${escapeHtml(task.id)}">
                <div class="task-card-top">
                    <h3 class="task-title">${escapeHtml(task.titulo)}</h3>
                    <button class="icon-button task-edit" type="button" data-edit-task="${escapeHtml(task.id)}"
                            title="Editar tarea" aria-label="Editar tarea">···</button>
                </div>
                ${task.descripcion ? `<p class="task-description">${escapeHtml(task.descripcion)}</p>` : ''}
                <div class="task-tags">
                    ${category ? `<span class="tag" ${category.color_hex ? `style="color:${escapeHtml(category.color_hex)}"` : ''}>${escapeHtml(category.nombre)}</span>` : ''}
                    <span class="tag ${priorityClass}">${escapeHtml(task.prioridad || 'MEDIA')}</span>
                </div>
                <footer class="task-footer">
                    ${assignee ? `<span class="assignee">${avatar}${escapeHtml(assignee.nombre)}</span>` : '<span class="assignee">Sin asignar</span>'}
                    ${date}
                </footer>
            </article>`;
    }

    function openTaskDialog(task = null) {
        $('#task-form').reset();
        $('#task-id').value = task?.id || '';
        $('#dialog-title').textContent = task ? 'Editar tarea' : 'Nueva tarea';
        $('#task-title').value = task?.titulo || '';
        $('#task-description').value = task?.descripcion || '';
        $('#task-category').value = task?.categoria_id || '';
        $('#task-assignee').value = task?.asignado_a || '';
        $('#task-state').value = task?.estado || 'PENDIENTE';
        $('#task-priority').value = task?.prioridad || 'MEDIA';
        $('#task-due-date').value = task?.fecha_vencimiento || '';
        $('#delete-task-button').hidden = !task;
        taskDialog.showModal();
        $('#task-title').focus();
    }

    function taskPayload() {
        const valueOrNull = (value) => value === '' ? null : Number(value);
        return {
            proyecto_id: Number(state.projectId),
            titulo: $('#task-title').value.trim(),
            descripcion: $('#task-description').value.trim() || null,
            categoria_id: valueOrNull($('#task-category').value),
            asignado_a: valueOrNull($('#task-assignee').value),
            estado: $('#task-state').value,
            prioridad: $('#task-priority').value,
            fecha_vencimiento: $('#task-due-date').value || null,
        };
    }

    $('#login-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const button = event.currentTarget.querySelector('button[type="submit"]');
        button.disabled = true;
        button.textContent = 'Conectando...';
        showNotice('');
        try {
            await login($('#correo').value.trim(), $('#password').value);
        } catch (error) {
            showNotice(error.message, true, true);
        } finally {
            button.disabled = false;
            button.textContent = 'Iniciar sesión';
        }
    });

    $('#logout-button').addEventListener('click', () => clearSession(''));
    $('#new-task-button').addEventListener('click', () => openTaskDialog());
    $('#close-dialog').addEventListener('click', () => taskDialog.close());
    $('#cancel-task').addEventListener('click', () => taskDialog.close());
    $('#project-picker').addEventListener('change', async (event) => {
        state.projectId = event.target.value;
        await loadProject();
    });

    $('#project-list').addEventListener('click', async (event) => {
        const button = event.target.closest('[data-project-id]');
        if (!button) return;
        state.projectId = button.dataset.projectId;
        await loadProject();
    });

    $('#task-form').addEventListener('submit', async (event) => {
        event.preventDefault();
        const id = $('#task-id').value;
        const submitButton = event.currentTarget.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        try {
            await request(id ? `/tareas/${encodeURIComponent(id)}` : '/tareas', {
                method: id ? 'PUT' : 'POST',
                body: JSON.stringify(taskPayload()),
            });
            taskDialog.close();
            await loadProject();
            showNotice(id ? 'Tarea actualizada.' : 'Tarea creada.');
        } catch (error) {
            showNotice(error.message, true);
        } finally {
            submitButton.disabled = false;
        }
    });

    $('#delete-task-button').addEventListener('click', async () => {
        const id = $('#task-id').value;
        if (!id || !window.confirm('¿Eliminar esta tarea? Esta acción no se puede deshacer.')) return;
        try {
            await request(`/tareas/${encodeURIComponent(id)}`, { method: 'DELETE' });
            taskDialog.close();
            await loadProject();
            showNotice('Tarea eliminada.');
        } catch (error) {
            showNotice(error.message, true);
        }
    });

    $('#board').addEventListener('click', (event) => {
        const editButton = event.target.closest('[data-edit-task]');
        if (!editButton) return;
        const task = state.tasks.find((item) => String(item.id) === String(editButton.dataset.editTask));
        if (task) openTaskDialog(task);
    });

    let draggedTaskId = null;
    $('#board').addEventListener('dragstart', (event) => {
        const card = event.target.closest('.task-card');
        if (!card) return;
        draggedTaskId = card.dataset.taskId;
        card.classList.add('dragging');
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', draggedTaskId);
    });
    $('#board').addEventListener('dragend', (event) => {
        event.target.closest('.task-card')?.classList.remove('dragging');
        document.querySelectorAll('.column.drag-over').forEach((column) => column.classList.remove('drag-over'));
    });
    $('#board').addEventListener('dragover', (event) => {
        const column = event.target.closest('.column');
        if (!column) return;
        event.preventDefault();
        column.classList.add('drag-over');
    });
    $('#board').addEventListener('dragleave', (event) => {
        const column = event.target.closest('.column');
        if (column && !column.contains(event.relatedTarget)) column.classList.remove('drag-over');
    });
    $('#board').addEventListener('drop', async (event) => {
        const column = event.target.closest('.column');
        if (!column) return;
        event.preventDefault();
        column.classList.remove('drag-over');
        const id = draggedTaskId || event.dataTransfer.getData('text/plain');
        const task = state.tasks.find((item) => String(item.id) === String(id));
        const newState = column.dataset.state;
        if (!task || task.estado === newState) return;

        const previousState = task.estado;
        task.estado = newState;
        renderBoard(Object.fromEntries(STATES.map((item) => [
            item.id, state.tasks.filter((entry) => entry.estado === item.id),
        ])));
        try {
            await request(`/tareas/${encodeURIComponent(id)}/estado`, {
                method: 'PATCH',
                body: JSON.stringify({ estado: newState }),
            });
            await loadProject();
        } catch (error) {
            task.estado = previousState;
            renderBoard(Object.fromEntries(STATES.map((item) => [
                item.id, state.tasks.filter((entry) => entry.estado === item.id),
            ])));
            showNotice(error.message, true);
        }
    });

    async function restoreSession() {
        state.token = sessionStorage.getItem(TOKEN_KEY);
        try {
            state.user = JSON.parse(sessionStorage.getItem(USER_KEY) || 'null');
        } catch {
            state.user = null;
        }

        if (!state.token || !state.user) {
            clearSession('');
            return;
        }
        $('#user-name').textContent = state.user.nombre || 'Usuario';
        const avatar = $('#user-avatar');
        if (state.user.avatar_url) {
            avatar.innerHTML = `<img class="avatar" src="${escapeHtml(state.user.avatar_url)}" alt="">`;
        } else {
            avatar.textContent = state.user.iniciales || '?';
        }
        setLoggedIn(true);
        await loadWorkspace();
    }

    if (!API_BASE) {
        showNotice('No está configurada la URL de la API. Define KANBAN_API_URL en .env.', true, true);
    } else {
        restoreSession();
    }
})();
