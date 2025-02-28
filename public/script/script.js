function toggleSearchField() {
    const searchForm = document.getElementById('searchForm');
    if (searchForm.style.display === 'none') {
        searchForm.style.display = 'flex';
        searchForm.querySelector('input').focus();
    } else {
        searchForm.style.display = 'none';
    }
}

// Close search form when clicking outside
document.addEventListener('click', function(event) {
    const searchForm = document.getElementById('searchForm');
    const searchIcon = document.querySelector('.search');
    
    if (!searchForm.contains(event.target) && event.target !== searchIcon) {
        searchForm.style.display = 'none';
    }
});
