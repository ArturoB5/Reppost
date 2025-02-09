document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const postId = urlParams.get('post_id');
    if (postId) {
        const targetPost = document.getElementById('post-' + postId);
        if (targetPost) {
            targetPost.style.transition = 'background-color 0.5s ease';
            targetPost.style.backgroundColor = '#5cb85c8c';targetPost.scrollIntoView({ behavior: 'smooth', block: 'center' });
            targetPost.style.boxShadow = '0px 0px 10px 5px rgba(92, 184, 92, 0.5)';
            setTimeout(() => {
                targetPost.style.boxShadow = '';
                targetPost.style.backgroundColor = '';
            }, 2000);
        }
    }
});