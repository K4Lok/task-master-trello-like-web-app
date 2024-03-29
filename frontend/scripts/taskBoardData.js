const boardContainer = document.querySelector('.board-container');
const newTaskBoardForm = document.getElementById('new-task-board-form');

const modal = document.querySelector('.modal');
const newTaskBoardModal = document.getElementById('new-task-board-modal');
const moreOptionModal = document.getElementById('more-option-modal');
const cancelButtons = document.querySelectorAll('.cancel-btn');

const newBtn = document.getElementById('new-btn');
const optionBtn = document.querySelectorAll('more-option-btn');
const deleteBtn = document.getElementById('delete-btn');
const updateBtn = document.getElementById('update-btn');

newBtn.addEventListener('click', handleNewBtnClick);

cancelButtons.forEach(cancelButton => {
    cancelButton.addEventListener('click', handleHideModal);
})

updateBtn.addEventListener('click', handleUpdateTaskBoard);

deleteBtn.addEventListener('click', handleDeleteTaskBoard);

newTaskBoardForm.addEventListener('submit', handleNewBoardSubmit);

getTaskBoard();

let globalBoardData;

function getTaskBoard() {
    authentication();

    const sessionId = Cookies.get('PHPSESSID');
    const uemail = Cookies.get('uemail');

    fetch(`${API_URI}/api/task-board?token=${sessionId}&uemail=${uemail}`, {
        method: 'GET',
    })
        .then(res => {
            if (res.ok) {
                return res.json();
            }
        })
        .then(response => {
            taskBoard = response;
            globalBoardData = taskBoard;

            if (!taskBoard.length) {
                return;
            }

            insertData(taskBoard);
            updateSideBar(taskBoard);
            getAllCardsAndAttachOptionButton();

            // if (Array.isArray($taskBoard) && $taskBoard.length > 0) {

            // }
        })
}

// $data: Array
function insertData($data) {
    let cards = '';

    $data.forEach((item, index) => {
        const card = `<div class="board-card">
                            <div class="card-header">
                                <a href="./task_board.html?id=${item['id']}"><h3>${item['name']}</h3></a>
                                <button class="more-option-btn" data-index=${index}>
                                    <img src="./public/resources/option-dot.svg" alt="">
                                </button>
                            </div>
                            <div class="description-box">
                                <p class="description">${item['description']}</p>
                                <p class="meta-data">Sections: ${item['section_num']}, Tasks: ${item['task_num']}</p>
                            </div>
                        </div>`;

        cards += card;
    });

    boardContainer.innerHTML = cards;
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

function handleNewBoardSubmit(e) {
    e.preventDefault();

    const formData = new FormData(newTaskBoardForm);

    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));
    
    fetch(`${API_URI}/api/task-board/create`, {
        method: 'POST',
        body: formData,
    }).then(res => {
        if (res.ok) {   
            handleHideModal();
            getTaskBoard();
            getAllCardsAndAttachOptionButton();
            return res.json();
        }
    }).then(response => {
        console.log(response);
    });
}

function getAllCardsAndAttachOptionButton() {
    const buttons = document.querySelectorAll('.more-option-btn');

    buttons.forEach(button => {
        button.addEventListener('click', () => handleShowOptionModel(button));
        // console.log(button.dataset.taskBoardId);
    })
}

function handleNewBtnClick() {
    modal.style.display = 'flex';
    newTaskBoardModal.style.display = 'flex';
}

function handleHideModal() {
    modal.style.display = 'none';
    newTaskBoardModal.style.display = 'none';
    moreOptionModal.style.display = 'none';
}

function showNewTaskBoardModal() {
    modal.style.display = 'flex';
    moreOptionModal.style.display = 'flex';
}

function handleShowOptionModel(e) {
    showNewTaskBoardModal();

    const selectedIndex = e.dataset.index;
    const selectedData = globalBoardData[selectedIndex];

    const taskBoardIdInput = document.getElementById('task-board-id');
    const boardNameInput = document.getElementById('modify-board-name');
    const descriptionInput = document.getElementById('modify-description');

    taskBoardIdInput.value = selectedData['id'];
    boardNameInput.value = selectedData['name'];
    descriptionInput.value = selectedData['description'];
    boardNameInput.placeholder = selectedData['name'];
    descriptionInput.placeholder = selectedData['description'];
}

function handleUpdateTaskBoard(e) {
    e.preventDefault();

    const form = document.getElementById('more-option-form');
    const formData = new FormData(form);

    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));

    fetch(`${API_URI}/api/task-board/update`, {
        method: "POST",
        body: formData,
    }).then(res => {
        if (res.ok) {
            return res.json();
        }
    }).then(response => {
        if (response.succeed) {
            getTaskBoard();
            handleHideModal();
        }
    });
}

function handleDeleteTaskBoard(e) {
    e.preventDefault();

    const form = document.getElementById('more-option-form');
    const formData = new FormData(form);

    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));

    fetch(`${API_URI}/api/task-board/delete`, {
        method: "POST",
        body: formData,
    }).then(res => {
        if (res.ok) {
            return res.json();
        }
    }).then(response => {
        if (response.succeed) {
            getTaskBoard();
            handleHideModal();
        }
    });
}