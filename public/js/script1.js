document.addEventListener('DOMContentLoaded', () => {
    // Add hover effect to navigation items
    const navItems = document.querySelectorAll('nav ul li a');
    navItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            item.style.color = '#0a66c2';
        });
        item.addEventListener('mouseleave', () => {
            item.style.color = '#666';
        });
    });

    // Add like functionality to posts
    const posts = document.querySelectorAll('.post');
    posts.forEach(post => {
        const likeButton = document.createElement('button');
        likeButton.textContent = 'Purr (Like)';
        likeButton.classList.add('like-button');
        
        let liked = false;
        likeButton.addEventListener('click', () => {
            if (!liked) {
                likeButton.textContent = 'Purred!';
                likeButton.style.backgroundColor = '#0a66c2';
                likeButton.style.color = '#ffffff';
                liked = true;
            } else {
                likeButton.textContent = 'Purr (Like)';
                likeButton.style.backgroundColor = '#f3f2ef';
                likeButton.style.color = '#0a66c2';
                liked = false;
            }
        });

        post.appendChild(likeButton);
    });
});