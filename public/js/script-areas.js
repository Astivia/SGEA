document.addEventListener('DOMContentLoaded', () => {
    const areas = [];
    const areasPerPage = 6;
    let currentPage = 1;

    const searchInput = document.getElementById('search-input');
    const createAreaBtn = document.getElementById('create-event-btn');
    const areasList = document.getElementById('areas-list');
    const pagination = document.getElementById('pagination');

    const registerModal = document.getElementById('register-modal');
    const registerModalClose = registerModal.querySelector('.close');

    const createAreaModal = document.getElementById('create-area-modal');
    const createAreaModalClose = createAreaModal.querySelector('.close');
    const createAreaForm = document.getElementById('create-area-form');
    const areaNameInput = document.getElementById('area-name');
    const areaDescriptionInput = document.getElementById('area-description');

    function closeModal(modal) {
        modal.style.display = 'none';
    }

    function openModal(modal) {
        modal.style.display = 'block';
    }

    function displayRegisterModal() {
        openModal(registerModal);
        setTimeout(() => closeModal(registerModal), 2000);
    }

    registerModalClose.onclick = () => closeModal(registerModal);
    createAreaModalClose.onclick = () => closeModal(createAreaModal);

    window.onclick = (event) => {
        if (event.target === registerModal) {
            closeModal(registerModal);
        } else if (event.target === createAreaModal) {
            closeModal(createAreaModal);
        }
    };

    function displayAreas() {
        areasList.innerHTML = '';
        const start = (currentPage - 1) * areasPerPage;
        const end = start + areasPerPage;
        const paginatedAreas = areas.slice(start, end);

        paginatedAreas.forEach(area => {
            const areaItem = document.createElement('div');
            areaItem.classList.add('area-item');

            const areaContent = `                
                <div class="area">
                    <h3>${area.name}</h3>
                    <p>${area.description}</p>
                    <button class="edit-area-btn">Editar</button>
                    <button class="delete-area-btn" data-id="${area.id}">Eliminar</button>
                </div>
            `;
            areaItem.innerHTML = areaContent;
            areasList.appendChild(areaItem);
        });

        displayPagination();
    }

    function displayPagination() {
        pagination.innerHTML = '';
        const totalPages = Math.ceil(areas.length / areasPerPage);

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.onclick = () => {
                currentPage = i;
                displayAreas();
            };
            if (i === currentPage) {
                pageButton.style.backgroundColor = '#4CAF50';
            }
            pagination.appendChild(pageButton);
        }
    }

    createAreaBtn.onclick = () => openModal(createAreaModal);

    createAreaForm.onsubmit = (event) => {
        event.preventDefault();
        const newArea = {
            id: Date.now(),
            name: areaNameInput.value,
            description: areaDescriptionInput.value,
            photo: 'assets/img/area-default.png'  // Placeholder photo path
        };
        areas.push(newArea);
        displayAreas();
        closeModal(createAreaModal);
        displayRegisterModal();
    };

    searchInput.addEventListener('input', () => {
        const searchTerm = searchInput.value.toLowerCase();
        const filteredAreas = areas.filter(area => 
            area.name.toLowerCase().includes(searchTerm)
        );
        displayFilteredAreas(filteredAreas);
    });

    function displayFilteredAreas(filteredAreas) {
        areasList.innerHTML = '';
        filteredAreas.forEach(area => {
            const areaItem = document.createElement('div');
            areaItem.classList.add('area-item');

            const areaContent = `
                <img src="${area.photo}" alt="Área Foto" class="area-photo">
                <div class="area">
                    <h3>${area.name}</h3>
                    <p>${area.description}</p>
                    <button class="edit-area-btn">Editar</button>
                    <button class="delete-area-btn" data-id="${area.id}">Eliminar</button>
                </div>
            `;
            areaItem.innerHTML = areaContent;
            areasList.appendChild(areaItem);
        });
    }
});
