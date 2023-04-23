// http://localhost:5050/signup

const signupForm = document.getElementById('signup');
const actionBtn = document.querySelector('.action-btn');

signupForm.addEventListener('submit', handleSignupForm);

function handleSignupForm(e) {
    e.preventDefault();
    console.log(e);
    actionBtn.innerHTML = '<div class="loader"></div>';
    actionBtn.parentElement.style.cursor = 'not-allowed';
    actionBtn.style.pointerEvents = 'none';
}
