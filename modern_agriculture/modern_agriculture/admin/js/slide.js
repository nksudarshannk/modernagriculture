
const toggleButton = document.getElementById('toggle-btn');
const sidebar = document.getElementById('sidebar');
const content = document.getElementById('content');


let isSidebarCollapsed = false;

toggleButton.addEventListener('click', function() {
   
    isSidebarCollapsed = !isSidebarCollapsed;

 
    if (isSidebarCollapsed) {
        sidebar.classList.add('collapsed');
        content.style.marginLeft = '0'; 
    } else {
        sidebar.classList.remove('collapsed');
        content.style.marginLeft = '200px'; 
    }
});