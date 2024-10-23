document.addEventListener('DOMContentLoaded', function() {
    const seeAllLink = document.querySelector('.see-all');
    seeAllLink.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = 'all-jobs.html';
    });
});