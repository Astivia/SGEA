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

        const filteredEvents = events.filter(event => event.eventId.toLowerCase().includes(searchTerm.toLowerCase()));
        const totalPages = Math.ceil(filteredEvents.length / eventsPerPage);
        const start = (page - 1) * eventsPerPage;
        const end = start + eventsPerPage;
        const eventsToDisplay = filteredEvents.slice(start, end);

        eventsToDisplay.forEach((event, index) => {
            const eventElement = document.createElement('div');
            eventElement.classList.add('event');
            eventElement.innerHTML = `
                <h3>ID: ${event.eventId}</h3>
                <p>Nombres: ${event.names}</p>
                <p>Apellidos: ${event.lastnames}</p>
                <p>Email: ${event.email}</p>
                <p>CURP: ${event.curp}</p>
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
        document.getElementById('edit-event-id').value = event.eventId;
        document.getElementById('edit-event-names').value = event.names;
        document.getElementById('edit-event-lastnames').value = event.lastnames;
        document.getElementById('edit-event-email').value = event.email;
        document.getElementById('edit-event-curp').value = event.curp;
        editEventModal.style.display = 'block';
    }

    function closeModals() {
        createEventModal.style.display = 'none';
        editEventModal.style.display = 'none';
    }

    function createEvent(event) {
        event.preventDefault();
        const eventId = document.getElementById('event-id').value;
        const names = document.getElementById('event-names').value;
        const lastnames = document.getElementById('event-lastnames').value;
        const email = document.getElementById('event-email').value;
        const curp = document.getElementById('event-curp').value;

        events.push({ eventId, names, lastnames, email, curp });
        renderEvents(currentPage);
        closeModals();
        openRegisterModal();
    }

    function updateEvent(event) {
        event.preventDefault();
        const eventId = document.getElementById('edit-event-id').value;
        const names = document.getElementById('edit-event-names').value;
        const lastnames = document.getElementById('edit-event-lastnames').value;
        const email = document.getElementById('edit-event-email').value;
        const curp = document.getElementById('edit-event-curp').value;

        events[editEventIndex] = { eventId, names, lastnames, email, curp };
        renderEvents(currentPage);
        closeModals();
    }

    function deleteEvent(index) {
        events.splice(index, 1);
        renderEvents(currentPage);
    }

    searchInput.addEventListener('input', () => {
        renderEvents(1, searchInput.value);
    });

    createEventBtn.addEventListener('click', openCreateModal);

    createEventForm.addEventListener('submit', createEvent);

    editEventForm.addEventListener('submit', updateEvent);

    closeModalButtons.forEach(button => {
        button.addEventListener('click', closeModals);
    });

    renderEvents();
});
