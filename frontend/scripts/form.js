const signupForm = document.getElementById('signup');
const actionBtn = document.querySelector('.action-btn');
const hiddenMessage = document.querySelector('.message');

signupForm.addEventListener('submit', handleSignupForm);

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
            
            if (!response.succedd) {
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
