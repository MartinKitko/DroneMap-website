function countChars(countFrom, displayTo) {
    document.getElementById(displayTo).textContent = document.getElementById(countFrom).value.length
}

function setDefaultOption(option) {
    if (option === "") {
        option = "red"
    }
    $('#m_color').val(option)
}

function checkForErrors(error, errorMessage) {
    if (error) {
        document.getElementById("error-message").innerHTML = errorMessage;
        document.getElementById("error-message").style.display = "block";
    } else {
        document.getElementById("error-message").style.display = "none";
    }
}

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