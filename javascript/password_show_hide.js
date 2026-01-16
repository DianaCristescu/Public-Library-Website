{/* 
<div class="password-input">
    <div>
        <h3>Password</h3>
        <i class="fa-solid fa-eye"></i>
    </div>
    <input type="password" name="password">
</div> 
*/}

const pInpDivs = document.querySelectorAll('.password-input');

pInpDivs.forEach (pInpDiv => {
    const pInpField = pInpDiv.querySelector('input');
    const pInpTitleDiv = pInpDiv.querySelector('div');
    const pInpShowHideIco = pInpTitleDiv.querySelector('i');

    pInpShowHideIco.addEventListener('click', function() {
        let type = pInpField.getAttribute('type') === 'password' ? 'text' : 'password';
        pInpField.setAttribute('type', type);
        if (type === 'password') {
            pInpShowHideIco.classList.remove('fa-eye');
            pInpShowHideIco.classList.add('fa-eye-slash');
        } else {
            pInpShowHideIco.classList.remove('fa-eye-slash');
            pInpShowHideIco.classList.add('fa-eye');
        }
    });
});