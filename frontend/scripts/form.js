const signupForm = document.getElementById('signup');
const loginForm = document.getElementById('login');
const actionBtn = document.querySelector('.action-btn');
const hiddenMessage = document.querySelector('.message');

if (signupForm) signupForm.addEventListener('submit', handleSignupForm);
if (loginForm) loginForm.addEventListener('submit', handleLoginForm);

function delay(time) {
    return new Promise(resolve => setTimeout(resolve, time));
}

async function handleSignupForm(e) {
    e.preventDefault();

    hiddenMessage.style.display = 'none';
    actionBtn.innerHTML = '<div class="loader"></div>';
    // actionBtn.style.pointerEvents = 'none';
    actionBtn.classList.add('loading');

    // To see the spinner effect.
    await delay(500);

    const form = new FormData(signupForm);

    try {
        const res = await fetch('http://localhost:5050/signup', {
            method: "POST",
            body: form
        });

        if (res.ok) {
            const response = await res.json();
            actionBtn.classList.remove('loading');
            hiddenMessage.style.display = 'block';
            actionBtn.innerHTML = 'Sign up';
            
            if (!response.succeed) {
                hiddenMessage.classList.add('not-good');
                hiddenMessage.innerHTML = response.message;

                return;
            }

            hiddenMessage.classList.remove('not-good');
            hiddenMessage.style.display = 'block';
            hiddenMessage.innerHTML = response.message;
        }
    }
    catch (e) {
        console.log('error: ', e);
    }
}

async function handleLoginForm(e) {
    e.preventDefault();

    hiddenMessage.style.display = 'none';
    actionBtn.innerHTML = '<div class="loader"></div>';
    actionBtn.classList.add('loading');

    // To see the spinner effect.
    await delay(500);

    const form = new FormData(loginForm);
    const email = form.get('email');

    try {
        const res = await fetch('http://localhost:5050/login', {
            method: "POST",
            body: form
        });

        if (res.ok) {
            const response = await res.json();
            actionBtn.classList.remove('loading');
            hiddenMessage.style.display = 'block';
            actionBtn.innerHTML = 'Sign up';
            
            if (!response.succeed) {
                hiddenMessage.classList.add('not-good');
                hiddenMessage.innerHTML = response.message;

                Cookies.remove('uemail', { path: '/', domain: '127.0.0.1'});
                Cookies.remove('PHPSESSID', { path: '/', domain: '127.0.0.1'});
                return;
            }

            hiddenMessage.classList.remove('not-good');
            hiddenMessage.style.display = 'block';
            hiddenMessage.innerHTML = response.message;
            Cookies.set('PHPSESSID', response.PHPSESSID);
            Cookies.set('uemail', email);
            // document.cookie = `PHPSESSID=${response.PHPSESSID};`;
            // document.cookie = `uemail=${email};`;
        }

        await delay(1000);
        location.href = './task_board.html';
    }
    catch (e) {
        console.log('error: ', e);
    }
}