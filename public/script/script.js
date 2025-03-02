// public/script/script.js
function toggleSearchField() {
    const searchForm = document.getElementById('searchForm');
    const searchIcon = document.querySelector('.search');
    
    if (searchForm.style.display === 'none' || !searchForm.style.display) {
        searchForm.style.display = 'flex';
        searchForm.querySelector('input').focus();
    } else {
        searchForm.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Close search form when clicking outside
    document.addEventListener('click', function(event) {
        const searchForm = document.getElementById('searchForm');
        const searchIcon = document.querySelector('.search');
        
        if (!searchForm.contains(event.target) && event.target !== searchIcon) {
            searchForm.style.display = 'none';
        }
    });

    // Vérifiez si le message flash existe
    const flashMessage = document.getElementById('flash-message');
    if (flashMessage) {
        // Masquez le message après 5 secondes (5000 millisecondes)
        setTimeout(() => {
            flashMessage.style.display = 'none';
        }, 5000);
    }
});