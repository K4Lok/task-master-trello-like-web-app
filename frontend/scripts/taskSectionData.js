const sectionContainer = document.getElementById('section-container');
const taskBoardIdInput = document.getElementById('task-board-id-input');
const title = document.querySelector('.top-bar h1');

const params = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, prop) => searchParams.get(prop),
});

const id = params.id;
taskBoardIdInput.value = id;

let globalSectionData;

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
    
            insertData(sections);
            getAllCardsAndAttachOptionButton();
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

    fetch('http://localhost:5050/api/task-board/update', {
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

function handleDeleteTaskSection(e) {
    e.preventDefault();

    const form = document.getElementById('more-option-form');
    const formData = new FormData(form);

    formData.append('token', Cookies.get('PHPSESSID'));
    formData.append('uemail', Cookies.get('uemail'));

    fetch('http://localhost:5050/api/task-board/delete', {
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
    const sectionNameInput = document.getElementById('modify-section-name');
    const descriptionInput = document.getElementById('modify-description');

    taskSectionIdInput.value = selectedData['id'];
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
