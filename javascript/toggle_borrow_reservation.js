function toggleBorrow(bookId, btnElement) {
    const params = new URLSearchParams();
    params.append('book_id', bookId);

    fetch('/biblioteca/api/toggle_borrow.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params
    })
    .then( response => {
        if (!response.ok) { throw new Error("HTTP error " + response.status); }
        return response.text();
    })
    .then( data => {
        let borrowed = data.trim();

        if (borrowed === '0') {
            btnElement.innerText = 'Borrow';
        } else {
            btnElement.innerText = 'Return';
        }
    })
}

function toggleReservation(bookId, btnElement) {
    const params = new URLSearchParams();
    params.append('book_id', bookId);

    fetch('/biblioteca/api/toggle_reservation.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params
    })
    .then( response => {
        if (!response.ok) { throw new Error("HTTP error " + response.status); }
        return response.text();
    })
    .then( data => {
        let borrowed = data.trim();

        if (borrowed === '0') {
            btnElement.innerText = 'Reserve';
        } else {
            btnElement.innerText = 'Cancel Reservation';
        }
    })
}