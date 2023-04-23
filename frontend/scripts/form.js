// http://localhost:5050/signup

const signupForm = document.getElementById('signup');
const actionBtn = document.querySelector('.action-btn');

signupForm.addEventListener('submit', handleSignupForm);

async function handleSignupForm(e) {
    e.preventDefault();

    actionBtn.innerHTML = '<div class="loader"></div>';
    actionBtn.parentElement.style.cursor = 'not-allowed';
    actionBtn.style.pointerEvents = 'none';

    const form = new FormData(signupForm);
    const username = form.get('username');
    const password = form.get('password');
    const email = form.get('email');

    const res = await fetch('http://localhost:5050/signup', {
        method: "POST",
        mode: "no-cors",
        body: JSON.stringify({username, password, email})
});
    const json = await res.json();

    console.log(json);
}
