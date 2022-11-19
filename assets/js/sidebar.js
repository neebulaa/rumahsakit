const toggleSidebar = document.querySelector('.toggle-sidebar');
const sidebar = document.querySelector('.custom-sidebar');
toggleSidebar.addEventListener('click', function(){
    sidebar.classList.toggle('sidebar-open');
});