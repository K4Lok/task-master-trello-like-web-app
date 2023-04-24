const toggleBtn = document.getElementById('toggle-btn');
const sidebar = document.querySelector('aside');

toggleBtn.addEventListener('click', handleToggleSidebar);

let isShowSidebar = true;
// sidebar.style.display = 'none';

function handleToggleSidebar() {
    isShowSidebar = !isShowSidebar;
    sidebar.style.display = isShowSidebar == true ? 'block' : 'none';
}