document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.comment-form');
    const errorDiv = document.getElementById('comment-errors');

    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        errorDiv.textContent = '';

        const formData = new FormData(form);

        fetch(window.location.href, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async response => {
            const data = await response.json();

            if (!response.ok) {
                if (data.errors) {
                    errorDiv.innerHTML = data.errors.map(err => `<div>${err}</div>`).join('');
                } else {
                    errorDiv.textContent = 'An unexpected error occurred.';
                }
                throw new Error('Validation failed');
            }

            if (data.status === 'success') {
                const noCommentsP = document.getElementById('no-comments');
                if (noCommentsP) {
                    noCommentsP.remove();
                }
                const commentsList = document.querySelector('.comments-list');
                const comment = document.createElement('div');
                comment.classList.add('comment');

                comment.innerHTML = `
                    <div class="comment-header">
                        <span class="comment-author">${data.name}</span>
                        <span class="comment-author">${data.email}</span>
                        <span class="comment-date">${data.createdAt}</span>
                    </div>
                    <p class="comment-text">${data.content}</p>
                `;

                commentsList.prepend(comment);
                form.reset();
            }
        })
        .catch(err => {
            console.error(err);
        });
    });
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('delete-comment-btn')) {
        const commentId = e.target.dataset.id;

        fetch(`/comment/delete/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const commentDiv = e.target.closest('.comment');
                if (commentDiv) {
                    commentDiv.remove();
                }

                const commentsList = document.querySelector('.comments-list');
                if (commentsList && commentsList.children.length === 0) {
                    commentsList.innerHTML = `<div class="comment" id="no-comments">
                    <p class="comment-text">No comments yet.</p>
                </div>`;
                }
            } else {
                console.error(data.message || 'Error deleting comment');
            }
        });
    }
});