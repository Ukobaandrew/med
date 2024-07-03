document.addEventListener("DOMContentLoaded", function () {
    const toggleButton = document.querySelector('.toggle-sidebar');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const mainContent = document.querySelector('.content2');
    const dock = document.querySelector('.dock');
    const Calendar = document.querySelector('#calendar');
    toggleButton.addEventListener('click', function () {
        sidebar.classList.toggle('active');
        content.classList.toggle('active');
        mainContent.classList.toggle('expanded-padding');
        Calendar.classList.toggle('padding');
        dock.classList.toggle('dot');
    });
});