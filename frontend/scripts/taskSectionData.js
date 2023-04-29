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
getTaskBoardNameByIdAndUpdateTitle(id);

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

function getTaskBoardNameByIdAndUpdateTitle(id) {
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