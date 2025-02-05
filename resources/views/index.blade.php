<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Board</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<!-- Authorization Modal -->
<div id="auth-modal" style="display: none;" class="modal">
    <div>
        <label for="username">Username:</label>
        <input type="text" id="username">
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" id="password">
    </div>
    <button id="login-btn">Login</button>
</div>

<!-- Main Content -->
<header style="display: none;">
    <h1>Task Board</h1>
    <p id="task-summary">Completed Tasks: 0/0</p>
</header>
<main style="display: none;">
    <div id="tasks"></div>
    <button id="new-task-btn">New Task</button>
</main>

<!-- New Task Modal -->
<div id="new-task-modal" style="display: none;" class="modal">
    <div>
        <label for="task-title">Title:</label>
        <input type="text" id="task-title">
    </div>
    <div>
        <label for="task-description">Description:</label>
        <textarea id="task-description"></textarea>
    </div>
    <div>
        <label for="task-due-date">Due Date:</label>
        <input type="date" id="task-due-date">
    </div>
    <div>
        <label for="task-status">Status:</label>
        <select id="task-status">
            <option value="0">Not Completed</option>
            <option value="1">Completed</option>
        </select>
    </div>
    <button id="save-task-btn">Save Task</button>
    <button id="close-modal-btn">Close</button>
</div>

<!-- Edit Task Modal -->
<div id="edit-task-modal" style="display: none;" class="modal">
    <div>
        <label for="edit-task-title">Title:</label>
        <input type="text" id="edit-task-title">
    </div>
    <div>
        <label for="edit-task-description">Description:</label>
        <textarea id="edit-task-description"></textarea>
    </div>
    <div>
        <label for="edit-task-due-date">Due Date:</label>
        <input type="date" id="edit-task-due-date">
    </div>
    <div>
        <label for="edit-task-status">Status:</label>
        <select id="edit-task-status">
            <option value="0">Not Completed</option>
            <option value="1">Completed</option>
        </select>
    </div>
    <button id="save-edit-task-btn">Save Task</button>
    <button id="close-edit-modal-btn">Close</button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
