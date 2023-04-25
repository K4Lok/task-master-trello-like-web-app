const sectionContainer = document.getElementById('section-container');

const params = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, prop) => searchParams.get(prop),
});

const id = params.id;

getTaskSection();
getTaskBoardAndUpdateSideBar();

function getTaskSection() {
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
    
            insertData(sections);
        });
    }
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
            // getAllCardsAndAttachOptionButton();
        })
}

function insertData($data) {
    let cards = '';

    $data.forEach(section => {
        const card = `<div class="section-card">
                            <div class="card-header">
                                <h3>${section['name']}</h3>
                                <button>
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

const newBtn = document.getElementById('new-btn');
const cancelButtons = document.querySelectorAll('.cancel-btn');

newBtn.addEventListener('click', handleNewBtnClick);
newTaskSectionForm.addEventListener('submit', handleNewBoardSubmit);

cancelButtons.forEach(cancelButton => {
    cancelButton.addEventListener('click', handleHideModal);
})

function handleNewBtnClick() {
    modal.style.display = 'flex';
    newTaskSectionModal.style.display = 'flex';
}

function handleHideModal() {
    modal.style.display = 'none';
    newTaskSectionModal.style.display = 'none';
    // moreOptionModal.style.display = 'none';
}

function handleNewBoardSubmit(e) {
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
            getAllCardsAndAttachOptionButton();
            return res.json();
        }
    }).then(response => {
        console.log(response);
    });
}