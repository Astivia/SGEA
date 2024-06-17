document.addEventListener('DOMContentLoaded', () => {
    const createEventBtn = document.getElementById('create-event-btn');
    const createEventModal = document.getElementById('create-event-modal');
    const closeButtons = document.querySelectorAll('.modal .close');

    createEventBtn.addEventListener('click', () => {
        createEventModal.style.display = 'block';
    });

    closeButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.closest('.modal').style.display = 'none';
        });
    });

    window.addEventListener('click', (e) => {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    });

    const menuToggle = document.querySelector('.menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    const menuHeads = document.querySelectorAll('.menu-head');

    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('active');
    });

    menuHeads.forEach(menuHead => {
        menuHead.addEventListener('click', () => {
            menuHead.nextElementSibling.classList.toggle('active');
        });
    });
});
