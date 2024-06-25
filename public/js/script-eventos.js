document.addEventListener('DOMContentLoaded', () => {
    const events = [];
    const eventsPerPage = 6;
    let currentPage = 1;
    let editEventIndex = null;

    const searchInput = document.getElementById('search-input');
    const createEventBtn = document.getElementById('create-event-btn');
    const eventsList = document.getElementById('events-list');
    const pagination = document.getElementById('pagination');
    const registerModal = document.getElementById('register-modal');
    const createEventModal = document.getElementById('create-event-modal');
    const editEventModal = document.getElementById('edit-event-modal');
    const createEventForm = document.getElementById('create-event-form');
    const editEventForm = document.getElementById('edit-event-form');
    const closeModalButtons = document.querySelectorAll('.close');

    function renderEvents(page = 1, searchTerm = '') {
        eventsList.innerHTML = '';
        pagination.innerHTML = '';

        const filteredEvents = events.filter(event => event.name.toLowerCase().includes(searchTerm.toLowerCase()));
        const totalPages = Math.ceil(filteredEvents.length / eventsPerPage);
        const start = (page - 1) * eventsPerPage;
        const end = start + eventsPerPage;
        const eventsToDisplay = filteredEvents.slice(start, end);

        eventsToDisplay.forEach((event, index) => {
            const eventElement = document.createElement('div');
            eventElement.classList.add('event');
            eventElement.innerHTML = `
                <h3>${event.name}</h3>
                <p>Acrónimo: ${event.acronym}</p>
                <p>Fecha de Inicio: ${event.startDate}</p>
                <p>Fecha de Término: ${event.endDate}</p>
                <button class="edit-event-btn" data-index="${start + index}">Editar</button>
                <button class="delete-event-btn" data-index="${start + index}">Eliminar</button>
            `;
            eventsList.appendChild(eventElement);
        });

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            if (i === page) pageButton.classList.add('active');
            pageButton.addEventListener('click', () => {
                currentPage = i;
                renderEvents(i, searchInput.value);
            });
            pagination.appendChild(pageButton);
        }

        const editButtons = document.querySelectorAll('.edit-event-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const index = event.target.dataset.index;
                openEditModal(index);
            });
        });

        const deleteButtons = document.querySelectorAll('.delete-event-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const index = event.target.dataset.index;
                deleteEvent(index);
            });
        });
    }

    function openRegisterModal() {
        registerModal.style.display = 'block';
        setTimeout(() => {
            registerModal.style.display = 'none';
        }, 2000);
    }

    function openCreateModal() {
        createEventModal.style.display = 'block';
    }

    function openEditModal(index) {
        editEventIndex = index;
        const event = events[index];
        document.getElementById('edit-event-name').value = event.name;
        document.getElementById('edit-event-acronym').value = event.acronym;
        document.getElementById('edit-event-start-date').value = event.startDate;
        document.getElementById('edit-event-end-date').value = event.endDate;
        editEventModal.style.display = 'block';
    }

    function closeModals() {
        createEventModal.style.display = 'none';
        editEventModal.style.display = 'none';
    }

    function createEvent(event) {
        event.preventDefault();
        const name = document.getElementById('event-name').value;
        const acronym = document.getElementById('event-acronym').value;
        const startDate = document.getElementById('event-start-date').value;
        const endDate = document.getElementById('event-end-date').value;

        events.push({ name, acronym, startDate, endDate });
        renderEvents(currentPage);
        closeModals();
        openRegisterModal();
    }

    function updateEvent(event) {
        event.preventDefault();
        const name = document.getElementById('edit-event-name').value;
        const acronym = document.getElementById('edit-event-acronym').value;
        const startDate = document.getElementById('edit-event-start-date').value;
        const endDate = document.getElementById('edit-event-end-date').value;

        events[editEventIndex] = { name, acronym, startDate, endDate };
        renderEvents(currentPage);
        closeModals();
        openRegisterModal();
    }

    function deleteEvent(index) {
        events.splice(index, 1);
        renderEvents(currentPage);
    }

    createEventBtn.addEventListener('click', openCreateModal);
    closeModalButtons.forEach(button => button.addEventListener('click', closeModals));
    createEventForm.addEventListener('submit', createEvent);
    editEventForm.addEventListener('submit', updateEvent);
    searchInput.addEventListener('input', () => renderEvents(1, searchInput.value));

    renderEvents();
});