function toggleWishlist(bookId, btnElement) {
    const icon = btnElement.querySelector('i');
    const params = new URLSearchParams();
    params.append('book_id', bookId);

    fetch('/biblioteca/api/toggle_wishlist.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params
    })
    .then( response => {
        if (!response.ok) { throw new Error("HTTP error " + response.status); }
        return response.text();
    })
    .then( data => {
        let inWishlist = data.trim();

        if (inWishlist === '0') {
            icon.classList.replace('fa-solid', 'fa-regular');
        } else {
            icon.classList.replace('fa-regular', 'fa-solid');
        }
    })
}