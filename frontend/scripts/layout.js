const toggleBtn = document.getElementById('toggle-btn');
const sidebar = document.querySelector('aside');

if (toggleBtn) toggleBtn.addEventListener('click', handleToggleSidebar);

let isShowSidebar = window.matchMedia("(min-width: 768px)").matches ? true : false;
if (isShowSidebar && sidebar) {
    sidebar.style.display = 'flex';
}

// Controll Sidebar toggle event
function handleToggleSidebar() {
    isShowSidebar = !isShowSidebar;
    sidebar.style.display = isShowSidebar == true ? 'flex' : 'none';
}

const userBtnGroup = document.getElementById('user-btn-group');

// Display header right corner button based on session
function controlUserButtonGroup() {
    const sessionId = Cookies.get('PHPSESSID');
    const uemail = Cookies.get('uemail');

    if (!sessionId || !uemail) {
        return;
    }

    userBtnGroup.innerHTML = `<button onClick="handleLogout()"><li>Log out</li></button>`;
}

controlUserButtonGroup();

function handleLogout() {
    Cookies.remove('uemail');
    Cookies.remove('PHPSESSID');
    location.reload();
}