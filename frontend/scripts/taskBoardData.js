const boardContainer = document.querySelector('.board-container');
const modal = document.querySelector('.modal');
const cancelModal = document.querySelector('.cancel-btn');
const newBtn = document.getElementById('new-btn');

newBtn.addEventListener('click', handleNewBtnClick);
cancelModal.addEventListener('click', () => {
    modal.style.display = 'none';
});

getTaskBoard();

function getTaskBoard() {
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
            $taskBoard = response;
            console.log($taskBoard);

            insertData($taskBoard);

            if (Array.isArray($taskBoard) && $taskBoard.length > 0) {

            }
        })
}

// $data: Array
function insertData($data) {
    let cards = '';

    $data.forEach(item => {
        const card = `<div class="board-card">
                            <div class="card-header">
                                <h3>${item['name']}</h3>
                                <button>
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

function handleNewBtnClick() {
    modal.style.display = 'flex';
}