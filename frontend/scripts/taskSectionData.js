const sectionContainer = document.getElementById('section-container');

const params = new Proxy(new URLSearchParams(window.location.search), {
    get: (searchParams, prop) => searchParams.get(prop),
});

const id = params.id;

if (id) {
    // http://localhost:5050/api/task-section?token=q02osit3ug19m70v67dl9omikm&uemail=ericsam188@gmail.com&id=2

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

        let cards = '';

        sections.forEach(section => {
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
    });
}