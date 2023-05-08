// Get all the task-container and its section-id to fetch its task card

function getTaskAndInsert() {
    const taskContainers = document.querySelectorAll('.task-container');

    const boardId = params.id;
    taskContainers.forEach(taskContainer => {
        const sectionId = taskContainer.dataset.sectionId;

        fetch(`${API_URI}/api/task?board_id=${boardId}&section_id=${sectionId}`, {
        method: 'GET',
    }).then(res => {
        if (res.ok) {   
            return res.json();
        }
    }).then(response => {
        if (!response.succeed || response['data'].length === 0) {
            return;
        }

        const tasks = response['data'];

        tasks.forEach(task => {
            globalTaskData[task['id']] = task;
        });

        insertTaskData(taskContainer, tasks);
        getAllTaskCardsAndAttachOptionButton();
        getAllCompleteCheckAndAttachListener();
        greyOutCompletedCard();
        addDragAndDrop();
    });
    });
}

function insertTaskData(taskContainer, tasks) {
    let taskCards = '';

    tasks.forEach(task => {
        const isCompleted = task['is_completed'] ? 'checked' : '';

        const taskCard = `  <div class="task-card" draggable="true" data-task-id=${task['id']} data-sort-index=${task['sort_index']}>
                                <div class="card-header">
                                    <h4>${task['name']}</h4>
                                    <button class="task-more-option-btn" data-index=${task['id']}>
                                        <img src="./public/resources/option-dot.svg" alt="">
                                    </button>
                                </div>
                                <p class="description">${task['content']}</p>
                                <div class="task-card-bottom">
                                    <div class="complete-group">
                                        <input class="complete-checkbox" type="checkbox" value="${task['is_completed']}" data-task-id=${task['id']} name="complete-checkbox" ${isCompleted}/>
                                        <span>completed</span>
                                    </div>
                                    <span class="date">${task['complete_date']}</span>
                                </div>
                            </div>`;

        taskCards += taskCard;
    });

    taskContainer.innerHTML = taskCards;
}

function getAllTaskCardsAndAttachOptionButton() {
    const buttons = document.querySelectorAll('.task-more-option-btn');

    buttons.forEach(button => {
        button.addEventListener('click', () => handleTaskShowOptionModel(button));
        // console.log(button.dataset.taskBoardId);
    })
}

function greyOutCompletedCard() {
    const checkboxes = document.querySelectorAll('.complete-checkbox');

    checkboxes.forEach(checkbox => {

        if (checkbox.checked) {
            checkbox.parentElement.parentElement.parentElement.classList.add("completed");
        }
    });
}