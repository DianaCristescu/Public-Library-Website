const toggleBtn = document.querySelector('.menu-toggle');
const nav = document.querySelector('.menu');
const main = document.querySelector('main');
const footer = document.querySelector('footer');
const pageTitle = document.querySelector('.page-title');

// 2. Add the Event Listener
toggleBtn.addEventListener('click', function() {
    nav.classList.toggle('close-menu');
    main.classList.toggle('menu-closed');
    footer.classList.toggle('menu-closed-footer');
    pageTitle.classList.toggle('page-title-menu-closed');

});