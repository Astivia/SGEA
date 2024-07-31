document.addEventListener('DOMContentLoaded', () => {
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
    sidebar.classList.toggle('active');
    ///////////////////////////////MODAL-CREATE///////////////////////////////
    const createBtn= document.getElementById('create-btn');
    if(createBtn!=null){

        const createModal = document.getElementById('create-modal');
        const closeButtons = document.querySelectorAll('.modal .close');

        createBtn.addEventListener('click', () => {
            createModal.style.display = 'block';
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

        
    }
    /////////////////////////////ALERTS////////////////////////////////////////
    // Seleccionar las alertas
    const alerts = document.querySelectorAll('.alert');
    // Función para desvanecer una alerta
    function fadeOutAlert(alert) {
        alert.style.transition = 'opacity 1s ease-in-out';
        alert.style.opacity = 0;

        setTimeout(() => {
            // Verificar si el elemento padre existe
            if (alert.parentNode) {
                alert.parentNode.removeChild(alert);
            }
        }, 1000); // 1 segundo = 1000 milisegundos
    }

    // Recorrer las alertas y agregar un evento de click
    alerts.forEach(alert => {
        alert.addEventListener('click', () => {
            fadeOutAlert(alert);

        });
    });
});

    
