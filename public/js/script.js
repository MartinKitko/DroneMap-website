//create.form.view.php
function countChars(countFrom, displayTo) {
    document.getElementById(displayTo).textContent = document.getElementById(countFrom).value.length
}

function setDefaultOption(option) {
    if (option === "") {
        option = "red"
    }
    $('#m_color').val(option)
}

//register.view.php
function checkForErrors(error, errorMessage) {
    if (error) {
        document.getElementById("error-message").innerHTML = errorMessage;
        document.getElementById("error-message").style.display = "block";
    } else {
        document.getElementById("error-message").style.display = "none";
    }
}

async function checkUsername(usernameInput) {
    const username = usernameInput.val();
    try {
        const response = await $.ajax({
            url: '?c=auth&a=checkUsername',
            method: 'POST',
            data: {username},
            dataType: 'json'
        });
        if (response.hasOwnProperty('taken')) {
            if (response.taken) {
                $('#username-error').text('Zadané používateľské meno už niekto používa');
            } else {
                $('#username-error').text('')
            }
        }
    } catch (error) {
        console.error(error);
    }
    checkErrors();
}

$(document).ready(function () {
    const usernameInput = $('#username');
    usernameInput.on('keyup', function () {
        checkUsername(usernameInput);
    });
});

async function checkEmail(emailInput) {
    const email = emailInput.val();
    try {
        const response = await $.ajax({
            url: '?c=auth&a=checkEmail',
            method: 'POST',
            data: {email},
            dataType: 'json'
        });
        if (response.hasOwnProperty('taken')) {
            if (response.taken) {
                $('#email-error').text('Zadaný email už niekto používa');
                $('#submit').prop('disabled', true);
            } else {
                $('#email-error').text('')
                $('#submit').prop('disabled', false);
            }
        }
        if (response.hasOwnProperty('notValid')) {
            if (response.notValid) {
                $('#email-error').text('Zadaný email je neplatný');
            } else {
                $('#email-error').text('');
            }
        }
    } catch (error) {
        console.error(error);
    }
    checkErrors();
}

$(document).ready(function () {
    const emailInput = $('#email');
    emailInput.on('keyup', function () {
        checkEmail(emailInput);
    });
});

async function checkPasswords(passwordInput, password2Input) {
    const password = passwordInput.val();
    const password2 = password2Input.val();
    try {
        const response = await $.ajax({
            url: '?c=auth&a=checkPasswords',
            method: 'POST',
            data: {password, password2},
            dataType: 'json'
        });
        if (response.hasOwnProperty('same')) {
            if (response.same) {
                $('#passwords-error').text('');
            } else {
                $('#passwords-error').text('Zadané heslá sa nezhodujú');
            }
        }
    } catch (error) {
        console.error(error);
    }
    checkErrors();
}

$(document).ready(function () {
    const passwordInput = $('#password');
    const password2Input = $('#password2');
    password2Input.on('keyup', function () {
        checkPasswords(passwordInput, password2Input);
    });
});

function checkErrors() {
    $('#submit').prop('disabled', $('#username-error').text() !== '' || $('#email-error').text() !== '' || $('#passwords-error').text() !== '');
}

//gallery.view.php
function lightbox() {
    const lightbox = document.createElement('div')
    lightbox.id = 'lightbox'
    document.body.appendChild(lightbox)

    const images = document.querySelectorAll('img')
    images.forEach(image => {
        image.addEventListener('click', e => {
            lightbox.classList.add('active')
            const img = document.createElement('img')
            img.src = image.src
            while (lightbox.firstChild) {
                lightbox.removeChild(lightbox.firstChild)
            }
            lightbox.appendChild(img)
        })
    })

    lightbox.addEventListener('click', e => {
        if (e.target !== e.currentTarget) {
            return
        }
        lightbox.classList.remove('active')
    })
}