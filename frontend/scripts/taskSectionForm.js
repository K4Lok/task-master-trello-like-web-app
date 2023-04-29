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
updateBtn.addEventListener('click', handleUpdateTaskSection);
deleteBtn.addEventListener('click', handleDeleteTaskSection);

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