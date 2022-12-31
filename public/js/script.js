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
    if (error === 'true') {
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

async function checkLogin(loginInput) {
    const username = loginInput.val();
    try {
        const response = await $.ajax({
            url: '?c=auth&a=checkUsername',
            method: 'POST',
            data: {username},
            dataType: 'json'
        });
        if (response.hasOwnProperty('taken')) {
            if (username !== '' && response.taken) {
                $('#login-error').text('');
            } else {
                $('#login-error').text('Zadané meno nie je registrované')
            }
        }
    } catch (error) {
        console.error(error);
    }
    checkErrors();
}

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
            } else {
                $('#email-error').text('')
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

function checkPasswdLength(passwordInput) {
    const password = passwordInput.val();
    if (password !== "" && password.length < 8) {
        $('#password-error').text('Zadané heslo je príliš krátke');
    } else {
        $('#password-error').text('');
    }
    checkErrors();
}

function checkPasswords(passwordInput, password2Input) {
    const password = passwordInput.val();
    const password2 = password2Input.val();
    if (password !== password2) {
        $('#passwords-error').text('Zadané heslá sa nezhodujú');
    } else {
        $('#passwords-error').text('');
    }
    checkErrors();
}

function checkErrors() {
    $('#submit').prop('disabled', $('#username-error').text() !== '' || $('#email-error').text() !== '' || $('#passwords-error').text() !== '');
}

//list.view.php
async function updateRating(markerId, rating) {
    try {
        const response = await fetch(`?c=markers&a=rating&id=${markerId}&rating=${rating}`, {
            method: 'POST',
        });
        const data = await response.json();
        if (data.hasOwnProperty('newAvgRating')) {
            const newAvgRating = data.newAvgRating;
            const markerRatingElement = document.querySelector(`[data-m-id="${markerId}"]`);
            markerRatingElement.textContent = newAvgRating;
        }

        const starElements = document.querySelectorAll(`[data-marker-id="${markerId}"]`);
        for (let i = starElements.length - 1; i >= 0; i--) {
            const starElement = starElements[i];
            if (starElements.length - i <= rating) {
                starElement.textContent = '★';
            } else {
                starElement.textContent = '☆';
            }
        }
    } catch (error) {
        console.error(error);
    }
}

async function updateStars() {
    const response = await fetch('?c=markers&a=getUserRatings');
    const data = await response.json();

    for (const marker of data.ratings) {
        const starElements = document.querySelectorAll(`[data-marker-id="${marker.marker_id}"]`);

        for (let i = starElements.length - 1; i >= 0; i--) {
            const starElement = starElements[i];
            if (starElements.length - i <= marker.rating) {
                starElement.textContent = '★';
            } else {
                starElement.textContent = '☆';
            }
        }
    }
}

function setupStarListeners() {
    const stars = document.querySelectorAll('.star');
    stars.forEach((star) => {
        star.addEventListener('click', function (event) {
            const markerId = event.target.dataset.markerId;
            const rating = Number(event.target.id.slice(-1));
            updateRating(markerId, rating);
        });
    });
}

//gallery.view.php
function lightbox() {
    const lightbox = document.createElement('div')
    lightbox.id = 'lightbox'
    document.body.appendChild(lightbox)

    const images = document.querySelectorAll('img')
    images.forEach(image => {
        image.addEventListener('click', () => {
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