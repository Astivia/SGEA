document.addEventListener('DOMContentLoaded', () => {
    const articles = [];
    const articlesPerPage = 6;
    let currentPage = 1;
    let editArticleIndex = null;

    const searchInput = document.getElementById('search-input');
    const createArticleBtn = document.getElementById('create-event-btn');
    const articlesList = document.getElementById('events-list');
    const pagination = document.getElementById('pagination');
    const registerModal = document.getElementById('register-modal');
    const createArticleModal = document.getElementById('create-article-modal');
    const editArticleModal = document.getElementById('edit-article-modal');
    const createArticleForm = document.getElementById('create-article-form');
    const editArticleForm = document.getElementById('edit-article-form');
    const closeModalButtons = document.querySelectorAll('.close');

    function renderArticles(page = 1, searchTerm = '') {
        articlesList.innerHTML = '';
        pagination.innerHTML = '';

        const filteredArticles = articles.filter(article => article.name.toLowerCase().includes(searchTerm.toLowerCase()));
        const totalPages = Math.ceil(filteredArticles.length / articlesPerPage);
        const start = (page - 1) * articlesPerPage;
        const end = start + articlesPerPage;
        const articlesToDisplay = filteredArticles.slice(start, end);

        articlesToDisplay.forEach((article, index) => {
            const articleElement = document.createElement('div');
            articleElement.classList.add('event');
            articleElement.innerHTML = `
                <h3>${article.name}</h3>
                <p>Área: ${article.area}</p>
                <button class="edit-event-btn" data-index="${start + index}">Editar</button>
                <button class="delete-event-btn" data-index="${start + index}">Eliminar</button>
            `;
            articlesList.appendChild(articleElement);
        });

        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            if (i === page) pageButton.classList.add('active');
            pageButton.addEventListener('click', () => {
                currentPage = i;
                renderArticles(i, searchInput.value);
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
                deleteArticle(index);
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
        createArticleModal.style.display = 'block';
    }

    function openEditModal(index) {
        editArticleIndex = index;
        const article = articles[index];
        document.getElementById('edit-article-name').value = article.name;
        document.getElementById('edit-article-area').value = article.area;
        editArticleModal.style.display = 'block';
    }

    function closeModals() {
        createArticleModal.style.display = 'none';
        editArticleModal.style.display = 'none';
    }

    function createArticle(event) {
        event.preventDefault();
        const name = document.getElementById('article-name').value;
        const area = document.getElementById('article-area').value;

        articles.push({ name, area });
        renderArticles(currentPage);
        closeModals();
        openRegisterModal();
    }

    function updateArticle(event) {
        event.preventDefault();
        const name = document.getElementById('edit-article-name').value;
        const area = document.getElementById('edit-article-area').value;

        articles[editArticleIndex] = { name, area };
        renderArticles(currentPage);
        closeModals();
        openRegisterModal();
    }

    function deleteArticle(index) {
        articles.splice(index, 1);
        renderArticles(currentPage);
    }

    createArticleBtn.addEventListener('click', openCreateModal);
    closeModalButtons.forEach(button => button.addEventListener('click', closeModals));
    createArticleForm.addEventListener('submit', createArticle);
    editArticleForm.addEventListener('submit', updateArticle);
    searchInput.addEventListener('input', () => renderArticles(1, searchInput.value));

    renderArticles();
});
