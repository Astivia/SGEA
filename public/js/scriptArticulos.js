document.addEventListener('DOMContentLoaded', () => {
    const articles = [];
    const articlesPerPage = 5;
    let currentPage = 1;
    let editArticleIndex = null;

    const searchInput = document.getElementById('search-input');
    const createArticleBtn = document.getElementById('create-article-btn');
    const articlesList = document.getElementById('articles-list');
    const pagination = document.getElementById('pagination');
    const registerModal = document.getElementById('register-modal');
    const createArticleModal = document.getElementById('create-article-modal');
    const viewArticleModal = document.getElementById('view-article-modal');
    const editArticleModal = document.getElementById('edit-article-modal');
    const createArticleForm = document.getElementById('create-article-form');
    const editArticleForm = document.getElementById('edit-article-form');
    const closeModalButtons = document.querySelectorAll('.close');

    function renderReviewers(container, reviewers) {
        container.innerHTML = '';
        reviewers.forEach((reviewer, index) => {
            const reviewerInput = document.createElement('input');
            reviewerInput.type = 'text';
            reviewerInput.value = reviewer;
            reviewerInput.placeholder = `Revisor ${index + 1}`;
            reviewerInput.required = true;
            container.appendChild(reviewerInput);
        });
    }

    function renderArticles(page = 1, searchTerm = '') {
        articlesList.innerHTML = '';
        pagination.innerHTML = '';

        const filteredArticles = articles.filter(article => article.title.toLowerCase().includes(searchTerm.toLowerCase()));
        const totalPages = Math.ceil(filteredArticles.length / articlesPerPage);
        const start = (page - 1) * articlesPerPage;
        const end = start + articlesPerPage;
        const articlesToShow = filteredArticles.slice(start, end);

        articlesToShow.forEach((article, index) => {
            const articleItem = document.createElement('div');
            articleItem.className = 'article-item';
            articleItem.innerHTML = `
                
                <img class="article-photo" src="${article.authorPhoto}" alt="${article.title}">
                <div class="article-details">
                    <h3>${article.title}</h3>
                    <button class="view-btn">Ver</button>
                    <button class="edit-btn">Editar</button>
                </div>
                
            `;
            articlesList.appendChild(articleItem);

            const viewBtn = articleItem.querySelector('.view-btn');
            viewBtn.addEventListener('click', () => {
                const articleDetails = document.getElementById('article-details');
                articleDetails.innerHTML = `
                    <img src="${article.authorPhoto}" alt="Foto del Autor">
                    <p><strong>Título:</strong> ${article.title}</p>
                    <p><strong>Descripción:</strong> ${article.description}</p>
                    <p><strong>Revisores:</strong> ${article.reviewers.join(', ')}</p>
                    <p><strong>Fecha de Publicación:</strong> ${article.publicationDate}</p>
                `;
                viewArticleModal.style.display = 'block';
            });

            const editBtn = articleItem.querySelector('.edit-btn');
            editBtn.addEventListener('click', () => {
                editArticleIndex = start + index;
                document.getElementById('edit-article-author-photo').value = article.authorPhoto;
                document.getElementById('edit-article-title').value = article.title;
                document.getElementById('edit-article-description').value = article.description;
                document.getElementById('edit-article-reviewers-count').value = article.reviewers.length;
                renderReviewers(document.getElementById('edit-reviewers-container'), article.reviewers);
                document.getElementById('edit-article-publication-date').value = article.publicationDate;
                editArticleModal.style.display = 'block';
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
                renderArticles(currentPage, searchInput.value);
            });
            pagination.appendChild(pageButton);
        }
    }

    searchInput.addEventListener('input', () => {
        renderArticles(1, searchInput.value);
    });

    createArticleBtn.addEventListener('click', () => {
        createArticleModal.style.display = 'block';
    });

    closeModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            registerModal.style.display = 'none';
            createArticleModal.style.display = 'none';
            viewArticleModal.style.display = 'none';
            editArticleModal.style.display = 'none';
        });
    });

    createArticleForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const authorPhoto = document.getElementById('article-author-photo').value;
        const title = document.getElementById('article-title').value;
        const description = document.getElementById('article-description').value;
        const reviewersCount = parseInt(document.getElementById('article-reviewers-count').value, 10);
        const reviewers = Array.from({ length: reviewersCount }, (_, i) => document.querySelector(`#create-article-form input[placeholder="Revisor ${i + 1}"]`).value);
        const publicationDate = document.getElementById('article-publication-date').value;

        articles.push({ authorPhoto, title, description, reviewers, publicationDate });
        createArticleForm.reset();
        createArticleModal.style.display = 'none';
        renderArticles(currentPage);
    });

    document.getElementById('article-reviewers-count').addEventListener('input', (e) => {
        const reviewersCount = parseInt(e.target.value, 10);
        const reviewersContainer = document.getElementById('reviewers-container');
        reviewersContainer.innerHTML = '';
        for (let i = 0; i < reviewersCount; i++) {
            const reviewerInput = document.createElement('input');
            reviewerInput.type = 'text';
            reviewerInput.placeholder = `Revisor ${i + 1}`;
            reviewerInput.required = true;
            reviewersContainer.appendChild(reviewerInput);
        }
    });

    editArticleForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const authorPhoto = document.getElementById('edit-article-author-photo').value;
        const title = document.getElementById('edit-article-title').value;
        const description = document.getElementById('edit-article-description').value;
        const reviewersCount = parseInt(document.getElementById('edit-article-reviewers-count').value, 10);
        const reviewers = Array.from({ length: reviewersCount }, (_, i) => document.querySelector(`#edit-article-form input[placeholder="Revisor ${i + 1}"]`).value);
        const publicationDate = document.getElementById('edit-article-publication-date').value;

        articles[editArticleIndex] = { authorPhoto, title, description, reviewers, publicationDate };
        editArticleForm.reset();
        editArticleModal.style.display = 'none';
        renderArticles(currentPage);
    });

    document.getElementById('edit-article-reviewers-count').addEventListener('input', (e) => {
        const reviewersCount = parseInt(e.target.value, 10);
        const reviewersContainer = document.getElementById('edit-reviewers-container');
        reviewersContainer.innerHTML = '';
        for (let i = 0; i < reviewersCount; i++) {
            const reviewerInput = document.createElement('input');
            reviewerInput.type = 'text';
            reviewerInput.placeholder = `Revisor ${i + 1}`;
            reviewerInput.required = true;
            reviewersContainer.appendChild(reviewerInput);
        }
    });

    renderArticles();
});
