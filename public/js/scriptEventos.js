document.addEventListener('DOMContentLoaded', () => {
    const events = [];
    const eventsPerPage = 5;
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
        const eventsToShow = filteredEvents.slice(start, end);

        eventsToShow.forEach((event, index) => {
            const eventItem = document.createElement('div');
            eventItem.className = 'event-item';
            eventItem.innerHTML = `
                <img class="event-photo" src="${event.photo}" alt="${event.name}">
                <div class="event-details">
                    <h3>${event.name}</h3>
                    <p>${event.description}</p>
                    <p><strong>Ponente:</strong> ${event.speaker}</p>
                    <p><strong>Fecha:</strong> ${event.date}</p>
                    <p><strong>Hora:</strong> ${event.time}</p>
                    <button class="register-btn">Registrarme</button>
                    <button class="edit-btn">Editar</button>
                </div>
            `;
            eventsList.appendChild(eventItem);

            const registerBtn = eventItem.querySelector('.register-btn');
            registerBtn.addEventListener('click', () => {
                registerModal.style.display = 'block';
            });

            const editBtn = eventItem.querySelector('.edit-btn');
            editBtn.addEventListener('click', () => {
                editEventIndex = start + index;
                document.getElementById('edit-event-name').value = event.name;
                document.getElementById('edit-event-description').value = event.description;
                document.getElementById('edit-event-photo').value = event.photo;
                document.getElementById('edit-event-speaker').value = event.speaker;
                document.getElementById('edit-event-date').value = event.date;
                document.getElementById('edit-event-time').value = event.time;
                editEventModal.style.display = 'block';
            });
        });

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('span');
            pageButton.className = 'pagination-btn';
            pageButton.innerText = i;
            if (i === page) {
                pageButton.style.fontWeight = 'bold';
            }
            pageButton.addEventListener('click', () => {
                currentPage = i;
                renderEvents(currentPage, searchInput.value);
            });
            pagination.appendChild(pageButton);
        }
    }

    searchInput.addEventListener('input', () => {
        renderEvents(1, searchInput.value);
    });

    createEventBtn.addEventListener('click', () => {
        createEventModal.style.display = 'block';
    });

    closeModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            registerModal.style.display = 'none';
            createEventModal.style.display = 'none';
            editEventModal.style.display = 'none';
        });
    });

    // createEventForm.addEventListener('submit', (e) => {
    //     e.preventDefault();
    //     const name = document.getElementById('event-name').value;
    //     const description = document.getElementById('event-description').value;
    //     const photo = document.getElementById('event-photo').value;
    //     const speaker = document.getElementById('event-speaker').value;
    //     const date = document.getElementById('event-date').value;
    //     const time = document.getElementById('event-time').value;

    //     events.push({ name, description, photo, speaker, date, time });
    //     createEventForm.reset();
    //     createEventModal.style.display = 'none';
    //     renderEvents(currentPage);
    // });

    // editEventForm.addEventListener('submit', (e) => {
    //     e.preventDefault();
    //     const name = document.getElementById('edit-event-name').value;
    //     const description = document.getElementById('edit-event-description').value;
    //     const photo = document.getElementById('edit-event-photo').value;
    //     const speaker = document.getElementById('edit-event-speaker').value;
    //     const date = document.getElementById('edit-event-date').value;
    //     const time = document.getElementById('edit-event-time').value;

    //     events[editEventIndex] = { name, description, photo, speaker, date, time };
    //     editEventForm.reset();
    //     editEventModal.style.display = 'none';
    //     renderEvents(currentPage);
    // });

    renderEvents();
});
