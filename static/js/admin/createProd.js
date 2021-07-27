const createProduct = async () => {
    let container = document.getElementsByClassName('container')[1]

    let err = []

    try {
        document.querySelectorAll('.text-danger').forEach((e) => e.remove())
    } catch {}
    let title = document.getElementById('title').value
    let description = document.getElementById('description').value
    let price = document.getElementById('price').value
    let photo = document.getElementById('photo').files[0]

    if (!title) {
        err.push('Title is required')
    }
    if (!description) {
        err.push('Description is required')
    }
    if (!price) {
        err.push('Price is required')
    }
    if (!photo) {
        err.push('Photo is required')
    }
    if (err.length > 0) {
        err.forEach((msg) => {
            let errDiv = document.createElement('div')
            errDiv.innerHTML = msg
            errDiv.classList.add('text-danger')
            container.appendChild(errDiv)
        })
        return
    }

    const formData = new FormData()
    formData.append('title', title)
    formData.append('description', description)
    formData.append('price', price)
    formData.append('photo', photo)

    let response = await fetch('/ajax/admin/create-product.php', {
        method: 'POST',
        body: formData,
    })

    let result = await response.json()

    if (result.errors) {
        result.errors.forEach((msg) => {
            let errDiv = document.createElement('div')
            errDiv.innerHTML = msg
            errDiv.classList.add('text-danger')
            container.appendChild(errDiv)
        })
        return
    }

    window.location.replace('/admin/prod.php')
}

try {
    document
        .getElementById('prod-submit')
        .addEventListener('click', createProduct)
} catch (error) {}
