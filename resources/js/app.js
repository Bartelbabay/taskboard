import './bootstrap';

$(document).ready(function () {
    checkAuthentication();

    $('#login-btn').on('click', function () {
        const username = $('#username').val();
        const password = $('#password').val();

        $.ajax({
            url: '/api/login',
            method: 'POST',
            data: {
                username: username,
                password: password
            },
            success: function (response) {
                if (response.success) {
                    $('#auth-modal').hide();
                    $('header, main').show();
                    fetchTasks();
                } else {
                    alert('Login failed. Please check your credentials.');
                }
            }
        });
    });

    $('#new-task-btn').on('click', function () {
        $('#new-task-modal').show();
    });

    $('#close-modal-btn').on('click', function () {
        $('#new-task-modal').hide();
    });

    $('#save-task-btn').on('click', function () {
        const title = $('#task-title').val();
        const description = $('#task-description').val();
        const due_date = $('#task-due-date').val();
        const status = $('#task-status').val();

        $.ajax({
            url: '/api/tasks',
            method: 'POST',
            data: {
                title: title,
                description: description,
                due_date: due_date,
                is_completed: status
            },
            success: function () {
                fetchTasks();
                $('#new-task-modal').hide();
            }
        });
    });

    $('#close-edit-modal-btn').on('click', function () {
        $('#edit-task-modal').hide();
    });

    $('#save-edit-task-btn').on('click', function () {
        const taskId = $('#edit-task-modal').data('task-id');
        const title = $('#edit-task-title').val();
        const description = $('#edit-task-description').val();
        const due_date = $('#edit-task-due-date').val();
        const status = $('#edit-task-status').val();

        $.ajax({
            url: `/api/tasks/${taskId}`,
            method: 'PUT',
            data: {
                title: title,
                description: description,
                due_date: due_date,
                is_completed: status
            },
            success: function () {
                fetchTasks();
                $('#edit-task-modal').hide();
            }
        });
    });
});

function checkAuthentication() {
    $.ajax({
        url: '/api/check-auth',
        method: 'GET',
        success: function (response) {
            if (response.authenticated) {
                $('#auth-modal').hide();
                $('header, main').show();
                fetchTasks();
            } else {
                $('#auth-modal').show();
                $('header, main').hide();
            }
        }
    });
}

function fetchTasks() {
    $.ajax({
        url: '/api/tasks',
        method: 'GET',
        success: function (tasks) {
            $('#tasks').empty();
            let completedTasks = 0;
            tasks.forEach(task => {
                if (task.is_completed) {
                    completedTasks++;
                }
                let html = `
                    <div class="task">
                        <div>${task.title}</div>
                        <div>${task.description}</div>
                        <button class="edit-task-btn" data-id="${task.id}">Edit</button>`;
                if (task.is_completed) {
                    html += '<span>Completed</span>';
                } else {
                    html += `<button class="mark-completed-btn" data-id="${task.id}">Mark as Completed</button>`;
                }
                html += '</div>';

                $('#tasks').append(html);
            });

            $('#task-summary').text(`Completed Tasks: ${completedTasks}/${tasks.length}`);

            $('.edit-task-btn').on('click', function () {
                const taskId = $(this).data('id');
                $.ajax({
                    url: `/api/tasks/${taskId}`,
                    method: 'GET',
                    success: function (task) {
                        $('#edit-task-title').val(task.title);
                        $('#edit-task-description').val(task.description);
                        $('#edit-task-due-date').val(task.due_date);
                        $('#edit-task-status').val(task.is_completed ? 1 : 0);
                        $('#edit-task-modal').data('task-id', taskId).show();
                    }
                });
            });

            $('.mark-completed-btn').on('click', function () {
                const taskId = $(this).data('id');
                $.ajax({
                    url: `/api/tasks/${taskId}/toggle-complete`,
                    method: 'PUT',
                    data: {
                        is_completed: 1
                    },
                    success: function () {
                        fetchTasks();
                    }
                });
            });
        }
    });
}

