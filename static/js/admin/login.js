const login = async () => {
    let errors = false

    try {
        document
            .querySelectorAll('.invalid-feedback')
            .forEach((e) => e.remove())
    } catch {}

    let emailElem = document.getElementById('email')
    let passwordElem = document.getElementById('password')

    email = emailElem.value
    password = passwordElem.value

    if (!email) {
        errors = true
        appendError(emailElem, 'Email is required')
    }
    if (!password) {
        errors = true
        appendError(passwordElem, 'Password is required')
    }

    if (password.length < 5) {
        errors = true
        appendError(passwordElem, 'Password too short')
    }

    emailElem.classList.add('is-valid')
    passwordElem.classList.add('is-valid')

    if (errors) {
        return
    }

    let data = JSON.stringify({ email: email, password: password })

    let response = await fetch('/ajax/login.php', {
        method: 'POST',
        body: data,
    })

    let result = await response.json()
    console.log(response)

    if (result == -2) {
        emailElem.classList.remove('is-valid')
        appendError(emailElem, 'Enter valid email')
        return
    }
    if (result == -1) {
        emailElem.classList.remove('is-valid')
        passwordElem.classList.remove('is-valid')
        appendError(emailElem, 'Incorrect email or password')
        appendError(passwordElem, 'Incorrect email or password')
        return
    }
    if (result == 1) {
        window.location.reload()
        return
    }
}
const appendError = (elem, msg) => {
    let errDiv = document.createElement('div')
    errDiv.classList.add('invalid-feedback')
    errDiv.innerHTML = msg

    elem.classList.add('is-invalid')
    elem.classList.remove('is-valid')
    elem.parentNode.appendChild(errDiv)
}
try {
    let loginBtn = document.getElementById('loginBtn')
    loginBtn.addEventListener('click', login)
} catch {}
