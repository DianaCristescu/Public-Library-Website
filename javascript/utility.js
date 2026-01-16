
// Save scroll position
function saveScrollPosition() {
    sessionStorage.setItem('scrollPosition', window.scrollY);
}

// Apply saved scroll position
document.addEventListener("DOMContentLoaded", function() {
    const scrollPosition = sessionStorage.getItem('scrollPosition');
    if (scrollPosition) {
        window.scrollTo(0, scrollPosition);
        sessionStorage.removeItem('scrollPosition');
    }
});