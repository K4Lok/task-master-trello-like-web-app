const sectionContainer = document.getElementById('section-container');
const taskBoardIdInput = document.getElementById('task-board-id-input');
const title = document.querySelector('.top-bar h1');

const params = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, prop) => searchParams.get(prop),
});

const id = params.id;
taskBoardIdInput.value = id;

let globalSectionData;
let globalTaskData = {};

getTaskSectionAndInsert();
getTaskBoardAndUpdateSideBar();
getTaskNameByIdAndUpdateTitle(id);

function getTaskSectionAndInsert() {
    if (id) {
        const token = Cookies.get('PHPSESSID');
        const uemail = Cookies.get('uemail');
    
        fetch(`http://localhost:5050/api/task-section?token=${token}&uemail=${uemail}&id=${id}`, {
            method: 'GET'
        }).then(res => {
            if (res.ok) {
                return res.json();
            }
        }).then(response => {
            sections = response;

            console.log(sections);

            globalSectionData = sections;

            if (!sections.length) {
                return;
            }
    
            insertData(sections);
            getAllCardsAndAttachOptionButton();
            getTaskAndInsert();
        });
    }
}

function getTaskNameByIdAndUpdateTitle(id) {
    if (!id) {
        return;
    }

    fetch(`http://localhost:5050/api/task-board/id?id=${id}`, {
            method: 'GET'
        }).then(res => {
            if (res.ok) {
                return res.json();
            }
        }).then(response => {
            taskBoardName = response['name'];

            if (!taskBoardName) {
                return;
            }

            title.innerHTML = taskBoardName;
        });
}

function getTaskBoardAndUpdateSideBar() {
    authentication();

    const sessionId = Cookies.get('PHPSESSID');
    const uemail = Cookies.get('uemail');

    fetch(`http://localhost:5050/api/task-board?token=${sessionId}&uemail=${uemail}`, {
        method: 'GET',
    })
        .then(res => {
            if (res.ok) {
                return res.json();
            }
        })
        .then(response => {
            taskBoard = response;
            globalData = taskBoard;

            updateSideBar(taskBoard);
        })
}

function insertData($data) {
    let cards = '';

    $data.forEach((section, index) => {
        const card = `<div class="section-card">
                            <div class="card-header">
                                <h3>${section['name']}</h3>
                                <button class="more-option-btn" data-index=${index}>
                                    <img src="./public/resources/option-dot.svg" alt="">
                                </button>
                            </div>
                            <div class="description-box">
                                <p class="description">${section['content']}</p>
                            </div>
                            <div class="task-container" data-board-id=${id} data-section-id=${section['id']}>
                            </div>
                        </div>`;

        cards += card;
    });

    sectionContainer.innerHTML = cards;
}


function updateSideBar($data) {
    let contentInnerHTML = `<div class="list-container">
                            <h2>My Task Board</h2>
                                <ul>`;

    $data.forEach((item) => {
        const listItem = `<a href="./task_board.html?id=${item['id']}"><li>${item['name']}</li></a>`;

        contentInnerHTML += listItem;
    });

    contentInnerHTML += `     </ul>
                        </div>`

    const sidebar = document.querySelector('aside');
    const originInnerHTML = sidebar.innerHTML;
    sidebar.innerHTML = contentInnerHTML + originInnerHTML;
}

// Logics for Modal and POST Request

const newTaskSectionForm = document.getElementById('new-task-section-form');

const modal = document.querySelector('.modal');
const newTaskSectionModal = document.getElementById('new-task-section-modal');
const moreOptionModal = document.getElementById('more-option-modal');
const taskMoreOptionModal = document.getElementById('task-more-option-modal');


const newBtn = document.getElementById('new-btn');
const cancelButtons = document.querySelectorAll('.cancel-btn');
const deleteBtn = document.getElementById('delete-btn');
const updateBtn = document.getElementById('update-btn');

newBtn.addEventListener('click', handleNewBtnClick);
newTaskSectionForm.addEventListener('submit', handleNewSectionSubmit);

cancelButtons.forEach(cancelButton => {
    cancelButton.addEventListener('click', handleHideModal);
})

updateBtn.addEventListener('click', handleUpdateTaskSection);
deleteBtn.addEventListener('click', handleDeleteTaskSection);

function handleNewBtnClick() {
    modal.style.display = 'flex';
    newTaskSectionModal.style.display = 'flex';
}

function handleHideModal() {
    modal.style.display = 'none';
    newTaskSectionModal.style.display = 'none';
    moreOptionModal.style.display = 'none';
    taskMoreOptionModal.style.display = 'none';
    newTaskModal.style.display = 'none';
}

function handleNewSectionSubmit(e) {
    e.preventDefault();

    const formData = new FormData(newTaskSectionForm);

    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));
    
    fetch('http://localhost:5050/api/task-section/create', {
        method: 'POST',
        body: formData,
    }).then(res => {
        if (res.ok) {   
            handleHideModal();
            getTaskSectionAndInsert();
            getAllCardsAndAttachOptionButton();
            return res.json();
        }
    }).then(response => {
        console.log(response);
    });
}

function handleUpdateTaskSection(e) {
    e.preventDefault();

    const form = document.getElementById('more-option-form');
    const formData = new FormData(form);

    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));

    fetch('http://localhost:5050/api/task-section/update', {
        method: "POST",
        body: formData,
    }).then(res => {
        if (res.ok) {
            return res.json();
        }
    }).then(response => {
        if (response.succeed) {
            getTaskSectionAndInsert();
            handleHideModal();
        }
    });
}

function handleDeleteTaskSection(e) {
    e.preventDefault();

    const form = document.getElementById('more-option-form');
    const formData = new FormData(form);

    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));

    fetch('http://localhost:5050/api/task-section/delete', {
        method: "POST",
        body: formData,
    }).then(res => {
        if (res.ok) {
            return res.json();
        }
    }).then(response => {
        if (response.succeed) {
            getTaskSectionAndInsert();
            handleHideModal();
        }
    });
}

function getAllCardsAndAttachOptionButton() {
    const buttons = document.querySelectorAll('.more-option-btn');

    buttons.forEach(button => {
        button.addEventListener('click', () => handleShowOptionModel(button));
        // console.log(button.dataset.taskBoardId);
    })
}

function handleShowOptionModel(e) {
    showMoreOptionModal();

    const selectedIndex = e.dataset.index;
    const selectedData = globalSectionData[selectedIndex];

    const taskSectionIdInput = document.getElementById('task-section-id');
    const taskSectionIdInput2 = document.getElementById('task-section-id-2');
    const sectionNameInput = document.getElementById('modify-section-name');
    const descriptionInput = document.getElementById('modify-description');

    taskSectionIdInput.value = selectedData['id'];
    taskSectionIdInput2.value = selectedData['id'];
    sectionNameInput.value = selectedData['name'];
    descriptionInput.value = selectedData['content'];
    sectionNameInput.placeholder = selectedData['name'];
    descriptionInput.placeholder = selectedData['content'];
}

function showMoreOptionModal() {
    modal.style.display = 'flex';
    newTaskSectionModal.style.display = 'none';
    moreOptionModal.style.display = 'flex';
}

// Task Card Logics
const newTaskForm = document.getElementById('new-task-form');
const newTaskModal = document.getElementById('new-task-modal');

const newTaskBtn = document.getElementById('new-task-btn');
const createTaskBtn = document.getElementById('create-task-btn');

newTaskBtn.addEventListener('click', handleShowNewTaskModal);
createTaskBtn.addEventListener('click', handleCreateNewTask);

function handleShowNewTaskModal(e) {
    e.preventDefault();

    handleHideModal();

    modal.style.display = 'flex';
    newTaskModal.style.display = 'flex';
}

function handleCreateNewTask(e) {
    e.preventDefault();

    const taskBoardId = params.id;

    const formData = new FormData(newTaskForm);
    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));
    formData.append('task-board-id', taskBoardId);

    fetch('http://localhost:5050/api/task/create', {
        method: 'POST',
        body: formData,
    }).then(res => {
        if (res.ok) {   
            handleHideModal();
            getTaskSectionAndInsert();
            getAllCardsAndAttachOptionButton();
            return res.json();
        }
    }).then(response => {
        // console.log(response);
    });
}


// Get all the task-container and its section-id to fetch its task card
// getTaskAndInsert();

function getTaskAndInsert() {
    const taskContainers = document.querySelectorAll('.task-container');

    const boardId = params.id;
    taskContainers.forEach(taskContainer => {
        const sectionId = taskContainer.dataset.sectionId;

        fetch(`http://localhost:5050/api/task?board_id=${boardId}&section_id=${sectionId}`, {
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
        addDragAndDrop();
    });
    });
}

function insertTaskData(taskContainer, tasks) {
    let taskCards = '';

    tasks.forEach(task => {
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
                                        <input type="checkbox" value="complete-checkbox" name="complete-checkbox" />
                                        <span>completed</span>
                                    </div>
                                    <span>${task['complete_date']}</span>
                                </div>
                            </div>`;

        taskCards += taskCard;
    });

    taskContainer.innerHTML = taskCards;
    getAllTaskCardsAndAttachOptionButton();
}

function getAllTaskCardsAndAttachOptionButton() {
    const buttons = document.querySelectorAll('.task-more-option-btn');

    buttons.forEach(button => {
        button.addEventListener('click', () => handleTaskShowOptionModel(button));
        // console.log(button.dataset.taskBoardId);
    })
}

function handleTaskShowOptionModel(e) {
    showTaskMoreOptionModal();

    const selectedIndex = e.dataset.index;
    const selectedData = globalTaskData[selectedIndex];

    const taskIdInput = document.getElementById('task-id');
    const taskNameInput = document.getElementById('modify-task-name');
    const descriptionInput = document.getElementById('modify-task-description');
    const completeDateInput = document.getElementById('modify-task-complete-date');

    taskIdInput.value = selectedData['id'];
    taskNameInput.value = selectedData['name'];
    descriptionInput.value = selectedData['content'];
    completeDateInput.value = selectedData['complete_date'];
    taskNameInput.placeholder = selectedData['name'];
    descriptionInput.placeholder = selectedData['content'];
}

function showTaskMoreOptionModal() {
    modal.style.display = 'flex';
    newTaskSectionModal.style.display = 'none';
    taskMoreOptionModal.style.display = 'flex';
}

// Update Task Card

const updateTaskBtn = document.getElementById('update-task-btn');
const deleteTaskBtn = document.getElementById('delete-task-btn');

updateTaskBtn.addEventListener('click', handleUpdateTask);
// deleteTaskBtn.addEventListener('click', handleDeleteTask);

function handleUpdateTask(e) {
    e.preventDefault();

    const form = document.getElementById('task-more-option-form');
    const formData = new FormData(form);

    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));

    fetch('http://localhost:5050/api/task/update', {
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
