function updatePageProgress(event, inputElement) {
    // Only trigger on "Enter" key
    if (event.key !== 'Enter') return;

    // Get Values
    const bookId = inputElement.dataset.bookId;
    const totalPages = parseInt(inputElement.dataset.totalPages);
    let newPage = parseInt(inputElement.value);

    // Simple Validation
    if (isNaN(newPage) || newPage < 0) newPage = 0;
    if (newPage > totalPages) newPage = totalPages;
    
    // Correct the input visual if it was out of bounds
    inputElement.value = newPage;
    inputElement.blur(); // Remove focus to show "saved" state

    // Update Progress Bar Visually (Immediate Feedback)
    const percentage = Math.round((newPage / totalPages) * 100);
    const progressBar = document.getElementById('progress-' + bookId);
    if (progressBar) {
        progressBar.style.width = percentage + '%';
    }

    // Send to Backend
    const params = new URLSearchParams();
    params.append('book_id', bookId);
    params.append('current_page', newPage);

    fetch('/biblioteca/api/update_progress.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: params
    })
    .then(response => response.text())
    .then(data => {
        console.log("Progress saved:", data);

        // Wait 2 seconds, then reload
        setTimeout(() => {
            location.reload();
        }, 500);
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Failed to save progress. Please check your connection.");
    });
}