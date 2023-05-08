// Update Task Card

const updateTaskBtn = document.getElementById('update-task-btn');
const deleteTaskBtn = document.getElementById('delete-task-btn');

updateTaskBtn.addEventListener('click', handleUpdateTask);
deleteTaskBtn.addEventListener('click', handleDeleteTask);

function handleUpdateTask(e) {
    e.preventDefault();

    const form = document.getElementById('task-more-option-form');
    const formData = new FormData(form);

    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));

    fetch(`${API_URI}/api/task/update`, {
        method: "POST",
        body: formData,
    }).then(res => {
        if (res.ok) {
            return res.json();
        }
    }).then(response => {
        if (response.succeed) {
            getTaskAndInsert();
            handleHideModal();
        }
    });
}

function handleDeleteTask(e) {
    e.preventDefault();

    const form = document.getElementById('task-more-option-form');
    const formData = new FormData(form);

    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));

    fetch(`${API_URI}/api/task/delete`, {
        method: "POST",
        body: formData,
    }).then(res => {
        if (res.ok) {
            return res.json();
        }
    }).then(response => {
        if (response.succeed) {
            getTaskAndInsert();
            handleHideModal();
        }
    });
}

function getAllCompleteCheckAndAttachListener() {
    const checkboxes = document.querySelectorAll('.complete-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', handleComplete);
    });
}

function handleComplete(e) {
    const taskId = e.target.dataset.taskId;
    const checked = e.target.checked;

    const isCompleted = checked ? 1 : 0;

    if (isCompleted) {
        e.target.parentElement.parentElement.parentElement.classList.add("completed");
    } else {
        e.target.parentElement.parentElement.parentElement.classList.remove("completed");
    }

    const formData = new FormData();

    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));
    formData.append('task_id', taskId);
    formData.append('is_completed', isCompleted);

    fetch(`${API_URI}/api/task/complete`, {
        method: "POST",
        body: formData,
    }).then(res => {
        if (res.ok) {
            return res.json();
        }
    }).then(response => {
        if (response.succeed) {
            // getTaskAndInsert();
        }
    });
}