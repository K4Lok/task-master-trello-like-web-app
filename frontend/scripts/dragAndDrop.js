let updated = false;

addDragAndDrop();

function addDragAndDrop() {

    const draggables = document.querySelectorAll(".task-card");
    const containers = document.querySelectorAll(".task-container");

    draggables.forEach((draggable) => {
        draggable.addEventListener("dragstart", () => {
            draggable.classList.add("dragging");
        });

        draggable.addEventListener("dragend", () => {
            updateSortingIndex();
            draggable.classList.remove("dragging");

            if (!updated) {
                updated = true;
                const taskId = draggable.dataset.taskId;
                const boardId = draggable.parentElement.dataset.boardId;
                const sectionId = draggable.parentElement.dataset.sectionId;

                moveTaskToSection(taskId, boardId, sectionId);
                setTimeout(() => {
                    updated = false;
                }, 100);
            }
        });
    });

    containers.forEach((container) => {
        container.addEventListener("dragover", (e) => {
            e.preventDefault();
            const afterElement = getDragAfterElement(container, e.clientY);
            const draggable = document.querySelector(".dragging");
            if (afterElement == null) {
                container.appendChild(draggable);
            } else {
                container.insertBefore(draggable, afterElement);
            }
        });
    });

    function getDragAfterElement(container, y) {
        const draggableElements = [
            ...container.querySelectorAll(".task-card:not(.dragging)"),
        ];

        return draggableElements.reduce(
            (closest, child) => {
                const box = child.getBoundingClientRect();
                const offset = y - box.top - box.height / 2;
                if (offset < 0 && offset > closest.offset) {
                    return { offset: offset, element: child };
                } else {
                    return closest;
                }
            },
            { offset: Number.NEGATIVE_INFINITY }
        ).element;
    }
}

function moveTaskToSection(taskId, boardId, sectionId) {
    const formData = new FormData();

    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));

    formData.append('task_id', taskId);
    formData.append('board_id', boardId);
    formData.append('section_id', sectionId);

    fetch('http://localhost:5050/api/task/move', {
        method: 'POST',
        body: formData,
    }).then(res => {
        if (res.ok) {   
            // getTaskSectionAndInsert();
            // getAllCardsAndAttachOptionButton();
            return res.json();
        }
    }).then(response => {
        console.log(response);
    });
}

function updateSortingIndex() {
    const containers = document.querySelectorAll(".task-container");

    containers.forEach(container => {
        const taskCards = container.querySelectorAll('.task-card');

        taskCards.forEach((taskCard, index) => {
            taskCard.dataset.sortIndex = index;

            taskId = taskCard.dataset.taskId;

            updateSortingIndexAPI(taskId, index);
        })
    });
}

function updateSortingIndexAPI(taskId, sortIndex) {
    const formData = new FormData();

    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));

    formData.append('task_id', taskId);
    formData.append('sort_index', sortIndex);

    fetch('http://localhost:5050/api/task/sort', {
        method: 'POST',
        body: formData,
    }).then(res => {
        if (res.ok) {   
            // getTaskSectionAndInsert();
            // getAllCardsAndAttachOptionButton();
            return res.json();
        }
    }).then(response => {
        console.log(response);
    });
}