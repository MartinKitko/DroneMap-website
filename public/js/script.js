function countChars(countFrom, displayTo) {
    document.getElementById(displayTo).textContent = document.getElementById(countFrom).value.length;
}

function setDefaultOption(option) {
    if (option === "") {
        option = "red";
    }
    $('#m_color').val(option);
}