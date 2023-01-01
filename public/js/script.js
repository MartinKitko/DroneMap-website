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

async function deleteImage(controller, elementId) {
    if (confirm('Naozaj chcete odstrániť tento obrázok?')) {
        try {
            const response = await $.ajax({
                url: '?c=' + controller +'&a=deletePhoto',
                method: 'POST',
                data: {elementId},
                dataType: 'json'
            });

            if (response.hasOwnProperty('successful')) {
                if (response.successful) {
                    document.getElementById("image-delete").classList.add("d-none");
                }
            }
        } catch (error) {
            console.error(error);
        }
    }
}

//register.view.php
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

function setupRegisterListeners() {
    $(document).ready(function () {
        const usernameInput = $('#username');
        usernameInput.on('keyup', function () {
            checkUsername(usernameInput);
        });
        const emailInput = $('#email');
        emailInput.on('keyup', function () {
            checkEmail(emailInput);
        });
        const passwordInput = $('#password');
        passwordInput.on('keyup', function () {
            checkPasswdLength(passwordInput);
        });
        const password2Input = $('#password2');
        password2Input.on('keyup', function () {
            checkPasswords(passwordInput, password2Input);
        });
    });
}

function setupLoginListener() {
    $(document).ready(function () {
        const loginInput = $('#login');
        loginInput.on('keyup', function () {
            checkLogin(loginInput);
        });
    });
}

function checkErrors() {
    $('#submit').prop('disabled', $('#login-error').text() !== '' || $('#username-error').text() !== '' || $('#email-error').text() !== '' || $('#passwords-error').text() !== '');
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
        if (data.hasOwnProperty('deleted') && data.deleted) {
            rating = 0;
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

async function checkHasMarkers() {
    const response = await fetch('?c=markers&a=getNumOfUsersMarkers');
    const data = await response.json();

    if (data.hasOwnProperty('markersCount')) {
        if (data.markersCount > 0) {
            document.getElementById("filter-author").classList.remove("d-none");
        }
    }
}

function filterAuthor(userId) {
    const cards = document.querySelectorAll('.markerCard');
    cards.forEach(card => {
        const cardAuthorId = card.dataset.authorId;
        if (parseInt(cardAuthorId) !== userId) {
            card.style.display = 'none';
        }
    });
    const filtBtn = document.getElementById('filterBtn');
    filtBtn.href = "javascript:showAll(" + userId + ")";
    filtBtn.innerText = "Zobraziť všetky miesta";
}

function showAll(userId) {
    const cards = document.querySelectorAll('.markerCard');
    cards.forEach(card => {
        card.style.display = '';
    });
    const filtBtn = document.getElementById('filterBtn');
    filtBtn.href = "javascript:filterAuthor(" + userId + ")";
    filtBtn.innerText = "Zobraziť iba moje miesta";
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